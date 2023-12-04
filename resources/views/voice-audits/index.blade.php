@extends('layouts.user')

@section('title', $voice_evaluation->name ?? 'Voice Audits')


@section('content')

    <div class="search-area">
        <div class="row">

            <div class="col-md-6">
                <h4 class="mb-0">Search</h4>
            </div>
            <div class="col-md-6">
                <div class="button-area">
                    <button type="button" id="btn-search" class="btn btn-primary"><i class="fas fa-filter"></i></button>
                </div>
            </div>

        </div>
        @if ($voice_evaluation)
            <form action="{{ route('voice-audits.index', $voice_evaluation->id) }}" method="get" autocomplete="off">
                @else
                    <form action="{{ route('voice-audits.index') }}" method="get" autocomplete="off">
                        @endif
                        <input type="hidden" name="search" value="1">
                        @php
                            $record_id = '';
                            $user_id = '';
                            $associate_id = '';
                            $campaign_id = '';
                            $project_id = '';
                            $outcome = '';
                            $status = '';
                            $from_date = '';
                            $to_date = '';
                            $from_time = '';
                            $to_time = '';

                            if (isset($_GET['search'])) {
                                if (!empty(@$_GET['user_id'])) {
                                    $user_id = @$_GET['user_id'];
                                }
                                if (!empty(@$_GET['associate_id'])) {
                                    $associate_id = @$_GET['associate_id'];
                                }
                                if (!empty(@$_GET['campaign_id'])) {
                                    $campaign_id = @$_GET['campaign_id'];
                                }
                                if (!empty(@$_GET['outcome'])) {
                                    $outcome = @$_GET['outcome'];
                                }
                                if (!empty(@$_GET['status'])) {
                                    $status = @$_GET['status'];
                                }
                                if (!empty(@$_GET['from_date'])) {
                                    $from_date = @$_GET['from_date'];
                                }
                                if (!empty(@$_GET['to_date'])) {
                                    $to_date = @$_GET['to_date'];
                                }
                                if (!empty(@$_GET['record_id'])) {
                                    $record_id = @$_GET['record_id'];
                                }

                                if (!empty(@$_GET['from_time'])) {
                                    $from_time = @$_GET['from_time'];
                                }
                                if (!empty(@$_GET['to_time'])) {
                                    $to_time = @$_GET['to_time'];
                                }
                                if (!empty(@$_GET['project_id'])) {
                                    $project_id = @$_GET['project_id'];
                                }
                            }

                        @endphp

                        <div class="card card-primary card-outline mt-3" id="search"
                             @if (isset($_GET['search'])) style="display: block;" @endif>
                            <div class="card-body">
                                <div class="row">

                                    <div class="form-group col-md-4">
                                        <label for="">Record ID</label>
                                        <input type="text" class="form-control" name="record_id"
                                               value="{{ $record_id }}"/>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="">Select Evaluator</label>
                                        <select name="user_id" class="form-control select2">
                                            <option value="-1">Select Option</option>
                                            @foreach ($users as $user)
                                                @if ($user->campaign_id == 4)
                                                    <option value="{{ $user->id }}"
                                                            @if ($user->id == $user_id) selected @endif>
                                                        {{ $user->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="">Select Associate</label>
                                        <select name="associate_id" class="form-control select2">
                                            <option value="-1">Select Option</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->hrms_id }}"
                                                        @if ($user->hrms_id == $associate_id) selected @endif>
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="">Select Campaign</label>
                                        <select name="campaign_id" class="form-control select2">
                                            <option value="-1">Select Option</option>
                                            @foreach ($campaigns as $campaign)
                                                <option value="{{ $campaign->id }}"
                                                        @if ($campaign->id == $campaign_id) selected @endif>
                                                    {{ $campaign->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="">Select Project</label>
                                        <select name="project_id" class="form-control select2">
                                            <option value="-1">Select Option</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}"
                                                        @if ($project->id == $project_id) selected @endif>
                                                    {{ $project->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="">Select Client Outcome</label>
                                        <select name="outcome" class="form-control select2">
                                            <option value="">Select</option>
                                            <option value="accepted" @if ($outcome == 'accepted') selected @endif>
                                                Billable
                                            </option>
                                            <option value="rejected" @if ($outcome == 'rejected') selected @endif>
                                                Non Billable
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="">Select Assigned to Team Lead</label>
                                        <select name="status" class="form-control select2">
                                            <option value="">Select</option>
                                            <option value="assigned to team lead"
                                                    @if ($status == 'assigned to team lead') selected @endif>Assigned
                                                to Team Lead
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="">From Date</label>
                                        <input type="datetime-local" class="form-control " name="from_date"
                                               value="{{ $from_date }}"  />
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="">To Date</label>
                                        <input type="datetime-local" class="form-control " name="to_date"
                                               value="{{ $to_date }}"  />
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Search</button>
                                @if ($voice_evaluation)
                                    <a href="{{ route('voice-audits.index', $voice_evaluation->id) }}" class="ml-5">Clear
                                        Search</a>
                                @else
                                    <a href="{{ route('voice-audits.index') }}" class="ml-5">Clear Search</a>
                                @endif
                            </div>
                        </div>
                    </form>

    </div>

    <!-- Default box -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">{{ $voice_evaluation->name ?? 'Voice Audits' }} List</h3>
            @if ($voice_evaluation)
                <div class="card-tools">
                    <a href="{{ route('export.voice-audits') }}?record_id={{ $record_id }}&user_id={{ $user_id }}&associate_id={{ $associate_id }}&campaign_id={{ $campaign_id }}&project_id={{ $project_id }}&outcome={{ $outcome }}&status={{ $status }}&from_date={{ $from_date }}&to_date={{ $to_date }}&from_time={{ $from_time }}&to_time={{ $to_time }}"
                       class="btn btn-success btn-sm ml-2" onclick="return confirm('Are you sure?')">Export Report</a>
                </div>
            @endif
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>@sortablelink('call_date', 'Record ID')</th>
                    <th>@sortablelink('user_id', 'Evaluator')</th>
                    <th>@sortablelink('associate_id', 'Associate')</th>
                    <th>Evaluation Time</th>
                    <th>Call Time</th>
                    <th>Result</th>
                    <th>Client Outcome</th>
                    @if (in_array(Auth::user()->roles[0]->name, ['Super Admin']) || (in_array(Auth::user()->roles[0]->name, ['Director','Manager','Team Lead','Associate']) && Auth::user()->campaign_id == 4))
                        <th>Agent Outcome</th>
                    @endif
                    <th>Call Type</th>
                    <th>Status</th>
                    <th>@sortablelink('created_at', 'Created Time')</th>
                    <th>Notes</th>
                    <th class="action">Action</th>
                </tr>
                </thead>
                <tbody>

                @if (count($voice_audits) > 0)

                    @foreach ($voice_audits as $audit)
                        <tr>
                            <td>
                                {{ $audit->record_id ?? 0 }}<br>
                                {{ $audit->call_date }}
                            </td>
                            <td>{{ $audit->user->name ?? 'undefined' }}</td>
                            <td>
                                {{ $audit->associate->name ?? 'undefined' }}
                                <br>({{ $audit->campaign->name ?? 'undefined' }}) -
                                ({{ $audit->project->name ?? '' }})
                            </td>
                            <td>{{ $audit->evaluation_time }}</td>
                            <td>{{ $audit->recording_duration }}</td>
                            <td>{{ $audit->percentage }}%</td>
                            <td>
                                @if ($audit->outcome == 'accepted')
                                    <span class="badge bg-success">Billable</span>
                                @else
                                    <span class="badge bg-danger">Non Billable</span>
                                @endif
                            </td>
                            @if (in_array(Auth::user()->roles[0]->name, ['Super Admin']) || (in_array(Auth::user()->roles[0]->name, ['Director','Manager','Team Lead','Associate']) && Auth::user()->campaign_id == 4))
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
                            <td>
                                @include('shared.voice-audit-status', ['status' => $audit->status])
                            </td>
                            <td>{{ $audit->created_at->format('d-m-Y g:i:s A') }}</td>
                            <td>{{ $audit->notes }}</td>
                            <td class="action">
                                <a href="{{ route('voice-audits.show', $audit) }}" class="btn btn-success btn-sm"><i
                                        class="fas fa-eye"></i></a>
                                @if (
                                    (in_array(Auth::user()->roles[0]->name, ['Director', 'Team Lead', 'Manager', 'Associate']) &&
                                        Auth::user()->campaign_id == 4) ||
                                        in_array(Auth::user()->roles[0]->name, ['Super Admin']))
                                    @php
                                        $access = true;
                                        if (Auth::user()->roles[0]->name == 'Associate' && $audit->user_id == Auth::user()->id) {
                                            $diff = $audit->created_at->diffInHours(now());
                                            if ($diff > 24) {
                                                $access = false;
                                            }
                                        }
                                    @endphp

                                    @if ($access)
                                        <a href="{{ route('voice-audits.edit', $audit) }}"
                                           class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                    @endif

                                   @if (Auth::user()->roles[0]->name == 'Super Admin' ||
                                            (Auth::user()->roles[0]->name == 'Manager' && Auth::user()->campaign_id == 4))
                                        <form action="{{ route('voice-audits.destroy', $audit) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    @endif
                                    @if (Auth::user()->roles[0]->name == 'Super Admin' ||
                                            (Auth::user()->roles[0]->name == 'Manager' && Auth::user()->campaign_id == 4))
                                        @if ($audit->status == 'assigned to team lead')
                                            <button type="button" class="btn btn-secondary btn-sm assign-audit-btn"
                                                    value="{{ $audit->id }}" data-toggle="modal"
                                                    data-target="#modal-assign-team-lead-audit" hidden><i
                                                    class="fa fa-check"></i></button>
                                        @else
                                            <button type="button" class="btn btn-secondary btn-sm assign-audit-btn"
                                                    value="{{ $audit->id }}" data-toggle="modal"
                                                    data-target="#modal-assign-team-lead-audit"><i
                                                    class="fa fa-check"></i></button>
                                        @endif

                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="10" class="text-center">No record found!</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        @if ($voice_audits->total() > 15)
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                {{ $voice_audits->appends(request()->input())->links() }}
            </div>
            <!-- /.card-footer-->
        @endif
    </div>
    <!-- /.card -->
    <!-- /.modal -->
    <div class="modal fade" id="modal-assign-team-lead-audit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Select Team Lead</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('voice-audits.assign-voice-audits') }}" method="post" autocapitalize="off">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="voice_audit_id" id="voice_audit_id">
                        <div class="form-group">
                            <label for="">Select Team Lead<span>*</span></label>
                            <select name="assigned_team_lead_id" id="team_lead_id" class="form-control select2"
                                    required>
                                <option value="">Select Option</option>
                                @foreach ($team_leads as $team_lead)
                                    <option value="{{ $team_lead->id }}">{{ $team_lead->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>

                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection



@section('scripts')

    <script>
        $(function () {
            $("#btn-search").click(function (e) {
                e.preventDefault();
                $("#search").slideToggle();
            });

            $('.datepicker1').datetimepicker({
                format: 'L',
                format: 'DD/MM/YYYY',
                keepInvalid: false
            });

            $('.datepicker2').datetimepicker({
                format: 'L',
                format: 'DD/MM/YYYY',
                keepInvalid: false
            });

            $('.datepicker3').datetimepicker({
                format: 'L',
                format: 'hh:mm:ss A',
                keepInvalid: false
            });

            $('.datepicker4').datetimepicker({
                format: 'L',
                format: 'hh:mm:ss A',
                keepInvalid: false
            });

        });
        $(document).ready(function () {
            $(document).on('click', '.assign-audit-btn', function () {
                const audit_id = $(this).val();
                // console.log(audit_id)
                $.ajax({
                    url: `{{ route('main') }}/voice-audits/get-voice-audit-detail/${audit_id}`,
                    type: 'GET',
                    dataType: 'json', // added data type
                    success: function (response) {
                        // console.log(response);
                        $('#voice_audit_id').val(response.voice_audit_id);
                        /*  $('#associate_id').val(response.associate_id);
                         $('#team_lead_id').val(response.team_lead_id);
                         $('#campaign_id').val(response.campaign_id);
                         $('#project_id').val(response.project_id);
                         $('#call_date').val(response.call_date);
                         $('#customer_name').val(response.customer_name);
                         $('#customer_phone').val(response.customer_phone); */
                    }
                });
            });
        });
    </script>

@endsection
