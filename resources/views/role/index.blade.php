
@extends('layouts.user')

@section('title', 'User Roles')


@section('content')


<div class="search-area">
    <div class="row">

        <div class="col-md-6">
            <h4 class="mb-0">Search</h4>
        </div>
        <div class="col-md-6">
            <div class="button-area">
                <button type="button" id="btn-search" class="btn btn-primary"><i class="fas fa-filter"></i></button>
            </div>
        </div>

    </div>

    <form action="{{ route('roles.index') }}" method="get" autocomplete="off">
        <input type="hidden" name="search" value="1">
        @php
            $name = '';

            if(isset($_GET['search'])){
                if(!empty($_GET['name'])){
                    $name = $_GET['name'];
                }
            }

        @endphp

        <div class="card card-primary card-outline mt-3" id="search" @if(isset($_GET['search'])) style="display: block;" @endif>
            <div class="card-body">
                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="">Enter Name</label>
                        <input type="text" name="name" value="{{ $name }}" class="form-control">
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('roles.index') }}" class="ml-5">Clear Search</a>
            </div>
        </div>
    </form>

</div>


<!-- Default box -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">User Roles List</h3>

        <div class="card-tools">
            <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create User Role
            </a>
        </div>
    </div>

    <div class="card-body">

        <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>User Role</th>
                <th>Created Time</th>
                <th class="action">Action</th>
              </tr>
            </thead>
            <tbody>

                @if(count($roles) > 0)

                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->id ?? 0 }}</td>
                            <td>{{ $role->name ?? 'undefined' }}</td>
                            <td>{{ $role->created_at->format('d-m-Y g:i:s A') }}</td>
                            <td class="action">
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('roles.destroy', $role) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="4" class="text-center">No record found!</td></tr>
                @endif

            </tbody>
        </table>

    </div>

    @if($roles->total() > 15)
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            {{ $roles->appends(request()->input())->links() }}
        </div>
        <!-- /.card-footer-->
    @endif
</div>
<!-- /.card -->

@endsection



@section('scripts')

<script>

    $(function () {
        $("#btn-search").click(function(e){
            e.preventDefault();
            $("#search").slideToggle();
        });

    });

</script>

@endsection
