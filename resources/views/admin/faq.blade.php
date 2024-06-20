@extends('admin.layouts.main')
@section('main-container')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <div class="content-wrapper">
        <div class="alert alert-danger" style="display: none;" id="errors"></div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $title }}</h4>
                        <hr>
                        <form class="forms-sample customwiths" method="post" action="{{ url('admin/faq') }}">
                            @csrf
                            <div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Question<span class="text-danger">
                                            *</span></label>
                                    <input type="text" name="question" class="form-control custom-input"
                                        value="{{ getFAQ(@$_GET['section'], 'question') }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Answer<span class="text-danger">
                                            *</span></label>
                                    <div class="editor">{!! getFAQ(@$_GET['section'], 'answer') !!}</div>
                                    <input type="hidden" name="answer" id="answer">
                                </div>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-success">Save</button>
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
                getContents();
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
                        toastr.success("Success");
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
