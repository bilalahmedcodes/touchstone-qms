<?php

namespace App\Exports;

use App\Models\VoiceAudit;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class EvaluationsExport implements FromView
{
    protected $request, $status;

    public function __construct($request, $status = null)
    {
        $this->request = $request;
        $this->status = $status;

    }

    public function view(): View
    {
        $request = $this->request;

        $status = $this->request->status;
        $query = new VoiceAudit();
        $query->with('user', 'associate', 'campaign');

        $query = $query->when($request, function ($query, $request) {
            $query->search($request);
        });
        if (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager']) && (Auth::user()->hrms_id != 87223 || Auth::user()->hrms_id != 238430) && Auth::user()->campaign_id != 4) {
            $query = $query->where('campaign_id', Auth::user()->campaign_id);
        }

        if (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Director']) && (Auth::user()->hrms_id == 87223 || Auth::user()->hrms_id == 238430) && Auth::user()->campaign_id != 4) {
            $query = $query->where('project_id', 2);
        }

        if ($status == 'pending') {
            $query = $query->where('outcome', 'rejected');
            $query = $query->doesnthave('appeal');
            $query = $query->doesnthave('action');
        } elseif ($status == 'rejected') {
            $query = $query->where('outcome', 'rejected');
        }
        $voice_audits = $query->orderBy('id', 'desc')->get();

        return view('exports.evaluations-export', [
            'voice_audits' => $voice_audits,
        ]);
    }
}
