@extends('league.layouts.main')
@section('main-container')
    <div class="body-content me-customs">
        <div class="namphomediv">
            <h1 class="pageTitle">Home</h1>
            <div class="mapbtns-div">
                <div class="Admins">
                    <div class="inputs-srch">
                        <input placeholder="Search" class="input-srch-field" type="text" oninput="search()" id="searchInput">
                        <button class="srch-mag-btn" type="button"> <img
                                src="{{ asset('storage/league') }}/img/srch-icon.png" alt=""> </button>
                        <input type="hidden" name="table_type" value="myTable">
                    </div>
                </div>
            </div>
        </div>

        <div class="list-viw-contet" id="list-conssst2">
            <div class="my-games">
                <h2 class="game-title">My Games</h2>
                <div class="btns-divs">
                    <button class="upcomne active" id="upc">Upcoming</button>
                    <button class="upcomne " id="his">History</button>
                </div>
            </div>

            <div id="upcoming_games">
                <table class="rowas-tabl" id="myTable">
                    <thead>
                        <tr>
                            <th>
                                Date/Time
                            </th>
                            @if (checkToggleStatus($league_data->leagueid, 'teams'))
                                {{-- leave blank --}}
                            @else
                                <th>Teams</th>
                            @endif
                            <th>Location</th>
                            <th>Primary</th>
                            @if (checkToggleStatus($league_data->leagueid, 'umpire_2'))
                                {{-- leave blank --}}
                            @else
                                <th>Secondary</th>
                            @endif
                            @if (checkToggleStatus($league_data->leagueid, 'umpire_3'))
                                {{-- leave blank --}}
                            @else
                                <th>third</th>
                            @endif
                            @if (checkToggleStatus($league_data->leagueid, 'umpire_4'))
                                {{-- leave blank --}}
                            @else
                                <th>Fourth</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rowCount = 0;
                        @endphp
                        @if (count($league_upcomming_games_grouped) > 0)
                            @foreach ($league_upcomming_games_grouped as $groupByDate => $league_upcomming_games)
                                <tr>
                                    <td colspan="7">
                                        <h6 class="this-is-it">
                                            {{ date('l F jS Y', strtotime($groupByDate)) }}
                                        </h6>
                                    </td>
                                </tr>
                                @foreach ($league_upcomming_games as $league_upcomming_game)
                                    @php
                                        $inputDate = $league_upcomming_game->gamedate_toDisplay;
                                        $carbonDate = Illuminate\Support\Carbon::parse($inputDate);
                                        $gamedate = $carbonDate->format('D m/d/y h:ia');
                                        for ($i = 1; $i <= 4; $i++) {
                                            $col = 'ump' . $i;
                                            if ($league_upcomming_game->umpreqd >= $i) {
                                                if ($league_upcomming_game->{$col} !== null) {
                                                    ${'ump' . $i} = $league_upcomming_game->{'umpire' . $i}->name;
                                                } else {
                                                    ${'ump' . $i} = '<span class="text-danger">Empty</span>';
                                                }
                                            } else {
                                                ${'ump' . $i} = '_ _';
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $gamedate }}</td>
                                        @if (checkToggleStatus($league_data->leagueid, 'teams'))
                                            {{-- leave blank --}}
                                        @else
                                            <td class="team">{{ $league_upcomming_game->hometeam->teamname }} vs
                                                {{ $league_upcomming_game->awayteam->teamname }}</td>
                                        @endif
                                        <td><span class="aspans">{{ $league_upcomming_game->location->ground }}</span></td>
                                        <td class="color-prmths">{!! $ump1 !!}</td>
                                        @if (checkToggleStatus($league_data->leagueid, 'umpire_2'))
                                            {{-- leave blank --}}
                                        @else
                                            <td class="color-prmths">{!! $ump2 !!}</td>
                                        @endif
                                        @if (checkToggleStatus($league_data->leagueid, 'umpire_3'))
                                            {{-- leave blank --}}
                                        @else
                                            <td class="color-prmths">{!! $ump3 !!}</td>
                                        @endif
                                        @if (checkToggleStatus($league_data->leagueid, 'umpire_4'))
                                            {{-- leave blank --}}
                                        @else
                                            <td class="color-prmths">{!! $ump4 !!}</td>
                                        @endif
                                    </tr>
                                    @php
                                        $rowCount++;
                                    @endphp
                                @endforeach
                            @endforeach
                        @endif
                    </tbody>

                </table>
                @if ($rowCount > 10)
                    <button id="toggleButton"><i class="fa-solid fa-angle-down"></i></button>
                @endif
            </div>



            <div id="historical_games" style="display: none;">
                <table class="rowas-tabl" id="myTable2">
                    <thead>
                        <tr>
                            <th>
                                Date/Time
                            </th>

                            @if (checkToggleStatus($league_data->leagueid, 'teams'))
                                {{-- leave blank --}}
                            @else
                                <th>Teams</th>
                            @endif

                            <th>Location</th>
                            <th>Primary</th>
                            @if (checkToggleStatus($league_data->leagueid, 'umpire_2'))
                                {{-- leave blank --}}
                            @else
                                <th>Secondary</th>
                            @endif
                            @if (checkToggleStatus($league_data->leagueid, 'umpire_3'))
                                {{-- leave blank --}}
                            @else
                                <th>third</th>
                            @endif
                            @if (checkToggleStatus($league_data->leagueid, 'umpire_4'))
                                {{-- leave blank --}}
                            @else
                                <th>Fourth</th>
                            @endif

                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rowPastCount = 0;
                        @endphp
                        @if (count($league_past_games_grouped) > 0)
                            @foreach ($league_past_games_grouped as $groupByDate => $league_past_games)
                                <tr>
                                    <td colspan="7">
                                        <h6 class="this-is-it">
                                            {{ date('l F jS Y', strtotime($groupByDate)) }}
                                        </h6>
                                    </td>
                                </tr>
                                @if ($league_past_games->count() > 0)
                                    @foreach ($league_past_games as $league_past_game)
                                        @php
                                            $inputDate = $league_past_game->gamedate_toDisplay;
                                            $carbonDate = Illuminate\Support\Carbon::parse($inputDate);
                                            $gamedate = $carbonDate->format('D m/d/y h:ia');
                                            for ($i = 1; $i <= 4; $i++) {
                                                $report_col = 'report' . $i;
                                                $col = 'ump' . $i;
                                                if ($league_past_game->umpreqd >= $i) {
                                                    if ($league_past_game->{$col} !== null) {
                                                        if ($league_past_game->report == 1) {
                                                            if ($league_past_game->{$report_col} !== null) {
                                                                if (
                                                                    checkIfReportIsFake(
                                                                        $league_past_game->gameid,
                                                                        $report_col,
                                                                    )
                                                                ) {
                                                                    $report = '<span class="text-danger">Absent</span>';
                                                                } else {
                                                                    if (
                                                                        checkIfReportIsHighlighted(
                                                                            $league_past_game->gameid,
                                                                            $report_col,
                                                                        )
                                                                    ) {
                                                                        $highlighted_class = 'highlighted_class';
                                                                        $text = 'Important Report';
                                                                    } else {
                                                                        $highlighted_class = '';
                                                                        $text = 'View Report';
                                                                    }
                                                                    $report =
                                                                        '<a href="javascript:void(0)" class="text-primary ' .
                                                                        $highlighted_class .
                                                                        '" onclick="view_report(' .
                                                                        $league_past_game->gameid .
                                                                        ', \'' .
                                                                        $report_col .
                                                                        '\', ' .
                                                                        $league_past_game->{$col} .
                                                                        ')">' .
                                                                        $text .
                                                                        '</a>';
                                                                }
                                                            } else {
                                                                if (
                                                                    checkIfReportIsFake(
                                                                        $league_past_game->gameid,
                                                                        $report_col,
                                                                    )
                                                                ) {
                                                                    $report = '<span class="text-danger">Absent</span>';
                                                                } else {
                                                                    $report =
                                                                        '<a data-text="Mark this umpire as absent?" href="' .
                                                                        url(
                                                                            'league/report-absent/' .
                                                                                $league_past_game->gameid .
                                                                                '/' .
                                                                                $report_col .
                                                                                '/' .
                                                                                $league_past_game->{$col},
                                                                        ) .
                                                                        '" onclick="confirmClickManual(event)" class="text-danger">Report Not Submitted</a>';
                                                                }
                                                            }
                                                        } else {
                                                            $report = '';
                                                        }
                                                        ${'ump' . $i} =
                                                            '<div>' .
                                                            $league_past_game->{'umpire' . $i}->name .
                                                            '</div><div>' .
                                                            $report .
                                                            '</div>';
                                                    } else {
                                                        ${'ump' . $i} = '<span class="text-danger">Empty</span>';
                                                    }
                                                } else {
                                                    ${'ump' . $i} = '_ _';
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $gamedate }}</td>
                                            @if (checkToggleStatus($league_data->leagueid, 'teams'))
                                                {{-- leave blank --}}
                                            @else
                                                <td class="team" id="teamvs{{ $league_past_game->gameid }}">
                                                    {{ $league_past_game->hometeam->teamname }} vs
                                                    {{ $league_past_game->awayteam->teamname }}</td>
                                            @endif
                                            <td>{{ $league_past_game->location->ground }}</td>
                                            <td class="color-prmths">{!! $ump1 !!}</td>
                                            @if (checkToggleStatus($league_data->leagueid, 'umpire_2'))
                                                {{-- leave blank --}}
                                            @else
                                                <td class="color-prmths">{!! $ump2 !!}</td>
                                            @endif
                                            @if (checkToggleStatus($league_data->leagueid, 'umpire_3'))
                                                {{-- leave blank --}}
                                            @else
                                                <td class="color-prmths">{!! $ump3 !!}</td>
                                            @endif
                                            @if (checkToggleStatus($league_data->leagueid, 'umpire_4'))
                                                {{-- leave blank --}}
                                            @else
                                                <td class="color-prmths">{!! $ump4 !!}</td>
                                            @endif
                                        </tr>
                                        @php
                                            $rowPastCount++;
                                        @endphp
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
                @if ($rowPastCount > 10)
                    <button id="toggleButton2"><i class="fa-solid fa-angle-down"></i></button>
                @endif

            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-hesn">
                    <h5 class="modalicons-title">Game Report</h5>
                    <div class="tej">
                        <a href="" id="reportAbsentBtn" class="submitbtns confirmCancel">Report Absent</a>
                    </div>
                    <button type="button" class="btn-closes" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-x"></i></button>

                </div>
                <div class="modal-body">

                    <div class="toptext-modal" id="subtext"></div>
                    <div id="reportquestions">

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- modal -->
    <script>
        function togglebtn(table_id, button_id, showAllRows = false) {
            const table = document.getElementById(table_id);
            const button = document.getElementById(button_id);
            if (table && button) {
                let rowsToShow = 10;
                const rows = table.querySelectorAll('tbody tr');
                for (let i = 0; i < rows.length; i++) {
                    if (showAllRows || i < rowsToShow) {
                        // Add the 'show-row' class
                        rows[i].classList.remove('hide-row');
                        rows[i].classList.add('show-row');
                    } else {
                        // Add the 'hide-row' class
                        rows[i].classList.remove('show-row');
                        rows[i].classList.add('hide-row');
                    }
                }
                button.innerHTML = showAllRows ?
                    '<i class="fa-solid fa-angle-up" onclick="togglebtn(\'' + table_id + '\',\'' + button_id + '\',' +
                    false +
                    ')"></i>' :
                    '<i class="fa-solid fa-angle-down" onclick="togglebtn(\'' + table_id + '\',\'' + button_id + '\',' +
                    true +
                    ')"></i>';
            }
            search();
        }
    </script>
    <script>
        $(document).ready(function() {
            togglebtn('myTable', 'toggleButton');
            filterTable('myTable');
            $("#his").on("click", function() {
                togglebtn('myTable2', 'toggleButton2');
                $("#upc").removeClass("active");
                $(this).addClass("active");
                $('#upcoming_games').hide();
                $('[name="table_type"]').val('myTable2');
                $('#historical_games').show();
                search();
            });
            $("#upc").on("click", function() {
                togglebtn('myTable', 'toggleButton');
                $("#his").removeClass("active");
                $(this).addClass("active");
                $('#historical_games').hide();
                $('[name="table_type"]').val('myTable');
                $('#upcoming_games').show();
                search();
            });
        });

        function view_report(gameid, report_column, umpid) {
            $.ajax({
                url: "{{ url('league/view-report') }}" + '/' + gameid + '/' + report_column,
                success: function(res) {
                    var teamvs = $('#teamvs' + gameid).text();
                    var leaguename = '{{ $league_data->leaguename }}';
                    if (teamvs.startsWith('Teams')) {
                        teamvs = teamvs.substring('Teams'.length).trim();
                    }
                    let subTextHtml = teamvs + ' for ' + leaguename;
                    $('#subtext').html(subTextHtml);
                    $('#reportquestions').html(res);
                    let url = "{{ url('league/report-absent') }}" + '/' + gameid + '/' + report_column + '/' +
                        umpid;
                    $('#reportAbsentBtn').attr('href', url);
                    $('#reportModal').modal('show');
                },
                error: function(res) {
                    toastr.error('Something went wrong..!!');
                }
            });
        }

        function search() {
            var table = $('[name="table_type"]').val();
            filterTable(table);
        }
    </script>
@endsection
