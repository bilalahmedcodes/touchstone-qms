<?php

namespace App\Exports;
use App\Models\AssignedVoiceAuditDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class ComplianceVoiceAuditExport implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }
    public function view(): View
    {
        $request = $this->request;

        $query = new AssignedVoiceAuditDetail();

        $query->with('user', 'associate', 'campaign');

        $query = $query->when($request, function ($query, $request) {
            $query->search($request);
        });

        $assigned_audits = $query->orderBy('id', 'desc')->get();

        return view('exports.compliance-voice-audits', [
            'assigned_audits' => $assigned_audits,
        ]);
    }
}
