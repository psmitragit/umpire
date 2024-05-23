<div class="sideholebar-lft">
    <div class="topbarmanage"></div>
    <div class="sidebars-menu">

        <div class="onlypohone-profile-pic">
            <div class="buttonx">X</div>
            <div class="text-cv">{{ $league_data->leaguename }}</div>
        </div>
        <div class="logo-menus">
            <a href="{{ url('league') }}" class="logocancor-s"><img class="logos-hcian"
                    src="{{ asset('storage/league') }}/img/uc-logo.png" alt=""></a>
        </div>
        <div class="buttons-disd">
            <a href="{{ url('league') }}" class="navlogos {{ $nav == 'home' ? 'active' : '' }}"> <span><i
                        class="fa-solid fa-calendar-days"></i></span> Home</a>
        </div>
        <div class="buttons-disd">
            <a href="{{ url('league/game-manual-schedule') }}"
                class="navlogos {{ $nav == 'auto_algo' ? 'active' : '' }}">
                <span><i class="fas fa-code-branch"></i></span> Run Schedule</a>
        </div>
        <div class="buttons-disd">
            <a href="{{ url('league/settings') }}" class="navlogos {{ $nav == 'settings' ? 'active' : '' }}"> <span><i
                        class="fa-solid fa-gear"></i></span> Settings</a>
        </div>
        <div class="buttons-disd">
            <a href="{{ url('league/games') }}" class="navlogos {{ $nav == 'games' ? 'active' : '' }}"> <span><i
                        class="fa-solid fa-baseball"></i></span> Games</a>
        </div>
        <div class="buttons-disd">
            <a href="{{ url('league/umpires') }}" class="navlogos {{ $nav == 'umpires' ? 'active' : '' }}"> <span><i
                        class="fa-brands fa-redhat"></i></span> Umpires</a>
        </div>
        <div class="buttons-disd">
            <a href="{{ url('league/payout') }}" class="navlogos {{ $nav == 'payout' ? 'active' : '' }}"> <span><i
                        class="fa-solid fa-sack-dollar"></i></span> Payout</a>
        </div>
        <div class="buttons-disd">
            <a href="{{ url('league/notifications') }}" class="navlogos"> <span><i
                        class="fa-regular fa-envelope"></i></span>
                Notification </a>
        </div>

        <div class="phone-newmenus">
            <div class="buttons-disd">
                <a href="{{ url('league/notification') }}"
                    class="navlogos {{ $nav == 'notification' ? 'active' : '' }}"> <span><i
                            class="fa-regular fa-envelope"></i></span> Notification

                </a>

            </div>
            <div class="buttons-disd">
                <a href="{{ url('league/change-password') }}"
                    class="navlogos {{ $nav == 'change-password' ? 'active' : '' }}"> <span><i
                            class="fa-solid fa-lock"></i></span> <span> Change Password</span>

                </a>
            </div>
        </div>

    </div>
</div>
