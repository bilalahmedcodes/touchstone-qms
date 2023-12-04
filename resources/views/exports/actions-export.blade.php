<table class="table table-bordered">
    <thead>
        <tr>
            <th>Record ID</th>
            <th>Evaluator</th>
            <th>HRMS ID</th>
            <th>Associate</th>
            <th>Reporting To</th>
            <th>Campaign</th>
            <th>Project</th>
            <th>Call Date</th>
            <th>Result</th>
            <th>Action</th>
            <th>Created Time</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>

        @if (count($voice_audits) > 0)

            @foreach ($voice_audits as $audit)
                <tr>
                    <td>{{ $audit->record_id ?? 0 }}</td>
                    <td>{{ $audit->user->name ?? 'undefined' }}</td>
                    <td>{{ $audit->associate->hrms_id ?? 'undefined' }}</td>
                    <td>{{ $audit->associate->name ?? 'undefined' }}</td>
                    <td>{{ $audit->associate->supervisor->name ?? 'undefined' }}</td>
                    <td>{{ $audit->campaign->name ?? 'undefined' }}</td>
                    <td>{{ $audit->project->name ?? '' }}</td>
                    <td>{{ $audit->call_date }}</td>
                    <td>{{ $audit->percentage }}%</td>
                    <td>{{ $audit->action->name }}</td>
                    <td>{{ $audit->created_at->format('d-m-Y g:i:s A') }}</td>
                    <td>{{ $audit->remarks ?? '' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="8" class="text-center">No record found!</td>
            </tr>
        @endif

    </tbody>
</table>
