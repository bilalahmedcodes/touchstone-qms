@extends('layouts.user')

@section('title', 'Fatal Report')


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

        <form action="{{ route('voice-reports.fatals') }}" method="get" autocomplete="off">
            <input type="hidden" name="search" value="1">
            @php

                $from_date = '';
                $to_date = '';
                $associate_id = '';
                $team_lead_id = '';
                $user_id = '';

                if (isset($_GET['search'])) {
                    if (!empty($_GET['from_date'])) {
                        $from_date = $_GET['from_date'];
                    }
                    if (!empty($_GET['to_date'])) {
                        $to_date = $_GET['to_date'];
                    }
                    if (!empty($_GET['associate_id'])) {
                        $associate_id = $_GET['associate_id'];
                    }
                    if (!empty($_GET['team_lead_id'])) {
                        $team_lead_id = $_GET['team_lead_id'];
                    }
                    if (!empty($_GET['user_id'])) {
                        $user_id = $_GET['user_id'];
                    }
                    if (!empty($_GET['team_lead_id'])) {
                        $team_lead_id = $_GET['team_lead_id'];
                    }
                    if (!empty($_GET['user_id'])) {
                        $user_id = $_GET['user_id'];
                    }
                }

            @endphp

            <div class="card card-primary card-outline mt-3" id="search"
                @if (isset($_GET['search'])) style="display: block;" @endif>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="">Select Associate</label>
                            <select name="associate_id" class="form-control select2">
                                <option value="-1">Select Option</option>
                                @foreach ($associates as $associate)
                                    <option value="{{ $associate->id }}" @if ($associate->id == $associate_id) selected @endif>
                                        {{ $associate->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Select Team Lead</label>
                            <select name="team_lead_id" class="form-control select2">
                                <option value="-1">Select Option</option>
                                @foreach ($teamLeads as $teamLead)
                                    <option value="{{ $teamLead->id }}" @if ($teamLead->id == $team_lead_id) selected @endif>
                                        {{ $teamLead->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Select Evaluator</label>
                            <select name="user_id" class="form-control select2">
                                <option value="-1">Select Option</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if ($user->id == $user_id) selected @endif>
                                        {{ $user->name }}</option>
                                @endforeach
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
                    <a href="{{ route('voice-reports.fatals') }}" class="ml-5">Clear Search</a>
                </div>
            </div>
        </form>

    </div>

    <!-- Default box -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Fatal Report</h3>
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>HRMS</th>
                        <th>Agent</th>
                        <th>Reporting To</th>
                        <th>Campaign</th>
                        <th>Feedback</th>
                        <th>Evaluator</th>
                    </tr>
                </thead>
                <tbody>

                    @if (count($user_evaluations) > 0)
                        @foreach ($user_evaluations as $item)
                            <tr>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->associate->hrms_id }}</td>
                                <td>{{ $item->associate->name }}</td>
                                <td>{{ $item->teamlead->name }}</td>
                                <td>{{ $item->campaign->name }}</td>
                                <td>{{ $item->notes }}</td>
                                <td>{{ $item->user->name }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="text-center">No records found!</td>
                        </tr>
                    @endif

                </tbody>
            </table>

        </div>

        @if ($user_evaluations->total() > 15)
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                {{ $user_evaluations->appends(request()->input())->links() }}
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
    </script>

@endsection
