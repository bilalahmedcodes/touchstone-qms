
@extends('layouts.user')

@section('title', 'Edit Custom Field')


@section('content')

<div class="back-area mb-3">
    <a href="{{ route('voice-evaluations.show', $voice_custom_field->evaluation) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
</div>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Custom Field</h3>
    </div>

    <form action="{{ route('voice-custom-fields.update', $voice_custom_field) }}" method="post" autocomplete="off">
        @csrf
        @method('put')
        <div class="card-body">

            <input type="hidden" name="voice_evaluation_id" value="{{ $voice_custom_field->voice_evaluation_id }}" required>

            <div class="form-group">
                <label for="exampleInputEmail1">Field Label <span>*</span></label>
                <input type="text" class="form-control" name="label" value="{{ old('label', $voice_custom_field->label) }}" placeholder="Enter Label" required>
            </div>
            @error('label')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputEmail1">Field Placeholder <span>*</span></label>
                <input type="text" class="form-control" name="placeholder" value="{{ old('placeholder', $voice_custom_field->placeholder) }}" placeholder="Enter Placeholder">
            </div>
            @error('placeholder')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputEmail1">Field Type <span>*</span></label>
                <select name="type" class="form-control select2" required>
                    <option value="">Select Option</option>
                    <option value="text" @if($voice_custom_field->type == 'text') selected @endif>Text</option>
                    <option value="dropdown" @if($voice_custom_field->type == 'dropdown') selected @endif>Dropdown</option>
                    <option value="textarea" @if($voice_custom_field->type == 'textarea') selected @endif>Textarea</option>
                </select>
            </div>
            @error('type')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputEmail1">Dropdown Options (comma separated)</label>
                <input type="text" class="form-control" name="options" value="{{ old('options', $voice_custom_field->options) }}" placeholder="Enter Options">
            </div>
            @error('options')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputPassword1">Select Status <span>*</span></label>
                <select name="status" class="form-control select2" required>
                    <option value="active" @if($voice_custom_field->status == 'active') selected @endif>Active</option>
                    <option value="disabled" @if($voice_custom_field->status == 'disabled') selected @endif>Disable</option>
                </select>
            </div>
            @error('status')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputPassword1">Select Position <span>*</span></label>
                <select name="position" class="form-control select2" required>
                    <option value="top" @if($voice_custom_field->position == 'top') selected @endif>Top</option>
                    <option value="bottom" @if($voice_custom_field->position == 'bottom') selected @endif>Bottom</option>
                </select>
            </div>
            @error('position')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputPassword1">Is Required? <span>*</span></label>
                <select name="required" class="form-control select2" required>
                    <option value="0" @if($voice_custom_field->required == 0) selected @endif>No</option>
                    <option value="1" @if($voice_custom_field->required == 1) selected @endif>Yes</option>
                </select>
            </div>
            @error('required')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            @php
                $campaign_ids = $voice_custom_field->campaigns->pluck('id')->toArray();
            @endphp

            <div class="form-group">
                <label for="">Select Campaign (Optional)</label>
                <select name="campaigns[]" id="campaigns" class="form-control select2" multiple>
                    <option value="">Select Option</option>
                    @foreach ($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" @if(in_array($campaign->id, $campaign_ids)) selected @endif>{{ $campaign->name }}</option>
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
