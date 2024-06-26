<div class="body-content me-customs">
    @if (!$page_data)

        <div class="maine-divs">
            <div class="main-shedule-div">
                <label for="" class="ensdt">Select Date </label>
                <input min='{{ date('Y-m-d', strtotime($minGameDate)) }}' type="date" wire:model='algoGameDate'
                    class="mayeb">


                <div class="text-center">
                    <button wire:click='searchGames' type="button" class="submitbtns mt-2">Search</button>
                </div>
            </div>
        </div>
    @else
        <div>
            <div class="list-viw-contet mt-30px" id="list-conssst2">
                <div class="namphomediv">
                    <h1 class="pageTitle pagesmall">SCHEDULED GAMES</h1>
                    <div class="d-flex">
                        <a class="blutns" href="{{ url('league/game-manual-schedule') }}">Reset</a>
                        <button wire:click='saveSchedule' type="button" class="upcomne active">Save schedule</button>
                    </div>
                    <div class="mapbtns-div moasbflexs">
                        <div class="Admins">
                            <div class="inputs-srch">
                                <input placeholder="Search" name="srch" class="input-srch-field srch" type="text"
                                    id="searchInput" oninput="filterTable('myTable2');">
                                <button type="button" class="srch-mag-btn srch"> <img
                                        src="{{ asset('storage/league') }}/img/srch-icon.png" alt=""> </button>

                            </div>
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
                                @if (checkToggleStatus($league_data->leagueid, 'umpire_2'))
                                    {{-- leave blank --}}
                                @else
                                    <th>2nd UMP</th>
                                @endif
                                @if (checkToggleStatus($league_data->leagueid, 'umpire_3'))
                                    {{-- leave blank --}}
                                @else
                                    <th>3rd UMP</th>
                                @endif
                                @if (checkToggleStatus($league_data->leagueid, 'umpire_4'))
                                    {{-- leave blank --}}
                                @else
                                    <th>4th UMP</th>
                                @endif
                                @if (checkToggleStatus($league_data->leagueid, 'age'))
                                    {{-- leave blank --}}
                                @else
                                    <th>Player Age</th>
                                @endif
                                <th>Umpire</th>
                                <th>Report</th>
                                <th>Payout</th>
                            </tr>
                        </thead>
                        <tbody id="search_output">
                            @if (!empty($page_data))
                                @foreach ($page_data as $k => $data)
                                    @php
                                        $inputDate = $data->gamedate_toDisplay;
                                        $carbonDate = Illuminate\Support\Carbon::parse($inputDate);
                                        $gamedate = $carbonDate->format('D m/d/y h:ia');
                                        $systemAssignedUmpires = @$assignedGameUmpires[$data->gameid];
                                        if (!empty($systemAssignedUmpires)) {
                                            foreach ($systemAssignedUmpires as $syPos => $sysUmp) {
                                                $data->{$syPos} = $sysUmp;
                                            }
                                        }
                                        $umpires = [$data->ump1, $data->ump2, $data->ump3, $data->ump4];
                                    @endphp
                                    <tr>
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
                                                    wire:click='assignRemoveUmpire({{ $data->gameid }}, "ump1")'
                                                    wire:key='assignedUmp-{{ $data->gameid }}-1'>{{ Illuminate\Support\Str::limit($data->umpire1->name, 8, '...') }}</span>
                                                <div class="fullnamediv">
                                                    {{ $data->umpire1->name }}
                                                </div>
                                            @else
                                                <a href='javascript:void(0)' class="blutns-table"
                                                    wire:click='assignRemoveUmpire({{ $data->gameid }}, "ump1")'
                                                    wire:key='assignedUmp-{{ $data->gameid }}-1'>Empty</a>
                                            @endif
                                        </td>
                                        @if (checkToggleStatus($league_data->leagueid, 'umpire_2'))
                                            {{-- leave blank --}}
                                        @else
                                            <td class="color-prmths hovername" data-pos="2">
                                                @if ($data->ump2 !== null)
                                                    <span class="halfname"
                                                        wire:click='assignRemoveUmpire({{ $data->gameid }}, "ump2")'
                                                        wire:key='assignedUmp-{{ $data->gameid }}-2'>{{ Illuminate\Support\Str::limit($data->umpire2->name, 8, '...') }}</span>
                                                    <div class="fullnamediv">
                                                        {{ $data->umpire2->name }}
                                                    </div>
                                                @else
                                                    @if ($data->umpreqd >= 2)
                                                        <a data-gameid="{{ $data->gameid }}" href='javascript:void(0)'
                                                            class="blutns-table"
                                                            wire:click='assignRemoveUmpire({{ $data->gameid }}, "ump2")'
                                                            wire:key='assignedUmp-{{ $data->gameid }}-2'>Empty</a>
                                                    @else
                                                        _ _
                                                    @endif
                                                @endif
                                            </td>
                                        @endif
                                        @if (checkToggleStatus($league_data->leagueid, 'umpire_3'))
                                            {{-- leave blank --}}
                                        @else
                                            <td class="color-prmths hovername" data-pos="3">
                                                @if ($data->ump3 !== null)
                                                    <span class="halfname"
                                                        wire:click='assignRemoveUmpire({{ $data->gameid }}, "ump3")'
                                                        wire:key='assignedUmp-{{ $data->gameid }}-3'>{{ Illuminate\Support\Str::limit($data->umpire3->name, 8, '...') }}</span>
                                                    <div class="fullnamediv">
                                                        {{ $data->umpire3->name }}
                                                    </div>
                                                @else
                                                    @if ($data->umpreqd >= 3)
                                                        <a href='javascript:void(0)' class="blutns-table"
                                                            wire:click='assignRemoveUmpire({{ $data->gameid }}, "ump3")'
                                                            wire:key='assignedUmp-{{ $data->gameid }}-3'>Empty</a>
                                                    @else
                                                        _ _
                                                    @endif
                                                @endif
                                            </td>
                                        @endif
                                        @if (checkToggleStatus($league_data->leagueid, 'umpire_4'))
                                            {{-- leave blank --}}
                                        @else
                                            <td class="color-prmths hovername" data-pos="4">
                                                @if ($data->ump4 !== null)
                                                    <span class="halfname"
                                                        wire:click='assignRemoveUmpire({{ $data->gameid }}, "ump4")'
                                                        wire:key='assignedUmp-{{ $data->gameid }}-4'>{{ Illuminate\Support\Str::limit($data->umpire4->name, 8, '...') }}</span>
                                                    <div class="fullnamediv">
                                                        {{ $data->umpire4->name }}
                                                    </div>
                                                @else
                                                    @if ($data->umpreqd >= 4)
                                                        <a href='javascript:void(0)' class="blutns-table"
                                                            wire:click='assignRemoveUmpire({{ $data->gameid }}, "ump4")'
                                                            wire:key='assignedUmp-{{ $data->gameid }}-4'>Empty</a>
                                                    @else
                                                        _ _
                                                    @endif
                                                @endif
                                            </td>
                                        @endif
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
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="umpireModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body" id="dateModalbody">
                            @if (!$league_data->umpires->isempty())
                                <div class="mina-users">
                                    @foreach ($league_data->umpires as $leagueumpires)
                                        @if ($leagueumpires->umpire->status == 1)
                                            @php
                                                if ($leagueumpires->umpire->profilepic == null) {
                                                    $src = asset('storage/umpire') . '/img/jone.jpg';
                                                } else {
                                                    $src =
                                                        asset('storage/images') .
                                                        '/' .
                                                        $leagueumpires->umpire->profilepic;
                                                }
                                            @endphp
                                            <div class="input-green-check-wrap nes-clas">

                                                <label class="lebelfor-ta-c" for="{{ $leagueumpires->umpire->umpid }}">
                                                    <input id="{{ $leagueumpires->umpire->umpid }}"
                                                        class="hour_checkbox custombasc" type="radio"
                                                        name="umpid"
                                                        wire:key='newumpid-{{ $leagueumpires->umpire->umpid }}'
                                                        wire:click='setUmpire({{ $leagueumpires->umpire->umpid }})'
                                                        value="{{ $leagueumpires->umpire->umpid }}">
                                                    <img src="{{ $src }}" class="league-propic">
                                                    <span
                                                        class="check-span-box">{{ $leagueumpires->umpire->name }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- modal -->
        </div>
        <script>
            // Assuming you have included the necessary JavaScript library for the datepicker
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize the datepicker
                $('#algoGameDate').datepicker({
                    // Add any options you need for the datepicker
                });
            });
        </script>
    @endif
    @include('livewire.includes.loader')
</div>
@include('livewire.includes.event')
