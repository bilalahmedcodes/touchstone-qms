
@extends('layouts.user')

@section('title', 'Create Role')


@section('content')

<div class="back-area mb-3">
    <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
</div>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create User Role</h3>
    </div>

    <form action="{{ route('roles.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="exampleInputEmail1">User Role</label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter User Role" required>
            </div>
            @error('name')
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
