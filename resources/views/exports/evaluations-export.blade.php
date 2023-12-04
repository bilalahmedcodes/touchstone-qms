<table class="table table-bordered">
    <thead>
        <tr>
            <th>Record ID</th>
            <th>HRMS ID</th>
            <th>Associate</th>
            <th>Reporting To</th>
            <th>Campaign</th>
            <th>Project</th>
            <th>Call Date</th>
            @if (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Director']) &&
                    (Auth::user()->hrms_id == 87223 || Auth::user()->hrms_id == 238430))
                <th>Phone</th>
            @endif
            <th>Result</th>
            <th>Client Outcome</th>
            <th>Agent Outcome</th>
            <th>Status</th>
            <th>Created Time</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>

        @if (count($voice_audits) > 0)

            @foreach ($voice_audits as $audit)
                <tr>
                    <td>
                        {{ $audit->record_id ?? 0 }}
                    </td>
                    <td>{{ $audit->associate->hrms_id ?? 'undefined' }}</td>
                    <td>{{ $audit->associate->name ?? 'undefined' }}</td>
                    <td>{{ $audit->associate->supervisor->name ?? '' }}</td>
                    <td>{{ $audit->campaign->name ?? 'undefined' }}</td>
                    <td>{{ $audit->project->name ?? '' }}</td>
                    <td>{{ $audit->call_date ?? '' }}</td>
                    @if (in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Director']) &&
                            (Auth::user()->hrms_id == 87223 || Auth::user()->hrms_id == 238430))
                        <td>{{ $audit->customer_phone ?? '' }}</td>
                    @endif
                    <td>{{ $audit->percentage ?? '' }}%</td>
                    <td>
                        @if ($audit->outcome == 'accepted')
                            <span class="badge bg-success">Billable</span>
                        @else
                            <span class="badge bg-danger">Non-Billable @if ($audit->review_priority == 1)
                                    / Critical
                                @endif
                            </span>
                        @endif
                    </td>
                    <td>
                        @if ($audit->agent_outcome == 'accepted')
                            <span class="badge bg-success">Accepted</span>
                        @else
                            <span class="badge bg-danger">Rejected @if ($audit->review_priority == 1)
                                    / Critical
                                @endif
                            </span>
                        @endif
                    </td>
                    <td>
                        @include('shared.voice-audit-status', ['status' => $audit->status])
                    </td>
                    <td>{{ $audit->created_at->format('d-m-Y g:i:s A') }}</td>
                    <td>{{ $audit->notes ?? '' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="8" class="text-center">No record found!</td>
            </tr>
        @endif
    </tbody>
</table>
