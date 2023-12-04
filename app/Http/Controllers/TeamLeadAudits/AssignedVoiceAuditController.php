<?php

namespace App\Http\Controllers\TeamLeadAudits;

use App\Models\User;
use App\Models\Project;
use App\Models\VoiceAudit;
use Illuminate\Http\Request;
use App\Models\VoiceEvaluation;
use App\Models\DatapointCategory;
use App\Models\AssignedVoiceAudit;
use App\Services\VoiceAuditService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignedVoiceAuditDetail;
use App\Models\Campaign;

class AssignedVoiceAuditController extends Controller
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
    public function index(VoiceEvaluation $voice_evaluation, Request $request)
    {
        $query = new AssignedVoiceAudit();
        if (Auth::user()->roles[0]->name == 'Team Lead' && Auth::user()->campaign_id == 4) {
            $query = $query->where('assigned_team_lead_id', Auth::user()->id)->where('status', 'pending');
        }
        $assigned_audits = $query->orderBy('id', 'desc')->paginate(10);
        return view('compliance-voice-audits.index', compact('voice_evaluation', 'assigned_audits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, VoiceEvaluation $voice_evaluation)
    {
        $assigned_audit_id = $request->assigned_voice_audit_id;
        $assigned_voice_audit = AssignedVoiceAudit::findOrFail($assigned_audit_id);
        $voice_audit = VoiceAudit::findOrFail($assigned_voice_audit->voice_audit_id);
        $categories = DatapointCategory::where('voice_evaluation_id', $voice_evaluation->id)
            ->where('campaign_id', @$voice_audit->campaign->id)
            ->where('project_id', @$voice_audit->project->id)
            ->where('status', 'active')
            ->with('datapoints')
            ->orderBy('sort', 'desc')
            ->get();
        return view('compliance-voice-audits.create', compact('voice_evaluation', 'assigned_audit_id', 'assigned_voice_audit', 'voice_audit', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $assigned_voice_audit_detail = AssignedVoiceAuditDetail::create($request->all());
        $this->voiceAuditService->insertAssignedAuditPoints($request, $assigned_voice_audit_detail);
        $assigned_voice_audit_id = $assigned_voice_audit_detail->id;
        $voice_audit_id = $assigned_voice_audit_detail->voice_audit_id;
        AssignedVoiceAudit::where('id', $assigned_voice_audit_id)->update(['status' => 'evaluated']);
        VoiceAudit::where('id', $voice_audit_id)->update(['status' => 'evaluated']);
        return redirect()
            ->route('compliance-voice-audits.index', 1)
            ->with('success', 'Compliance Voice Audit created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AssignedVoiceAuditDetail $assigned_voice_audit_detail)
    {
        $voice_audit = VoiceAudit::findOrFail($assigned_voice_audit_detail->voice_audit_id);
        $categories = $this->voiceAuditService->getComplianceAuditCategories($voice_audit);
        $voice_evaluation = VoiceEvaluation::findOrFail($voice_audit->voice_evaluation_id);

        return view('compliance-voice-audits.show')->with(compact('assigned_voice_audit_detail', 'categories', 'voice_evaluation'));
    }

    public function showAllComplianceAudits(VoiceEvaluation $voice_evaluation, Request $request)
    {
        $voice_audit = '';
        $query = new AssignedVoiceAuditDetail();
        $query = $query->when($request, function ($query, $request) {
            $query->search($request);
        });
        $assigned_audits = $query->with('voice_audit')->orderBy('id', 'desc')->paginate(10);
        $users = User::role('Associate')->orderBy('name', 'asc')->get();
        $qa_team_leads = User::role('Team Lead')->where('campaign_id',4)->orderBy('name', 'asc')->get();
        $projects = Project::orderBy('name', 'asc')->get();
        $campaigns = Campaign::orderBy('name','asc')->get();
        return view('compliance-voice-audits.show-all', compact('voice_evaluation', 'assigned_audits','users','projects','campaigns','qa_team_leads'));
    }

}
