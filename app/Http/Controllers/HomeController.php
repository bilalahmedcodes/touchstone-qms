<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\VoiceAudit;
use Illuminate\Http\Request;
use App\Models\VoiceEvaluation;
use App\Models\VoiceAuditAppeal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = new VoiceAudit();

        $voice_audit_count = $this->getVoiceAuditCounts();
        $voice_accepted_count = $this->getVoiceAcceptedCounts();
        $voice_rejected_count = $this->getVoiceRejectedCounts();
        $voice_pending_reviews_count = $this->getVoicePendingReviewsCounts();
        $voice_audit_appeals_count = $this->getVoiceAuditAppealsCounts();
        $voice_actions_count = $this->getVoiceActionsCounts();
        $voice_pending_count = 0;

        $home_warranty_count = $this->getHomeWarrantyCounts();
        $home_warranty_accepted_count = $this->getHomeWarrantyAcceptedCounts();
        $home_warranty_rejected_count = $this->getHomeWarrantyRejectedCounts();
        $home_warranty_pending_count = $this->getHomeWarrantyPendingReviewsCounts();
        $home_warranty_audit_appeals_count = $this->getHomeWarrantyAuditAppealsCounts();
        $home_warranty_action_count = $this->getHomeWarrantyActionsCounts();
        $home_warranty_pending_count = 0;

        $mortgage_count = $this->getMortgageCounts();
        $mortgage_accepted_count = $this->getMortgageAcceptedCounts();
        $mortgage_rejected_count = $this->getMortgageRejectedCounts();
        $mortgage_pending_count = $this->getMortgagePendingReviewsCounts();
        $mortgage_audit_appeals_count = $this->getMortgageAuditAppealsCounts();
        $mortgage_action_count = $this->getMortgageActionsCounts();
        $mortgage_pending_count = 0;

        $solar_count = $this->getSolarCounts();
        $solar_accepted_count = $this->getSolarAcceptedCounts();
        $solar_rejected_count = $this->getSolarRejectedCounts();
        $solar_pending_count = $this->getSolarPendingReviewsCounts();
        $solar_audit_appeals_count = $this->getSolarAuditAppealsCounts();
        $solar_action_count = $this->getSolarActionsCounts();
        $solar_pending_count = 0;

        $response = Http::get('https://crm.touchstone-communications.com/api/pending_qa_counts?API_KEY=36ca9bf0d6acc4f31223b714f07a6279')->body();
        $response = json_decode($response, true);
        if ($response['status'] == 200) {
            $home_warranty_pending_count = $response['data']['home_warranty'];
            $mortgage_pending_count = $response['data']['solar'];
            $solar_pending_count = $response['data']['mortgage'];
        }

        if (in_array(Auth::user()->roles[0]->name, ['Super Admin']) || (Auth::user()->campaign_id == 4 || Auth::user()->campaign_id == 98)) {
            $query = new VoiceAudit();

            $query->with('user', 'associate', 'campaign');

            if (Auth::user()->roles[0]->name == 'Associate' && Auth::user()->campaign_id == 4) {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (in_array(Auth::user()->roles[0]->name, ['Team Lead']) && Auth::user()->campaign_id == 4) {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            } elseif (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Associate']) && Auth::user()->campaign_id != 4) {
                abort(403);
            }
            $voice_audits = $query
                ->sortable()
                ->orderBy('id', 'desc')
                ->paginate(10);
            if (in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead']) && Auth::user()->campaign_id == 4) {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
            $user_evaluations = User::whereHas('voiceAudits', function ($query) use ($request) {
                $query = $query->when($request, function ($query, $request) {
                    $query->search($request);
                });
            })->paginate(10);
            return view('home')->with(compact('voice_audit_count', 'voice_accepted_count', 'voice_pending_reviews_count', 'voice_audit_appeals_count', 'voice_actions_count', 'voice_audits', 'user_evaluations', 'home_warranty_count', 'home_warranty_accepted_count', 'home_warranty_pending_count', 'home_warranty_audit_appeals_count', 'home_warranty_action_count', 'mortgage_count', 'mortgage_accepted_count', 'mortgage_pending_count', 'mortgage_audit_appeals_count', 'mortgage_action_count', 'solar_count', 'solar_accepted_count', 'solar_pending_count', 'solar_audit_appeals_count', 'solar_action_count', 'home_warranty_pending_count', 'mortgage_pending_count', 'solar_pending_count', 'voice_rejected_count', 'home_warranty_rejected_count', 'mortgage_rejected_count', 'solar_rejected_count'));
        } elseif (in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead'])) {
            $query = new VoiceAudit();

            $query->with('user', 'associate', 'campaign');

            if (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager']) && (Auth::user()->hrms_id != 87223 || Auth::user()->hrms_id != 238430) && Auth::user()->campaign_id != 4) {
                $query = $query->where('campaign_id', Auth::user()->campaign_id);
            }

            if (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Director']) && (Auth::user()->hrms_id == 87223 || Auth::user()->hrms_id == 238430) && Auth::user()->campaign_id != 4) {
                $query = $query->where('project_id', 2);
            }

            $voice_audits = $query
                ->sortable()
                ->orderBy('id', 'desc')
                ->paginate(10);

            return view('home-campaign')->with(compact('voice_audit_count', 'voice_pending_reviews_count', 'voice_audit_appeals_count', 'voice_actions_count', 'voice_audits'));
        }

        return view('home-default');
    }

    public function getVoiceAuditCounts()
    {
        $query = new VoiceAudit();
        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager']) && (Auth::user()->hrms_id != 87223 || Auth::user()->hrms_id != 238430) && Auth::user()->campaign_id != 4) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        } elseif (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Director']) && (Auth::user()->hrms_id == 87223 || Auth::user()->hrms_id == 238430) && Auth::user()->campaign_id != 4) {
            $query = $query->where('project_id', 2);
        }
        $voice_audit_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $voice_audit_count;
    }
    public function getHomeWarrantyCounts()
    {
        $query = new VoiceAudit();
        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        // $voice_audit_count = $query->count();
        $home_warranty_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('campaign_id', 1)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $home_warranty_count;
    }
    public function getSolarCounts()
    {
        $query = new VoiceAudit();

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        // $voice_audit_count = $query->count();
        $solar_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('campaign_id', 2)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $solar_count;
    }
    public function getMortgageCounts()
    {
        $query = new VoiceAudit();
        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        // $voice_audit_count = $query->count();
        $mortgage_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('campaign_id', 3)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $mortgage_count;
    }
    public function getVoiceAcceptedCounts()
    {
        $query = new VoiceAudit();

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        $voice_accepted_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'accepted')
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $voice_accepted_count;
    }

    public function getHomeWarrantyAcceptedCounts()
    {
        $query = new VoiceAudit();

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        $home_warranty_accepted_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'accepted')
            ->where('campaign_id', 1)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $home_warranty_accepted_count;
    }
    public function getMortgageAcceptedCounts()
    {
        $query = new VoiceAudit();

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        $mortgage_accepted_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'accepted')
            ->where('campaign_id', 3)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $mortgage_accepted_count;
    }
    public function getSolarAcceptedCounts()
    {
        $query = new VoiceAudit();

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        $solar_accepted_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'accepted')
            ->where('campaign_id', 2)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $solar_accepted_count;
    }

    public function getVoiceRejectedCounts()
    {
        $query = new VoiceAudit();

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        $voice_rejected_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'rejected')
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $voice_rejected_count;
    }
    public function getHomeWarrantyRejectedCounts()
    {
        $query = new VoiceAudit();

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        $home_warranty_rejected_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'rejected')
            ->where('campaign_id', 1)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $home_warranty_rejected_count;
    }
    public function getMortgageRejectedCounts()
    {
        $query = new VoiceAudit();

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        $mortgage_rejected_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'rejected')
            ->where('campaign_id', 3)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $mortgage_rejected_count;
    }
    public function getSolarRejectedCounts()
    {
        $query = new VoiceAudit();

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        $solar_rejected_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'rejected')
            ->where('campaign_id', 2)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $solar_rejected_count;
    }

    public function getVoicePendingReviewsCounts()
    {
        $query = new VoiceAudit();

        $query = $query->doesnthave('appeal');
        $query = $query->doesnthave('action');

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager']) && (Auth::user()->hrms_id != 87223 || Auth::user()->hrms_id != 238430) && Auth::user()->campaign_id != 4) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        } elseif (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Director']) && (Auth::user()->hrms_id == 87223 || Auth::user()->hrms_id == 238430) && Auth::user()->campaign_id != 4) {
            $query = $query->where('project_id', 2);
        }
        $voice_pending_reviews_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'rejected')
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $voice_pending_reviews_count;
    }
    public function getHomeWarrantyPendingReviewsCounts()
    {
        $query = new VoiceAudit();

        $query = $query->doesnthave('appeal');
        $query = $query->doesnthave('action');

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        $home_warranty_pending_reviews_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'rejected')
            ->where('campaign_id', 1)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $home_warranty_pending_reviews_count;
    }
    public function getMortgagePendingReviewsCounts()
    {
        $query = new VoiceAudit();

        $query = $query->doesnthave('appeal');
        $query = $query->doesnthave('action');

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        $mortgage_pending_reviews_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'rejected')
            ->where('campaign_id', 3)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $mortgage_pending_reviews_count;
    }
    public function getSolarPendingReviewsCounts()
    {
        $query = new VoiceAudit();

        $query = $query->doesnthave('appeal');
        $query = $query->doesnthave('action');

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        $solar_pending_reviews_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'rejected')
            ->where('campaign_id', 2)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $solar_pending_reviews_count;
    }

    public function getVoiceAuditAppealsCounts()
    {
        $query = new VoiceAudit();

        $query = $query->has('appeal');

        $query = $query->whereHas('appeal', function ($query) {
            $query = $query->where('status', 'pending');
        });

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager']) && (Auth::user()->hrms_id != 87223 || Auth::user()->hrms_id != 238430) && Auth::user()->campaign_id != 4) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        } elseif (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Director']) && (Auth::user()->hrms_id == 87223 || Auth::user()->hrms_id == 238430) && Auth::user()->campaign_id != 4) {
            $query = $query->where('project_id', 2);
        }
        // $voice_audit_appeals_count = $query->count();
        $voice_audit_appeals_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $voice_audit_appeals_count;
    }

    public function getHomeWarrantyAuditAppealsCounts()
    {
        $query = new VoiceAudit();

        $query = $query->has('appeal');

        $query = $query->whereHas('appeal', function ($query) {
            $query = $query->where('status', 'pending');
        });

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        // $voice_audit_appeals_count = $query->count();
        $home_warranty_audit_appeals_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('campaign_id', 1)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $home_warranty_audit_appeals_count;
    }
    public function getMortgageAuditAppealsCounts()
    {
        $query = new VoiceAudit();

        $query = $query->has('appeal');

        $query = $query->whereHas('appeal', function ($query) {
            $query = $query->where('status', 'pending');
        });

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        // $voice_audit_appeals_count = $query->count();
        $mortgage_audit_appeals_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('campaign_id', 3)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $mortgage_audit_appeals_count;
    }

    public function getSolarAuditAppealsCounts()
    {
        $query = new VoiceAudit();

        $query = $query->has('appeal');

        $query = $query->whereHas('appeal', function ($query) {
            $query = $query->where('status', 'pending');
        });

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        // $voice_audit_appeals_count = $query->count();
        $solar_audit_appeals_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('campaign_id', 2)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $solar_audit_appeals_count;
    }

    public function getVoiceActionsCounts()
    {
        $query = new VoiceAudit();

        $query = $query->has('action');

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager']) && (Auth::user()->hrms_id != 87223 || Auth::user()->hrms_id != 238430) && Auth::user()->campaign_id != 4) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        } elseif (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Director']) && (Auth::user()->hrms_id == 87223 || Auth::user()->hrms_id == 238430) && Auth::user()->campaign_id != 4) {
            $query = $query->where('project_id', 2);
        }
        // $voice_actions_count = $query->where('outcome', 'rejected')->count();
        $voice_actions_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'rejected')
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $voice_actions_count;
    }

    public function getHomeWarrantyActionsCounts()
    {
        $query = new VoiceAudit();

        $query = $query->has('action');

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        // $voice_actions_count = $query->where('outcome', 'rejected')->count();
        $home_warranty_actions_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'rejected')
            ->where('campaign_id', 1)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $home_warranty_actions_count;
    }

    public function getMortgageActionsCounts()
    {
        $query = new VoiceAudit();

        $query = $query->has('action');

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        // $voice_actions_count = $query->where('outcome', 'rejected')->count();
        $mortgage_actions_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'rejected')
            ->where('campaign_id', 3)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $mortgage_actions_count;
    }
    public function getSolarActionsCounts()
    {
        $query = new VoiceAudit();

        $query = $query->has('action');

        if (Auth::user()->campaign_id == 4) {
            if (Auth::user()->roles[0]->name == 'Associate') {
                $query = $query->where('user_id', Auth::user()->id);
            } elseif (Auth::user()->roles[0]->name == 'Team Lead') {
                $query = $query->whereHas('user', function ($query) {
                    $query = $query->where('reporting_to', Auth::user()->id);
                    $query = $query->orWhere('id', Auth::user()->id);
                });
            }
        } elseif (in_array(Auth::user()->roles[0]->name, ['Manager', 'Team Lead'])) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }
        // $voice_actions_count = $query->where('outcome', 'rejected')->count();
        $solar_actions_count = $query
            ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
            ->where('outcome', 'rejected')
            ->where('campaign_id', 2)
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthname')
            ->get();
        return $solar_actions_count;
    }
}
