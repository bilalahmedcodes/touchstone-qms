<?php

namespace App\Http\Controllers\Voice;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Project;
use App\Models\Campaign;
use App\Models\VoiceAudit;
use Illuminate\Http\Request;
use App\Models\VoiceEvaluation;
use App\Models\VoiceAuditStatus;
use App\Models\DatapointCategory;
use App\Models\AssignedVoiceAudit;
use Illuminate\Support\Facades\DB;
use App\Services\VoiceAuditService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\VoiceAuditRequest;

class VoiceAuditController extends Controller
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
        $this->voiceAuditService->auditShowAccess();

        $query = new VoiceAudit();

        $query->with('user', 'associate', 'campaign');

        if (Auth::user()->roles[0]->name == 'Associate' && Auth::user()->campaign_id == 4) {
            $query = $query->where('user_id', Auth::user()->id)->where('status', '!=', 'assigned to team lead');
        } elseif (in_array(Auth::user()->roles[0]->name, ['Team Lead']) && Auth::user()->campaign_id == 4) {
            $query = $query->where('status', '!=', 'assigned to team lead')->whereHas('user', function ($query) {
                $query = $query->where('reporting_to', Auth::user()->hrms_id);
                $query = $query->orWhere('id', Auth::user()->id);
            });
        } elseif (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Associate']) && Auth::user()->campaign_id != 4) {
            abort(403);
        }

        $query = $query->when($request, function ($query, $request) {
            $query->search($request);
        });

        if ($request->has('review')) {
            if (!empty($request->review)) {
                $query = $query->doesnthave('appeal');
                $query = $query->doesnthave('action');
                $query = $query->where('outcome', 'rejected');
            }
        }

        $voice_audits = $query
            ->sortable()
            ->orderBy('id', 'desc')
            ->paginate(15);

        $users = User::orderBy('name', 'asc')->get();
        $projects = Project::orderBy('name', 'asc')->get();

        $data['apiKey'] = 'L8qq9PZyRg6ieKGEKhZolGC0vJWLw8iEJ88DRdyOg';
        $queryString = http_build_query($data);
        $url = 'https://stagingcrm.touchstone-communications.com/api/campaigns?' . $queryString; //url of 2nd website where data is to be send+
        $getdata = $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $getdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $campaigns = curl_exec($ch);
        curl_close($ch);
        $campaigns = json_decode($campaigns);
        $team_leads = User::where('campaign_id', 4)
            ->role('Team Lead')
            ->where('status','active')
            ->orderBy('name', 'asc')
            ->get();
        return view('voice-audits.index')->with(compact('voice_evaluation', 'voice_audits', 'users', 'campaigns', 'projects', 'team_leads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, VoiceEvaluation $voice_evaluation)
    {
        $record_id = $request->record_id;
        $campaign_id = $request->campaign_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $data['apiKey'] = 'L8qq9PZyRg6ieKGEKhZolGC0vJWLw8iEJ88DRdyOg';
        $data['campaign_id'] = $request->campaign_id;
        $data['record_id'] = $request->record_id;
        $queryString = http_build_query($data);
        $url = 'https://stagingcrm.touchstone-communications.com/api/searchRecords?' . $queryString; //url of 2nd website where data is to be send+
        $getdata = $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $getdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $record = curl_exec($ch);
        curl_close($ch);
        @$record = json_decode(@$record);
        if (!$record) {
            return redirect()
                ->back()
                ->with('error', 'Record information not found!');
        }
        $categories = DatapointCategory::where('voice_evaluation_id', $voice_evaluation->id)
            ->where('campaign_id', @$record->campaign->id)
            ->where('project_id', @$record->project->id)
            ->where('status', 'active')
            ->with('datapoints')
            ->orderBy('sort', 'desc')
            ->get();
        $reporting_to = User::where('hrms_id', @$record->agent_user->reporting_to_id)->first();

        return view('voice-audits.create')->with(compact('voice_evaluation', 'categories', 'record', 'record_id', 'campaign_id', 'reporting_to', 'from_date', 'to_date'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoiceAuditRequest $request)
    {
        try {
            DB::beginTransaction();
            $campaign_id = $request->campaign_id;
            $data['record_id'] = $request->record_id;
            $data['outcome'] = $request->outcome;
            $data['campaign_id'] = $campaign_id;
            $data['score'] = $request->score;
            $data['apiKey'] = 'L8qq9PZyRg6ieKGEKhZolGC0vJWLw8iEJ88DRdyOg';
            $queryString = http_build_query($data);
            $url = 'https://stagingcrm.touchstone-communications.com/api/updateQmsStatus?' . $queryString; //url of 2nd website where data is to be send+
            $postdata = $data;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $result = curl_exec($ch);
            curl_close($ch);
            if (Session('data_url')) {
            $result = json_decode($result);
            if ($result->status == 200) {
                $voice_audit = VoiceAudit::create($request->all());
                $this->voiceAuditService->insertAuditPoints($request, $voice_audit);
                if ($request->review_priority == 1) {
                    $user = User::role('Team Lead')
                        ->where('hrms_id', $voice_audit->team_lead_id)
                        ->first();
                    if ($user) {
                        $manager = User::where('hrms_id', $user->reporting_to)->first();
                        $director = User::where('hrms_id', $manager->reporting_to)->first();
                        $this->sendEscalationEmail($voice_audit, $manager, $director);
                    } else {
                        $manager = User::where('hrms_id', $voice_audit->team_lead_id)->first();
                        $director = User::where('hrms_id', $manager->reporting_to)->first();
                        $this->sendEscalationEmail($voice_audit, $manager, $director);
                    }
                }
                    DB::commit();
                    return redirect(Session('data_url'))->with('success', 'Voice Audit created successfully!');
                }
            }
        } catch (\Exception $e) {
            $campaign_id = $request->campaign_id;
            $data['record_id'] = $request->record_id;
            $data['outcome'] = 'pending';
            $data['campaign_id'] = $campaign_id;
            $data['score'] = $request->score;
            $data['apiKey'] = 'L8qq9PZyRg6ieKGEKhZolGC0vJWLw8iEJ88DRdyOg';
            $queryString = http_build_query($data);
            $url = 'https://stagingcrm.touchstone-communications.com/api/updateQmsStatus?' . $queryString; //url of 2nd website where data is to be send+
            $postdata = $data;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $result = curl_exec($ch);
            curl_close($ch);
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
    // public function store(VoiceAuditRequest $request)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $data = [
    //             'record_id' => $request->record_id,
    //             'outcome' => $request->outcome,
    //             'campaign_id' => $request->campaign_id,
    //             'score' => $request->score,
    //             'apiKey' => 'L8qq9PZyRg6ieKGEKhZolGC0vJWLw8iEJ88DRdyOg'
    //         ];
    //         $queryString = http_build_query($data);
    //         $url = 'https://stagingstagingcrm.touchstone-communications.com/api/updateQmsStatus?' . $queryString;
    //         $ch = curl_init();
    //         curl_setopt_array($ch, [
    //             CURLOPT_URL => $url,
    //             CURLOPT_SSL_VERIFYPEER => false,
    //             CURLOPT_POST => true,
    //             CURLOPT_TIMEOUT => 60,
    //             CURLOPT_POSTFIELDS => $data,
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_FOLLOWLOCATION => true,
    //             CURLOPT_HTTPHEADER => ['Content-Type: application/json']
    //         ]);
    //         $result = json_decode(curl_exec($ch));
    //         curl_close($ch);
    //         if (Session::has('data_url')) {
    //         if ($result && $result->status == 200) {
    //             $voice_audit = VoiceAudit::create($request->all());
    //             $this->voiceAuditService->insertAuditPoints($request, $voice_audit);
    //             if ($request->review_priority == 1) {
    //                 $user = User::role('Team Lead')
    //                     ->where('hrms_id', $voice_audit->team_lead_id)
    //                     ->first();
    //                 if ($user) {
    //                     $manager = User::where('hrms_id', $user->reporting_to)->first();
    //                     $director = User::where('hrms_id', $manager->reporting_to)->first();
    //                     $this->sendEscalationEmail($voice_audit, $manager, $director);
    //                 } else {
    //                     $manager = User::where('hrms_id', $voice_audit->team_lead_id)->first();
    //                     $director = User::where('hrms_id', $manager->reporting_to)->first();
    //                     $this->sendEscalationEmail($voice_audit, $manager, $director);
    //                 }
    //             }
    //                 DB::commit();
    //                 return redirect(Session::get('data_url'))->with('success', 'Voice Audit created successfully!');
    //             }
    //         }
    //         throw new Exception('Failed to store voice audit');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         $data['outcome'] = 'pending';
    //         $queryString = http_build_query($data);
    //         $url = 'https://stagingstagingcrm.touchstone-communications.com/api/updateQmsStatus?' . $queryString;
    //         $ch = curl_init();
    //         curl_setopt_array($ch, [
    //             CURLOPT_URL => $url,
    //             CURLOPT_SSL_VERIFYPEER => false,
    //             CURLOPT_POST => true,
    //             CURLOPT_TIMEOUT => 60,
    //             CURLOPT_POSTFIELDS => $data,
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_FOLLOWLOCATION => true,
    //             CURLOPT_HTTPHEADER => ['Content-Type: application/json']
    //         ]);
    //         curl_exec($ch);
    //         curl_close($ch);
    //         return redirect()->back()->with('error', $e->getMessage());
    //     }
    // }

    public function sendEscalationEmail($voice_audit, $manager, $director)
    {
        if ($manager->email && $director->email) {
            $data['hrms_id'] = $voice_audit->associate->hrms_id ?? '';
            $data['agent'] = $voice_audit->associate->name ?? '';
            $data['manager'] = $manager->email;
            $data['director'] = $director->email;
            $data['recordId'] = $voice_audit->record_id;
            $data['phone'] = $voice_audit->customer_phone;
            $data['notes'] = $voice_audit->notes;
            $data['created_date'] = $voice_audit->created_at;
            Mail::send('mail.escalation', $data, function ($message) use ($data) {
                $message
                    ->to($data['manager'], 'Manager')
                    ->cc('qa@touchstone.com.pk', 'Quality Assurance')
                    ->cc('Training@touchstone.com.pk', 'Training')
                    ->cc('hujaved@touchstone.com.pk', 'QA Manager')
                    ->cc($data['director'], 'Director')
                    ->bcc('biahmed@touchstone.com.pk')
                    ->subject('QA Escalation Alert');
                $message->from('qa@touchstone.com.pk', 'QA Evaluations');
            });
        } else {
            $data['hrms_id'] = $voice_audit->associate->hrms_id ?? '';
            $data['agent'] = $voice_audit->associate->name ?? '';
            $data['manager'] = $manager->name;
            $data['director'] = $director->name;
            $data['recordId'] = $voice_audit->record_id;
            $data['phone'] = $voice_audit->customer_phone;
            $data['notes'] = $voice_audit->notes;
            $data['created_date'] = $voice_audit->created_at;
            Mail::send('mail.escalation-error', $data, function ($message) use ($data) {
                $message
                    ->to('db@touchstone.com.pk', 'DB')
                    ->to('hujaved@touchstone.com.pk', 'QA Manager')
                    ->cc('qa@touchstone.com.pk', 'Quality Assurance')
                    ->bcc('biahmed@touchstone.com.pk')
                    ->subject('QA Escalation Alert Error for Missing Information');
                $message->from('qa@touchstone.com.pk', 'QA Evaluations');
            });
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(VoiceAudit $voice_audit)
    {
        $this->voiceAuditService->auditShowAccess();

        $categories = $this->voiceAuditService->getAuditCategories($voice_audit);

        $voice_evaluation = VoiceEvaluation::findOrFail($voice_audit->voice_evaluation_id);

        return view('voice-audits.show')->with(compact('voice_audit', 'categories', 'voice_evaluation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(VoiceAudit $voice_audit)
    {
        $this->voiceAuditService->auditEditAccess($voice_audit);

        $users = User::role(['Manager', 'Team Lead', 'Associate'])
            ->orderBy('name', 'ASC')
            ->get();

        $categories = $this->voiceAuditService->getAuditCategories($voice_audit);

        $voice_evaluation = VoiceEvaluation::findOrFail($voice_audit->voice_evaluation_id);

        return view('voice-audits.edit')->with(compact('voice_audit', 'users', 'categories', 'voice_evaluation'));
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
        //return $request;
        $data = [
            'record_id' => $voice_audit->record_id,
            'outcome' => $request->outcome ?? $voice_audit->outcome,
            'campaign_id' => $voice_audit->campaign_id,
            'score' => $request->score ?? $voice_audit->score,
            'apiKey' => 'L8qq9PZyRg6ieKGEKhZolGC0vJWLw8iEJ88DRdyOg'
        ];
        $queryString = http_build_query($data);
        $url = 'https://stagingcrm.touchstone-communications.com/api/updateQmsStatus?' . $queryString;
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);
        $result = json_decode(curl_exec($ch));
        curl_close($ch);
        $this->voiceAuditService->auditEditAccess($voice_audit);

            $this->voiceAuditService->updateAuditPoints($request, $voice_audit);
            $voice_audit->update($request->all());
            $appeal = $this->voiceAuditService->updateAppeal($voice_audit);
            if ($appeal) {
                return redirect()
                    ->back()
                    ->with('success', 'Appeal accepted & Evaluation updated successfully!');
            }
            return redirect()
                ->back()
                ->with('success', 'Evaluation updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoiceAudit $voice_audit)
    {
        $this->voiceAuditService->auditDeleteAccess($voice_audit);

        $voice_audit->delete();
        return redirect()
            ->back()
            ->with('success', 'Voice Audit deleted successfully!');
    }

    public function qaPendingSaleSheet(VoiceEvaluation $voice_evaluation, Request $request)
    {
        $sales_data = '';
        $data['apiKey'] = 'L8qq9PZyRg6ieKGEKhZolGC0vJWLw8iEJ88DRdyOg';
        $queryString = http_build_query($data);
        $url = 'https://stagingcrm.touchstone-communications.com/api/campaigns?' . $queryString; //url of 2nd website where data is to be send+
        $getdata = $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $getdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $campaigns = curl_exec($ch);
        curl_close($ch);
        $campaigns = json_decode($campaigns);

        if ($request->campaign_id) {
            $data['id'] = $request->campaign_id;
            $data['from_date'] = @$request->from_date; //Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
            $data['to_date'] = @$request->to_date; //Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
            // print_r($data);exit;
            $queryString = http_build_query($data);
            // urlencode is used whenever the special characters comes
            $url = 'https://stagingcrm.touchstone-communications.com/api/qa_records?' . $queryString; //url of 2nd website where data is to be send+
            $getdata = $data;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $result = curl_exec($ch);
            curl_close($ch);
            $sales_data = json_decode($result);
        }
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        Session::put('data_url', request()->fullUrl());
        session::get('data_url');
        return view('voice-audits.qa-pending-sale-sheet', compact('voice_evaluation', 'sales_data', 'campaigns', 'from_date', 'to_date'));
    }

    public function getAuditDetail($voice_audit)
    {
        $voice_audit_detail = VoiceAudit::findOrFail($voice_audit);
        $data = [
            'voice_audit_id' => $voice_audit_detail->id,
        ];
        return response()->json($data, 200);
    }
    public function assignTeamLeadAudit(Request $request)
    {
        $assigned_audit = AssignedVoiceAudit::create($request->all());
        $voice_audit_id = $assigned_audit->voice_audit_id;
        VoiceAudit::where('id', $voice_audit_id)->update(['status' => 'assigned to team lead']);
        return redirect()
            ->back()
            ->with('success', 'Audit Assigned Successfully');
    }
}
