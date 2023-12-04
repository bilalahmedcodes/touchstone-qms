<table class="table table-bordered">
    <thead>
        <tr>
            <th>Record ID</th>
            <th>Agent Name</th>
            <th>Outcome</th>
        </tr>
    </thead>
    <tbody>
        @if (count($audits) > 0)
            @foreach ($audits as $audit)
                <tr>
                    <tr>
                        <td>{{ $audit->record_id ?? '-' }}</td>
                        <td>{{ $audit->user->name ?? '-' }}</td>
                        <td>{{ $audit->outcome ?? '-' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center">No record found!</td>
            </tr>
        @endif
    </tbody>
</table>