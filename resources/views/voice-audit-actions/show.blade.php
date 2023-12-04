@extends('layouts.user')

@section('title', 'Audit Appeal Detail')


@section('content')

    <div class="back-area mb-3">
        <a href="{{ route('voice-audit-actions.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
    </div>

    <div class="search-area">
        <div class="row">

            <div class="col-md-6">
                <h4 class="mb-0">Audit Action Detail</h4>
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
