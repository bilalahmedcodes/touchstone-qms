@extends('layouts.app')

@section('title', 'General Audit')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="search-area pt-2 pb-2">
        <div class="row">

            <div class="col-md-6">
                <h4 class="mb-0">Search</h4>
            </div>
            <div class="col-md-6">
                <div class="button-area">
                    <button type="button" id="btn-search" class="btn btn-primary float-right"><i
                            class="fas fa-filter"></i></button>
                </div>
            </div>

        </div>
        @php
            $user_id = '';
            $outcome = '';
            if (isset($_GET['user_id'])) {
                $user_id = $_GET['user_id'];
            }
            if (isset($_GET['outcome'])) {
                $outcome = $_GET['outcome'];
            }

        @endphp

        <form action="{{ route('audit.index') }}" method="get" autocomplete="off">
            <input type="hidden" name="search" value="1">
            <div class="card card-primary card-outline mt-3" id="search" @if (!isset($_GET['search'])) style="display: none;" @endif>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Agent Name</label>
                            <select name="user_id" class="form-control select2">
                                <option value="">Select Option</option>
                                @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Outcome</label>
                                <select name="outcome" class="form-control select2">
                                    <option value="">Select Option</option>
                                    <option value="Qualified" @if ($outcome == 'Qualified') @endif>Qualified</option>
                                    <option value="Rejected" @if ($outcome == 'Rejected') @endif>Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('audit.index') }}" class="ml-5">Clear Search</a>
                </div>
            </div>
        </form>

    </div>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">General Audit List</h3>
            <div class="card-tools">
                <a href="{{ route('audit.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Start Audit
                </a>
            </div>
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Record Id</th>
                        <th>Associate</th>
                        <th>Campaign</th>
                        <th>Reporting To</th>
                        <th>Call Duration</th>
                        <th>Outcome</th>
                        <th>Call Date</th>
                        @if ((in_array(Auth::user()->roles[0]->name, ['Super Admin','Director','Manager', 'Team Lead'])))
                        <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if (count($audits) > 0)

                        @foreach ($audits as $audit)
                            <tr>
                                <td>{{ $audit->record_id ?? 'N/A' }}</td>
                                <td>{{ $audit->user->name ?? 'N/A'  }}</td>    
                                
                                <td>{{ $audit->user->campaign->name ?? 'N/A' }}</td>
                                <td>{{ $audit->user->getSupervisor->name ?? 'N/A' }}</td>
                                <td>{{ $audit->recording_duration ?? 'N/A' }}</td>
                                <td>
                                    @if ($audit->outcome == 'Qualified')
                                        <span class="badge bg-success">Qualified</span>
                                    @else
                                        <span class="badge bg-danger">Not Qualified</span>
                                    @endif
                                </td>
                                <td>{{ $audit->created_at ?? 'N/A' }}</td>
                                <td>
                                    @if ((in_array(Auth::user()->roles[0]->name, ['Super Admin','Director','Manager', 'Team Lead'])))
                                    <a href="{{ route('audit.edit', $audit) }}" class="btn btn-primary btn-sm"><i
                                            class="fas fa-edit"></i></a>
                                    @endif
                                    @if ((in_array(Auth::user()->roles[0]->name, ['Super Admin'])))
                                    <a href="{{ route('audit.destroy', $audit) }}" class="btn btn-primary btn-sm"><i
                                                class="fas fa-trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">No record found!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if ($audits->total() > 6)
            <div class="card-footer clearfix">
                {{ $audits->appends(request()->input())->links() }}
            </div>
        @endif

    </div>
    @section('scripts')
    <script>
        $("#btn-search").click(function() {
            $("#search").toggle();
        });
    </script>
@endsection
























































































@endsection
