<table class="table">
    <tr>
        <th style="border-top: none;">Record ID</th>
        <td style="border-top: none;">{{ $voice_audit->record_id ?? '' }}</td>
    </tr>
    <tr>
        <th>Call Date</th>
        <td>{{ $voice_audit->call_date ?? '' }}</td>
    </tr>
    <tr>
        <th>Associate</th>
        <td>{{ $voice_audit->associate->name ?? '' }}</td>
    </tr>
    <tr>
        <th>Campaign</th>
        <td>{{ $voice_audit->campaign->name ?? '' }}</td>
    </tr>
    <tr>
        <th>Project</th>
        <td>{{ $voice_audit->project->name ?? '' }}</td>
    </tr>
    <tr>
        <th>Customer</th>
        <td>{{ $voice_audit->customer_name ?? '' }}</td>
    </tr>
    @if (in_array(Auth::user()->roles[0]->name, ['Super Admin']) || (in_array(Auth::user()->roles[0]->name, ['Director','Manager']) && Auth::user()->campaign_id == 4))
        <tr>
            <th>Phone</th>
            <td>{{ $voice_audit->customer_phone ?? '' }}</td>
        </tr>
    @endif
    <tr>
        <th>Rec. Duration</th>
        <td>{{ $voice_audit->recording_duration ?? '' }}</td>
    </tr>
    <tr>
        <th>Score</th>
        <td>{{ $voice_audit->percentage ?? '' }}%</td>
    </tr>
    <tr>
        <th>Client Outcome</th>
        <td>
            @if ($voice_audit->outcome == 'accepted')
                <span class="badge bg-success">Billable</span>
            @else
                <span class="badge bg-danger">Non Billable @if ($voice_audit->review_priority == 1)
                        / Fatal
                    @endif
                </span>
            @endif
        </td>
    </tr>
    
    <tr>
        <th>Agent Outcome</th>
        <td>
            @if ($voice_audit->agent_outcome == 'accepted')
                <span class="badge bg-success">Accepted</span>
            @else
                <span class="badge bg-danger">Rejected
                </span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Status</th>
        <td>
            @include('shared.voice-audit-status', ['status' => $voice_audit->status])
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <strong>Notes</strong><br>
            {{ $voice_audit->notes ?? '' }}
        </td>
    </tr>
    @if ($voice_audit->appeal)
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
    @endif
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
