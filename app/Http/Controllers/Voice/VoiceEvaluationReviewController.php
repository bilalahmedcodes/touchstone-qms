<?php

namespace App\Http\Controllers\Voice;

use App\Models\User;
use App\Models\Campaign;
use App\Models\VoiceAudit;
use Illuminate\Http\Request;
use App\Models\VoiceEvaluation;
use App\Services\VoiceAuditService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\MyVoiceEvaluationReviewTrait;
use App\Http\Requests\VoiceReviewAppealRequest;
use App\Models\Project;
use App\Models\VoiceAuditAppeal;
use App\Models\VoiceEvaluationAction;

class VoiceEvaluationReviewController extends Controller
{
    use MyVoiceEvaluationReviewTrait;

    public $voiceAuditService;

    public function __construct(VoiceAuditService $voiceAuditService)
    {
        $this->voiceAuditService = $voiceAuditService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $status = null)
    {
//        $query = new VoiceAudit();
//        $p = new Project;
//
//        $query->with('user', 'associate', 'campaign');
//
//        $query = $query->when($request, function ($query, $request) {
//            $query->search($request);
//        });
//
//        if (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager','Director']) && (Auth::user()->hrms_id != 87223 || Auth::user()->hrms_id != 238430) && Auth::user()->campaign_id != 4) {
//            $query = $query->where('campaign_id', Auth::user()->campaign_id);
//            $p = $p->orderBy('name', 'asc');
//        }
//
//        if (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Director']) && (Auth::user()->hrms_id == 87223 || Auth::user()->hrms_id == 238430) && Auth::user()->campaign_id != 4) {
//            $query = $query->where('project_id', 2);
//            $p = $p->where('id',2)->orderBy('name', 'asc');
//        }
		$query = new VoiceAudit();
            $p = new Project;
            $query->with('user', 'associate', 'campaign');
        if (in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead'])) {
        $query = $query->when($request, function ($query, $request) {
            $query->search($request);
        });            

            if (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager']) && (Auth::user()->hrms_id != 87223 || Auth::user()->hrms_id != 238430) && Auth::user()->campaign_id != 4) {
                $query = $query->where('campaign_id', Auth::user()->campaign_id);
            }

            if (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Director']) && (Auth::user()->hrms_id == 87223 || Auth::user()->hrms_id == 238430) && Auth::user()->campaign_id != 4) {
                $query = $query->where('project_id', 2);
                $p = $p->where('id',2)->orderBy('name', 'asc');
            }
            if ($status == 'pending') {
                $query = $query->where('outcome', 'rejected');
                $query = $query->doesnthave('appeal');
                $query = $query->doesnthave('action');
            } elseif ($status == 'rejected') {
                $query = $query->where('outcome', 'rejected');
            }
        }
        $voice_audits = $query
            ->sortable()
            ->orderBy('id', 'desc')
            ->paginate(100);

        $users = User::orderBy('name', 'asc')->get();
        $campaigns = Campaign::where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        $projects = $p->get();
        return view('voice-evaluation-reviews.index')->with(compact('voice_audits', 'users', 'campaigns', 'projects', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(VoiceAudit $voice_audit, $status = null)
    {
        $categories = $this->voiceAuditService->getAuditCategories($voice_audit);

        $voice_evaluation = VoiceEvaluation::findOrFail($voice_audit->voice_evaluation_id);

        $evaluation_actions = VoiceEvaluationAction::where('status', 'active')
            ->orderBy('sort', 'ASC')
            ->get();

        return view('voice-evaluation-reviews.show')->with(compact('voice_audit', 'categories', 'voice_evaluation', 'status', 'evaluation_actions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(VoiceAudit $voice_audit)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VoiceReviewAppealRequest $request, VoiceAudit $voice_audit, $status = null)
    {
        $appeal = VoiceAuditAppeal::where('voice_audit_id', $voice_audit->id)->count();
        if ($appeal > 0) {
            return redirect()
                ->back()
                ->with('error', 'Sorry! The evaluation review already has an appeal.');
        }
        VoiceAuditAppeal::create([
            'voice_audit_id' => $voice_audit->id,
            'remarks' => $request->remarks,
        ]);

        $voice_audit->status = 'appeal requested';
        $voice_audit->save();

        return redirect()
            ->route('voice-evaluation-reviews.index', $status)
            ->with('success', 'Appeal has been submitted successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoiceAudit $voice_audit)
    {
    }
}
