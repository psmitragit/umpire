<div class="counts-nacvs">
    <div class="settingsnav">
        <a href="{{ url('league/settings') }}" class="nav-settings {{ $active_sub_nav_bar == 'general' ? 'actively' : '' }}"> General</a>
    </div>

    <div class="settingsnav">
        <a href="{{ url('league/settings/points') }}" class="nav-settings {{ $active_sub_nav_bar == 'points' ? 'actively' : '' }}"> Points</a>
    </div>
    <div class="settingsnav">
        <a href="{{ url('league/settings/report') }}" class="nav-settings {{ $active_sub_nav_bar == 'report' ? 'actively' : '' }}"> Report</a>
    </div>
    <div class="settingsnav">
        <a href="{{ url('league/settings/notification') }}" class="nav-settings {{ $active_sub_nav_bar == 'notification' ? 'actively' : '' }}"> Notifications</a>
    </div>
    <div class="settingsnav">
        <a href="{{ url('league/settings/application') }}" class="nav-settings {{ $active_sub_nav_bar == 'application' ? 'actively' : '' }}">Application</a>
    </div>
    <div class="settingsnav">
        <a href="{{ url('league/settings/teams') }}" class="nav-settings {{ $active_sub_nav_bar == 'teams' ? 'actively' : '' }}">Add teams</a>
    </div>
    <div class="settingsnav">
        <a href="{{ url('league/settings/location') }}" class="nav-settings {{ $active_sub_nav_bar == 'location' ? 'actively' : '' }}">Add Location</a>
    </div>
</div>
