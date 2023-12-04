@extends('layouts.user')

@section('title', 'Dashboard')


@section('content')


<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $voice_audit_count[0]['count'] ?? 0 }}</h3>

                <p>Total Voice Audits</p>
            </div>
            
        </div>
    </div>
    <!-- ./col -->
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $voice_pending_reviews_count[0]['count'] ?? 0 }}</h3>

                <p>Pending Voice Reviews</p>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $voice_audit_appeals_count[0]['count'] ?? 0 }}</h3>

                <p>Pending Voice Appeals</p>
            </div>
            
        </div>
    </div>
    <!-- ./col -->
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $voice_actions_count[0]['count'] ?? 0 }}</h3>
                <p>Total Voice Actions</p>
            </div>
        </div>
    </div>
    <!-- ./col -->
</div>


<!-- Default box -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Recent Voice Evaluations</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered">
            <thead>
              <tr>
                <th>Record ID</th>
                <th>Associate</th>
                <th>Campaign</th>
                <th>Project</th>
                <th>Call Date</th>
                <th>Result</th>
                <th>Outcome</th>
                <th>@sortablelink('created_at', 'Created Time')</th>
                <th class="action">Action</th>
              </tr>
            </thead>
            <tbody>

                @if(count($voice_audits) > 0)

                    @foreach ($voice_audits as $audit)
                        <tr>
                            <td>
                                {{ $audit->record_id ?? 0 }}
                            </td>
                            <td>{{ $audit->associate->name ?? 'undefined' }}</td>
                            <td>{{ $audit->associate->campaign->name ?? 'undefined' }}</td>
                            <td>{{ $audit->project->name ?? '' }}</td>
                            <td>{{ $audit->call_date }}</td>
                            <td>{{ $audit->percentage }}%</td>
                            <td>
                                @if($audit->outcome == 'accepted')
                                    <span class="badge bg-success">Accepted</span>
                                @else
                                    <span class="badge bg-danger">Rejected @if($audit->review_priority == 1) / Critical @endif</span>
                                @endif
                            </td>
                            <td>{{ $audit->created_at->format('d-m-Y g:i:s A') }}</td>
                            <td class="action">
                                <a href="{{ route('voice-evaluation-reviews.show', $audit) }}" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="8" class="text-center">No record found!</td></tr>
                @endif

            </tbody>
        </table>

    </div>

</div>
<!-- /.card -->


@endsection
