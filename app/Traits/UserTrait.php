<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

/**
 *
 */
trait UserTrait
{
    public function campaignUsers(Request $request, $campaign_id)
    {
        $users = User::select("id", DB::raw("name as text"))->where('campaign_id', $campaign_id)->where('status', 1)->where('name', 'LIKE', "%{$request->q}%")->get();
        $data = ['results' => $users];
        return response()->json($data, 200);
    }

    public function getDetail($user_id){
        $user = User::findOrFail($user_id);

        $reporting_id = "";
        $reporting = "";
        $campaign = "";

        if($user->supervisor){
            $reporting = $user->supervisor->name;
            $reporting_id = $user->supervisor->id;
        }

        if($user->campaign){
            $campaign = $user->campaign->name;
        }

        $data = ['reporting_to' => $reporting, 'reporting_id' => $reporting_id, 'campaign' => $campaign];

        return response()->json($data, 200);
    }
}


