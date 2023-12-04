@extends('layouts.user')

@section('title', 'Voice Evaluation')


@section('content')

<div class="back-area mb-3">
    <a href="{{ route('voice-evaluations.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-2"></i> Go Back</a>
</div>

<h4>Setup - {{ $voice_evaluation->name }}</h4>
<!-- Default box -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Data Points</h3>
            @if (Auth::user()->roles[0]->name == 'Super Admin')
        <div class="card-tools">
            <a href="{{ route('datapoint-categories.create', $voice_evaluation) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create Category
            </a>
        </div>
            @endif
    </div>
    <div class="card-body">
            @if (count($categories) > 0)
                @foreach ($categories as $category)
                  @php
                    $categoryTotal = 0;
                  @endphp
        <div class="category">
            <div class="title">
                <h4>
                                {{ $category->name ?? '' }}
                                @foreach ($category->datapoints as $item)
                                @php
                                    $categoryTotal = $item->score + $categoryTotal;
                                @endphp
                            @endforeach
                    <small style="font-size: 12px;">({{ $category->project->name ?? '' }}) (Total Score: {{ $categoryTotal }})</small>
                </h4>

                <ul>
                    <li>
                        <a href="{{ route('datapoints.create', $category) }}" class="btn btn-xs btn-success">
                            <i class="fas fa-plus"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('datapoint-categories.edit', $category) }}" class="btn btn-xs btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('datapoint-categories.destroy', $category) }}" method="post">
                                        @csrf
                                        @method('delete')
                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

                        @if (count($category->datapoints) > 0)
            <div class="data-points">
                <table class="table">
                                    @foreach ($category->datapoints as $item)
                    <tr>
                        <td width="25%">{{ $item->name }}
                        </td>
                        <td>{{ $item->question }}</td>
                        <td>{{ $item->score ?? '' }}</td>
                        <td class="action">
                            <a href="{{ route('datapoints.edit', $item) }}" class="btn btn-primary btn-xs">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('datapoints.destroy', $item) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
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

        @if ($categories->total() > 15)
                <!-- /.card-body -->
    <div class="card-footer clearfix">
                {{ $categories->appends(request()->input())->links() }}
    </div>
    <!-- /.card-footer-->
        @endif
</div>
<!-- /.card -->
@endsection
