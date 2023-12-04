
@extends('layouts.user')

@section('title', 'Data Points')


@section('content')


<div class="search-area">
    <div class="row">

        <div class="col-md-6">
            <h4 class="mb-0">Data Points</h4>
        </div>

    </div>

</div>

<!-- Default box -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Data Points & Categories</h3>
        @if(Auth::user()->roles[0]->name == 'Super Admin')
            <div class="card-tools">
                <a href="{{ route('datapoint-categories.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Create Category
                </a>
            </div>
        @endif
    </div>

    <div class="card-body">

        @if(count($categories) > 0)
            @foreach ($categories as $category)
                <div class="category">
                    <div class="title">
                        <h4>{{ $category->name }}</h4>
                        <ul>
                            <li>
                                <a href="{{ route('datapoints.create', $category) }}" class="btn btn-xs btn-success"><i class="fas fa-plus"></i></a>
                            </li>
                            <li>
                                <a href="{{ route('datapoint-categories.edit', $category) }}" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i></a>
                            </li>
                            <li>
                                <form action="{{ route('datapoint-categories.destroy', $category) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    @if(count($category->datapoints) > 0)
                        <div class="data-points">
                            <table class="table">
                                @foreach ($category->datapoints as $item)
                                    <tr>
                                        <td width="25%">{{ $item->name }} @if($item->course_id) <small>(Course ID: {{ $item->course_id }})</small> @endif</td>
                                        <td>{{ $item->question }}</td>
                                        <td class="action">
                                            <a href="{{ route('datapoints.edit', $item) }}" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('datapoints.destroy', $item) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                    @else
                        <h5 class="text-center">No data points found!</h5>
                    @endif

                </div>

            @endforeach
        @else
            <h4 class="text-center">No records found</h4>
        @endif

    </div>

    @if($categories->total() > 15)
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            {{ $categories->appends(request()->input())->links() }}
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
