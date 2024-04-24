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
                        
                        <div class="text-center">
                            <select id="course" class="selectd"
                                onchange="filter_preset(this.value, 'schedule-on-any-game')">
                                @foreach ($all_presets as $option)
                                    <option {{ $selected_preset == $option->presetid ? 'selected' : '' }}
                                        value="{{ $option->presetid }}">{{ $option->presetname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <form class="forms-sample ms-5" method="post"
                            action="{{ url('admin/save_base_point/' . $preset->presetid) }}">
                            @csrf
                            <div class="mian-foir">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="me-2">Enter the points to be</label>
                                    <select name="addlesss"  class="selectd" id="" required>
                                        <option {{ !empty($page_data) && $page_data->addless == '-' ? 'selected' : '' }}
                                            value="-">Deduct</option>
                                        <option {{ !empty($page_data) && $page_data->addless == '+' ? 'selected' : '' }}
                                            value="+">Add</option>
                                    </select>
                                    <input type="number" class="selectd" name="point" required
                                        value="{{ !empty($page_data) ? $page_data->point : '0' }}">
                                </div>

                            </div>
                            <button type="submit" class="btn btn-success mx-auto d-block">Save</button>
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
                            toastr.success("Success.");
                        } else if (res.status == 0) {
                            toastr.error("Something went wrong.");
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
                                }, 500);
                                $('#errors').show().delay(10000).hide(0);
                                $('#errors').html(errorString);
                            }
                        }
                    }
                });
            }));
        });
    </script>
@endsection
