@extends('league.layouts.main')
@section('main-container')
    <style>
        .sel2 {
            width: 90px;
        }
    </style>
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
                    <form class="forms-sample form" method="post" action="{{ url('league/save_umpire_position') }}">
                        @csrf
                        <div class="headrepages-point">
                            Position
                        </div>
                        <div id="dynamic_field">
                            <div class="displayflesxsa tegss week">
                                <span class="text1s  ump-pos">For Primary umpire <input type="hidden" name="position[]"
                                        placeholder="00" value="1" class="form-control name_list" required /></span>

                                <span class="text2s mr-0 "><select name="addless[]"
                                        class="noames-select custom-width-selector" id="" required>

                                        <option
                                            {{ !$page_data->isEmpty() && $page_data[0]->addless == '-' ? 'selected' : '' }}
                                            value="-">Deduct</option>
                                        <option
                                            {{ !$page_data->isEmpty() && $page_data[0]->addless == '+' ? 'selected' : '' }}
                                            value="+">Add</option>
                                    </select></span>
                                <span class="pists"><input
                                        value="{{ !$page_data->isEmpty() ? $page_data[0]->point : '0' }}" type="number"
                                        name="point[]" placeholder="00" class="name-inpostya" required></span>
                                <span class="tos">Points</span>

                            </div>

                            <div class="displayflesxsa tegss week">
                                <span class="text1s  ump-pos">For 2nd,3rd,4th umpire <input type="hidden" name="position[]"
                                        placeholder="00" value="2" class="form-control name_list" required /></span>

                                <span class="text2s mr-0 "><select name="addless[]"
                                        class="noames-select custom-width-selector" id="" required>
                                        <option
                                            {{ !$page_data->isEmpty() && $page_data[1]->addless == '-' ? 'selected' : '' }}
                                            value="-">Deduct</option>
                                        <option
                                            {{ !$page_data->isEmpty() && $page_data[1]->addless == '+' ? 'selected' : '' }}
                                            value="+">Add</option>
                                    </select></span>
                                <span class="pists"><input
                                        value="{{ !$page_data->isEmpty() ? $page_data[1]->point : '0' }}" type="number"
                                        name="point[]" placeholder="00" class="name-inpostya" required></span>
                                <span class="tos">Points</span>

                            </div>
                            <div class="buttonsubmit age weeks">

                                <button class="submit redbtn" type="submit">Save</button>
                            </div>
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
