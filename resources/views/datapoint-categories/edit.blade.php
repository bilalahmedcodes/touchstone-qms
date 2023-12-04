
@extends('layouts.user')

@section('title', 'Edit Category')


@section('content')

<div class="back-area mb-3">
    <a href="{{ route('voice-evaluations.show', $datapoint_category->evaluation) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
</div>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Datapoint Category</h3>
    </div>

    <form action="{{ route('datapoint-categories.update', $datapoint_category) }}" method="post" autocomplete="off">
        @csrf
        @method('put')
        <div class="card-body">
            <input type="hidden" name="voice_evaluation_id" value="{{ $datapoint_category->voice_evaluation_id }}" required>
            <div class="form-group">
                <label for="exampleInputEmail1">Datapoint Category Name <span>*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $datapoint_category->name) }}" placeholder="Enter Datapoint Category Name" required>
            </div>
            @error('name')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            {{-- @php
                $campaign_ids = $datapoint_category->campaigns->pluck('id')->toArray();
            @endphp --}}

            <div class="form-group">
                <label for="">Select Campaigns</label>
                <select name="campaign_id" id="campaigns" class="form-control select2">
                    <option value="">Select Option</option>
                    @foreach ($campaigns as $campaign)
                        <option value="{{ $campaign->id }}"@if ($campaign->id == $datapoint_category->campaign_id) selected @endif>{{ $campaign->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('campaign_id')
                <div class="validate-error">{{ $message }}</div>
            @enderror
            <div class="form-group">
                <label for="">Select Project</label>
                <select name="project_id" id="projects" class="form-control select2">
                </select>
            </div>
            @error('project_id')
                <div class="validate-error">{{ $message }}</div>
            @enderror
            <div class="form-group">
                <label for="exampleInputEmail1">Sort Order <span>*</span></label>
                <input type="text" class="form-control" name="sort" value="{{ old('sort', $datapoint_category->sort) }}" placeholder="Enter Sort Order" required>
            </div>
            @error('sort')
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
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#campaigns').change(function() {
                var id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: "https://crm.touchstone-communications.com/api/projects",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        campaign_id: id,
                        api_key: '235fsdfkbAusfiewrg9348'
                    },
                    success: function(data) {
                        console.log(data);
                        let select_option = '<option value="">Select Option</option>';
                        data.forEach(element => {
                            select_option += "<option value=" + element.id + ">" +
                                element.name + "</option>";
                        });
                        document.getElementById("projects").innerHTML = select_option;
                    }
                });
            });
        });
    </script>
@endsection
