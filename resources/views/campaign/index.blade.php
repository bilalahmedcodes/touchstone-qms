
@extends('layouts.user')

@section('title', 'Campaigns')


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

    <form action="{{ route('campaigns.index') }}" method="get" autocomplete="off">
        <input type="hidden" name="search" value="1">
        @php
            $name = '';
            $status = '';

            if(isset($_GET['search'])){
                if(!empty($_GET['name'])){
                    $name = $_GET['name'];
                }

                if(!empty($_GET['status'])){
                    $status = $_GET['status'];
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

                    <div class="form-group col-md-4">
                        <label for="">Select Status</label>
                        <select name="status" class="form-control select2">
                            <option value="">Select</option>
                            <option value="active" @if($status == 'active') selected @endif>Active</option>
                            <option value="disable" @if($status == 'disable') selected @endif>Disabled</option>
                        </select>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('campaigns.index') }}" class="ml-5">Clear Search</a>
            </div>
        </div>
    </form>

</div>

<!-- Default box -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Campaigns List</h3>
        @if(Auth::user()->roles[0]->name == 'Super Admin')
            <div class="card-tools">
                <a href="{{ route('campaigns.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Create Campaign
                </a>
            </div>
        @endif
    </div>

    <div class="card-body">

        <table class="table table-bordered">
            <thead>
              <tr>
                <th>@sortablelink('id', 'ID')</th>
                <th>@sortablelink('name', 'Campaign Name')</th>
                <th>@sortablelink('database_name', 'Database Name')</th>
                <th>@sortablelink('status', 'Status')</th>
                <th>@sortablelink('created_at', 'Created Time')</th>
                @if(Auth::user()->roles[0]->name == 'Super Admin')
                <th class="action">Action</th>
                @endif
              </tr>
            </thead>
            <tbody>

                @if(count($campaigns) > 0)

                    @foreach ($campaigns as $campaign)
                        <tr>
                            <td>{{ $campaign->id ?? 0 }}</td>
                            <td>{{ $campaign->name ?? 'undefined' }}</td>
                            <td>{{ $campaign->database_name ?? 'NA' }}</td>
                            <td>
                                @if($campaign->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Disabled</span>
                                @endif
                            </td>
                            <td>{{ $campaign->created_at->format('d-m-Y g:i:s A') }}</td>
                            @if(Auth::user()->roles[0]->name == 'Super Admin')
                            <td class="action">
                                <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('campaigns.destroy', $campaign) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="5" class="text-center">No record found!</td></tr>
                @endif

            </tbody>
        </table>

    </div>

    @if($campaigns->total() > 15)
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            {{ $campaigns->appends(request()->input())->links() }}
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
