<?php

namespace App\Http\Controllers\Voice;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Project;
use App\Models\Campaign;
use App\Models\VoiceAudit;
use Illuminate\Http\Request;
use App\Models\VoiceEvaluation;
use App\Models\VoiceAuditAppeal;
use App\Models\DatapointCategory;
use App\Services\VoiceAuditService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\VoiceAuditRequest;

class VoiceAuditAppealController extends Controller
{

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
    public function index(Request $request)
    {
        $query = new VoiceAudit;

        $query->with('user', 'associate', 'campaign');

        $query = $query->has('appeal');

        $query = $query->whereHas('appeal', function ($query) use($request) {
            $query = $query->when($request, function ($query, $request) {
                $query->search($request);
            });
        });

        if(Auth::user()->roles[0]->name == 'Associate' && Auth::user()->campaign_id == 4){
            $query = $query->where('user_id', Auth::user()->id);
        }
        elseif(in_array(Auth::user()->roles[0]->name, ['Team Lead']) && Auth::user()->campaign_id == 4){
            $query = $query->whereHas('user', function ($query) {
                $query = $query->where('reporting_to', Auth::user()->hrms_id);
                $query = $query->orWhere('id', Auth::user()->id);
            });

        }
        elseif(in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Associate']) && Auth::user()->campaign_id != 4){
            abort(403);
        }

        $query = $query->when($request, function ($query, $request) {
            $query->search($request);
        });

        $voice_audits = $query->sortable()->orderBy('id', 'desc')->paginate(100);

        $users = User::where('status','active')->orderBy('name', 'asc')->get();
        $campaigns = Campaign::where('status', 'active')->orderBy('name', 'asc')->get();
        $projects = Project::orderBy('name', 'asc')->get();
        return view('voice-audit-appeals.index')->with(compact('voice_audits', 'users', 'campaigns','projects'));
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
    public function store(VoiceAuditRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(VoiceAudit $voice_audit)
    {
        $categories = $this->voiceAuditService->getAuditCategories($voice_audit);

        $voice_evaluation = VoiceEvaluation::findOrFail($voice_audit->voice_evaluation_id);

        return view('voice-audit-appeals.show')->with(compact('voice_audit', 'categories', 'voice_evaluation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(VoiceAudit $voice_audit)
    {
        $users = User::role(["Manager", "Team Lead", "Associate"])->orderBy('name', 'ASC')->get();

        $categories = $this->voiceAuditService->getAuditCategories($voice_audit);

        $voice_evaluation = VoiceEvaluation::findOrFail($voice_audit->voice_evaluation_id);

        return view('voice-audit-appeals.edit')->with(compact('voice_audit', 'users', 'categories', 'voice_evaluation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VoiceAuditRequest $request, VoiceAudit $voice_audit)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoiceAudit $voice_audit)
    {
        $appeal = VoiceAuditAppeal::findOrFail($voice_audit->appeal->id);
        $appeal->status = 'rejected';
        $appeal->save();

        $voice_audit->status = 'appeal rejected';
        $voice_audit->save();

        return redirect()->route('voice-audit-appeals.index')->with('success', 'Appeal rejected successfully!');
    }

}
