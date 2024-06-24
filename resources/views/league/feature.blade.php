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
            <div class="row">
                <div class="col-md-12">
                    @livewire('LeagueSettings', ['leagueRow' => $league_data])
                </div>
            </div>
        </div>
    </div>
@endsection
