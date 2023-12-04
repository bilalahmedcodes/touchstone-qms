@extends('layouts.user')

@section('title', 'Timesheet Report')


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

        <form action="{{ route('voice-reports.timesheet') }}" method="get" autocomplete="off">
            <input type="hidden" name="search" value="1">
            @php
                $user_id = '';
                $from_date = '';
                $to_date = '';
                $from_time = '';
                $to_time = '';

                if (isset($_GET['search'])) {
                    if (!empty($_GET['id'])) {
                        $user_id = $_GET['id'];
                    }

                    if (!empty($_GET['from_date'])) {
                        $from_date = $_GET['from_date'];
                    }
                    if (!empty($_GET['to_date'])) {
                        $to_date = $_GET['to_date'];
                    }

                    if (!empty($_GET['from_time'])) {
                        $from_time = $_GET['from_time'];
                    }
                    if (!empty($_GET['to_time'])) {
                        $to_time = $_GET['to_time'];
                    }
                }

            @endphp

            <div class="card card-primary card-outline mt-3" id="search"
                @if (isset($_GET['search'])) style="display: block;" @endif>
                <div class="card-body">
                    <div class="row">

                        <div class="form-group col-md-4">
                            <label for="">Select Evaluator</label>
                            <select name="id" class="form-control select2">
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
                    <a href="{{ route('voice-reports.timesheet') }}" class="ml-5">Clear Search</a>
                </div>
            </div>
        </form>

    </div>

    <!-- Default box -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Timesheet Report</h3>
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Evaluator Name</th>
                        <th class="text-center">Total Evaluations</th>
                        <th class="text-center">Total Handling Time</th>
                        <th class="text-center">Total Call Time</th>
                        <th class="text-center">Avg. Handling Time</th>
                        <th class="text-center">Avg. Call Time</th>
                        @if (in_array(Auth::user()->roles[0]->name, ['Super Admin']) ||
                        (in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead']) &&
                            (Auth::user()->campaign_id == 4)))
                        <th class="action">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>

                    @if (count($user_evaluations) > 0)
                        @foreach ($user_evaluations as $item)
                            @php
                                $handling_time = 0;
                                $avg_handling_time = 0;
                                $calling_time = 0;
                                $avg_calling_time = 0;

                                if (count($item->voiceAudits) > 0) {
                                    foreach ($item->voiceAudits as $audit) {
                                        if ($audit->evaluation_time) {
                                            sscanf($audit->evaluation_time, '%d:%d:%d', $hours, $minutes, $seconds);
                                            $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;

                                            $handling_time = $handling_time + $time_seconds;
                                        }
                                        if ($audit->recording_duration) {
                                            sscanf($audit->recording_duration, '%d:%d:%d', $hours, $minutes, $seconds);
                                            $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;

                                            $calling_time = $calling_time + $time_seconds;
                                        }
                                    }

                                    $avg_handling_time = $handling_time / count($item->voiceAudits);
                                    $avg_calling_time = $calling_time / count($item->voiceAudits);
                                }

                            @endphp
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td class="text-center">{{ count($item->voiceAudits) }}</td>
                                <td class="text-center">{{ gmdate('H:i:s', $handling_time) }}</td>
                                <td class="text-center">{{ gmdate('H:i:s', $calling_time) }}</td>
                                <td class="text-center">{{ gmdate('H:i:s', $avg_handling_time) }}</td>
                                <td class="text-center">{{ gmdate('H:i:s', $avg_calling_time) }}</td>
                                @if (in_array(Auth::user()->roles[0]->name, ['Super Admin']) ||
                                (in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead']) &&
                                    (Auth::user()->campaign_id == 4)))
                                <td class="action">
                                    <a href="{{ route('voice-audits.index', 1) }}?search=1&record_id=&user_id={{ $item->id }}&associate_id=-1&campaign_id=-1&outcome=&from_date={{ $from_date }}&to_date={{ $to_date }}&from_time={{ $from_time }}&to_time={{ $to_time }}&review="
                                        class="btn btn-success btn-sm" target="_blank"><i class="fas fa-eye"></i></a>
                                </td>
                                @endif
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
