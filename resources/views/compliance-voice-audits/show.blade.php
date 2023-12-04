@extends('layouts.user')

@section('title', 'Voice Audit Detail')


@section('content')

    <div class="back-area mb-3">
        <a href="{{ route('compliance-voice-audits.show-all', $assigned_voice_audit_detail->voice_evaluation_id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
    </div>

    <div class="search-area">
        <div class="row">

            <div class="col-md-6">
                <h4 class="mb-0">Compliance Voice Audit Detail</h4>
            </div>

        </div>
    </div>

    <div class="row">

        <div class="col-md-4">
            <!-- Default box -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Audit Detail</h3>
                </div>
                <div class="card-body table-responsive">

                    @include('shared.compliance-evaluation-review-detail', ['assigned_voice_audit_detail' => $assigned_voice_audit_detail])

                </div>

            </div>
        </div>

        <div class="col-md-8">

            <!-- Default box -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Audit Points</h3>
                </div>

                <div class="card-body">

                    @include('shared.compliance-evaluation-review-points', ['assigned_voice_audit_detail' => $assigned_voice_audit_detail, 'categories' => $categories])

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
