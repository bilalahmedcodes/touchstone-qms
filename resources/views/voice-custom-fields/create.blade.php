
@extends('layouts.user')

@section('title', 'Create Custom Field')


@section('content')

<div class="back-area mb-3">
    <a href="{{ route('voice-evaluations.show', $voice_evaluation) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
</div>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create Custom Field</h3>
    </div>

    <form action="{{ route('voice-custom-fields.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">

            <input type="hidden" name="voice_evaluation_id" value="{{ $voice_evaluation->id }}" required>

            <div class="form-group">
                <label for="exampleInputEmail1">Field Label <span>*</span></label>
                <input type="text" class="form-control" name="label" value="{{ old('label') }}" placeholder="Enter Label" required>
            </div>
            @error('label')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputEmail1">Field Placeholder <span>*</span></label>
                <input type="text" class="form-control" name="placeholder" value="{{ old('placeholder') }}" placeholder="Enter Placeholder">
            </div>
            @error('placeholder')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputEmail1">Field Type <span>*</span></label>
                <select name="type" class="form-control select2" required>
                    <option value="">Select Option</option>
                    <option value="text">Text</option>
                    <option value="dropdown">Dropdown</option>
                    <option value="textarea">Textarea</option>
                </select>
            </div>
            @error('type')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputEmail1">Dropdown Options (comma separated)</label>
                <input type="text" class="form-control" name="options" value="{{ old('options') }}" placeholder="Enter Options">
            </div>
            @error('options')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputPassword1">Select Status <span>*</span></label>
                <select name="status" class="form-control select2" required>
                    <option value="active">Active</option>
                    <option value="disabled">Disable</option>
                </select>
            </div>
            @error('status')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputPassword1">Select Position <span>*</span></label>
                <select name="position" class="form-control select2" required>
                    <option value="top">Top</option>
                    <option value="bottom">Bottom</option>
                </select>
            </div>
            @error('position')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputPassword1">Is Required? <span>*</span></label>
                <select name="required" class="form-control select2" required>
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            @error('required')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="">Select Campaigns (Optional)</label>
                <select name="campaigns[]" id="campaigns" class="form-control select2" multiple>
                    <option value="">Select Option</option>
                    @foreach ($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('campaign_id')
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
