@extends('layouts.user')

@section('title', 'Edit Datapoints')


@section('content')

    <div class="back-area mb-3">
        <a href="{{ route('solar-lts.voice-evaluations.index') }}" class="btn btn-secondary btn-sm"><i
                class="fas fa-arrow-left mr-2"></i> Go Back</a>
    </div>

    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Datapoints</h3>
        </div>

        <form action="{{ route('solar-lts.voice-evaluations.datapoints.update', $datapoint) }}" method="post" autocomplete="off">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Datapoint Name</label>
                    <input type="text" class="form-control" name="name" value="{{ $datapoint->name }}">
                </div>
                @error('name')
                    <div class="validate-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Question</label>
                    <input type="text" class="form-control" name="question" value="{{ $datapoint->question }}">
                </div>
                @error('question')
                    <div class="validate-error">{{ $message }}</div>
                @enderror
            </div>
            <input type="hidden" class="form-control" name="category_id" value="{{ $datapoint->category_id }}">
            
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <!-- /.card-footer-->

        </form>
    </div>
    <!-- /.card -->

@endsection
