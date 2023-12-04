
@extends('layouts.user')

@section('title', 'Create Voice Evaluation Action')


@section('content')

<div class="back-area mb-3">
    <a href="{{ route('voice-evaluation-actions.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
</div>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create Voice Evaluation Action</h3>
    </div>

    <form action="{{ route('voice-evaluation-actions.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="exampleInputEmail1">Voice Evaluation Action Title</label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter Voice Evaluation Action Title" required>
            </div>
            @error('name')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputEmail1">Sort Order</label>
                <input type="number" min="0" class="form-control" name="sort" value="{{ old('sort', 1) }}">
            </div>
            @error('sort')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputPassword1">Select Status</label>
                <select name="status" class="form-control select2" required>
                    <option value="active">Active</option>
                    <option value="disabled">Disable</option>
                </select>
            </div>
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
