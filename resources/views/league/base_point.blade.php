@extends('league.layouts.main')
@section('main-container')
    <div class="body-content">


        <div class="namphomediv">
            <h1 class="pageTitle">League Settings</h1>
            <div class="mapbtns-div">

            </div>
        </div>






        <!-- add all settings page menu  -->

        @include('league.layouts.settings_menubar')

        <!-- add all settings page menu  -->

        <!-- add all settings page  -->
        <div class="namevs-anes">

            @include('league.layouts.preset_selectBox')

            <div class="row">
                <div class="col-md-3">
                    @include('league.layouts.point_sidebar')
                </div>
                <div class="col-md-9">
                    <form class="forms-sample customwiths form" method="post" action="{{ url('league/save_base_point') }}">
                        @csrf
                        <div class="headrepages-point">
                            Schedule on any Games
                        </div>
                        <div class="displayflesxsa tegss">
                            <span class="text1s ">Enter the points to be</span>
                            <span class="text2s mr-0 "><select name="addlesss" class="noames-select" id=""
                                    required>
                                    <option {{ !empty($page_data) && $page_data->addless == '-' ? 'selected' : '' }}
                                        value="-">Deduct</option>
                                    <option {{ !empty($page_data) && $page_data->addless == '+' ? 'selected' : '' }}
                                        value="+">Add</option>
                                </select></span>
                            <span class="pists"><input class="name-inpostya" type="number" name="point" required
                                    value="{{ !empty($page_data) ? $page_data->point : '0' }}"></span>
                        </div>
                        <div class="buttonsubmit">
                            <button class="submit redbtn" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(e) {
            $('.form').on('submit', (function(e) {
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
