@extends('league.layouts.main')
@section('main-container')
    <div class="body-content">
        <style>
            .slider.round {
                width: 60px;
            }

            input:checked+.slider::before {
                -webkit-transform: translateX(29px);

                -ms-transform: translateX(29px);

                transform: translateX(29px);

            }

            .custom-hr {
                width: 80%;
                margin: auto;
                margin-top: auto;
                margin-top: 30px;
            }

            .buttonforsubmit.custom-cls {
                position: fixed;
                left: 67%;
                z-index: 5;
                margin-top: 6px;
            }

            .retricts {
                padding-bottom: 50px;
            }

            @media screen and (max-width: 1600px) {
                .buttonforsubmit.custom-cls {
                    position: fixed;
                    right: 26%;
                    left: unset;
                    z-index: 5;
                    margin-top: 6px;
                }
            }

            @media screen and (max-width: 1139px) {
                .buttonforsubmit.custom-cls {
                    position: fixed;
                    left: 60%;
                    z-index: 5;
                    margin-top: 6px;
                }

                .buttonforsubmit.custom-cls .submit.redbtn.mx-auto {
                    padding: 10px 20px;
                }
            }

            @media screen and (max-width: 991px) {
                .buttonforsubmit.custom-cls {
                    position: fixed;
                    right: 2%;
                    z-index: 5;
                    margin-top: 6px;
                }
            }

            @media screen and (max-width: 760px) {

                .buttonforsubmit.custom-cls {
                    position: relative;
                    right: 0;
                    z-index: 0;
                    margin-top: 0;
                    left: 0;
                }
            }
        </style>

        <div class="namphomediv">
            <h1 class="pageTitle">League Settings</h1>
            <div class="mapbtns-div">

            </div>
        </div>




        <!-- add all settings page menu  -->

        @include('league.layouts.settings_menubar')

        <!-- add all settings page menu  -->

        <div class="alert alert-danger" style="display: none;" id="errors"></div>

        <div class="settings-body">

            <form action="{{ url('league/save_general_settings') }}" method="POST">
                @csrf
                <div class="displayflesx justify-content-center">
                    <span class="game-lables">Open to applications</span>
                    <label class="switch" onclick="demoWarning();">
                        <input disabled value="1" name="umpire_joining_status" class="form-check-input report" type="checkbox"
                            id="flexSwitchCheckChecked" {{ $league_data->umpire_joining_status !== 0 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <div class="buttonforsubmit custom-cls">
                        <button class="submit redbtn mx-auto" type="button" onclick="demoWarning();">Update</button>
                    </div>
                </div>
                <hr class="custom-hr">
                @if (!checkToggleStatus($league_data->leagueid, 'auto_scheduler'))
                    <div class="displayflesx ">
                        <span class="text1s col-md-5">Umpire starts with</span>
                        <span class="pists col-md-auto"><input
                                {{ checkToggleStatus($league_data->leagueid, 'auto_scheduler') ? 'readonly' : '' }}
                                name="joiningpoint" type="text" class="name-inpostya"
                                value="{{ $league_data->joiningpoint }}"></span>
                        <span class="text2s mr-0 col-md-5">Points when joining league</span>
                    </div>
                @endif


                @if (!checkToggleStatus($league_data->leagueid, 'auto_scheduler'))
                    <div class="displayflesx">
                        <span class="text1s col-md-5">auto assign umpires</span>
                        <span class="pists col-md-auto"><input
                                {{ checkToggleStatus($league_data->leagueid, 'auto_scheduler') ? 'readonly' : '' }}
                                name="assignbefore" value="{{ $league_data->assignbefore }}" type="text"
                                class="name-inpostya"></span>
                        <span class="text2s mr-0 col-md-5">days before game</span>
                    </div>
                @endif


                <div class="displayflesx">
                    <span class="text1s col-md-5">umpire can leave game</span>
                    <span class="pists col-md-auto"><input name="leavebefore" value="{{ $league_data->leavebefore }}"
                            type="text" class="name-inpostya"></span>
                    <span class="text2s mr-0 col-md-5">days before game</span>
                </div>
                <div class="displayflesx">
                    <span class="text1s col-md-5">default game pay in $</span>
                    <span class="pists col-md-auto"><input name="defaultpay" value="{{ $league_data->defaultpay }}"
                            type="text" class="name-inpostya"></span>
                    <!-- <div class="text2s mr-0">days before game</div> -->
                </div>

                @if (!checkToggleStatus($league_data->leagueid, 'age'))
                    <hr class="custom-hr-setting">
                    <div class="retricts">
                        <div class="text-resti">Restrict games based on age</div>



                        <div class="displayflesx">
                            <span class="text1s col-md-5">By default Primary umpire must be</span>
                            <span class="pists col-md-auto"><input
                                    {{ checkToggleStatus($league_data->leagueid, 'age') ? 'readonly' : '' }}
                                    name="mainumpage" value="{{ $league_data->mainumpage }}" type="text"
                                    class="name-inpostya"></span>
                            <span class="text2s mr-0 col-md-5">years older</span>
                        </div>




                        <div class="displayflesx">
                            <span class="text1s col-md-5">and 2nd,3rd,4th umpire must be</span>
                            <span class="pists col-md-auto"><input
                                    {{ checkToggleStatus($league_data->leagueid, 'age') ? 'readonly' : '' }}
                                    name="otherumpage" value="{{ $league_data->otherumpage }}" type="text"
                                    class="name-inpostya"></span>
                            <span class="text2s mr-0 col-md-5">years older</span>
                        </div>
                    </div>
                @endif

            </form>

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
    </div>
@endsection
