<?php

namespace App\Http\Controllers\API;

use App\Models\VoiceAudit;
use Illuminate\Http\Request;
use App\Models\VoiceAuditAppeal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\VoiceReviewAppealRequest;

class ApiController extends Controller
{
    public function getQANotes(Request $request)
    {
        $data = VoiceAudit::with('appeal')
            ->where('record_id', $request->record_id)
            ->first();

        return response()->json([
            'success' => 200,
            'message' => 'Voice Audit',
            'data' => $data ? $data : [],
        ]);
    }
    public function getAllVoiceAudits(Request $request)
    {
        $data = VoiceAudit::select('id','record_id','percentage','notes','campaign_id')->where('crm_status',0)->get();

        return response()->json([
            'success' => 200,
            'message' => 'Voice Audit',
            'data' => $data ? $data : [],
        ]);
    }

    public function updateAudit(Request $request)
    {
        // return $request;
        $data = \DB::table('voice_audits')->where('id',$request->id)->limit(1)->update([
            'crm_status' => "1"
        ]); 

        return response()->json([
            'success' => 200,
            'message' => 'UpdateVoice Audit', 
            'id' =>$request->id
        ]);
    }
    public function generateAppeal(Request $request)
    {
        try {
            $voice_audit = VoiceAudit::where('record_id', $request->record_id)
                ->where('associate_id', $request->associate_id)
                ->first();

            $appeal = VoiceAuditAppeal::where('voice_audit_id', $voice_audit->id)->count();
            if ($appeal > 0) {
                return response()->json([
                    'error' => 500,
                    'Message' => 'Appeal Already Exists',
                ]);
            }
            $voice_audit_appeal = VoiceAuditAppeal::create([
                'user_id' => $request->user_id,
                'campaign_id' => $request->campaign_id,
                'voice_audit_id' => $voice_audit->id,
                'remarks' => $request->remarks,
            ]);
            $voice_audit->status = 'appeal requested';
            $voice_audit->save();
            return response()->json([
                'success' => 200,
                'Message' => 'Appeal Generated Successfully',
                'data' => $voice_audit_appeal ? $voice_audit_appeal : [],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => 500,
                'Message' => $e->getMessage(),
            ]);
        }
    }

    public function updateVoiceAudit()
    {
        $users = DB::table('crm_users')->get();
        // return $crm_users;
        try {
            foreach ($users as $user) {
                // return $user;
                DB::table('voice_audits')
                    ->where('associate_id', $user->id)
                    ->update([
                        'associate_id' => $user->hrms_id,
                        'team_lead_id' => $user->reporting_to ?? 0,
                    ]);
            }
            return response()->json([
                'success' => 200,
                'message' => 'Updated Successfully',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'failed' => 500,
                'Message' => $e->getMessage(),
            ]);
        }
    }
}
