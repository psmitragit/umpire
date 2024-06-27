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
                    <form class="forms-sample form" method="post" action="{{ url('league/save_day_of_week') }}">
                        @csrf
                        <div class="headrepages-point">
                            Days of the week
                        </div>
                        <div id="dynamic_field">
                            @php
                                $days = [
                                    'MON' => 'MONDAY',
                                    'TUE' => 'TUESDAY',
                                    'WED' => 'WEDNESDAY',
                                    'THU' => 'THURSDAY',
                                    'FRI' => 'FRIDAY',
                                    'SAT' => 'SATURDAY',
                                    'SUN' => 'SUNDAY',
                                ];
                                $i = 0;
                            @endphp
                            @foreach ($days as $k => $val)
                                <section class="displayflesxsa tegss age" id="row{{ $i + 1 }}">
                                    <span class="text1s  date">{{ $val }}<input type="hidden" name="dayname[]"
                                        placeholder="00" value="{{ $k }}"
                                        class="form-control name_list" required /></span>

                                    <span class="text2s mr-0 "><select name="addless[]"
                                            class="noames-select custom-width-selector" id="" required>
                                            <option
                                                {{ !$page_data->isEmpty() && $page_data[$i]->addless == '-' ? 'selected' : '' }}
                                                value="-">Deduct</option>
                                            <option
                                                {{ !$page_data->isEmpty() && $page_data[$i]->addless == '+' ? 'selected' : '' }}
                                                value="+">Add</option>
                                        </select></span>
                                    <span class="pists"><input
                                            value="{{ !$page_data->isEmpty() ? $page_data[$i]->point : '0' }}" type="number"
                                            name="point[]" placeholder="00" class="name-inpostya" required></span>
                                    <span class="tos">Points</span>
                                </section>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                            <div class="buttonsubmit age weeks">

                                <button class="submit redbtn" type="button" onclick="demoWarning();">Save</button>
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
