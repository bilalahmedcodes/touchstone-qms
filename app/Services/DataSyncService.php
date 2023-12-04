<?php

namespace App\Services;

use App\Models\User;
use App\Models\Project;
use App\Models\Campaign;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class DataSyncService
{
    // campaigns
    public function campaigns()
    {
        $response = Http::get('https://crm.touchstone-communications.com/api/get-campaigns')->body();
        $response = json_decode($response, true);
        if ($response['success'] == 200) {
            $campaigns = json_decode($response['data']);
            if (count($campaigns) > 0) {
                foreach ($campaigns as $qms_campaign) {
                    if ($qms_campaign->id > 0) {
                        $campaign = Campaign::where('id', $qms_campaign->id)->first();
                        if (!$campaign) {
                            $campaign = new Campaign();
                        }
                        $campaign->id = $qms_campaign->id;
                        $campaign->name = $qms_campaign->name;
                        $campaign->table_name = $qms_campaign->table_name;
                        $campaign->status = $qms_campaign->status;
                        $campaign->save();
                    }
                }
            }
        }
    }

    public function clients()
    {
        $response = Http::get('https://crm.touchstone-communications.com/api/get-clients')->body();
        $response = json_decode($response, true);
        if ($response['success'] == 200) {
            $clients = json_decode($response['data']);
            if (count($clients) > 0) {
                foreach ($clients as $qms_client) {
                    if ($qms_client->id > 0) {
                        $client = Client::where('id', $qms_client->id)->first();
                        if (!$client) {
                            $client = new Client();
                        }
                        $client->id = $qms_client->id;
                        $client->client_code = $qms_client->client_code;
                        $client->name = $qms_client->name;
                        $client->campaign_id = $qms_client->campaign_id;
                        $client->save();
                    }
                }
            }
        }
    }

    public function projects()
    {
        $response = Http::get('https://crm.touchstone-communications.com/api/get-projects')->body();
        $response = json_decode($response, true);
        if ($response['success'] == 200) {
            $projects = json_decode($response['data']);
            if (count($projects) > 0) {
                foreach ($projects as $qms_project) {
                    if ($qms_project->id > 0) {
                        $project = Project::where('id', $qms_project->id)->first();
                        if (!$project) {
                            $project = new Project();
                        }
                        $project->id = $qms_project->id;
                        $project->name = $qms_project->name;
                        $project->project_code = $qms_project->project_code;
                        $project->client_id = $qms_project->client_id;
                        $project->save();
                    }
                }
            }
        }
    }

    public function users()
    {
        $response = Http::get('https://crm.touchstone-communications.com/api/employee-details')->body();
        $response = json_decode($response, true);
        if ($response['success'] == 200) {
            $users = $response['data'];
            if (count($users) > 0) {
                foreach ($users as $qms_user) {
                    if ($qms_user['id'] > 0) {
                        $password = '';
                        $user = User::where('hrms_id', $qms_user['hrms_id'])->first();
                        if (!$user) {
                            $user = new User();
                        }
                        $password = 'Touch@'.$qms_user['hrms_id'];
                        $fullName = @$qms_user['first_name'] . ' ' . @$qms_user['middle_name'] . ' ' . @$qms_user['last_name'];
                        $name = str_replace('-', '', $fullName);
                        $user->hrms_id = $qms_user['hrms_id'];
                        $user->reporting_to = $qms_user['reporting_to'];
                        $user->email = $qms_user['email'];
                        $user->password = $password;
                        $user->password_text = trim($password);
                        $user->name = $name;
                        $user->campaign_id = $this->assignCampaignId($qms_user['campaign']);
                        $user->status = $this->assignStatus($qms_user['status']);
                        $user->campaign_name = $qms_user['campaign'];
                        $user->save();
                        $directors = ['Assistant Director', 'Director Operations'];
                        $managers = ['Assistant Manager', 'Assistant Manager Operations', 'Manager', 'Manager Operations'];
                        $team_leads = ['Team Lead Ops', 'Team lead'];
                        $associates = ['CSR', 'Snr CSR', 'QAS'];
                        if (in_array($qms_user['designation'], $directors)) {
                            $user->assignRole('Director');
                        } elseif (in_array($qms_user['designation'], $managers)) {
                            $user->assignRole('Manager');
                        } elseif (in_array($qms_user['designation'], $team_leads)) {
                            $user->assignRole('Team Lead');
                        } elseif (in_array($qms_user['designation'], $associates)) {
                            $user->assignRole('Associate');
                        }
                    }
                }
            }
        }
    }

    public function assignCampaignId($campaign)
    {
        $campaign_id = 0;
        if ($campaign == 'Mortgage' || $campaign == 'Mortgage x' || $campaign == 'Mortgage Vertical') {
            $campaign_id = 3;
        } elseif ($campaign == 'Solar' || $campaign == 'Solar X') {
            $campaign_id = 2;
        } elseif ($campaign == 'Home Warranty') {
            $campaign_id = 1;
        } elseif ($campaign == 'QA vocie Team Humayun' || $campaign == 'Quality Operations') {
            $campaign_id = 4;
        } else {
            $campaign_id = 0;
        }
        return $campaign_id;
    }

    public function assignStatus($status)
    {
        $userStatus = '';
        if ($status == 'Confirmed' || $status == 'Probation') {
            $userStatus = 'active';
        } elseif ($status == 'Absence from work' || $status == 'Contract End' || $status == 'Dismissal' || $status == 'Resigned' || $status == 'Terminated' || $status == 'Training completed' || $status == 'Absence from work ' || $status == 'Contract End ' || $status == 'Dismissal ' || $status == 'Resigned ' || $status == 'Terminated ' || $status == 'Training completed ') {
            $userStatus = 'disabled';
        } else {
            $userStatus = 'disabled';
        }
        return $userStatus;
    }
}
