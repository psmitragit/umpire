@extends('general.layouts.main')
@section('main-container')
    <main>
        <div class="home-banner">
            <div class="banner-tecxts">
                <h1 class="banner-title"><span>Umpire</span> Central</h1>
                <div class="texr-banne">Your All-in-One Hub for Umpire Scheduling to bring your baseball league to the next level</div>
            </div>

            <div class="overkaycolor"></div>
            <div class="overkaycolor2"></div>
        </div>
        <div class="logo">
            <div class="logo-uc">
                <img src="{{ asset('storage/frontend/image/uc-logo.png') }}" alt="">
            </div>
        </div>





        <div class="container">
            <div class="px-100 sc-2 pb-0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="imism" style="background-image: url({{ asset('storage/frontend/image/sectionImg2jpg.jpg') }});">
                            <img class="shadowimg" src="{{ asset('storage/frontend/image/secimg1.jpg') }}" alt="">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="side-tbsa">
                            <h3 class="subtitle">Baseball</h3>
                            <h2 class="section-1s">umpire signup</h2>
                            <div class="texts-sec2">Connect with local leagues, manage your schedule with ease, and step up to the plate with confidence. Create your account today and take the first step towards a game-changing umpiring experience.</div>
                            <a href="{{ url('umpire-signup') }}" class="buton-signup">Sign Up</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-100 section2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="bacgs">
                            <h2 class="loginbottom">Umpires Login</h2>
                            <div class="logintexts">Log in to access your dashboard, manage your upcoming games, and set your availability.</div>
                            <a href="{{ url('umpire-login') }}" class="transpbtn">Login</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bacgs2">
                            <h2 class="loginbottom">League Owners Login</h2>
                            <div class="logintexts">Log in to coordinate schedules, manage umpire assignments, and view payroll.</div>
                            <a href="{{ url('league-login') }}" class="transpbtn">Login</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endsection
