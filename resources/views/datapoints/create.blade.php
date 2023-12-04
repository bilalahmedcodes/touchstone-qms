@extends('layouts.user')

@section('title', 'Create Data Point')


@section('content')

    <div class="back-area mb-3">
        <a href="{{ route('voice-evaluations.show', 1) }}" class="btn btn-secondary btn-sm"><i
                class="fas fa-arrow-left mr-2"></i> Go Back</a>
    </div>

    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create Data Point</h3>
        </div>

        <form action="{{ route('datapoints.store') }}" method="post" autocomplete="off">
            @csrf
            <div class="card-body">
                <input type="hidden" name="voice_evaluation_id" value="{{ $datapoint_category->voice_evaluation_id }}"
                    required>
                <div class="form-group">
                    <label for="exampleInputEmail1">Select Campaign <span>*</span></label>
                    <select name="campaign_id" class="form-control select2" id="campaign_list">
                        <option value="{{ $datapoint_category->campaign->id }}">{{ $datapoint_category->campaign->name }}
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Select Project <span>*</span></label>
                    <select name="project_id" class="form-control select2">
                        <option value="{{ $datapoint_category->project->id ?? '' }}">{{ $datapoint_category->project->name ?? 'No Project' }}
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Select Category <span>*</span></label>
                    <select name="datapoint_category_id" class="form-control select2" required>
                        <option value="{{ $datapoint_category->id }}">{{ $datapoint_category->name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Data Point Name <span>*</span></label>
                    <input type="text" class="form-control" name="name" placeholder="Enter Data Point Name" required>
                </div>
                @error('name')
                    <div class="validate-error">{{ $message }}</div>
                @enderror

                <div class="form-group">
                    <label for="exampleInputEmail1">Question <span>*</span></label>
                    <input type="text" class="form-control" name="question" placeholder="Enter Question" required>
                </div>
                @error('question')
                    <div class="validate-error">{{ $message }}</div>
                @enderror
                <div class="form-group">
                    <label for="exampleInputEmail1">Score <span>*</span></label>
                    <input type="text" class="form-control" name="score" placeholder="Enter score" required>
                </div>
                @error('score')
                    <div class="validate-error">{{ $message }}</div>
                @enderror

                {{-- <div class="form-group">
                <label for="exampleInputEmail1">Course ID</label>
                <input type="text" class="form-control" name="course_id" value="" placeholder="Enter Course ID">
            </div>
            @error('socourse_idrt')
                <div class="validate-error">{{ $message }}</div>
            @enderror --}}

                <div class="form-group">
                    <label for="exampleInputEmail1">Sort Order <span>*</span></label>
                    <input type="text" class="form-control" name="sort" value="1" placeholder="Enter Sort Order"
                        required>
                </div>
                @error('sort')
                    <div class="validate-error">{{ $message }}</div>
                @enderror

            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <!-- /.card-footer-->

        </form>
    </div>
    <!-- /.card -->

@endsection
