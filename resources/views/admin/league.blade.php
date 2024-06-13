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
                     @livewire('Admin.LeagueSettings')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
