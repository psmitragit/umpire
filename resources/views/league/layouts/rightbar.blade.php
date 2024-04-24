<div class="pos-abes-righ-sidebar">

    <div class="upcoming-games">
        <div class="upcomingpages-flex">
            <h2>Upcoming Games</h2>
            <a href="{{ url('league') }}" class="textreds">View All</a>
        </div>

        @if ($luc_games = league_upcoming_games(3))
        @foreach ($luc_games as $luc_game)
            @php
                $luc_game_inputDate = Illuminate\Support\Carbon::parse($luc_game->gamedate_toDisplay);
                $luc_game_dateFormatted = $luc_game_inputDate->format('D n/j');
                $luc_game_timeFormatted = $luc_game_inputDate->format('g:ia');
            @endphp
            <div class="datesd">
                <span class="texts-with-icon"><i
                        class="fa-solid fa-calendar-days"></i> {{ $luc_game_dateFormatted }}</span>
                <span class="texts-with-icon"><i class="fa-solid fa-clock"></i> {{ $luc_game_timeFormatted }}</span>
                <span class="texts-with-icon"><i class="fa-solid fa-users"></i> {{ $luc_game->playersage }} Yrs</span>
            </div>


            <div class="heading">
                <i class="fa-solid fa-location-dot"></i>
                <span class="pointer-location">{{ $luc_game->location->ground }}</span>

            </div>
        @endforeach
    @endif


    </div>

    <div class="notification">
        <div class="notificationdis">Notifications</div>
        @if ($league_notis = get_notifications($league_data->leagueid,2, 4))
            @foreach ($league_notis as $league_noti)
                <a class="iconwithtext">
                    <div class="niti-icon">{!! $league_noti->icon->code !!}</div>
                    <div class="test-fhas">{{ $league_noti->leaguemsg }}</div>
                </a>
            @endforeach
        @endif
        <a href="{{ url('league/notifications') }}" class="viewmore">View More</a>
    </div>

</div>
