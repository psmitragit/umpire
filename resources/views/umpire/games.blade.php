@extends('umpire.layouts.main')
@section('main-container')
    @php
        $today = Illuminate\Support\Carbon::now();
    @endphp
    <div class="body-content">
        <div class="namphomediv">
            <h1 class="pageTitle">Leagues</h1>
            <div class="mapbtns-div">
                <button id="mapview" class="redbtn-mapview trssnps"><i class="fa-solid fa-map"></i> Map view</button>
                <button id="listview" class="redbtn-mapview ritmrgn"><i class="fa-solid fa-list "></i> List
                    view</button>
            </div>
        </div>
        <div class="mapviews-cont d-none" id="map-cont">
            <div id="map" style="height: 450px;"></div>
        </div>


        <div class="list-viw-contet d-none" id="list-cont">
            <div class="my-games">
                <h2 class="game-title"> <span class="anglearows"> My Leagues</span> <i class="fa fa-angle-right"></i>
                    {{ $league->leaguename }}</h2>
                <div class="btns-divs">
                    <button class="upcomne active" id="all-matches">All Matches</button>
                    <button class="upcomne" id="assigned">Assigned</button>
                </div>
            </div>
            <div id="games">
                <table class="rowas-tabl" id="myTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Teams</th>
                            <th>Location</th>
                            <th>Pay</th>
                            <th>Assigned</th>
                        </tr>
                    </thead>
                    <tbody id="myTableBody">
                        @php
                            $row_count = 0;
                        @endphp
                        @if (count($games_grouped) > 0)
                            @foreach ($games_grouped as $groupByDate => $games)
                                <tr>
                                    <td colspan="13">
                                        <h6 class="this-is-it">
                                            {{ date('l F jS Y', strtotime($groupByDate)) }}
                                        </h6>
                                    </td>
                                </tr>
                                @foreach ($games as $game)
                                    @php
                                        $inputDate = $game->gamedate_toDisplay;
                                        $carbonDate = Illuminate\Support\Carbon::parse($inputDate);
                                        $gamedate = $carbonDate->format('D m/d/y h:ia');

                                        $umpreq = $game->umpreqd;
                                        $i = 1;
                                        $empty_umps = [];
                                        for ($i = 1; $i <= $umpreq; $i++) {
                                            $col = 'ump';
                                            $col = $col . $i;
                                            if ($game->{$col} == null) {
                                                $pay = 0;
                                                if ($col == 'ump1') {
                                                    $pay = '$' . $game->ump1pay + $game->ump1bonus;
                                                } else {
                                                    $pay = '$' . $game->ump234pay + $game->ump234bonus;
                                                }
                                                if ($col == 'ump1') {
                                                    $newcol = 'Main Umpire';
                                                } elseif ($col == 'ump2') {
                                                    $newcol = '2nd Umpire';
                                                } elseif ($col == 'ump3') {
                                                    $newcol = '3rd Umpire';
                                                } elseif ($col == 'ump4') {
                                                    $newcol = '4th Umpire';
                                                } else {
                                                    $newcol = '';
                                                }
                                                $empty_umps[] = [
                                                    'pos' => $newcol,
                                                    'pay' => $pay,
                                                    'col' => $col,
                                                ];
                                            }
                                        }
                                    @endphp
                                    @if (!empty($empty_umps) && $game->gamedate >= $today)
                                        @foreach ($empty_umps as $empty_ump)
                                            <tr>
                                                <td>{{ $gamedate }}</td>
                                                <td class="team">{{ $game->hometeam->teamname }} vs
                                                    {{ $game->awayteam->teamname }}</td>
                                                <td>{{ $game->location->ground }}</td>
                                                <td class="text-success">{!! $empty_ump['pay'] !!}</td>
                                                <td>
                                                    <button data-text="Are you sure you want to be assigned to this game?"
                                                        href="{{ url('umpire/assign-to-game/' . $game->gameid . '/' . $empty_ump['col']) }}"
                                                        class="view-btn primart-yehs"
                                                        onclick="return opnemodals(this,true)">{{ $empty_ump['pos'] }}</button>
                                                </td>
                                            </tr>
                                            @php
                                                $row_count++;
                                            @endphp
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>{{ $gamedate }}</td>
                                            <td class="team">{{ $game->hometeam->teamname }} vs
                                                {{ $game->awayteam->teamname }}</td>
                                            <td>{{ $game->location->ground }}</td>
                                            <td>_ _</td>
                                            <td>_ _</td>
                                        </tr>
                                        @php
                                            $row_count++;
                                        @endphp
                                    @endif
                                @endforeach
                            @endforeach
                        @endif
                    </tbody>
                </table>
                @if ($row_count > 10)
                    <button id="toggleButton"><i class="fa-solid fa-angle-down"></i></button>
                @endif
            </div>
            <div id="assigned_games" style="display: none">
                <table class="rowas-tabl" id="">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Teams</th>
                            <th>Location</th>
                            <th>Pay</th>
                            <th>Assigned</th>
                        </tr>
                    </thead>
                    <tbody id="myTableBody">
                        @if (count($assignedGames_grouped) > 0)
                            @foreach ($assignedGames_grouped as $groupByDate => $assignedGames)
                                <tr>
                                    <td colspan="13">
                                        <h6 class="this-is-it">
                                            {{ date('l F jS Y', strtotime($groupByDate)) }}
                                        </h6>
                                    </td>
                                </tr>
                                @foreach ($assignedGames as $assignedGame)
                                    @php
                                        $inputDate = $assignedGame->gamedate_toDisplay;
                                        $carbonDate = Illuminate\Support\Carbon::parse($inputDate);
                                        $assignedGameDate = $carbonDate->format('D m/d/y h:ia');
                                        $umpType = '';

                                        if ($assignedGame->ump1 == $umpire_data->umpid) {
                                            $umpType = '<span class="text-success">Yes(Main Umpire)</span>';
                                        } elseif ($assignedGame->ump2 == $umpire_data->umpid) {
                                            $umpType = '<span class="text-success">Yes(2nd Umpire)</span>';
                                        } elseif ($assignedGame->ump3 == $umpire_data->umpid) {
                                            $umpType = '<span class="text-success">Yes(3rd Umpire)</span>';
                                        } elseif ($assignedGame->ump4 == $umpire_data->umpid) {
                                            $umpType = '<span class="text-success">Yes(4th Umpire)</span>';
                                        }
                                        $pay = 0;
                                        if ($assignedGame->ump1 == $umpire_data->umpid) {
                                            $pay = '$' . $assignedGame->ump1pay + $assignedGame->ump1bonus;
                                        } else {
                                            $pay = '$' . $assignedGame->ump234pay + $assignedGame->ump234bonus;
                                        }
                                    @endphp
                                    <tr class="assigned">
                                        <td>{{ $assignedGameDate }}</td>
                                        <td class="team">{{ $assignedGame->hometeam->teamname }} vs
                                            {{ $assignedGame->awayteam->teamname }}</td>
                                        <td>{{ $assignedGame->location->ground }}</td>
                                        <td class="text-success">{!! $pay !!}</td>
                                        <td>{!! $umpType !!}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        const mapview = document.getElementById('mapview')
        const listview = document.getElementById('listview')
        const mapviewsCont = document.querySelector('#map-cont')
        const listcont = document.querySelector('#list-cont')
        const bodycontent = document.querySelector('.checkbody')
        mapview.onclick = function mapviews() {
            mapviewsCont.classList.remove('d-none')
            listcont.classList.add('d-none')
            mapview.classList.remove('trssnps')
            listview.classList.add('trssnps')
            bodycontent.classList.remove("bgs-colors")
        }
        listview.onclick = function listviews() {
            listcont.classList.remove('d-none')
            mapviewsCont.classList.add('d-none')
            listview.classList.remove('trssnps')
            mapview.classList.add('trssnps')
            bodycontent.classList.add("bgs-colors")
        }

        function togglebtn(table_id, button_id, showAllRows = false) {
            const table = document.getElementById(table_id);
            const button = document.getElementById(button_id);
            if (table && button) {
                let rowsToShow = 10;
                const rows = table.querySelectorAll('tbody tr');
                for (let i = 0; i < rows.length; i++) {
                    if (showAllRows || i < rowsToShow) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
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
        }
    </script>
    <script>
        $(document).ready(function() {
            listview.click();
            togglebtn('myTable', 'toggleButton');
            $("#assigned").on("click", function() {
                $("#all-matches").removeClass("active");
                $(this).addClass("active");
                $("#games").hide();
                $("#assigned_games").show();
                $("#toggleButton").hide();
            });
            $("#all-matches").on("click", function() {
                togglebtn('myTable', 'toggleButton');
                $("#assigned").removeClass("active");
                $(this).addClass("active");
                $("#games").show();
                $("#assigned_games").hide();
                $("#toggleButton").show();
            });
        });
    </script>
@endsection
