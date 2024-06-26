@extends('umpire.layouts.main')
@section('main-container')
    <div class="body-content">
        <style>
            .checkbody {
                background: #f9f9fa;
            }
        </style>
        <h1 class="pageTitle margintops-buttom">Leagues</h1>



        <div class="list-viw-contet m-top" id="list-cont">
            <div class="alert alert-danger" style="display: none;" id="errors"></div>
            <div class="my-games">
                <h2 class="game-title">My League</h2>
            </div>
            <table class="rowas-tabl" id="myTable">
                <thead>
                    <tr>
                        <th>
                            league
                        </th>
                        <th>
                            Avg weekly game count
                        </th>
                        <th>Avg pay</th>

                        <th>My games</th>
                        <th>view games</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    @if ($umpire_data->leagues->count() > 0)
                        @foreach ($umpire_data->leagues as $joined_leagues)
                            <tr>
                                <td>{{ $joined_leagues->league->leaguename }}</td>
                                <td class="time-table">{{ count_avg_league_games_per_week($joined_leagues->leagueid) }}</td>
                                <td class="text-success">
                                    ${{ count_avg_league_games_pay_per_week($joined_leagues->leagueid) }}</td>
                                <td>{{ get_umpire_games_count_in_a_league($umpire_data->umpid, $joined_leagues->leagueid) }}
                                </td>
                                <td><a href="{{ url('umpire/league-games/' . $joined_leagues->leagueid) }}"
                                        class="view-btn">View</a></td>
                                <td><a onclick="demoWarning();" href="javascript:;"
                                        class="view-btn">Leave</a></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" align="center">You haven't joined any leagues yet..</td>
                        </tr>
                    @endif
                </tbody>

            </table>
            @if ($umpire_data->leagues->count() > 3)
                <button id="toggleButton"><i class="fa-solid fa-angle-down"></i></button>
            @endif
        </div>


        <div class="list-viw-contet m-top" id="list-cont">
            <div class="my-games">
                <div class="divflex-forsrch">
                    <h2 class="game-title">Other Leagues</h2>
                    <div>
                        <div class="inputs-srch">
                            <input placeholder="Search by leagues" class="input-srch-field" type="text" id="searchInput">
                            <button class="srch-mag-btn" type="button"> <img
                                    src="{{ asset('storage/umpire') }}/img/srch-icon.png" alt="">
                            </button>

                        </div>
                    </div>
                </div>
            </div>
            <table class="rowas-tabl" id="myTable2">
                <thead>
                    <tr>
                        <th>
                            league
                        </th>
                        <th>
                            Avg weekly game count
                        </th>
                        {{-- <th>slots</th> --}}
                        <th>Avg pay</th>

                        <th>status</th>

                    </tr>
                </thead>
                <tbody>
                    @if ($leagues->count() > 0)
                        @foreach ($leagues as $league)
                            <tr>
                                <td id="leaguename{{ $league->leagueid }}">{{ $league->leaguename }}</td>
                                <td class="time-table">{{ count_avg_league_games_per_week($league->leagueid) }}</td>
                                {{-- <td>{!! check_game_slot_status($league->leagueid) !!}</td> --}}
                                <td class="text-success">${{ count_avg_league_games_pay_per_week($league->leagueid) }}</td>
                                <td>
                                    @php
                                        $umpire_apply = $league
                                            ->umpire_apply()
                                            ->where('umpid', $umpire_data->umpid)
                                            ->first();
                                    @endphp
                                    @if ($umpire_apply == null)
                                        @if ($league->applications->count() > 0)
                                            <a href="javascript:void(0)" class="view-btn redbtn"
                                                onclick="applyLeague({{ $league->leagueid }})">Apply</a>
                                        @else
                                            <button
                                                data-text="There are currently no questions when applying for this league. Are you sure you want to apply?"
                                                href="{{ url('umpire/apply-league/' . $league->leagueid) }}"
                                                class="view-btn redbtn " onclick="opnemodals(this)">Apply</button>
                                        @endif
                                    @elseif ($umpire_apply->status == 0)
                                        <span class="text-warning">Pending</span>
                                    @elseif ($umpire_apply->status == 2)
                                        <span class="text-danger">Denied</span>
                                    @elseif ($umpire_apply->status == 3)
                                        <span class="text-warning">Asked for interview</span>
                                    @endif


                                </td>

                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            @if ($leagues->count() > 3)
                <button id="toggleButton2"><i class="fa-solid fa-angle-down"></i></button>
            @endif

        </div>


        <!-- Modal -->
        <div class="modal fade" id="applyLeagueModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-hesn">
                        <h5 class="modalicons-title" id="leagueTitle"></h5>
                        <button type="button" class="btn-closes" data-bs-dismiss="modal" aria-label="Close"><i
                                class="fa-solid fa-x"></i></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('umpire/apply-league') }}" method="POST">
                            @csrf
                            <input type="hidden" name="league_id" required>
                            <div class="toptext-modal">
                                Answer all the question below
                            </div>
                            <div id="applicationQuestionsOutput">

                            </div>

                            <div class="text-center submit-bten-modal">
                                <button class="submitbtns" type="button" onclick="demoWarning();">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- modal -->

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table2 = document.getElementById('myTable2');
            const button2 = document.getElementById('toggleButton2');
            let showAllRows2 = false;
            let rowsToShow2 = 3;

            function toggleRows() {
                const rows2 = table2.querySelectorAll('tbody tr');
                for (let i = 0; i < rows2.length; i++) {
                    if (showAllRows2 || i < rowsToShow2) {
                        rows2[i].style.display = '';
                    } else {
                        rows2[i].style.display = 'none';
                    }
                }
                button2.innerHTML = showAllRows2 ?
                    '<i class="fa-solid fa-angle-up"></i>' :
                    '<i class="fa-solid fa-angle-down"></i>';

                showAllRows2 = !showAllRows2;
            }
            toggleRows();
            button2.addEventListener('click', toggleRows);
        });





        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('myTable');
            const button = document.getElementById('toggleButton');
            let showAllRows = false;
            let rowsToShow = 3;

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
        function applyLeague(id) {
            $.ajax({
                url: "{{ url('umpire/apply-league') }}" + '/' + id,
                success: function(res) {
                    $('[name="league_id"]').val(id);
                    var leaguename = $('#leaguename' + id).text();
                    $('#leagueTitle').text(leaguename);
                    $('#applicationQuestionsOutput').html(res);
                    $('#applyLeagueModal').modal('show');
                },
                error: function(res) {
                    toastr.error('No questions are available.');
                }
            });
        }
    </script>
    <script>
        $(document).ready(function(e) {
            $('form').on('submit', (function(e) {
                e.preventDefault();
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
                            window.location.reload();
                        } else {
                            toastr.error(res.msg);
                        }

                    },
                    error: function(response) {

                        if (response.status === 422) {
                            var errorString = '';
                            var errors = response.responseJSON.errors;
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    var errorMessage = errors[key][0];
                                    errorString += '<p>' + errorMessage + '</p>';
                                }
                                $('html, body').animate({
                                    scrollTop: 0
                                });
                                $('#errors').show().delay(5000).hide(0);
                                $('#errors').html(errorString);
                            }
                        }
                    }
                });
            }));
        });
    </script>
@endsection
