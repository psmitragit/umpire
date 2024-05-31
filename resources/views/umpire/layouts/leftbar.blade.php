<div class="sideholebar-lft">
    <div class="topbarmanage"></div>
    <div class="sidebars-menu">
        <div class="logo-menus">
            <a href="{{ url('umpire') }}" class="logocancor-s"><img class="logos-hcian"
                    src="{{ asset('storage/umpire') }}/img/uc-logo.png" alt=""></a>
        </div>
        <div class="profile-images">
            <div class="buttonxss" onclick="test()">X</div>

            <div class="sidebar-aimsgs"> <img class="umpdp" src="{{ $src }}" alt=""></div>
            <div class="nmae-scn"> {{ $umpire_data->name }}</div>
        </div>
        <div class="buttons-disd">
            <a href="{{ url('umpire') }}" class="navlogos {{ $nav == 'home' ? 'active' : '' }}"> <span><i
                        class="fa-solid fa-calendar-days"></i></span> Home</a>
        </div>
        <div class="buttons-disd">
            <a href="{{ url('umpire/leagues') }}" class="navlogos {{ $nav == 'leagues' ? 'active' : '' }}"> <span><i
                        class="fa-solid fa-trophy"></i></span> Leagues</a>
        </div>
        <div class="buttons-disd">
            <a href="{{ url('umpire/avail') }}" class="navlogos {{ $nav == 'avail' ? 'active' : '' }}"> <span><i
                        class="fa-solid fa-circle-check"></i></span> Availability</a>
        </div>
        <div class="buttons-disd">
            <a href="{{ url('umpire/games') }}" class="navlogos {{ $nav == 'games' ? 'active' : '' }}"> <span><i
                        class="fa-solid fa-calendar-check"></i></span> Open Games</a>
        </div>
        <div class="buttons-disd">
            <a href="{{ url('umpire/earning') }}" class="navlogos {{ $nav == 'earning' ? 'active' : '' }}"> <span><i
                        class="fa-solid fa-sack-dollar"></i></span> My Earning</a>
        </div>

        <div class="buttons-disd">
            <a href="{{ url('umpire/notifications') }}" class="navlogos {{ $nav == 'notifications' ? 'active' : '' }}"> <span><i
                        class="fa-regular fa-envelope"></i></span>
                Notification </a>
        </div>


        <div class="phnes">
            <div class="buttons-disd">
                <a href="{{ url('umpire/profile') }}" class="navlogos {{ $nav == 'profile' ? 'active' : '' }}">
                    <span><i class="fa-regular fa-user"></i></span>
                    Profile

                </a>
            </div>
            {{-- <div class="buttons-disd">
                <a href="{{ url('umpire/notifications') }}" class="navlogos {{ $nav == 'notifications' ? 'active' : '' }}">
                    <span><i class="fa-regular fa-envelope"></i></span>
                    Notification

                </a>
            </div> --}}
            <div class="buttons-disd">
                <a href="{{ url('umpire/change-password') }}"
                    class="navlogos {{ $nav == 'change-password' ? 'active' : '' }}">
                    <span><i class="fa-solid fa-lock"></i></span> Change Password

                </a>
            </div>
        </div>
    </div>
</div>
