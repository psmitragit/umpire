<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/images/fabicon.png') }}">
    <link rel="stylesheet" href="{{ asset('storage/league') }}/css/all.css">
    <link rel="stylesheet" href="{{ asset('storage/league') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('storage/league') }}/css/nav.css">
    <link rel="stylesheet" href="{{ asset('storage/league') }}/css/slick-theme.css">
    <link rel="stylesheet" href="{{ asset('storage/league') }}/css/slick.css">
    <link rel="stylesheet" href="{{ asset('storage/league') }}/css/style.css">
    <link rel="stylesheet" href="{{ asset('storage/league') }}/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('storage/css/toastr.min.css') }}">
    @livewireStyles
    @livewireScripts
    <script src="{{ asset('storage/league') }}/js/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('storage/js/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('storage/league') }}/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('storage/league') }}/js/navigation.js"></script>
    <script src="{{ asset('storage/league') }}/js/nav.js"></script>
    <script src="{{ asset('storage/league') }}/js/slick.min.js"></script>
    <script src="{{ asset('storage/league') }}/js/jquery-ui.js"></script>
    <script src="{{ asset('storage/league') }}/js/dataTables.min.js"></script>
    <script src="{{ asset('storage/league') }}/js/app.js"></script>
    <script src="{{ asset('storage/js/toastr.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            if ($('.modal').length > 0) {
                if ($('.modal#sendnoti').length === 0) {
                    $(document).on('shown.bs.modal', '.modal', function() {
                        var modal = $(this);
                        var modalBody = modal.find('.modal-body');
                        var form = modal.find('form');
                        var output = form.find(
                            ':input[type="text"]:first, :input[type="number"]:first, textarea:first');
                        output.focus();
                    });
                } else {
                    $(":input[type='text']:first, :input[type='number']:first, textarea:first").focus();
                }
            } else {
                $(":input[type='text']:first, :input[type='number']:first, textarea:first").focus();
            }
        });
    </script>
</head>



<body class="checkbody">
    {!! stagingMark() !!}
    @include('league.layouts.error_alert')
    <header>
        <div class="topbane-bg">
            <div class="row  align-items-center custompadding">
                <div class="phone-logo">
                    <a href="{{ url('league') }}" class="logocancor-s"><img class="logos-hcian"
                            src="{{ asset('storage/league') }}/img/uc-logo.png" alt=""></a>
                </div>
                <div class="col-auto ms-auto phonenoe">
                    <div class="text-center leaguename">{{ $league_data->leaguename }}</div>
                </div>
                <div class="col-auto ms-auto">
                    <div class="cudtomflex">
                        <div class="name-propic">


                            <div class="accname">
                                {{ logged_in_league_admin_data()->email }}
                                <span><i class="fa-solid fa-chevron-down"></i></span>
                                <div class="submenus d-none">

                                    <div class="profiels">
                                        <a href="{{ url('league/notifications') }}" class="text-and-icon"> <span>
                                                Notification</span>
                                            <span><i class="fa-regular fa-envelope"></i></span>
                                        </a>
                                        <a href="{{ url('league/change-password') }}" class="text-and-icon"> <span>
                                                Change Password</span>
                                            <span><i class="fa-solid fa-lock"></i></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="logoutbtn" data-bs-toggle="modal" data-bs-target="#logout">
                            <i class="fa-solid fa-power-off"></i>
                        </div>

                        <div class="hambrgrbtn">
                            <i class="fa-solid fa-bars"></i>
                        </div>
                    </div>

                </div>

            </div>

        </div>
        <style>
            .pac-container {
                z-index: 9999;
            }
        </style>
    </header>
    <script>
        const accname = document.querySelector('.accname')
        const submenus = document.querySelector('.submenus')
        accname.onclick = function clicksubmen() {
            submenus.classList.toggle('d-none')
        }
    </script>

    <div class="modal fade" id="logout" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-loout-container">
                    <h3 class="textfot-logout">Are you sure you want to Log out?</h3>
                    <div class="buttons-flex hyscs">
                        <div class="button1div"><button type="button" class="redbtn submit"
                                onclick="window.location.replace('{{ url('league/logout') }}')">Log out</button></div>
                        <div class="buttondiv-trans"><button class="cnclbtn buycnm" data-bs-dismiss="modal">Stay logged
                                in</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('league.layouts.leftbar')
