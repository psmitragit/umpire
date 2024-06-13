@extends('umpire.layouts.main')
@section('main-container')
    <div class="body-content me-customs">
        <div class="namphomediv">
            <h1 class="pageTitle">Open Games</h1>
            <div class="mapbtns-div">

            </div>
        </div>



        <div class="list-viw-contet" id="list-cont">
            <table class="rowas-tabl" id="myTable">
                <thead>
                    <tr>
                        <th>Date Time</th>
                        <th>league</th>
                        <th>Teams</th>
                        <th>Location</th>
                        <th>pay</th>
                        <th>Empty slots</th>
                    </tr>
                </thead>
                <tbody id="myTableBody">
                    @php
                        $row_count = 0;
                    @endphp
                    @if ($upcomming_games_grouped->count() > 0)
                        @foreach ($upcomming_games_grouped as $groupByDate => $upcomming_games)
                            <tr class="gamegroupclass" id="gamegroupclass{{ $groupByDate }}">
                                <td colspan="6">
                                    <h6 class="this-is-it">
                                        {{ date('l F jS Y', strtotime($groupByDate)) }}
                                    </h6>
                                </td>
                            </tr>
                            @foreach ($upcomming_games as $upcoming_game)
                                @php
                                    $inputDate = $upcoming_game->gamedate_toDisplay;
                                    $carbonDate = Illuminate\Support\Carbon::parse($inputDate);
                                    $gamedate = $carbonDate->format('D m/d/y h:ia');
                                    $isAssigned = false;
                                    if (
                                        $upcoming_game->ump1 == $umpire_data->umpid ||
                                        $upcoming_game->ump2 == $umpire_data->umpid ||
                                        $upcoming_game->ump3 == $umpire_data->umpid ||
                                        $upcoming_game->ump4 == $umpire_data->umpid
                                    ) {
                                        $isAssigned = true;
                                    }
                                    $gameleague = $upcoming_game->league;
                                    $now = Illuminate\Support\Carbon::now();
                                    $diffInDays = $now->diffInDays($carbonDate);
                                @endphp
                                @if ($diffInDays <= $gameleague->assignbefore)
                                    @if (!$isAssigned)
                                        @php
                                            $umpreq = $upcoming_game->umpreqd;
                                            $i = 1;
                                            $empty_umps = [];
                                            for ($i = 1; $i <= $umpreq; $i++) {
                                                $col = 'ump';
                                                $col = $col . $i;
                                                if ($upcoming_game->{$col} == null) {
                                                    $pay = 0;
                                                    if ($col == 'ump1') {
                                                        $pay =
                                                            '$' .
                                                            $upcoming_game->ump1pay +
                                                            $upcoming_game->ump1bonus;
                                                    } else {
                                                        $pay =
                                                            '$' .
                                                            $upcoming_game->ump234pay +
                                                            $upcoming_game->ump234bonus;
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
                                        @if (!empty($empty_umps))
                                            @foreach ($empty_umps as $empty_ump)
                                                <tr class="gamegroupclass{{ $groupByDate }}">
                                                    <td>{{ $gamedate }}</td>
                                                    <td>{{ $upcoming_game->league->leaguename }}</td>
                                                    <td class="team">{{ $upcoming_game->hometeam->teamname }} vs
                                                        {{ $upcoming_game->awayteam->teamname }}</td>
                                                    <td>{{ $upcoming_game->location->ground }}</td>
                                                    <td class="text-success">{!! $empty_ump['pay'] !!}</td>
                                                    <td>
                                                        <button
                                                            data-text="Are you sure you want to be assigned to this game?"
                                                            href="{{ url('umpire/assign-to-game/' . $upcoming_game->gameid . '/' . $empty_ump['col']) }}"
                                                            class="view-btn primart-yehs"
                                                            onclick="return opnemodals(this,true)">{{ $empty_ump['pos'] }}</button>
                                                    </td>
                                                </tr>
                                                @php
                                                    $row_count++;
                                                @endphp
                                            @endforeach
                                        @else
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    @endif
                </tbody>
            </table>
            @if ($row_count > 7)
                <button id="toggleButton"><i class="fa-solid fa-angle-down"></i></button>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const table = document.getElementById('myTable');
                        const button = document.getElementById('toggleButton');
                        let showAllRows = false;
                        let rowsToShow = 7;

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
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('tr.gamegroupclass').each(function() {
                var rowId = $(this).attr('id');
                var classToCheck = rowId;
                if ($('tr.' + classToCheck).length === 0) {
                    $(this).remove();
                }
            });
        });
    </script>
@endsection
