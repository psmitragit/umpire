@extends('admin.layouts.main')
@section('main-container')
    <div class="content-wrapper">
        <div class="alert alert-danger" style="display: none;" id="errors"></div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $title }}</h4>
                        <hr>
                        <form class="forms-sample customwiths" method="post"
                            action="{{ url('admin/edit-umpire/' . $page_data->umpid) }}">
                            @csrf
                            <div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Name<span class="text-danger"> *</span></label>
                                    <input required type="text" name="name" class="form-control custom-input"
                                        value="{{ !empty($page_data) ? $page_data->name : '' }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Phone No.<span class="text-danger"></span></label>
                                    <input type="text" name="phone" class="form-control custom-input"
                                        value="{{ !empty($page_data) ? $page_data->phone : '' }}">
                                    @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">DOB<span class="text-danger"> *</span></label>
                                    <input required type="date" name="dob" class="form-control custom-input"
                                        value="{{ !empty($page_data) ? $page_data->dob : '' }}">
                                    @error('dob')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">ZIP<span class="text-danger"> *</span></label>
                                    <input required type="number" name="zip" class="form-control custom-input"
                                        value="{{ !empty($page_data) ? $page_data->zip : '' }}">
                                    @error('zip')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">BIO</label>
                                    <textarea class="form-control textarea" name="bio" style="height: 120px;">{{ !empty($page_data) ? $page_data->bio : '' }}</textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
