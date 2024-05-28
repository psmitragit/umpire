<div class="pos-abes-righ-sidebar">

    <div class="upcoming-games asff">
        <div class="upcomingpages-flex">
            <h2>Upcoming Games</h2>
            <a href="{{ url('umpire') }}" class="textreds">View All</a>
        </div>
        <div class="games-upcomings">
            @if ($upc_games = umpire_upcoming_games(3))
                @foreach ($upc_games as $upc_game)
                    @php
                        $upc_game_inputDate = Illuminate\Support\Carbon::parse($upc_game->gamedate_toDisplay);
                        $upc_game_dateFormatted = $upc_game_inputDate->format('D n/j');
                        $upc_game_timeFormatted = $upc_game_inputDate->format('g:ia');
                    @endphp
                    <div class="datesd">
                        <span class="texts-with-icon"><i class="fa-solid fa-calendar-days"></i>
                            {{ $upc_game_dateFormatted }}</span>
                        <span class="texts-with-icon"><i class="fa-solid fa-clock"></i>
                            {{ $upc_game_timeFormatted }}</span>
                        <span class="texts-with-icon"><i class="fa-solid fa-users"></i> {{ $upc_game->playersage }}
                            Yrs</span>
                    </div>


                    <div class="heading">
                        <i class="fa-solid fa-location-dot"></i>
                        <span class="pointer-location">{{ $upc_game->location->ground }}</span>

                    </div>
                @endforeach
            @endif
        </div>
        @if (checkIfUmpireNeedsToSubmitReport() > 0)
            <a href="{{ url('umpire/show-reports') }}" class="blue-btns view-btn primart-yehs">Submit Reports</a>
        @endif

    </div>



    <div class="notification">
        <div class="notificationdis">Notifications</div>
        @if ($ump_notis = get_notifications($umpire_data->umpid, 3, 4))
            @foreach ($ump_notis as $ump_noti)
                @php
                    $pretext = '';
                    if ($ump_noti->type == 1) {
                        $pretext = $ump_noti->league->leaguename . ': ';
                    }
                @endphp
                <a class="iconwithtext">
                    <div class="niti-icon">{!! $ump_noti->icon->code !!}</div>
                    <div class="test-fhas"><b>{{ $pretext }}</b>{{ $ump_noti->msg }}</div>
                </a>
            @endforeach
        @endif
        <a href="{{ url('umpire/notifications') }}" class="viewmore">View More</a>
    </div>
</div>
