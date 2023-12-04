@extends('layouts.user')

@section('title', 'Voice Evaluations')

@section('content')
    <div class="back-area mb-3">
        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go
            Back</a>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Datapoints</h3>
            <div class="card-tools">
                <a href="{{ route('solar-lts.voice-evaluations.categories.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Create Category
                </a>
            </div>
        </div>

        <div class="card-body">


            @if (count($categories) > 0)

                @foreach ($categories as $category)
                    <div class="row">
                        <div class="col-md-11">
                            <h3>{{ $category->name ?? 'undefined' }}</h3>
                        </div>
                        <div class="col-md-1">
                            <table>
                                <tr>
                                    <td><a href="{{ route('solar-lts.voice-evaluations.datapoints.create', $category->id) }}"
                                            class="btn btn-success btn-sm"><i class="fas fa-plus"></i></a></td>
                                    <td><a href="{{ route('solar-lts.voice-evaluations.categories.edit', $category->id) }}"
                                            class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a></td>
                                    <td><a href="{{ route('solar-lts.voice-evaluations.categories.destroy', $category->id) }}"
                                            class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <table class="table">
                        <tbody>
                            @foreach ($category->datapoints as $datapoint)
                                <tr>
                                    <td width="15%">{{ $datapoint->name }}</td>
                                    <td>{{ $datapoint->question }}</td>
                                    <td width="8%"><a
                                            href="{{ route('solar-lts.voice-evaluations.datapoints.edit', $datapoint->id) }}"
                                            class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('solar-lts.voice-evaluations.datapoints.destroy', $datapoint->id) }}"
                                            class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>



                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @else
                <h4 class="text-center">No record found!</h4>
            @endif

        </div>
    </div>


@endsection
