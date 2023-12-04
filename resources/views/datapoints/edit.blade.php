
@extends('layouts.user')

@section('title', 'Edit Data Point')


@section('content')

<div class="back-area mb-3">
    <a href="{{ route('voice-evaluations.show', $datapoint->evaluation) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
</div>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Data Point</h3>
    </div>

    <form action="{{ route('datapoints.update', $datapoint) }}" method="post" autocomplete="off">
        @csrf
        @method('put')
        <div class="card-body">
            <input type="hidden" name="voice_evaluation_id" value="{{ $datapoint->voice_evaluation_id }}" required>
            <div class="form-group">
                <label for="exampleInputEmail1">Select Campaign <span>*</span></label>
                <select name="campaign_id" class="form-control select2" id="campaign_list">
                    <option value="{{ $datapoint->category->campaign->id }}">{{ $datapoint->category->campaign->name }}
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Select Project <span>*</span></label>
                <select name="project_id" class="form-control select2" >
                    <option value="{{ $datapoint->category->project->id ?? '' }}">{{ $datapoint->category->project->name ?? '' }}
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Select Category <span>*</span></label>
                <select name="datapoint_category_id" class="form-control select2" required>
                    <option value="{{ $datapoint->category->id ?? '' }}">{{ $datapoint->category->name ?? '' }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1">Data Point Name <span>*</span></label>
                <input type="text" class="form-control" name="name" value="{{ $datapoint->name }}" placeholder="Enter Data Point Name" required>
            </div>
            @error('name')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputEmail1">Question <span>*</span></label>
                <input type="text" class="form-control" name="question" value="{{ $datapoint->question }}" placeholder="Enter Question" required>
            </div>
            @error('question')
                <div class="validate-error">{{ $message }}</div>
            @enderror
            <div class="form-group">
                <label for="exampleInputEmail1">Score <span>*</span></label>
                <input type="text" class="form-control" name="score" value="{{ $datapoint->score }}" placeholder="Enter Score" >
            </div>
            @error('score')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputEmail1">Sort Order <span>*</span></label>
                <input type="text" class="form-control" name="sort" value="{{ $datapoint->sort }}" placeholder="Enter Sort Order" required>
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
