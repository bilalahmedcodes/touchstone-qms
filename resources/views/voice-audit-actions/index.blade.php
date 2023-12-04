
@extends('layouts.user')

@section('title', 'Voice Audit Actions')


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

    <form action="{{ route('voice-audit-actions.index') }}" method="get" autocomplete="off">
        <input type="hidden" name="search" value="1">
        @php
            $record_id = '';
            $user_id = -1;
            $associate_id = -1;
            $campaign_id = -1;
            $outcome = -1;
            $from_date = '';
            $to_date = '';
            $project_id = '';

            if(isset($_GET['search'])){
                $user_id = $_GET['user_id'];
                $associate_id = $_GET['associate_id'];
                $campaign_id = $_GET['campaign_id'];
                $outcome = $_GET['outcome'];

                if (!empty($_GET['from_date'])) {
                    $from_date = $_GET['from_date'];
                }
                if (!empty($_GET['to_date'])) {
                    $to_date = $_GET['to_date'];
                }
                if (!empty($_GET['record_id'])) {
                    $record_id = $_GET['record_id'];
                }
                if (!empty($_GET['project_id'])) {
                    $project_id = $_GET['project_id'];
                }
            }

        @endphp

        <div class="card card-primary card-outline mt-3" id="search" @if(isset($_GET['search'])) style="display: block;" @endif>
            <div class="card-body">
                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="">Record ID</label>
                        <input type="text" class="form-control" name="record_id" value="{{ $record_id }}" />
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Select Evaluator</label>
                        <select name="user_id" class="form-control select2">
                            <option value="-1">Select Option</option>
                            @foreach ($users as $user)
                                @if($user->campaign_id == 4)
                                    <option value="{{ $user->id }}" @if($user->id == $user_id) selected @endif>{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Select Associate</label>
                        <select name="associate_id" class="form-control select2">
                            <option value="-1">Select Option</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->hrms_id }}" @if($user->hrms_id == $associate_id) selected @endif>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Select Campaign</label>
                        <select name="campaign_id" class="form-control select2">
                            <option value="-1">Select Option</option>
                            @foreach ($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" @if($user->id == $campaign_id) selected @endif>{{ $campaign->name }}</option>
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
                        <label for="">Select Outcome</label>
                        <select name="outcome" class="form-control select2">
                            <option value="">Select</option>
                            <option value="accepted" @if($outcome == 'accepted') selected @endif>Accepted</option>
                            <option value="rejected" @if($outcome == 'rejected') selected @endif>Rejected</option>
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
                <a href="{{ route('voice-audit-actions.index') }}" class="ml-5">Clear Search</a>
            </div>
        </div>
    </form>

</div>

<!-- Default box -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Voice Audit Actions List</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered">
            <thead>
              <tr>
                <th>@sortablelink('record_id', 'Record ID')</th>
                <th>@sortablelink('user_id', 'Evaluator')</th>
                <th>@sortablelink('associate_id', 'Associate')</th>
                <th>@sortablelink('call_date', 'Call Date')</th>
                <th>Result</th>
                <th>Action</th>
                <th>@sortablelink('created_at', 'Created Time')</th>
                <th class="action">Action</th>
              </tr>
            </thead>
            <tbody>

                @if(count($voice_audits) > 0)

                    @foreach ($voice_audits as $audit)
                        <tr>
                            <td>{{ $audit->record_id ?? 0 }}</td>
                            <td>{{ $audit->user->name ?? 'undefined' }}</td>
                            <td>
                                {{ $audit->associate->name ?? 'undefined' }}
                                <br>({{ $audit->campaign->name ?? '' }}) - ({{ $audit->project->name ?? '' }})
                            </td>
                            <td>{{ $audit->call_date }}</td>
                            <td>{{ $audit->percentage }}%</td>
                            <td>{{ $audit->action->action->name }}</td>
                            <td>{{ $audit->created_at->format('d-m-Y g:i:s A') }}</td>
                            <td class="action">
                                <a href="{{ route('voice-audit-actions.show', $audit) }}" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="9" class="text-center">No record found!</td></tr>
                @endif

            </tbody>
        </table>

    </div>

    @if($voice_audits->total() > 15)
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

    $(function () {
        $("#btn-search").click(function(e){
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
