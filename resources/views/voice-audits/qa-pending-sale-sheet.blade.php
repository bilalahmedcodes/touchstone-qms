@extends('layouts.user')

@section('title', 'Pending Audits')


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
            <form action="{{ route('voice-audits.qa-pending-sale-sheet', $voice_evaluation->id) }}" method="get"
                  autocomplete="off">
                @else
                    <form action="{{ route('voice-audits.qa-pending-sale-sheet') }}" method="get" autocomplete="off">
                        @endif
                        <input type="hidden" name="search" value="1">
                        @php
                            $campaign_id = '';
                            $from_date = '';
                            $to_date = '';
                            if (isset($_GET['search'])) {
                                $campaign_id = $_GET['campaign_id'];
                            }
                            if (isset($_GET['search'])) {
                                if (!empty(@$_GET['from_date'])) {
                                    $from_date = @$_GET['from_date'];
                                }
                            }
                            if (isset($_GET['search'])) {
                                if (!empty(@$_GET['to_date'])) {
                                    $to_date = @$_GET['to_date'];
                                }
                            }
                        @endphp

                        <div class="card card-primary card-outline mt-3" id="search"
                             @if (isset($_GET['search'])) style="display: block;" @endif>
                            <div class="card-body">
                                <div class="row">
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
                                        <label for="">From Date</label>
                                        <input type="date" class="form-control " name="from_date"
                                               value="{{ $from_date }}"
                                               data-toggle="datetimepicker" data-target=".datepicker1"/>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="">To Date</label>
                                        <input type="date" class="form-control " name="to_date" value="{{ $to_date }}"
                                               data-toggle="datetimepicker" data-target=".datepicker2"/>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Search</button>
                                @if ($voice_evaluation)
                                    <a href="{{ route('voice-audits.qa-pending-sale-sheet', $voice_evaluation->id) }}"
                                       class="ml-5">Clear
                                        Search</a>
                                @else
                                    <a href="{{ route('voice-audits.qa-pending-sale-sheet') }}" class="ml-5">Clear
                                        Search</a>
                                @endif
                            </div>
                        </div>
                    </form>

    </div>

    <!-- Default box -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Pending Audits List</h3>
        </div>

        <div class="card-body">

            <table id="qa-pending" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Sr #</th>
                    <th>Record ID</th>
                    <th>HRMS ID</th>
                    <th>Agent Name</th>
                    <th>Campaign</th>
                    <th>Project</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @if ($sales_data)
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($sales_data as $key => $data)
                        <tr>
                            <td>
                                {{ $i++ }}
                            </td>
                            <td>
                                {{ $data->record_id ?? 0 }}
                            </td>
                            <td>
                                {{ $data->user->HRMSID ?? 0 }}
                            </td>
                            <td>
                                {{ $data->user->name ?? '' }}
                            </td>
                            <td>
                                {{ $data->campaign->name ?? '' }}
                            </td>
                            <td>
                                {{ $data->project->name ?? '' }}
                            </td>
                            <td>
                                {{ date('m-d-Y', strtotime(@$data->created_at)) ?? '' }}
                            </td>
                            <td class="action">
                                <form action="{{ route('voice-audits.create', $voice_evaluation) }}" method="get">
                                    <input type="hidden" class="form-control" name="record_id"
                                           value="{{ $data->record_id ?? 0 }}" required>
                                    <input type="hidden" class="form-control" name="campaign_id"
                                           value="{{ $data->campaign->id ?? 0 }}" required>
                                    <input type="hidden" name="from_date" value="{{ $from_date }}">
                                    <input type="hidden" name="to_date" value="{{ $to_date }}">
                                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus"></i>
                                        Start
                                        Audit
                                    </button>
                                </form>
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
            $("#qa-pending").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": true,
                "buttons": ["copy", "csv", "excel"]
            }).buttons().container().appendTo('#qa-pending_wrapper .col-md-6:eq(0)');
        });
    </script>

@endsection
