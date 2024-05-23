<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/images/fabicon.png') }}">
    <link rel="stylesheet" href="{{ asset('storage/umpire') }}/css/all.css">
    <link rel="stylesheet" href="{{ asset('storage/umpire') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('storage/umpire') }}/css/nav.css">
    <link rel="stylesheet" href="{{ asset('storage/umpire') }}/css/slick-theme.css">
    <link rel="stylesheet" href="{{ asset('storage/umpire') }}/css/slick.css">
    <link rel="stylesheet" href="{{ asset('storage/umpire') }}/css/style.css">
    <link rel="stylesheet" href="{{ asset('storage/css/toastr.min.css') }}">


    <script src="{{ asset('storage/umpire') }}/js/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('storage/umpire') }}/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('storage/umpire') }}/js/navigation.js"></script>
    <script src="{{ asset('storage/umpire') }}/js/nav.js"></script>
    <script src="{{ asset('storage/umpire') }}/js/slick.min.js"></script>
    <script src="{{ asset('storage/umpire') }}/js/jquery-ui.js"></script>
    <script src="{{ asset('storage/umpire') }}/js/app.js"></script>
    <script src="{{ asset('storage/js/toastr.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(":input[type='text']:first, :input[type='number']:first, textarea:first").focus();
        });
    </script>
</head>
@if (Session::has('event'))
    @if (Session::get('event') == 'show-report')
        <script>
            $(document).ready(function() {
                $('#listview').click();
                setTimeout(() => {
                    $('#his').click();
                }, 100);
            });
        </script>
    @endif
@endif

<body class="checkbody">
    {!! stagingMark() !!}
    @include('umpire.layouts.error_alert')
    <header>
        <div class="topbane-bg">
            <div class="cudtomflex">
                <div class="phone-nones-logo">
                    <a href="{{ url('umpire') }}" class="logocancor-s"><img class="logos-hcian"
                            src="{{ asset('storage/umpire') }}/img/uc-logo.png" alt=""></a>
                </div>
               
                <div class="name-propic">
                    @php
                        $oweReceived = getUmpireOweReceived($umpire_data->umpid);
                        if ($umpire_data->profilepic == null) {
                            $src = asset('storage/umpire') . '/img/jone.jpg';
                        } else {
                            $src = asset('storage/images') . '/' . $umpire_data->profilepic;
                        }
                    @endphp
                    <div class="image-propic">
                        <img class="umpdp" src="{{ $src }}" alt="">
                    </div>
                    <div class="accname">
                        {{ $umpire_data->name }}
                        <span><i class="fa-solid fa-chevron-down"></i></span>
                        <div class="submenus d-none">

                            <div class="profiels">
                                <a href="{{ url('umpire/profile') }}" class="text-and-icon"> <span> Profile</span>
                                    <span><i class="fa-regular fa-user"></i></span>
                                </a>
                                {{-- <a href="{{ url('umpire/notifications') }}" class="text-and-icon"> <span>
                                        Notification</span>
                                    <span><i class="fa-regular fa-envelope"></i></span>
                                </a> --}}
                                <a href="{{ url('umpire/change-password') }}" class="text-and-icon"> <span> Change
                                        Password</span>
                                    <span><i class="fa-solid fa-lock"></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="logoutbtn" data-bs-toggle="modal" data-bs-target="#logout">
                    <i class="fa-solid fa-power-off"></i>
                </div>
                <div class="money-bgsd">
                    <i class="fa-solid fa-sack-dollar"></i>
                    <span class="moneys">${{ $oweReceived['total_pending'] }}</span>
                </div>
                <div class="hambrgrbtn">
                    <i class="fa-solid fa-bars"></i>
                </div>
            </div>
        </div>
    </header>
    <script>
        const accname = document.querySelector('.accname')
        const submenus = document.querySelector('.submenus')
        accname.onclick = function clicksubmen() {
            submenus.classList.toggle('d-none')
        }
    </script>
    <script>
        $('.hambrgrbtn').click(function(e) {

            $('.sideholebar-lft').toggleClass('toggle-class');


        });

        function test() {

            $('.sideholebar-lft').removeClass('toggle-class');
        }
    </script>
    <script>
        $(document).ready(function() {
            if (!$('table').hasClass('table-responsive-sm')) {
                $("table thead th").each(function(index) {
                    var columnName = $(this).text().trim();
                    if (columnName !== "") {
                        $("table tbody tr").each(function() {
                            var td = $(this).find("td:nth-child(" + (index + 1) + ")");
                            td.html("<span class='phone-labels'>" + columnName + "</span>" + td
                                .html());
                        });
                    }
                });
            }
        });
    </script>
    <div class="modal fade" id="logout" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-loout-container">
                    <h3 class="textfot-logout">Are you sure you want to Log out?</h3>
                    <div class="buttons-flex hyscs">
                        <div class="button1div"><button type="button" class="redbtn submit"
                                onclick="window.location.replace('{{ url('umpire/logout') }}')">Log out</button></div>
                        <div class="buttondiv-trans"><button data-bs-dismiss="modal" class="cnclbtn buycnm">Stay logged
                                in</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('umpire.layouts.leftbar')
