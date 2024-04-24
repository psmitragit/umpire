<div class="point-nav">
    <div class="point-anc-div">
        <a href="{{ url('league/settings/point/schedule-on-any-game') }}"
            class="points-acn {{ $point_menu == 1 ? 'active' : '' }}">Schedule on any game</a>
    </div>
    <div class="point-anc-div">
        <a href="{{ url('league/settings/point/age-of-players') }}"
            class="points-acn {{ $point_menu == 2 ? 'active' : '' }}">Age of Players</a>
    </div>
    <div class="point-anc-div">
        <a href="{{ url('league/settings/point/location') }}"
            class="points-acn {{ $point_menu == 3 ? 'active' : '' }}">Location</a>
    </div>
    <div class="point-anc-div">
        <a href="{{ url('league/settings/point/pay') }}"
            class="points-acn {{ $point_menu == 4 ? 'active' : '' }}">Pay</a>
    </div>
    <div class="point-anc-div">
        <a href="{{ url('league/settings/point/time') }}"
            class="points-acn {{ $point_menu == 5 ? 'active' : '' }}">Time</a>
    </div>
    <div class="point-anc-div">
        <a href="{{ url('league/settings/point/day-of-week') }}"
            class="points-acn {{ $point_menu == 6 ? 'active' : '' }}">Day of week</a>
    </div>
    <div class="point-anc-div">
        <a href="{{ url('league/settings/point/umpire-position') }}"
            class="points-acn {{ $point_menu == 7 ? 'active' : '' }}">Umpire Position</a>
    </div>
    <div class="point-anc-div">
        <a href="{{ url('league/settings/point/umpire-duration') }}"
            class="points-acn {{ $point_menu == 8 ? 'active' : '' }}">Umpire Duration</a>
    </div>
    <div class="point-anc-div">
        <a href="{{ url('league/settings/point/total-game') }}"
            class="points-acn {{ $point_menu == 9 ? 'active' : '' }}">Total Game</a>
    </div>
</div>
