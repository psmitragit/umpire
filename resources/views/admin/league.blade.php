@extends('admin.layouts.main')
@section('main-container')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="col-md-3">
                                <h4 class="card-title">{{ $title }}</h4>
                            </div>
                            <div class="col-md-6">
                                <form class="d-flex align-baseline" style="align-items: end;"
                                    action="{{ url('admin/sent_invite') }}" method="post">
                                    @csrf
                                    <div class="">
                                        <input style="width: 300px;" required type="text" name="email"
                                            class="form-control" placeholder="invite@league.com">
                                    </div>
                                    <div class="ms-2">
                                        <button type="submit" class="btn btn-success">Invite league Owner</button>


                                    </div>
                                </form>
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="{{ url('admin/add_league') }}" class="btn btn-primary">Add New League</a>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>League Name</th>
                                        <th>League Owner Name</th>
                                        <th>Contact Number</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$page_data->isEmpty())
                                        @foreach ($page_data as $data)
                                            <tr>
                                                <td>{{ $data->leaguename }}</td>
                                                <td>{{ $data->name }}</td>
                                                <td>{{ $data->phone }}</td>
                                                <td class="text-end">
                                                    <a href="{{ url('admin/edit_league/' . $data->leagueid) }}"
                                                        class="btn btn-warning">Edit</a>
                                                    @if ($data->status == 0)
                                                        <a href="{{ url('admin/league_status/' . $data->leagueid . '/1') }}"
                                                            class="btn btn-success"
                                                            onclick="return confirm('Are you sure you want to activate this league?')">Activate</a>
                                                    @elseif ($data->status == 1)
                                                        <a href="{{ url('admin/league_status/' . $data->leagueid . '/0') }}"
                                                            class="btn btn-danger"
                                                            onclick="return confirm('Are you sure you want to deactivate this league?')">Deactivate</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td align="center" colspan="9">No League Found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
