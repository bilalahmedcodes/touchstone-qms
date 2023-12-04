<table class="table table-bordered">
    <thead>
        <tr>
            <th>QA Team Lead</th>
            <th>Associate</th>
            <th>Reporting to</th>
            <th>Campaign</th>
            <th>Project</th>
            <th>Score</th>
            <th>Outcome</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Call Date</th>
            <th>Audit Date</th>
        </tr>
    </thead>
    <tbody>

        @if (count($assigned_audits) > 0)

            @foreach ($assigned_audits as $key => $audit)
                <tr>
                    <td>{{ $audit->user->name ?? '' }}</td>
                    <td>{{ $audit->associate->name ?? '' }}</td>
                    <td>{{ $audit->associate->supervisor->name ?? '' }}</td>
                    <td>{{ $audit->campaign->name ?? '' }}</td>
                    <td>{{ $audit->project->name ?? '' }}</td>
                    <td>{{ $audit->percentage ?? 0 }}</td>
                    <td>
                        @if ($audit->outcome == 'accepted')
                            <span class="badge bg-success">Accepted</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td>{{ $audit->customer_name ?? '' }}</td>
                    <td>{{ $audit->customer_phone ?? 0 }}</td>
                    <td>{{ $audit->call_date ?? 0 }}</td>
                    <td>{{ $audit->created_at->format('d-m-Y g:i:s A') }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="10" class="text-center">No record found!</td>
            </tr>
        @endif
    </tbody>
</table>