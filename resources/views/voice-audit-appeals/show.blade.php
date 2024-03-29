@extends('layouts.user')

@section('title', 'Audit Appeal Detail')


@section('content')

    <div class="back-area mb-3">
        <a href="{{ route('voice-audit-appeals.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
    </div>

    <div class="search-area">
        <div class="row">

            <div class="col-md-6">
                <h4 class="mb-0">Audit Appeal Detail</h4>
            </div>

        </div>
    </div>

    <div class="row">

        <div class="col-md-4">
            <!-- Default box -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Evaluation Detail</h3>
                </div>
                <div class="card-body table-responsive">

                    @include('shared.evaluation-review-detail', ['voice_audit' => $voice_audit])

                    @if(
                        (in_array(Auth::user()->roles[0]->name, ['Director', 'Team Lead', 'Manager', 'Associate']) && Auth::user()->campaign_id == 4) ||
                        (in_array(Auth::user()->roles[0]->name, ['Super Admin']))
                        )

                        @if($voice_audit->appeal->status == 'pending')
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="{{ route('voice-audit-appeals.edit', $voice_audit) }}" class="btn btn-success btn-block">Edit Evaluation</a>
                                    </div>
                                    <div class="col-md-6">
                                        <form action="{{ route('voice-audit-appeals.destroy', $voice_audit) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure?')">Reject Appeal</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                    @endif

                </div>
            </div>
        </div>

        <div class="col-md-8">

            <!-- Default box -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Evaluation Points</h3>
                </div>

                <div class="card-body">

                    @include('shared.evaluation-review-points', ['categories' => $categories])

                </div>

            </div>
            <!-- /.card -->

        </div>

    </div>






@endsection



@section('scripts')

    <script>
        $(function() {

            $("#duration").inputmask({
                mask: '99:99:99',
                placeholder: ' ',
                showMaskOnHover: false,
                showMaskOnFocus: false,
                onBeforePaste: function(pastedValue, opts) {
                    var processedValue = pastedValue;

                    //do something with it

                    return processedValue;
                }
            });

        });
    </script>

@endsection
