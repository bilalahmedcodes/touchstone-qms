@extends('layouts.user')

@section('title', 'Voice Evaluations')


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

        <form action="{{ route('voice-evaluation-reviews.index', $status) }}" method="get" autocomplete="off">

            <input type="hidden" name="search" value="1">
            @php
                $record_id = '';
                $associate_id = '';
                $campaign_id = '';
                $project_id = '';
                $outcome = '';
                $from_date = '';
                $to_date = '';

                if (isset($_GET['search'])) {
                    if (!empty($_GET['associate_id'])) {
                        $associate_id = $_GET['associate_id'];
                    }
                    if (!empty($_GET['campaign_id'])) {
                        $campaign_id = $_GET['campaign_id'];
                    }
                    if (!empty($_GET['project_id'])) {
                        $project_id = $_GET['project_id'];
                    }
                    if (!empty($_GET['outcome'])) {
                        $outcome = $_GET['outcome'];
                    }
                    if (!empty($_GET['from_date'])) {
                        $from_date = $_GET['from_date'];
                    }
                    if (!empty($_GET['to_date'])) {
                        $to_date = $_GET['to_date'];
                    }
                    if (!empty($_GET['record_id'])) {
                        $record_id = $_GET['record_id'];
                    }
                }

            @endphp

            <div class="card card-primary card-outline mt-3" id="search"
                @if (isset($_GET['search'])) style="display: block;" @endif>
                <div class="card-body">
                    <div class="row">

                        <div class="form-group col-md-4">
                            <label for="">Record ID</label>
                            <input type="text" class="form-control" name="record_id" value="{{ $record_id }}" />
                        </div>

                        <div class="form-group col-md-4">
                            <label for="">Select Associate</label>
                            <select name="associate_id" class="form-control select2">
                                <option value="">Select Option</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->hrms_id }}" @if ($user->hrms_id == $associate_id) selected @endif>
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Select Campaign</label>
                            <select name="campaign_id" class="form-control select2">
                                <option value="">Select Option</option>
                                @foreach ($campaigns as $campaign)
                                    <option value="{{ $campaign->id }}" @if ($campaign->id == $campaign_id) selected @endif>
                                        {{ $campaign->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Select Project</label>
                            <select name="project_id" class="form-control select2">
                                <option value="">Select Option</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" @if ($project->id == $project_id) selected @endif>
                                        {{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Select Client Outcome</label>
                            <select name="outcome" class="form-control select2">
                                <option value="">Select</option>
                                <option value="accepted" @if ($outcome == 'accepted') selected @endif>Billable</option>
                                <option value="rejected" @if ($outcome == 'rejected') selected @endif>Non Billable</option>
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

                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('voice-evaluation-reviews.index', $status) }}" class="ml-5">Clear Search</a>
                </div>
            </div>
        </form>

    </div>

    <!-- Default box -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Voice Evaluations List</h3>
            <div class="card-tools">
                <a href="{{ route('export.evaluations-export', $status) }}?record_id={{ $record_id }}&associate_id={{ $associate_id }}&campaign_id={{ $campaign_id }}&project_id={{ $project_id }}&outcome={{ $outcome }}&from_date={{ $from_date }}&to_date={{ $to_date }}"
                    class="btn btn-success btn-sm ml-2" onclick="return confirm('Are you sure?')">Export Report</a>
            </div>
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>@sortablelink('record_id', 'Record ID')</th>
                        <th>@sortablelink('associate_id', 'Associate')</th>
                        <th>@sortablelink('campaign_id', 'Campaign')</th>
                        <th>@sortablelink('project_id', 'Project')</th>
                        <th>@sortablelink('call_date', 'Call Date')</th>
                        <th>Result</th>
                        <th>Client Outcome</th>
                        <th>Agent Outcome</th>
                        <th>Status</th>
                        <th>@sortablelink('created_at', 'Created Time')</th>
                        <th class="action">Action</th>
                    </tr>
                </thead>
                <tbody>

                    @if (count(@$voice_audits) > 0)

                        @foreach (@$voice_audits as $audit)
                            <tr>
                                <td>
                                    {{ @$audit->record_id ?? 0 }}
                                </td>
                                <td>{{ @$audit->associate->name ?? 'undefined' }}</td>
                                <td>{{ @$audit->associate->campaign->name ?? 'undefined' }}</td>
                                <td>{{ @$audit->project->name ?? 'undefined' }}</td>
                                <td>{{ @$audit->call_date ?? '' }}</td>
                                <td>{{ @$audit->percentage ?? '' }}%</td>
                                <td>
                                    @if (@$audit->outcome == 'accepted')
                                        <span class="badge bg-success">Billable</span>
                                    @else
                                        <span class="badge bg-danger">Non-Billable @if (@$audit->review_priority == 1)
                                                / Critical
                                            @endif
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if (@$audit->agent_outcome == 'accepted')
                                        <span class="badge bg-success">Accepted</span>
                                    @else
                                        <span class="badge bg-danger">Rejected 
                                           
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @include('shared.voice-audit-status', ['status' => @$audit->status])
                                </td>
                                <td>{{ @$audit->created_at->format('d-m-Y g:i:s A') }}</td>
                                <td class="action">
                                    <a href="{{ route('voice-evaluation-reviews.show', [$audit, $status]) }}"
                                        class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center">No record found!</td>
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

        });
    </script>

@endsection
