@extends('umpire.layouts.main')
@section('main-container')
    <div class="body-content">
        <div class="namphomediv">
            <h1 class="pageTitle">Home</h1>
            <div class="mapbtns-div">
                <button id="mapview" class="redbtn-mapview"><i class="fa-solid fa-map"></i> Map view</button>
                <button id="listview" class="redbtn-mapview ritmrgn trssnps"><i class="fa-solid fa-list "></i> List
                    view</button>
            </div>
        </div>
        <div class="mapviews-cont" id="map-cont">
            <div id="map"></div>
        </div>


        <div class="list-viw-contet d-none" id="list-cont">
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
                                Date
                            </th>
                            <th>Teams</th>

                            <th>LEAGUE</th>
                            <th>Location</th>
                            <th>Umpier</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rowCount = 0;
                        @endphp
                        @if ($umpire_upcomming_games_grouped->count() > 0)
                            @foreach ($umpire_upcomming_games_grouped as $groupByDate => $umpire_upcomming_games)
                                <tr>
                                    <td colspan="6">
                                        <h6 class="this-is-it">
                                            {{ date('l F jS Y', strtotime($groupByDate)) }}
                                        </h6>
                                    </td>
                                </tr>
                                @foreach ($umpire_upcomming_games as $upcoming_game)
                                    @php
                                        $inputDate = $upcoming_game->gamedate_toDisplay;
                                        $carbonDate = Illuminate\Support\Carbon::parse($inputDate);
                                        $gamedate = $carbonDate->format('D m/d/y h:ia');
                                        $umpType = '';
                                        if ($upcoming_game->ump1 == $umpire_data->umpid) {
                                            $umpType = 'Main Umpire';
                                        } elseif ($upcoming_game->ump2 == $umpire_data->umpid) {
                                            $umpType = '2nd Umpire';
                                        } elseif ($upcoming_game->ump3 == $umpire_data->umpid) {
                                            $umpType = '3rd Umpire';
                                        } elseif ($upcoming_game->ump4 == $umpire_data->umpid) {
                                            $umpType = '4th Umpire';
                                        }

                                        $today = Illuminate\Support\Carbon::now();
                                        $game_date = explode(' ', $upcoming_game->gamedate)[0];
                                        $cancelbefore = (int) $upcoming_game->league->leavebefore;
                                        $initialDate = Illuminate\Support\Carbon::parse($game_date);
                                        $modifiedDate = $initialDate->subDays($cancelbefore);
                                        $daysDifference = $today->diffInDays($modifiedDate);
                                        if ($modifiedDate->isSameDay($today) || $modifiedDate->greaterThan($today)) {
                                            $cancel_text =
                                                '<a data-text="Are you sure you want to leave the game?" class="confirmCancel view-btn redbtn" href="' .
                                                url('umpire/cancel-game/' . $upcoming_game->gameid) .
                                                '">Leave</a>';
                                        } else {
                                            $cancel_text = '';
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $gamedate }}</td>
                                        <td class="team">{{ $upcoming_game->hometeam->teamname }} vs
                                            {{ $upcoming_game->awayteam->teamname }}</td>
                                        <td>{{ $upcoming_game->league->leaguename }}</td>
                                        <td><span class="loasys">{{ $upcoming_game->location->ground }}</span></td>
                                        <td class="ump-pose">{{ $umpType }}</td>
                                        <td>{!! $cancel_text !!}</td>
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
            <div id="historical_games" style="display: none">
                <table class="rowas-tabl" id="myTable2">
                    <thead>
                        <tr>
                            <th>
                                Date
                            </th>
                            <th>Teams</th>

                            <th>LEAGUE</th>
                            <th>Location</th>
                            <th>Report</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($umpire_past_games->count() > 0)
                            @foreach ($umpire_past_games as $past_game)
                                @php
                                    $inputDate = $past_game->gamedate_toDisplay;
                                    $carbonDate = Illuminate\Support\Carbon::parse($inputDate);
                                    $gamedate = $carbonDate->format('D m/d/y h:ia');
                                    $report_btn = '';
                                    $reportcol = '';
                                    if ($past_game->report == 1) {
                                        if ($past_game->ump1 == $umpire_data->umpid) {
                                            $reportcol = 'report1';
                                        } elseif ($past_game->ump2 == $umpire_data->umpid) {
                                            $reportcol = 'report2';
                                        } elseif ($past_game->ump3 == $umpire_data->umpid) {
                                            $reportcol = 'report3';
                                        } elseif ($past_game->ump4 == $umpire_data->umpid) {
                                            $reportcol = 'report4';
                                        }
                                        if ($past_game->{$reportcol} !== null) {
                                            if (checkIfReportIsFake($past_game->gameid, $reportcol)) {
                                                $report_btn = '<span class="text-danger">Absent</span>';
                                            } else {
                                                $report_btn =
                                                    '<span class="text-success"><i class="fa-solid fa-check"></i> Submitted</span>';
                                            }
                                        } else {
                                            if (!checkIfReportIsFake($past_game->gameid, $reportcol)) {
                                                $report_btn =
                                                    '
                                                <div>
                                            <a href="javascript:void(0)" class="view-btn primart-yehs " onclick="submitReport(' .
                                                    $past_game->gameid .
                                                    ', \'' .
                                                    $reportcol .
                                                    '\')">Submit Report</a>
                                            </div>
                                            ';
                                            } else {
                                                $report_btn = '<span class="text-danger">Absent</span>';
                                            }
                                        }
                                    } else {
                                        $report_btn = '<span class="text-secondary">NA</span>';
                                    }

                                @endphp
                                <tr>
                                    <td>{{ $gamedate }}</td>
                                    <td class="team" id="teamvs{{ $past_game->gameid }}">
                                        {{ $past_game->hometeam->teamname }} vs
                                        {{ $past_game->awayteam->teamname }}</td>
                                    <td id="leaguename{{ $past_game->gameid }}">{{ $past_game->league->leaguename }}</td>
                                    <td><span class="loasys">{{ $past_game->location->ground }}</span></td>
                                    <td id="reportbtnrow{{ $past_game->gameid }}">{!! $report_btn !!}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                </table>
                @if ($umpire_past_games->count() > 10)
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
                    <h5 class="modalicons-title">Submit Report</h5>
                    <button type="button" class="btn-closes" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-x"></i></button>
                    <a href="" id="reportAbsentBtn" class="btn btn-danger confirmCancel">Report Absent</a>
                </div>
                <div class="modal-body">
                    <form action="{{ url('umpire/submit-report') }}" method="POST">
                        @csrf
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="toggle_email_noti" name="toggle_email_noti">
                            <label class="form-check-label" for="toggle_email_noti">Notify league admin of
                                report</label>
                        </div>
                        <input type="hidden" name="game_id" required>
                        <input type="hidden" name="report_column" required>
                        <div class="toptext-modal" id="subtext"></div>
                        <div id="reportquestions">

                        </div>

                        <div class="text-center submit-bten-modal">
                            <button id="reportSubmitBtn" class="submitbtns" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- modal -->
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
            togglebtn('myTable', 'toggleButton');
            $("#his").on("click", function() {
                togglebtn('myTable2', 'toggleButton2');
                $("#upc").removeClass("active");
                $(this).addClass("active");
                $('#upcoming_games').hide();
                $('#historical_games').show();
            });
            $("#upc").on("click", function() {
                togglebtn('myTable', 'toggleButton');
                $("#his").removeClass("active");
                $(this).addClass("active");
                $('#historical_games').hide();
                $('#upcoming_games').show();
            });
        });
    </script>
    <script>
        function submitReport(gameid, report_column) {
            $.ajax({
                url: "{{ url('umpire/submit-report') }}" + '/' + gameid,
                success: function(res) {
                    $('[name="game_id"]').val(gameid);
                    $('[name="report_column"]').val(report_column);
                    var teamvs = $('#teamvs' + gameid).text();
                    var leaguename = $('#leaguename' + gameid).text();
                    $('#subtext').html(teamvs + ' of ' + leaguename);
                    $('#reportquestions').html(res);
                    let url = "{{ url('umpire/report-absent') }}" + '/' + gameid + '/' + report_column;
                    $('#reportAbsentBtn').attr('href', url);
                    $('#reportModal').modal('show');
                },
                error: function(res) {
                    toastr.error('Something went wrong..!!');
                }
            });
        }
    </script>
    <script>
        $(document).ready(function(e) {
            $('#toggle_email_noti').prop('checked', false);
            $('form').on('submit', (function(e) {
                e.preventDefault();
                $('#reportSubmitBtn').text('Updating...');
                $('#reportSubmitBtn').attr('disabled', true);
                $.ajax({
                    url: $(this).attr('action'),
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: new FormData(this),
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 1) {
                            toastr.success('Success');
                            $('#reportModal').modal('hide');
                            var btnhtml =
                                '<span class="text-success"><i class="fa-solid fa-check"></i> Submitted</span>';
                            $('#reportbtnrow' + res.gameid).html(btnhtml);
                        } else {
                            toastr.error(res.message);
                        }
                        $('#reportSubmitBtn').text('Submit');
                        $('#reportSubmitBtn').attr('disabled', false);
                        $('#toggle_email_noti').prop('checked', false);
                    },
                    error: function(response) {
                        toastr.error('Something went wrong..!!');
                        $('#reportSubmitBtn').text('Submit');
                        $('#reportSubmitBtn').attr('disabled', false);
                        $('#toggle_email_noti').prop('checked', false);
                    }
                });
            }));
        });
    </script>
@endsection
