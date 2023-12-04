@extends('layouts.user')

@section('title', 'Evaluation Review Detail')


@section('content')

    <div class="back-area mb-3">
        <a href="{{ route('voice-evaluation-reviews.index', $status) }}" class="btn btn-secondary btn-sm"><i
                class="fas fa-arrow-left mr-2"></i> Go Back</a>
    </div>

    <div class="search-area">
        <div class="row">

            <div class="col-md-6">
                <h4 class="mb-0">Evaluation Review Detail</h4>
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

                @if ($voice_audit->appeal == null && $voice_audit->outcome == 'rejected' && $voice_audit->action == null)
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="#" class="btn btn-success btn-block" data-toggle="modal"
                                    data-target="#modal-appeal" data-controls-modal="#modal-appeal" data-backdrop="static"
                                    data-keyboard="false">Submit Appeal</a>
                            </div>
                            <div class="col-md-6">
                                <a href="#" class="btn btn-warning btn-block" data-toggle="modal"
                                    data-target="#modal-actions" data-controls-modal="#modal-appeal" data-backdrop="static"
                                    data-keyboard="false">Submit Action</a>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- <div class="card-footer">
                    <div class="row">
                        @if ($voice_audit->appeal == null && $voice_audit->outcome == 'rejected' && $voice_audit->action == null)
                            <div class="col-md-6">
                                <a href="#" class="btn btn-success btn-block" data-toggle="modal"
                                    data-target="#modal-appeal" data-controls-modal="#modal-appeal" data-backdrop="static"
                                    data-keyboard="false">Submit Appeal</a>
                            </div>
                        @endif

                        @if ($voice_audit->action == null && $voice_audit->outcome == 'rejected')
                            <div class="col-md-6">
                                <a href="#" class="btn btn-warning btn-block" data-toggle="modal"
                                    data-target="#modal-actions" data-controls-modal="#modal-appeal" data-backdrop="static"
                                    data-keyboard="false">Submit Action</a>
                            </div>
                        @endif
                    </div>
                </div> --}}
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



    <div class="modal fade" id="modal-appeal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Evaluation Review Appeal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('voice-evaluation-reviews.update', [$voice_audit, $status]) }}" method="post"
                    autocapitalize="off">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="">Remarks <span>*</span></label>
                            <textarea name="remarks" rows="5" class="form-control" placeholder="Enter your remarks..." placeholder=""></textarea>
                        </div>
                        @error('remarks')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>

                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <div class="modal fade" id="modal-actions">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Evaluation Review Action</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('voice-evaluation-reviews.update-actions', [$voice_audit, $status]) }}"
                    method="post" autocapitalize="off">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="">Select Action <span>*</span></label>
                            <select name="voice_evaluation_action_id" class="form-control select2">
                                <option value="">Select Action</option>
                                @foreach ($evaluation_actions as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('voice_evaluation_action_id')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror

                        <div class="form-group">
                            <label for="">Remarks <span>*</span></label>
                            <textarea name="remarks" rows="5" class="form-control" placeholder="Enter your remarks..." placeholder=""></textarea>
                        </div>
                        @error('remarks')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>

                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->




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
