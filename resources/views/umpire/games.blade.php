@extends('umpire.layouts.main')
@section('main-container')
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
                    {{ $games[0]->league->leaguename }}</h2>
                <div class="btns-divs">
                    <button class="upcomne active" id="all-matches">All Matches</button>
                    <button class="upcomne" id="assigned">Assigned</button>
                </div>
            </div>
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
                    @if ($games->count() > 0)
                        @php
                            $row_count = 0;
                        @endphp
                        @foreach ($games as $game)
                            @php
                                $inputDate = $game->gamedate_toDisplay;
                                $carbonDate = Illuminate\Support\Carbon::parse($inputDate);
                                $gamedate = $carbonDate->format('D m/d/y h:ia');
                                $today = Illuminate\Support\Carbon::now();
                                $umpType = '';
                                $isAssigned = true;
                                if ($game->ump1 == $umpire_data->umpid) {
                                    $umpType = '<span class="text-success">Yes(Main Umpire)</span>';
                                } elseif ($game->ump2 == $umpire_data->umpid) {
                                    $umpType = '<span class="text-success">Yes(2nd Umpire)</span>';
                                } elseif ($game->ump3 == $umpire_data->umpid) {
                                    $umpType = '<span class="text-success">Yes(3rd Umpire)</span>';
                                } elseif ($game->ump4 == $umpire_data->umpid) {
                                    $umpType = '<span class="text-success">Yes(4th Umpire)</span>';
                                } else {
                                    $isAssigned = false;
                                }
                            @endphp
                            @if ($isAssigned)
                                @php
                                    $pay = 0;
                                    if ($game->ump1 == $umpire_data->umpid) {
                                        $pay = '$' . $game->ump1pay . '+$' . $game->ump1bonus;
                                    } else {
                                        $pay = '$' . $game->ump234pay . '+$' . $game->ump234bonus;
                                    }
                                @endphp
                                <tr class="assigned">
                                    <td>{{ $gamedate }}</td>
                                    <td class="team">{{ $game->hometeam->teamname }} vs
                                        {{ $game->awayteam->teamname }}</td>
                                    <td>{{ $game->location->ground }}</td>
                                    <td class="text-success">{!! $pay !!}</td>
                                    <td>{!! $umpType !!}</td>
                                </tr>
                                @php
                                    $row_count++;
                                @endphp
                            @else
                                @php
                                    $umpreq = $game->umpreqd;
                                    $i = 1;
                                    $empty_umps = [];
                                    for ($i = 1; $i <= $umpreq; $i++) {
                                        $col = 'ump';
                                        $col = $col . $i;
                                        if ($game->{$col} == null) {
                                            $pay = 0;
                                            if ($col == 'ump1') {
                                                $pay = '$' . $game->ump1pay . '+$' . $game->ump1bonus;
                                            } else {
                                                $pay = '$' . $game->ump234pay . '+$' . $game->ump234bonus;
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
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
            @if ($row_count > 10)
                <button id="toggleButton"><i class="fa-solid fa-angle-down"></i></button>
            @endif

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
        document.addEventListener('DOMContentLoaded', function() {
            listview.click();
            const table = document.getElementById('myTable');
            const button = document.getElementById('toggleButton');
            let showAllRows = false;
            let rowsToShow = 10;

            function toggleRows() {
                const rows = table.querySelectorAll('tbody tr');
                for (let i = 0; i < rows.length; i++) {
                    if (showAllRows || i < rowsToShow) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
                button.innerHTML = showAllRows ?
                    '<i class="fa-solid fa-angle-up"></i>' :
                    '<i class="fa-solid fa-angle-down"></i>';

                showAllRows = !showAllRows;
            }
            toggleRows();
            button.addEventListener('click', toggleRows);
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#assigned").on("click", function() {
                $("#all-matches").removeClass("active");
                $(this).addClass("active");
                $("#myTableBody tr:not(.assigned)").hide();
                $("#myTableBody tr.assigned").show();
                $("#toggleButton").hide();
            });
            $("#all-matches").on("click", function() {
                $("#assigned").removeClass("active");
                $(this).addClass("active");
                $("#myTableBody tr").show();
                $("#toggleButton").hide();
            });
        });
    </script>
@endsection
