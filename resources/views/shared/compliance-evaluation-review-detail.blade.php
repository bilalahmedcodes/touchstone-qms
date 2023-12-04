<table class="table">
    <tr>
        <th>Call Date</th>
        <td>{{ $assigned_voice_audit_detail->call_date ?? '' }}</td>
    </tr>
    <tr>
        <th>Associate</th>
        <td>{{ $assigned_voice_audit_detail->associate->name ?? '' }}</td>
    </tr>
    <tr>
        <th>Campaign</th>
        <td>{{ $assigned_voice_audit_detail->associate->campaign->name ?? '' }}</td>
    </tr>
    <tr>
        <th>Project</th>
        <td>{{ $assigned_voice_audit_detail->project->name ?? '' }}</td>
    </tr>
    <tr>
        <th>Customer</th>
        <td>{{ $assigned_voice_audit_detail->customer_name ?? '' }}</td>
    </tr>
    @if (in_array(Auth::user()->roles[0]->name, ['Super Admin', 'Director', 'Manager']))
        <tr>
            <th>Phone</th>
            <td>{{ $assigned_voice_audit_detail->customer_phone ?? '' }}</td>
        </tr>
    @endif
    <tr>
        <th>Rec. Duration</th>
        <td>{{ $assigned_voice_audit_detail->recording_duration ?? '' }}</td>
    </tr>
    <tr>
        <th>Score</th>
        <td>{{ $assigned_voice_audit_detail->percentage ?? '' }}%</td>
    </tr>
    <tr>
        <th>Outcome</th>
        <td>
            @if ($assigned_voice_audit_detail->outcome == 'accepted')
                <span class="badge bg-success">Accepted</span>
            @else
                <span class="badge bg-danger">Rejected @if ($assigned_voice_audit_detail->review_priority == 1)
                        / Critical
                    @endif
                </span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Status</th>
        <td>
            @if ($assigned_voice_audit_detail->status == 'evaluated')
                                        <span class="badge bg-primary">Evaluated</span>
                                    @endif
        </td>
    </tr>
    <tr>
        <td><strong>Evaluation Time</strong></td>
        <td>{{ $assigned_voice_audit_detail->evaluation_time ?? '00:00:00' }}</td>
    </tr>
    <tr>
        <td colspan="2">
            <strong>Notes</strong><br>
            {{ $assigned_voice_audit_detail->notes ?? '' }}
        </td>
    </tr>
    {{-- @if ($voice_audit->appeal)
        <tr>
            <td colspan="2">
                <strong>Appeal Remarks</strong><br>
                {{ $voice_audit->appeal->remarks ?? '' }}
            </td>
        </tr>
    @endif

    @if ($voice_audit->action)
        <tr>
            <th>Action</th>
            <td>{{ $voice_audit->action->action->name }}</td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Action Remarks</strong><br>
                {{ $voice_audit->action->remarks ?? '' }}
            </td>
        </tr>
    @endif --}}
    {{-- <tr>
        <td colspan="2">
            <strong>Call Recording</strong><br><br>
            <audio controls style="width: 100%;">
                <source src="https://www.learningcontainer.com/wp-content/uploads/2020/02/Kalimba.mp3" type="audio/mpeg">
            </audio>
            <br>
            {{ $voice_audit->recording_link ?? '' }}
        </td>
    </tr> --}}
</table>
