<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ActionsExport;
use App\Exports\AppealsExport;
use App\Exports\EvaluationsExport;
use App\Exports\VoiceAuditsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ComplianceVoiceAuditExport;

class ExportController extends Controller
{
    public function voiceAudits(Request $request)
    {
        $now = now();
        return Excel::download(new VoiceAuditsExport($request), "Voice-Audits-{$now->toString()}.xlsx");
    }
    public function complianceVoiceAudits(Request $request)
    {
        $now = now();
        return Excel::download(new ComplianceVoiceAuditExport($request), "Compliance-Voice-Audits-{$now->toString()}.xlsx");
    }
    public function evaluations(Request $request, $status = null)
    {
        $now = now();
        return Excel::download(new EvaluationsExport($request), "Evaluations-{$now->toString()}.xlsx");
    }
    public function appeals(Request $request)
    {
        $now = now();
        return Excel::download(new AppealsExport($request), "Appeals-{$now->toString()}.xlsx");
    }
    public function actions(Request $request)
    {
        $now = now();
        return Excel::download(new ActionsExport($request), "Actions-{$now->toString()}.xlsx");
    }
}
