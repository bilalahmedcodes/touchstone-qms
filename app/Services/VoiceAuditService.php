<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Datapoint;
use App\Models\VoiceAudit;
use App\Models\VoiceAuditPoint;
use App\Models\VoiceAuditAppeal;
use App\Models\DatapointCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\VoiceAuditCustomField;
use App\Models\AssignedVoiceAuditPoint;

/**
 *
 */
class VoiceAuditService
{
    // insert audit data points
//    public function insertAuditPoints($request, $voice_audit)
//    {
//        foreach ($request->all() as $key => $item) {
//            $key = explode("-", $key);
//            if (count($key) > 1) {
//                if ($key[0] == "answer") {
//                    $datapoint = Datapoint::find($key[1]);
//                    VoiceAuditPoint::create([
//                        "voice_audit_id" => $voice_audit->id,
//                        "datapoint_category_id" => $datapoint->datapoint_category_id,
//                        "datapoint_id" => $datapoint->id,
//                        "answer" => $item
//                    ]);
//                }
//            }
//        }
//    }
    public function insertAuditPoints($request, $voice_audit)
    {
        foreach ($request->all() as $key => $item) {
            $key = explode('-', $key);
            if (count($key) > 1) {
                if ($key[0] == 'answer') {
                    $datapoint = Datapoint::find($key[1]);
                    if (str_contains($item, 'Na')) {
                        $item=-1;
                    }
                    VoiceAuditPoint::create([
                        'voice_audit_id' => $voice_audit->id,
                        'datapoint_category_id' => $datapoint->datapoint_category_id,
                        'datapoint_id' => $datapoint->id,
                        'answer' => $item,
                    ]);
                }
            }
        }
    }
    public function insertAssignedAuditPoints($request, $assigned_voice_audit_detail)
    {
        foreach ($request->all() as $key => $item) {
            $key = explode("-", $key);
            if (count($key) > 1) {
                if ($key[0] == "answer") {
                    $datapoint = Datapoint::find($key[1]);
                    AssignedVoiceAuditPoint::create([
                        "voice_audit_id" => $assigned_voice_audit_detail->voice_audit_id,
                        "assigned_voice_audit_id" => $assigned_voice_audit_detail->assigned_voice_audit_id,
                        "assigned_voice_audit_detail_id" => $assigned_voice_audit_detail->id,
                        "datapoint_category_id" => $datapoint->datapoint_category_id,
                        "datapoint_id" => $datapoint->id,
                        "answer" => $item,
                    ]);
                }
            }
        }
    }

    // insert audit data points
    public function updateAuditPoints($request, $voice_audit)
    {

        foreach ($request->all() as $key => $item) {
            $key = explode("-", $key);

            if (count($key) > 1) {
                if ($key[0] == "answer") {
                    if (str_contains($item, 'Na')) {
                        $item=-1;
                    }
                    $ev_point = VoiceAuditPoint::find($key[1]);
                    $ev_point->update(["answer" => $item]);
                }
            }
        }
    }

    public function getAuditCategories($voice_audit)
    {
        $datapoint_categories = DatapointCategory::where('campaign_id', $voice_audit->campaign_id)
            ->where('project_id', $voice_audit->project_id)
            ->orderBy('sort', 'asc')
            ->get();

        // get evaluation categories
        $categories = array();
        foreach ($datapoint_categories as $category) {
            $ev_points = VoiceAuditPoint::with('datapoint')
                ->where('datapoint_category_id', $category->id)
                ->where('voice_audit_id', $voice_audit->id)
                ->orderBy('id', 'asc')
                ->get();
            $categories[$category->name] = $ev_points;
        }

        return $categories;
    }

    public function getComplianceAuditCategories($voice_audit)
    {
        $datapoint_categories = DatapointCategory::where('campaign_id', $voice_audit->campaign_id)
            ->where('project_id', $voice_audit->project_id)
            ->orderBy('sort', 'asc')
            ->get();
        // get evaluation categories
        $categories = array();
        foreach ($datapoint_categories as $category) {
            $ev_points = AssignedVoiceAuditPoint::with('datapoint')
                ->where('datapoint_category_id', $category->id)
                ->where('voice_audit_id', $voice_audit->id)
                ->orderBy('id', 'asc')->get();
            $categories[$category->name] = $ev_points;
        }

        return $categories;
    }

    public function updateAppeal($voice_audit)
    {
        if ($voice_audit->appeal && $voice_audit->outcome == 'accepted') {
            $appeal = VoiceAuditAppeal::findOrFail($voice_audit->appeal->id);
            $appeal->status = 'accepted';
            $appeal->save();

            $voice_audit->status = 'appeal accepted';
            $voice_audit->save();

            return true;
        }

        return false;
    }


    public function auditShowAccess()
    {
        $access = false;
        if (in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead', 'Associate']) && Auth::user()->campaign_id == 4) {
            $access = true;
        } elseif (in_array(Auth::user()->roles[0]->name, ['Super Admin', 'Director'])) {
            $access = true;
        }

        if ($access == false) {
            abort(403);
        }

        return true;
    }

    public function auditEditAccess($voice_audit)
    {
        $access = false;
        if (in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead', 'Associate']) && Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate' && $voice_audit->user_id == Auth::user()->id) {
                $diff = $voice_audit->created_at->diffInHours(now());
                if ($diff < 24) {
                    $access = true;
                }
            } elseif (in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead'])) {
                $access = true;
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Super Admin'])) {
            $access = true;
        }

        if ($access == false) {
            abort(403);
        }

        return true;
    }

    public function auditDeleteAccess($voice_audit)
    {
        $access = false;

        if (in_array(Auth::user()->roles[0]->name, ['Super Admin']) || (in_array(Auth::user()->roles[0]->name, ['Manager']) && (Auth::user()->campaign_id == 4))) {
            $access = true;
        }

        if ($access == false) {
            abort(403);
        }

        return true;
    }


}
