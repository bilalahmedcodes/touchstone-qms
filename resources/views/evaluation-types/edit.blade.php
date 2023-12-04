
@extends('layouts.user')

@section('title', 'Edit Evaluation Type')


@section('content')

<div class="back-area mb-3">
    <a href="{{ route('evaluation-types.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
</div>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Evaluation Type</h3>
    </div>

    <form action="{{ route('evaluation-types.update', $evaluation_type) }}" method="post" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="exampleInputEmail1">Evaluation Type</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $evaluation_type->name) }}" placeholder="Enter Evaluation Type" required>
            </div>
            @error('name')
                <div class="validate-error">{{ $message }}</div>
            @enderror
            <div class="form-group">
                <label for="exampleInputPassword1">Select Status</label>
                <select name="status" class="form-control select2" required>
                    <option value="active" @if($evaluation_type->status == 'active') selected @endif>Active</option>
                    <option value="disabled" @if($evaluation_type->status == 'disabled') selected @endif>Disable</option>
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
