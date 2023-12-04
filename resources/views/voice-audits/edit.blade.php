@extends('layouts.user')

@section('title', $voice_evaluation->name ?? 'Edit Voice Audit')

@section('content')

    <div class="back-area mb-3">
        <a href="{{ route('voice-audits.index', $voice_evaluation->id) }}" class="btn btn-secondary btn-sm"><i
                class="fas fa-arrow-left mr-2"></i> Go Back</a>
    </div>

    <div class="search-area">
        <div class="row">

            <div class="col-md-6">
                <h4 class="mb-0">Edit {{ $voice_evaluation->name }} <small>(ID: {{ $voice_audit->id }})</small></h4>
            </div>

        </div>
    </div>


    <form action="{{ route('voice-audits.update', $voice_audit) }}" method="post" autocomplete="off">
        @csrf
        @method('put')
        <input type="hidden" name="voice_evaluation_id" value="{{ $voice_evaluation->id }}" required>

        <!-- Default box -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Information (Step 1 of 3)</h3>
            </div>
            <div class="card-body">
                @include('shared.voice-audit-edit-information', ['voice_audit' => $voice_audit])
            </div>

        </div>
        <!-- /.card -->
        <!-- Default box -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Data Points (Step 2 of 3)</h3>
            </div>
            <div class="card-body">
                @php $points = 0; @endphp
                @include('shared.voice-audit-edit-datapoints', ['categories' => $categories])
            </div>

        </div>
        <!-- Default box -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Evaluation (Step 3 of 3)</h3>
            </div>
            <div class="card-body">

                @include('shared.voice-audit-edit-evaluation', ['voice_audit' => $voice_audit])

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <!-- /.card-footer-->

        </div>
        <!-- /.card -->

    </form>

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $(".qrating input").click(function percentage() {
                let percentage = 0;
                let total = 100;
                let na = 0;
                let checkedButtons = $("input:radio:checked")
                checkedButtons.each(function (chkdButton) {
                    if ($(this).val().indexOf("Na") >= 0) {
                        let na_value = $(this).val().split("Na-").pop()
                        na = na + parseInt(na_value);
                    } else {
                        percentage = parseInt(percentage) + parseInt($(this).val());
                    }
                });
                total = total - na;
                resultPercentage = (percentage / total) * 100;
                $(".percentage").val(Math.round(resultPercentage));
            });
        });


        $(function () {
            $('.datepicker').datetimepicker({
                format: 'L',
                format: 'DD-MM-YYYY',
                keepInvalid: false
            });
            $('#agent').on('change', function () {
                let user_id = this.value;

                if (user_id != "") {
                    $.ajax({
                        url: `{{ route('main') }}/get-user-detail/${user_id}`,
                        type: 'GET',
                        dataType: 'json', // added data type
                        success: function (res) {
                            $("#reporting_id").val(res.reporting_id);
                            $("#reporting").val(res.reporting_to);
                        }
                    });
                }
            });

            /* $(".qrating input").click(function percentage() {
                let percentage = 0;
                let checkedButtons = $("input[type='radio']:checked");
                checkedButtons.each(function(chkdButton) {
                    if ($(this).val() == "1" || $(this).val() == "6") {
                        percentage = percentage + 1;
                    }

                });

                resultPercentage = (percentage / total) * 100;
                $(".percentage").val(resultPercentage.toFixed(2));
            }); */
            /* $(document).ready(function() {
                $('.qrating').change(function() {
                    total = 0;
                    total_no = 0;
                    $('input[type=radio]:checked').each(function() {
                        if (this.value == 0) {
                            total_no++;
                        }
                        total++;
                    })
                    total_yes = total - total_no;
                    resultPercentage = (total_yes / total) * 100;
                    $(".percentage").val(resultPercentage.toFixed(2));
                });
            }); */

            /* $(document).ready(function() {
                $('.qrating').change(function() {
                    let percentage = 0;
                    let total = 25;
                    let yesCount = 0;
                    let noCount = 0;
                    let naCount = 0;
                    $('input[type=radio]:checked').each(function() {
                        if ($(this).val() == 1) {
                            yesCount = yesCount + 1;
                        }
                        if ($(this).val() == 0) {
                            noCount = noCount + 1;
                        }
                        if ($(this).val() == 6) {
                            naCount = naCount + 1;
                        }
                        if ($(this).val() == 1) {
                            percentage = percentage + 1;
                        } else if ($(this).val() == 6) {
                            percentage = percentage + 1;
                        }
                    })
                    qPercentage = (100 / (yesCount + noCount));
                    noPercentage = (noCount * qPercentage)
                    resultPercentage = (yesCount * qPercentage); //(100/(percentage - naCount));
                    //console.log("Percentage:" + resultPercentage);
                    //$(".percentage").val(resultPercentage.toFixed(2));
                });
            }); */
            /* var temp = 100;
            $('.review').on('change', function() {

                checked = $('.review').is(':checked')
                if (checked) {
                    temp = 50;
                    $(".percentage").val("50.00");
                }
                if (!checked) {
                    var tempValue = $(".percentage").val();
                    temp = 100;
                    $(".percentage").val("100.00");
                }
            }); */


            $("#duration").inputmask({
                mask: '99:99:99',
                placeholder: ' ',
                showMaskOnHover: false,
                showMaskOnFocus: false,
                onBeforePaste: function (pastedValue, opts) {
                    var processedValue = pastedValue;

                    //do something with it

                    return processedValue;
                }
            });
        });
    </script>
    {{-- <script>
        $(function() {

            $('.datepicker').datetimepicker({
                format: 'L',
                format: 'DD-MM-YYYY',
                keepInvalid: false
            });

            $('#agent').on('change', function() {
                var user_id = this.value;

                if (user_id != "") {
                    $.ajax({
                        url: `{{ route('main') }}/get-user-detail/${user_id}`,
                        type: 'GET',
                        dataType: 'json', // added data type
                        success: function(res) {
                            $("#reporting_id").val(res.reporting_id);
                            $("#reporting").val(res.reporting_to);
                            $("#campaign").val(res.campaign);
                        }
                    });
                }
            });

            $(document).ready(function() {
                $('.qrating').change(function() {
                    let percentage = 0;
                    let total = 25;
                    let yesCount = 0;
                    let noCount = 0;
                    let naCount = 0;
                    $('input[type=radio]:checked').each(function() {
                        if ($(this).val() == 1) {
                            yesCount = yesCount + 1;
                        }
                        if ($(this).val() == 0) {
                            noCount = noCount + 1;
                        }
                        if ($(this).val() == 6) {
                            naCount = naCount + 1;
                        }
                        if ($(this).val() == 1) {
                            percentage = percentage + 1;
                        } else if ($(this).val() == 6) {
                            percentage = percentage + 1;
                        }
                    })
                    qPercentage = (100 / (yesCount + noCount));
                    noPercentage = (noCount * qPercentage)
                    resultPercentage = (yesCount * qPercentage); //(100/(percentage - naCount));
                    console.log("Percentage:" + resultPercentage);
                    $(".percentage").val(resultPercentage.toFixed(2));
                });
            });
            /* var temp = 100;
            $('.review').on('change', function() {

                checked = $('.review').is(':checked')
                if (checked) {
                    temp = 50;
                    $(".percentage").val("50.00");
                }
                if (!checked) {
                    var tempValue = $(".percentage").val();
                    temp = 100;
                    $(".percentage").val("100.00");
                }
            }); */

            $('.review').on('change', function() {
                // console.log(resultPercentage);
                checked = $('.review').is(':checked')
                if (checked) {
                    $(".percentage").val((resultPercentage / 2).toFixed(2));
                } else {
                    $(".percentage").val(resultPercentage.toFixed(2));
                }
                /* if (!checked) {
                    var tempValue = $(".percentage").val();
                    temp = 100;
                    $(".percentage").val("100.00");
                } */
            });

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

            $('.review').on('change', function() {
            checked = $('.review').is(':checked')
            if (checked) {
                $(".percentage").val(50);
            }
            if (!checked) {
                $(".percentage").val(resultPercentage);
            }

        });

        });
    </script> --}}

@endsection
