
@extends('layouts.user')

@section('title', 'Create Campaign')


@section('content')

<div class="back-area mb-3">
    <a href="{{ route('campaigns.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
</div>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create Campaign</h3>
    </div>

    <form action="{{ route('campaigns.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="exampleInputEmail1">Campaign Name</label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter Campaign Name" required>
            </div>
            @error('name')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputEmail1">Database Name (Optional)</label>
                <input type="text" class="form-control" name="database_name" value="{{ old('database_name') }}" placeholder="Enter Database Name">
            </div>
            @error('database_name')
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
