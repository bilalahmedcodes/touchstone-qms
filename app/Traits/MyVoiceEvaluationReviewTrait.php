<?php

namespace App\Traits;

use App\Models\User;
use App\Models\VoiceAudit;
use Illuminate\Http\Request;
use App\Models\VoiceEvaluation;
use App\Models\VoiceAuditAction;
use App\Services\VoiceAuditService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\VoiceReviewActionRequest;

/**
 *
 */
trait MyVoiceEvaluationReviewTrait
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myEvaluationReviews(Request $request)
    {
        $query = new VoiceAudit;

        $query->with('associate', 'campaign');

        $query = $query->when($request, function ($query, $request) {
            $query->search($request);
        });

        $query = $query->where('associate_id', Auth::user()->id);

        $voice_audits = $query->sortable()->orderBy('id', 'desc')->paginate(15);

        return view('voice-evaluation-reviews.my-evaluation-reviews')->with(compact('voice_audits'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function myEvaluationReviewShow(VoiceAudit $voice_audit, VoiceAuditService $voiceAuditService)
    {
        $categories = $voiceAuditService->getAuditCategories($voice_audit);

        $voice_evaluation = VoiceEvaluation::findOrFail($voice_audit->voice_evaluation_id);

        return view('voice-evaluation-reviews.my-evaluation-reviews-detail')->with(compact('voice_audit', 'categories', 'voice_evaluation'));
    }

    public function appeals(Request $request){
        $query = new VoiceAudit;

        $query->with('associate', 'campaign');

        $query = $query->when($request, function ($query, $request) {
            $query->search($request);
        });

        if (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager']) && (Auth::user()->hrms_id != 87223 || Auth::user()->hrms_id != 238430)) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }

        if (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Director']) && (Auth::user()->hrms_id == 87223 || Auth::user()->hrms_id == 238430)) {
            $query = $query->where('project_id', 2);
        }
        $query = $query->has('appeal');

        $query = $query->whereHas('appeal', function ($query) use($request) {
            $query = $query->when($request, function ($query, $request) {
                $query->search($request);
            });
        });

        $voice_audits = $query->sortable()->orderBy('id', 'desc')->paginate(15);

        $users = User::where('campaign_id', Auth::user()->campaign_id)->orderBy('name', 'asc')->get();

        return view('voice-evaluation-reviews.appeals')->with(compact('voice_audits', 'users'));
    }

    public function appealShow(VoiceAudit $voice_audit, VoiceAuditService $voiceAuditService){
        $categories = $voiceAuditService->getAuditCategories($voice_audit);

        $voice_evaluation = VoiceEvaluation::findOrFail($voice_audit->voice_evaluation_id);

        return view('voice-evaluation-reviews.appeals-detail')->with(compact('voice_audit', 'categories', 'voice_evaluation'));
    }

    // takeAction
    public function updateAction(VoiceReviewActionRequest $request, VoiceAudit $voice_audit, $status = NULL)
    {

        $action = VoiceAuditAction::where('voice_audit_id', $voice_audit->id)->count();

        if($action > 0){
            return redirect()->back()->with('error', 'Sorry! The evaluation review already has an action.');
        }

        VoiceAuditAction::create([
            'voice_audit_id' => $voice_audit->id,
            'voice_evaluation_action_id' => $request->voice_evaluation_action_id,
            'remarks' => $request->remarks
        ]);

        $voice_audit->status = 'action taken';
        $voice_audit->save();

        return redirect()->route('voice-evaluation-reviews.index', $status)->with('success', 'Appeal has been submitted successfully!');

    }

    public function actions(Request $request){
        $query = new VoiceAudit;

        $query->with('associate', 'campaign');

        $query = $query->when($request, function ($query, $request) {
            $query->search($request);
        });

        if (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager']) && (Auth::user()->hrms_id != 87223 || Auth::user()->hrms_id != 238430)) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }

        if (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Director']) && (Auth::user()->hrms_id == 87223 || Auth::user()->hrms_id == 238430)) {
            $query = $query->where('project_id', 2);
        }

        $query = $query->has('action');

        $query = $query->whereHas('action', function ($query) use($request) {
            $query = $query->when($request, function ($query, $request) {
                $query->search($request);
            });
        });

        $voice_audits = $query->sortable()->orderBy('id', 'desc')->paginate(15);

        $users = User::where('campaign_id', Auth::user()->campaign_id)->orderBy('name', 'asc')->get();

        return view('voice-evaluation-reviews.actions')->with(compact('voice_audits', 'users'));
    }

    public function actionShow(VoiceAudit $voice_audit, VoiceAuditService $voiceAuditService){
        $categories = $voiceAuditService->getAuditCategories($voice_audit);

        $voice_evaluation = VoiceEvaluation::findOrFail($voice_audit->voice_evaluation_id);

        return view('voice-evaluation-reviews.actions-detail')->with(compact('voice_audit', 'categories', 'voice_evaluation'));
    }
}


