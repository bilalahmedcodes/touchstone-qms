<table class="table table-bordered">
    <thead>
        <tr>
            <th>Evaluator</th>
            <th>Record ID</th>
            <th>Call Date</th>
            <th>Customer Name</th>
            <th>Customer Phone</th>
            <th>HRMS ID</th>
            <th>Associate</th>
            <th>Reporting To</th>
            <th>Campaign</th>
            <th>Project</th>
            <th>Result</th>
            <th>Outcome</th>
            @if (in_array(Auth::user()->roles[0]->name, ['Super Admin']) ||
                    (in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead', 'Associate']) &&
                        Auth::user()->campaign_id == 4))
                <th>Agent Outcome</th>
            @endif
            <th>Call Type</th>
            <th>Handling Time</th>
            <th>Call Time</th>
            <th>Evaluation Date</th>
            <th>QA Notes</th>
        </tr>
    </thead>
    <tbody>


        @foreach ($voice_audits as $audit)
            <tr>
                <td>{{ $audit->user->name ?? '' }}</td>
                <td>{{ $audit->record_id ?? 0 }}</td>
                <td>{{ $audit->call_date }}</td>
                <td>{{ $audit->customer_name ?? '' }}</td>
                <td>{{ $audit->customer_phone ?? '' }}</td>
                <td>{{ $audit->associate->hrms_id ?? '' }}</td>
                <td>{{ $audit->associate->name ?? '' }}</td>
                <td>{{ $audit->associate->supervisor->name ?? '' }}</td>
                <td>{{ $audit->campaign->name ?? '' }}</td>
                <td>{{ $audit->project->name ?? '' }}</td>
                <td>{{ $audit->percentage }}</td>
                <td>
                    @if ($audit->outcome == 'accepted')
                        Accepted
                    @else
                        Rejected
                    @endif
                </td>
                @if (in_array(Auth::user()->roles[0]->name, ['Super Admin']) ||
                        (in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead', 'Associate']) &&
                            Auth::user()->campaign_id == 4))
                    <td>
                        @if ($audit->agent_outcome == 'accepted')
                            <span class="badge bg-success">Accepted</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                @endif
                <td>
                    @if ($audit->call_type == 'general')
                        <span class="badge bg-dark">General</span>
                    @else
                        <span class="badge bg-success">Sales</span>
                    @endif
                </td>
                <td>{{ $audit->evaluation_time }}</td>
                <td>{{ $audit->recording_duration }}</td>
                <td>{{ $audit->created_at->format('d-m-Y g:i:s A') }}</td>
                <td>{{ $audit->notes }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
