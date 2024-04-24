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
                            action="{{ !empty($page_data) ? url('admin/edit_league/' . $page_data->leagueid) : url('admin/add_league') }}">
                            @csrf
                            <div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">League Name<span class="text-danger"> *</span></label>
                                    <input required type="text" name="leaguename" class="form-control custom-input"
                                        value="{{ !empty($page_data) ? $page_data->leaguename : '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">League Owner Name<span class="text-danger">
                                            *</span></label>
                                    <input required type="text" name="name" class="form-control custom-input"
                                        value="{{ !empty($page_data) ? $page_data->name : '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Phone No.<span class="text-danger"></span></label>
                                    <input type="text" name="phone" class="form-control custom-input"
                                        value="{{ !empty($page_data) ? $page_data->phone : '' }}">
                                </div>
                            </div>
                            <button type="submit"
                                class="btn btn-success">{{ !empty($page_data) ? 'Update' : 'Add' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(e) {
            $('form').on('submit', (function(e) {
                e.preventDefault();
                var url = '{{ url('/admin/league') }}';
                $.ajax({
                    url: $(this).attr('action'),
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: new FormData(this),
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 1) {
                            window.location.replace(url);
                        } else {
                            $('#errors').show();
                            $('#errors').html(res.errors);
                        }

                    },
                    error: function(response) {

                        if (response.status === 422) {
                            var errorString = '';
                            var errors = response.responseJSON.errors;
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    var errorMessage = errors[key][0];
                                    errorString += '<p>' + errorMessage + '</p>';
                                }
                                $('html, body').animate({
                                    scrollTop: 0
                                });
                                $('#errors').show().delay(5000).hide(0);
                                $('#errors').html(errorString);
                            }
                        }
                    }
                });
            }));
        });
    </script>
@endsection
