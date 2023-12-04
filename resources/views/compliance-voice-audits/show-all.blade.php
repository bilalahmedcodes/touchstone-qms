@extends('layouts.user')

@section('title', 'Compliance Voice Audits')


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
            <form action="{{ route('compliance-voice-audits.show-all', 1) }}" method="get" autocomplete="off">
            @else
                <form action="{{ route('compliance-voice-audits.show-all') }}" method="get" autocomplete="off">
        @endif
        <input type="hidden" name="search" value="1">
        @php

            $assigned_team_lead_id = '';
            $associate_id = '';
            $campaign_id = '';
            $project_id = '';
            $outcome = '';
            $review = '';
            $from_date = '';
            $to_date = '';
            $from_time = '';
            $to_time = '';

            if (isset($_GET['search'])) {
                $assigned_team_lead_id = @$_GET['user_id'];
                $associate_id = @$_GET['associate_id'];
                $campaign_id = @$_GET['campaign_id'];
                $outcome = @$_GET['outcome'];
                $review = @$_GET['review'];

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
                        <label for="">Select QA Team Lead</label>
                        <select name="assigned_team_lead_id" class="form-control select2">
                            <option value="-1">Select Option</option>
                            @foreach ($qa_team_leads as $team_lead)
                                <option value="{{ $team_lead->id }}" @if ($team_lead->id == $assigned_team_lead_id) selected @endif>
                                    {{ $team_lead->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Select Associate</label>
                        <select name="associate_id" class="form-control select2">
                            <option value="-1">Select Option</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if ($user->id == $associate_id) selected @endif>
                                    {{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Select Campaign</label>
                        <select name="campaign_id" class="form-control select2">
                            <option value="-1">Select Option</option>
                            @foreach ($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" @if ($campaign->id == $campaign_id) selected @endif>
                                    {{ $campaign->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Select Project</label>
                        <select name="project_id" class="form-control select2">
                            <option value="-1">Select Option</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @if ($project->id == $project_id) selected @endif>
                                    {{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">From Date</label>
                        <input type="text" class="form-control datetimepicker-input datepicker1" name="from_date"
                            value="{{ $from_date }}" data-toggle="datetimepicker" data-target=".datepicker1" />
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">To Date</label>
                        <input type="text" class="form-control datetimepicker-input datepicker2" name="to_date"
                            value="{{ $to_date }}" data-toggle="datetimepicker" data-target=".datepicker2" />
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">From Time</label>
                        <input type="text" class="form-control datetimepicker-input datepicker3" name="from_time"
                            value="{{ $from_time }}" data-toggle="datetimepicker" data-target=".datepicker3" />
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">To Time</label>
                        <input type="text" class="form-control datetimepicker-input datepicker4" name="to_time"
                            value="{{ $to_time }}" data-toggle="datetimepicker" data-target=".datepicker4" />
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Select Outcome</label>
                        <select name="outcome" class="form-control select2">
                            <option value="">Select</option>
                            <option value="accepted" @if ($outcome == 'accepted') selected @endif>Accepted</option>
                            <option value="rejected" @if ($outcome == 'rejected') selected @endif>Rejected</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Search</button>
                @if ($voice_evaluation)
                    <a href="{{ route('compliance-voice-audits.show-all', 1) }}" class="ml-5">Clear Search</a>
                @else
                    <a href="{{ route('compliance-voice-audits.show-all') }}" class="ml-5">Clear Search</a>
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
                    <a href="{{ route('export.compliance-voice-audits') }}?assigned_team_lead_id={{ $assigned_team_lead_id }}&associate_id={{ $associate_id }}&campaign_id={{ $campaign_id }}&project_id={{ $project_id }}&from_date={{ $from_date }}&to_date={{ $to_date }}&from_time={{ $from_time }}&to_time={{ $to_time }}&outcome={{ $outcome }}"
                        class="btn btn-success btn-sm ml-2" onclick="return confirm('Are you sure?')">Export Report</a>
                </div>
            @endif
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>QA Team Lead</th>
                        <th>Associate</th>
                        <th>Agent's Score</th>
                        <th>Team Lead's Score</th>
                        <th>Agent's Outcome</th>
                        <th>Team Lead's Outcome</th>
                        <th>Audit Date</th>
                        <th class="action">Action</th>
                    </tr>
                </thead>
                <tbody>

                    @if (count($assigned_audits) > 0)

                        @foreach ($assigned_audits as $key => $audit)
                            <tr>
                                <td>{{ $assigned_audits->firstItem() + $key }}</td>
                                <td>{{ $audit->user->name ?? '' }}</td>
                                <td>{{ $audit->associate->name ?? '' }}</td>
                                <td>{{ $audit->voice_audit->percentage ?? 0 }}</td>
                                <td>{{ $audit->percentage ?? 0 }}</td>
                                <td>
                                    @if ($audit->voice_audit->outcome == 'accepted')
                                        <span class="badge bg-success">Accepted</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($audit->outcome == 'accepted')
                                        <span class="badge bg-success">Accepted</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>

                                <td>{{ $audit->created_at->format('d-m-Y g:i:s A') }}</td>
                                <td class="action">
                                    <a href="{{ route('compliance-voice-audits.show', $audit) }}"
                                        class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
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

        @if ($assigned_audits->total() > 15)
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                {{ $assigned_audits->appends(request()->input())->links() }}
            </div>
            <!-- /.card-footer-->
        @endif
    </div>




@endsection



@section('scripts')

    <script>
        $(function() {
            $("#btn-search").click(function(e) {
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
        $(document).ready(function() {
            $(document).on('click', '.assign-audit-btn', function() {
                const audit_id = $(this).val();
                // console.log(audit_id)
                $.ajax({
                    url: `{{ route('main') }}/voice-audits/get-voice-audit-detail/${audit_id}`,
                    type: 'GET',
                    dataType: 'json', // added data type
                    success: function(response) {
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
