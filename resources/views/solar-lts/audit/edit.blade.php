@extends('layouts.user')

@section('title', 'Edit Audit Form')

@section('content')

    <div class="back-area mb-3">
        <a href="{{ route('solar-lts.audit.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-2"></i> Go
            Back
        </a>
    </div>
    <!-- Default box -->

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Voice Evaluation</h3>
        </div>
        <form action="{{ route('solar-lts.audit.update', $audit) }}" method="post" autocomplete="off">
            @csrf
            @method("PUT")
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Agent Name</label>

                            <select name="user_id" class="form-control select2" id="user_list">

                                <option value="">Select Option</option>
                                @foreach ($users as $user)

                                    <option value="{{ $user->id }}" @if ($user->id == $audit->user_id) selected @endif>{{ $user->name ?? '' }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        @error('user_id')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-4">
                        <label for="exampleInputEmail1">Reporting To</label>
                        <input type="text" class="form-control" name="reporting_to" id="reporting_to"
                            placeholder="Enter Reporting To" value="{{ $audit->user->supervisor->name ?? '' }}" disabled>
                    </div>
                    <div class="col-sm-4">
                        <label for="exampleInputEmail1">Campaign Name</label>
                        <input type="text" class="form-control" name="name" id="campaign_name"
                            placeholder="Enter Campaign Name" value="{{ $audit->user->campaign->name ?? '' }}" disabled>
                    </div>
                    <div class="col-sm-4">
                        <label for="exampleInputEmail1">Record Id</label>
                        <input type="text" class="form-control" name="record_id" placeholder="Enter Record Id"
                            value="{{ $audit->record_id }}" required>
                    </div>
                    @error('record_id')
                        <div class="validate-error">{{ $message }}</div>
                    @enderror

                </div>
            </div>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Datapoints</h3>
                </div>
                <div class="card-body">
                    @php
                        @$old_category_id = 0;
                    @endphp
                    @if (count($responses) > 0)
                        @foreach ($responses as $response)

                            @if (@$old_category_id != @$response->datapoint->category->id)
                                <div class="row">
                                    <div class="col-md-11">
                                        <h3>{{ $response->datapoint->category->name ?? 'undefined' }}</h3>
                                    </div>
                                </div>
                            @endif
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td width="15%">{{ @$response->datapoint->name }}</td>
                                        <td>{{ @$response->datapoint->question }}</td>
                                        <td width="10%" class="radios">
                                            <label class="radio-inline qrating">
                                                <input type="radio" id="datapoint" class="radio" value="1"
                                                    @if ($response->answer == 1) checked @endif name="answer-{{ $response->id }}">
                                                Yes

                                            </label>
                                            <label class="radio-inline qrating">
                                                <input type="radio" class="radio" id="datapoint" value="0"
                                                    @if ($response->answer == 0) checked @endif name="answer-{{ $response->id }}">
                                                No

                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            @php
                                @$old_category_id = @$response->datapoint->category->id;
                            @endphp
                        @endforeach

                    @else

                        <h4 class="text-center">No record found!</h4>
                    @endif

                </div>
            </div>


            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Evaluations</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="exampleInputEmail1">Outcome</label>
                            <input type="text" class="form-control" name="outcome" id="outcome"
                             value="{{ $audit->outcome }}" required>
                        </div>
                        @error('outcome')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
                        <div class="col-sm-4">
                            <label for="exampleInputEmail1">Customer Name</label>
                            <input type="text" class="form-control" name="customer_name" placeholder="Enter Customer Name"
                                value="{{ $audit->customer_name }}" required>
                        </div>
                        @error('customer_name')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
                        <div class="col-sm-4">
                            <label for="exampleInputEmail1">Customer Phone</label>
                            <input type="text" class="form-control" name="customer_phone"
                                value="{{ $audit->customer_phone }}" placeholder="Enter Customer Phone" required>
                        </div>
                        @error('customer_phone')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
                        <div class="col-sm-4">
                            <label>Recording Duration</label>
                            <div class="input-group date" id="timepicker" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#timepicker" name="recording_duration" value="{{ $audit->recording_duration }}">
                              <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                              </div>
                              </div>
                          </div>
                          @error('recording_duration')
                          <div class="validate-error">{{ $message }}</div>
                      @enderror
                        <div class="col-sm-4">
                            <label for="exampleInputEmail1">Recording Link</label>
                            <input type="text" class="form-control" name="recording_link"
                                value="{{ $audit->recording_link }}" placeholder="Enter Recording Link" required>
                        </div>
                        @error('recording_link')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
                        <div class="col-sm-6">
                            <!-- textarea -->
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea class="form-control" rows="3" name="notes">{{ $audit->notes }}</textarea>
                            </div>
                        </div>
                        @error('notes')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>



@endsection

@section('scripts')


    <script type="text/javascript">
        $(document).ready(function() {
            $('#user_list').change(function() {
                var id = $(this).val();
                $.ajax({
                    url: '{{ env('APP_URL') }}audit/get-user-detail/' + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        user = response.data
                        $("#reporting_to").val(user.reporting_to);
                        $("#campaign_name").val(user.campaign_name);
                    }
                })
            });
        });

        $(document).ready(function() {
            $('input[type=radio]').change(function() {
                total = 0;
                total_no = 0;
                $('input[type=radio]:checked').each(function() {
                    if (this.value == 0) {
                        total_no++;
                    }
                    total++;
                })
                total_yes = total - total_no;
                /* outcome = (total_yes / total) * 100; */
                if (total_no > 0) {
                    $("#outcome").val("Not Qualified");
                } else {
                    $("#outcome").val("Qualified");
                }

            });
        });
            //Timepicker
    $('#timepicker').datetimepicker({
      format: 'h:mm:ss'
    })


    </script>

@endsection
