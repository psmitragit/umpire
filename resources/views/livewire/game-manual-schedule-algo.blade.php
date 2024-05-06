<div class="body-content me-customs">
    <div class="list-viw-contet mt-30px" id="list-conssst2">
        <div class="namphomediv">
            <h1 class="pageTitle pagesmall">SCHEDULED GAMES</h1>
            <div>
                <a class="btn btn-primary" href="{{ url('league/game-manual-schedule') }}">Reset</a>
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
                        <th>Game</th>
                        <th>Location</th>
                        <th>1st UMP</th>
                        <th>2nd UMP</th>
                        <th>3rd UMP</th>
                        <th>4th UMP</th>
                        <th>Player Age</th>
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
                                    foreach ($systemAssignedUmpires as $syPos=>$sysUmp) {
                                        $data->{$syPos} = $sysUmp;
                                    }
                                }
                                $umpires = [$data->ump1, $data->ump2, $data->ump3, $data->ump4];
                            @endphp
                            <tr>
                                <td class="date-leag">{{ $gamedate }}</td>
                                <td class="team-a">{{ $data->hometeam->teamname }} vs {{ $data->awayteam->teamname }}
                                </td>
                                <td class="location-game"> <span class="aspans"> {{ $data->location->ground }}</span>
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
                                <td class="color-prmths hovername" data-pos="2">
                                    @if ($data->ump2 !== null)
                                        <span class="halfname"
                                            wire:click='assignRemoveUmpire({{ $data->gameid }}, "ump2")'
                                            wire:key='assignedUmp-{{ $data->gameid }}-2'>{{ Illuminate\Support\Str::limit($data->umpire2->name, 8, '...') }}</span>
                                        <div class="fullnamediv">
                                            {{ $data->umpire2->name }}
                                        </div>
                                    @else
                                        <a data-gameid="{{ $data->gameid }}" href='javascript:void(0)'
                                            class="blutns-table"
                                            wire:click='assignRemoveUmpire({{ $data->gameid }}, "ump2")'
                                            wire:key='assignedUmp-{{ $data->gameid }}-2'>Empty</a>
                                    @endif
                                </td>
                                <td class="color-prmths hovername" data-pos="3">
                                    @if ($data->ump3 !== null)
                                        <span class="halfname"
                                            wire:click='assignRemoveUmpire({{ $data->gameid }}, "ump3")'
                                            wire:key='assignedUmp-{{ $data->gameid }}-3'>{{ Illuminate\Support\Str::limit($data->umpire3->name, 8, '...') }}</span>
                                        <div class="fullnamediv">
                                            {{ $data->umpire3->name }}
                                        </div>
                                    @else
                                        <a href='javascript:void(0)' class="blutns-table"
                                            wire:click='assignRemoveUmpire({{ $data->gameid }}, "ump3")'
                                            wire:key='assignedUmp-{{ $data->gameid }}-3'>Empty</a>
                                    @endif
                                </td>
                                <td class="color-prmths hovername" data-pos="4">
                                    @if ($data->ump4 !== null)
                                        <span class="halfname"
                                            wire:click='assignRemoveUmpire({{ $data->gameid }}, "ump4")'
                                            wire:key='assignedUmp-{{ $data->gameid }}-4'>{{ Illuminate\Support\Str::limit($data->umpire4->name, 8, '...') }}</span>
                                        <div class="fullnamediv">
                                            {{ $data->umpire4->name }}
                                        </div>
                                    @else
                                        <a href='javascript:void(0)' class="blutns-table"
                                            wire:click='assignRemoveUmpire({{ $data->gameid }}, "ump4")'
                                            wire:key='assignedUmp-{{ $data->gameid }}-4'>Empty</a>
                                    @endif
                                </td>
                                <td>{{ $data->playersage }}</td>
                                <td>{{ $data->umpreqd }}</td>
                                <td>{!! $data->report == 1
                                    ? '<i class="fa-solid fa-check text-success"></i>'
                                    : '<i class="fa-solid fa-x text-danger"></i>' !!}</td>
                                <td class="text-success">${{ $data->ump1pay + $data->ump234pay }} +
                                    ${{ $data->ump1bonus + $data->ump234bonus }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="umpireModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body" id="dateModalbody">
                        @if (!$league_data->umpires->isempty())
                            <div class="mina-users">
                                @foreach ($league_data->umpires as $leagueumpires)
                                    @php
                                        if ($leagueumpires->umpire->profilepic == null) {
                                            $src = asset('storage/umpire') . '/img/jone.jpg';
                                        } else {
                                            $src = asset('storage/images') . '/' . $leagueumpires->umpire->profilepic;
                                        }
                                    @endphp
                                    <div class="input-green-check-wrap nes-clas">

                                        <label class="lebelfor-ta-c" for="{{ $leagueumpires->umpire->umpid }}">
                                            <input id="{{ $leagueumpires->umpire->umpid }}"
                                                class="hour_checkbox custombasc" type="radio" name="umpid" wire:key='newumpid-{{ $leagueumpires->umpire->umpid }}' wire:click='setUmpire({{ $leagueumpires->umpire->umpid }})'
                                                value="{{ $leagueumpires->umpire->umpid }}">
                                            <img src="{{ $src }}" class="league-propic">
                                            <span class="check-span-box">{{ $leagueumpires->umpire->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
    <!-- modal -->
</div>
@include('livewire.includes.event')
