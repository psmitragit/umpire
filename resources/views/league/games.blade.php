@extends('league.layouts.main')
@section('main-container')
    <style>
        .halfname {
            cursor: pointer;
        }
    </style>
    <div class="body-content me-customs">
        <div class="list-viw-contet mt-30px" id="list-conssst2">
            <div class="namphomediv">
                <h1 class="pageTitle pagesmall">SCHEDULED GAMES</h1>
                <div class="mapbtns-div moasbflexs">
                    <div class="Admins">
                        <div class="inputs-srch">
                            <input placeholder="Search" name="srch" class="input-srch-field srch" type="text"
                                id="searchInput" oninput="filterTable('myTable2');">
                            <button type="button" class="srch-mag-btn srch"> <img
                                    src="{{ asset('storage/league') }}/img/srch-icon.png" alt=""> </button>

                        </div>
                    </div>
                    <div class="bluebtns">
                        <a href="{{ url('league/add-game') }}" class="blutns">+ Add Game</a>

                    </div>
                    <div class="green-btns">
                        <form method="POST" id="uploadForm" enctype="multipart/form-data"
                            action="{{ url('league/import-format') }}">
                            @csrf
                            <input name="file" type="file" id="fileInput" accept=".csv" style="display: none;">
                            <button class="upcomne active" id="hist" type="button">
                                <i class="fa-regular fa-file-excel"></i> Upload
                            </button>
                        </form>
                        <script>
                            document.getElementById("hist").addEventListener("click", function() {
                                document.getElementById("fileInput").click();
                            });
                            document.getElementById("fileInput").addEventListener("change", function() {
                                const fileInput = document.getElementById("fileInput").files[0];
                                if (fileInput) {
                                    $('#uploadForm').submit();
                                }
                            });
                        </script>
                    </div>
                    <div class="text-esldwnlos">
                        <a href="{{ url('league/export-format') }}"><i class="fa-solid fa-arrow-down"></i> Download excel
                            format</a>
                    </div>
                </div>
            </div>





            <div id="tab2s" class="">
                <table class="rowas-tabl table-gmaessc tabgamestable" id="myTable2">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            @if (checkToggleStatus($league_data->leagueid, 'teams'))
                                {{-- leave blank --}}
                            @else
                                <th>Game</th>
                            @endif
                            <th>Location</th>
                            <th>1st UMP</th>
                            <th>2nd UMP</th>
                            <th>3rd UMP</th>
                            <th>4th UMP</th>
                            @if (checkToggleStatus($league_data->leagueid, 'age'))
                                {{-- leave blank --}}
                            @else
                                <th>Player Age</th>
                            @endif
                            <th>Umpire</th>
                            <th>Report</th>
                            <th>Payout</th>
                            <th></th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody id="search_output">
                        @if ($page_data->count() > 0)
                            @foreach ($page_data as $groupByDate => $league_upcomming_games)
                                <tr>
                                    <td colspan="13">
                                        <h6 class="this-is-it">
                                            {{ date('l F jS Y', strtotime($groupByDate)) }}
                                        </h6>
                                    </td>
                                </tr>
                                @foreach ($league_upcomming_games as $data)
                                    @php
                                        $inputDate = $data->gamedate_toDisplay;
                                        $carbonDate = Illuminate\Support\Carbon::parse($inputDate);
                                        $gamedate = $carbonDate->format('D m/d/y h:ia');
                                        $umpires = [$data->ump1, $data->ump2, $data->ump3, $data->ump4];
                                    @endphp
                                    <tr data-umpreqd="{{ $data->umpreqd }}"
                                        data-umpires="{{ htmlspecialchars(json_encode($umpires)) }}"
                                        data-gameid="{{ $data->gameid }}">
                                        <td class="date-leag">{{ $gamedate }}</td>
                                        @if (checkToggleStatus($league_data->leagueid, 'teams'))
                                            {{-- leave blank --}}
                                        @else
                                            <td class="team-a">{{ $data->hometeam->teamname }} vs
                                                {{ $data->awayteam->teamname }}
                                            </td>
                                        @endif
                                        <td class="location-game"> <span class="aspans">
                                                {{ $data->location->ground }}</span>
                                        </td>
                                        <td class="color-prmths hovername" data-pos="1">
                                            @if ($data->ump1 !== null)
                                                <span class="halfname"
                                                    onclick='removeUmpire(this)'>{{ Illuminate\Support\Str::limit($data->umpire1->name, 8, '...') }}</span>
                                                <div class="fullnamediv">
                                                    {{ $data->umpire1->name }}
                                                </div>
                                            @else
                                                <a href='javascript:void(0)' class="blutns-table"
                                                    onclick="assignUmpire(this)">Empty</a>
                                            @endif
                                        </td>
                                        <td class="color-prmths hovername" data-pos="2">
                                            @if ($data->ump2 !== null)
                                                <span class="halfname"
                                                    onclick='removeUmpire(this)'>{{ Illuminate\Support\Str::limit($data->umpire2->name, 8, '...') }}</span>
                                                <div class="fullnamediv">
                                                    {{ $data->umpire2->name }}
                                                </div>
                                            @else
                                                <a data-gameid="{{ $data->gameid }}" href='javascript:void(0)'
                                                    class="blutns-table" onclick="assignUmpire(this)">Empty</a>
                                            @endif
                                        </td>
                                        <td class="color-prmths hovername" data-pos="3">
                                            @if ($data->ump3 !== null)
                                                <span class="halfname"
                                                    onclick='removeUmpire(this)'>{{ Illuminate\Support\Str::limit($data->umpire3->name, 8, '...') }}</span>
                                                <div class="fullnamediv">
                                                    {{ $data->umpire3->name }}
                                                </div>
                                            @else
                                                <a href='javascript:void(0)' class="blutns-table"
                                                    onclick="assignUmpire(this)">Empty</a>
                                            @endif
                                        </td>
                                        <td class="color-prmths hovername" data-pos="4">
                                            @if ($data->ump4 !== null)
                                                <span class="halfname"
                                                    onclick='removeUmpire(this)'>{{ Illuminate\Support\Str::limit($data->umpire4->name, 8, '...') }}</span>
                                                <div class="fullnamediv">
                                                    {{ $data->umpire4->name }}
                                                </div>
                                            @else
                                                <a href='javascript:void(0)' class="blutns-table"
                                                    onclick="assignUmpire(this)">Empty</a>
                                            @endif
                                        </td>
                                        @if (checkToggleStatus($league_data->leagueid, 'age'))
                                            {{-- leave blank --}}
                                        @else
                                            <td>{{ $data->playersage }}</td>
                                        @endif
                                        <td>{{ $data->umpreqd }}</td>
                                        <td>{!! $data->report == 1
                                            ? '<i class="fa-solid fa-check text-success"></i>'
                                            : '<i class="fa-solid fa-x text-danger"></i>' !!}</td>
                                        <td class="text-success">
                                            ${{ $data->ump1pay + $data->ump234pay + $data->ump1bonus + $data->ump234bonus }}
                                        </td>
                                        <td class="wisb">
                                            @if ($data->ump1 == null && $data->ump2 == null && $data->ump3 == null && $data->ump4 == null)
                                                <a href="{{ url('league/edit-game/' . $data->gameid) }}"
                                                    class="delete-notifixctasc blue-bgs ac  sc"><i
                                                        class="fa-solid fa-pencil"></i></a>
                                            @endif
                                        </td>
                                        <td class="wisb"><a data-text = 'Are you sure you want to cancel this game?'
                                                href="{{ url('league/delete-game/' . $data->gameid) }}"
                                                class="delete-notifixctasc addbgs asd sc ms-2 confirmCancel"><i
                                                    class="fa-regular fa-trash-can"></i></a></td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="umpireModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body" id="dateModalbody">
                    <form action="{{ url('league/manual-assign') }}" method="POST" id="manualAssign">
                        @csrf
                        <input type="hidden" name="pos" required>
                        <input type="hidden" name="gameid" required>
                        @if (!$league_data->umpires->isempty())
                            <div class="mina-users">
                                @foreach ($league_data->umpires as $leagueumpires)
                                    @if ($leagueumpires->umpire->status == 1)
                                        @php
                                            if ($leagueumpires->umpire->profilepic == null) {
                                                $src = asset('storage/umpire') . '/img/jone.jpg';
                                            } else {
                                                $src =
                                                    asset('storage/images') . '/' . $leagueumpires->umpire->profilepic;
                                            }
                                        @endphp
                                        <div class="input-green-check-wrap nes-clas">

                                            <label class="lebelfor-ta-c" for="{{ $leagueumpires->umpire->umpid }}">
                                                <input id="{{ $leagueumpires->umpire->umpid }}"
                                                    class="hour_checkbox custombasc" type="radio" name="umpid"
                                                    value="{{ $leagueumpires->umpire->umpid }}">
                                                <img src="{{ $src }}" class="league-propic">
                                                <span class="check-span-box">{{ $leagueumpires->umpire->name }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        <div class="buttons-flex hyscs">
                            @if (!$league_data->umpires->isempty())
                                <div class="button1div">
                                    <button class="redbtn submit" type="submit" id="savebtn">Save</button>
                                </div>
                            @endif
                            <div class="buttondiv-trans">
                                <button class="cnclbtn buycnm" type="button" data-bs-dismiss="modal"
                                    id="">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- modal -->
    <script>
        $(document).ready(function() {
            filterTable('myTable2');
        });

        function assignUmpire(e) {
            var umpreqdTr = $(e).closest('tr');
            var umpreqd = parseInt(umpreqdTr.data('umpreqd'));
            var pos = parseInt($(e).closest('td').data('pos'));
            if (pos <= umpreqd) {
                var umpires = $(umpreqdTr).data('umpires');
                var gameid = $(umpreqdTr).data('gameid');
                for (var i = 0; i < umpires.length; i++) {
                    umpires[i] = parseInt(umpires[i]);
                }
                umpires = umpires.filter(function(element) {
                    return !isNaN(element);
                });

                $('input[name="umpid"]').each(function() {
                    var umpid = parseInt($(this).val());
                    if ($.inArray(umpid, umpires) !== -1) {
                        $(this).closest('div').hide();
                    } else {
                        $(this).closest('div').show();
                    }
                });
                $('[name="pos"]').val('ump' + pos);
                $('[name="gameid"]').val(gameid);
                $('#umpireModal').modal('show');
            } else {
                toastr.error('Can\'t assign umpire to this position..!!');
            }
        }
        $('#manualAssign').on('submit', (function(e) {
            e.preventDefault();
            $('#savebtn').text('Saving...');
            $('#savebtn').attr('disabled', true);
            $.ajax({
                url: $(this).attr('action'),
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                data: new FormData(this),
                type: 'post',
                dataType: "json",
                success: function(res) {
                    if (res.status == 2) {
                        var text =
                            'Hey there! It looks like umpire is already committed to another game on this date. Are you sure you want to assign to this game as well?';

                        var href = '{{ url('league/same-game-assign') }}' + '/' + res
                            .gameid +
                            '/' + res.pos +
                            '/' + res.umpid;

                        $('#cctext').html(text);
                        $('#confirmLink').attr("href", href);
                        $('#confirmCancelModel').modal('show');
                        $('#savebtn').text('Save');
                        $('#savebtn').attr('disabled', false);
                    } else {
                        window.location.reload();
                    }
                },
                error: function(res) {
                    window.location.reload();
                }
            });
        }));

        function removeUmpire(e) {
            var gameid = $(e).closest('tr').data('gameid');
            var pos = parseInt($(e).closest('td').data('pos'));
            var umpname = $(e).next('.fullnamediv').text();
            var text = 'Are you sure you want to remove <b>' + umpname + '</b> from this game?';
            var href = '{{ url('league/remove-umpire') }}' + '/' + gameid + '/ump' + pos;
            $('#cctext').html(text);
            $('#confirmLink').attr("href", href);
            $('#confirmCancelModel').modal('show');
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.lebelfor-ta-c').click(function() {
                $('.input-green-check-wrap').removeClass('active');
                $(this).closest('.input-green-check-wrap').addClass('active');
            });
        });
    </script>
@endsection
