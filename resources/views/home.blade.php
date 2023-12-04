@extends('layouts.user')
@section('title', 'Dashboard')
@section('content')
    {{-- <div class="search-area">
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

        <form action="{{ route('home') }}" method="get" autocomplete="off">

            <input type="hidden" name="search" value="1">
            @php
                $start_date = '';
                $end_date = '';

                if (isset($_GET['search'])) {
                    if (!empty(@$_GET['start_date'])) {
                        $start_date = @$_GET['start_date'];
                    }
                    if (!empty(@$_GET['end_date'])) {
                        $end_date = @$_GET['end_date'];
                    }
                }

            @endphp

            <div class="card card-primary card-outline mt-3" id="search"
                @if (isset($_GET['search'])) style="display: block;" @endif>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="">Start Date</label>
                            <input type="text" class="form-control datetimepicker-input datepicker1" name="start_date"
                                value="{{ $start_date }}" data-toggle="datetimepicker" data-target=".datepicker1" />
                        </div>

                        <div class="form-group col-md-6">
                            <label for="">End Date</label>
                            <input type="text" class="form-control datetimepicker-input datepicker2" name="end_date"
                                value="{{ $end_date }}" data-toggle="datetimepicker" data-target=".datepicker2" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('home') }}" class="ml-5">Clear Search</a>
                </div>
            </div>
        </form>

    </div> --}}
    <div class="row">
        <div class="col-md-2">
            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-success">
                    <h3 class="widget-user-username">{{ $voice_audit_count[0]['count'] ?? (0 ?? ($voice_audit_count ?? 0)) }}
                    </h3>
                    <br>
                    <h5 class="widget-user-desc">Completed Voice Audits</h5>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">
                                    {{ $home_warranty_count[0]['count'] ?? (0 ?? ($home_warranty_count ?? 0)) }}</h5>
                                <span>Home Warranty</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">
                                    {{ $mortgage_count[0]['count'] ?? (0 ?? ($mortgage_count ?? 0)) }}
                                </h5>
                                <span>Mortgage</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">{{ $solar_count[0]['count'] ?? (0 ?? ($solar_count ?? 0)) }}
                                </h5>
                                <span>Solar</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.widget-user -->
        </div>

        <div class="col-md-2">
            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-danger">
                    <h3 class="widget-user-username">
                        {{ $home_warranty_pending_count + $mortgage_pending_count + $solar_pending_count ?? 0 }} </h3>
                    <br>
                    <h5 class="widget-user-desc">Pending Voice Audits</h5>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">
                                    {{ $home_warranty_pending_count ?? 0 }}</h5>
                                <span>Home Warranty</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">{{ $mortgage_pending_count ?? 0 }}
                                </h5>
                                <span>Mortgage</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">{{ $solar_pending_count ?? 0 }}</h5>
                                <span>Solar</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.widget-user -->
        </div>

        <div class="col-md-2">
            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-warning">
                    <h3 class="widget-user-username">{{ $voice_accepted_count[0]['count'] ?? 0 }}</h3><br>
                    <h5 class="widget-user-desc">Accepted Voice Audits</h5>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">{{ $home_warranty_accepted_count[0]['count'] ?? 0 }}</h5>
                                <span>Home Warranty</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">{{ $mortgage_accepted_count[0]['count'] ?? 0 }}</h5>
                                <span>Mortgage</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">{{ $solar_accepted_count[0]['count'] ?? 0 }}</h5>
                                <span>Solar</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.widget-user -->
        </div>

        <div class="col-md-2">
            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-secondary">
                    <h3 class="widget-user-username">{{ $voice_rejected_count[0]['count'] ?? 0 }}</h3><br>
                    <h5 class="widget-user-desc">Rejected Voice Audits</h5>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">{{ $home_warranty_rejected_count[0]['count'] ?? 0 }}</h5>
                                <span>Home Warranty</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">{{ $mortgage_rejected_count[0]['count'] ?? 0 }}</h5>
                                <span>Mortgage</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">{{ $solar_rejected_count[0]['count'] ?? 0 }}</h5>
                                <span>Solar</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.widget-user -->
        </div>

        <div class="col-md-2">
            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">{{ $voice_audit_appeals_count[0]['count'] ?? 0 }}</h3><br>
                    <h5 class="widget-user-desc">Pending Voice Appeals</h5>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">{{ $home_warranty_audit_appeals_count[0]['count'] ?? 0 }}
                                </h5>
                                <span>Home Warranty</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">{{ $mortgage_audit_appeals_count[0]['count'] ?? 0 }}</h5>
                                <span>Mortgage</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">{{ $solar_audit_appeals_count[0]['count'] ?? 0 }}</h5>
                                <span>Solar</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.widget-user -->
        </div>

        <div class="col-md-2">
            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-primary">
                    <h3 class="widget-user-username">{{ $voice_actions_count[0]['count'] ?? 0 }}</h3><br>
                    <h5 class="widget-user-desc">Voice Actions</h5>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">{{ $home_warranty_action_count[0]['count'] ?? 0 }}</h5>
                                <span>Home Warranty</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">{{ $mortgage_action_count[0]['count'] ?? 0 }}</h5>
                                <span>Mortgage</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">{{ $solar_action_count[0]['count'] ?? 0 }}</h5>
                                <span>Solar</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.widget-user -->
        </div>

    </div>
    @if (in_array(Auth::user()->roles[0]->name, ['Super Admin']) ||
            (in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead']) &&
                Auth::user()->campaign_id == 4))
        <!-- Default box -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Recent Voice Audits</h3>
            </div>

            <div class="card-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Record ID</th>
                            <th>Evaluator</th>
                            <th>Associate</th>
                            <th>Evaluation Time</th>
                            <th>Result</th>

                            <th>Outcome</th>
                            <th>Created Time</th>
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
                                        <br>({{ $audit->campaign->name ?? '' }}) -
                                        ({{ $audit->project->name ?? '' }})
                                    </td>
                                    <td>{{ $audit->evaluation_time }}</td>
                                    <td>{{ $audit->percentage }}%</td>

                                    <td>
                                        @if ($audit->outcome == 'accepted')
                                            <span class="badge bg-success">Accepted</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>{{ $audit->created_at->format('d-m-Y g:i:s A') }}</td>
                                    <td class="action">
                                        <a href="{{ route('voice-audits.show', $audit) }}" class="btn btn-success btn-sm"
                                            target="_blank"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center">No record found!</td>
                            </tr>
                        @endif

                    </tbody>
                </table>

            </div>

        </div>
    @endif
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
