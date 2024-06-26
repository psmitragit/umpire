@extends('league.layouts.main')
@section('main-container')
    @php
        if ($page_data->profilepic == null) {
            $src = asset('storage/umpire') . '/img/jone.jpg';
        } else {
            $src = asset('storage/images') . '/' . $page_data->profilepic;
        }
    @endphp
    <div class="body-content">
        <div class="viewprofiles"><a href="{{ url('league/umpires') }}"><i class="fa-solid fa-chevron-left"></i> Go Back</a>
        </div>

        <div class="row">
            <div class="col-md-5 d-flex">
                <div class="col-md-5">
                    <div class="profilePic-pro"><img src="{{ $src }}" alt="" class="the_images-forthes">
                    </div>
                </div>
                <div class="col-md-7">
                    <h2 class="names-divs">{{ $page_data->name }}</h2>

                    <div class="age-div-profile-ump">
                        <span class="Ags-s2s">Age: {{ get_age($page_data->dob) }} </span> • <span
                            class="dates-profile-date">{{ date('m/d/Y', strtotime($page_data->dob)) }}</span>

                    </div>
                    <div class="zipcodes">
                        <span class="zipcode">Zip code: {{ $page_data->zip }}</span> • <span class="jods">Joined on
                            {{ date('m/d/y', strtotime($league_umpire->created_at)) }}</span>
                    </div>
                    <div class="spanhonenops">
                        <span class="profile-phoneicon"><i class="fa-solid fa-phone"></i></span>
                        <span class="phone-number-text">{{ $page_data->phone }}</span>
                    </div>




                    <div class="spanhonenops">
                        <span class="profile-phoneicon"><i class="fa-solid fa-envelope"></i></span>
                        <span class="phone-number-text mail"><a
                                href="mailto:{{ $page_data->user->email }}">{{ $page_data->user->email }}</a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="expbio">Experience Bio:</div>
                <div class="text-lores">{{ $page_data->bio }}</div>
            </div>
            <div class="col-md-2">
                <div class="blockbrn">
                    <a href="{{ url('league/remove-umpire-from-league/' . $page_data->umpid) }}"
                        class="submit redbtn w-100 text-center addbtnsa confirmCancel"
                        data-text="Are you sure you want to remove this umpire?">Remove</a>

                </div>
                <div class="approves">
                    @php
                        if ($blocked) {
                            $text = 'Unblock';
                        } else {
                            $text = 'Block';
                        }
                    @endphp
                    <a href="{{ url('league/block-unblock-umpire/' . $page_data->umpid) }}"
                        class="submit redbtn w-100 text-center addbtnsa confirmCancel"
                        data-text="Are you sure you want to {{ $text }} this umpire?">{{ $text }}</a>

                </div>
                <div class="approves"><a href="javascript:void(0)" class="application greenbtn w-100 text-center bnosns-brn"
                        data-bs-toggle="modal" data-bs-target="#sendnoti">Add Bonus</a></div>
            </div>
        </div>


        <div class="tablesAddsias">
            <table class="dtars-tables-profile">
                <thead>
                    <tr>
                        <th>total games in league</th>
                        <th>total games</th>
                        <th>Currently assigned</th>
                        <th>Current score</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ get_umpire_games_count_in_a_league($page_data->umpid, $league_data->leagueid) }}</td>
                        <td>{{ get_umpire_games_count_in_a_league($page_data->umpid, null) }}</td>
                        <td class="text-success">
                            {{ get_umpire_games_count_in_a_league($page_data->umpid, $league_data->leagueid, true) }}</td>
                        <td class="text-info">{{ $league_umpire->points }} <button class="s-edits" type="button"
                                data-bs-toggle="modal" data-bs-target="#editpoint"><i class="fa-solid fa-pen"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="mt-50px mb-50px">
            <div class="row">
                <div class="col-md-6">
                    <div class="minas-div-flexs new-class">
                        <div class="headresd-texts">
                            <span class="kabes">Add Blacklist</span>
                        </div>
                        <div class="d-flex flex-wrap">
                            <div
                                class="by-temas-texts {{ checkToggleStatus($league_data->leagueid, 'divisions') ? 'disabled' : '' }}">
                                <span class="inpoysascolor nessc" id="colors1"> </span>
                                <input type="radio" name="as" class="d-none" id="radio1s">
                                <span class="acctives" id="color-text1">By team</span>
                            </div>
                            <div class="bylocatons-tetx">
                                <span class="inpoysascolor nessc" id="colors2"> </span>
                                <input type="radio" name="as" class="d-none" id="radio2s">
                                <span class="acctives" id="color-text2">By Location</span>

                            </div>
                            <div
                                class="by-temas-texts {{ checkToggleStatus($league_data->leagueid, 'divisions') ? 'disabled' : '' }}">
                                <span class="inpoysascolor nessc" id="colors3"> </span>
                                <input type="radio" name="as" class="d-none" id="radio3s">
                                <span class="acctives" id="color-text3">By division</span>
                            </div>
                        </div>
                    </div>
                    <form class="select-teams teamselect"
                        action="{{ url('league/block-unblock-team/' . $page_data->umpid) }}" method="post">
                        @csrf
                        <span class="select_team-text">Select teams:</span>
                        <select name="team_id" id="teamSelect" class="teamsf" required>
                            @if ($teams->count() > 0)
                                @foreach ($teams as $team)
                                    <option value="{{ $team->teamid }}">{{ $team->teamname }}</option>
                                @endforeach
                            @endif
                        </select>
                        <button class="addnew redbtn submit ters" type="submit">+ Add</button>
                    </form>

                    <form class="select-teams groundselect"
                        action="{{ url('league/block-unblock-ground/' . $page_data->umpid) }}" style="display:none;"
                        method="POST">
                        @csrf
                        <span class="select_team-text">Select grounds:</span>
                        <select name="location_id" id="groundSelect" class="teamsf" required>
                            @if ($locations->count() > 0)
                                @foreach ($locations as $location)
                                    <option value="{{ $location->locid }}">{{ $location->ground }}</option>
                                @endforeach
                            @endif
                        </select>
                        <button class="addnew redbtn submit nsaecz" type="submit">+ Add</button>
                    </form>
                    <form class="select-teams divisionselect"
                        action="{{ url('league/block-unblock-division/' . $page_data->umpid) }}" method="post">
                        @csrf
                        <span class="select_team-text">Select division:</span>
                        <select name="divid" id="divisionselect" class="teamsf" required>
                            @if ($league_data->divisions->count() > 0)
                                @foreach ($league_data->divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <button class="addnew redbtn submit ters" type="submit">+ Add</button>
                    </form>

                </div>
                <div class="col-md-6">
                    <form action="{{ url('league/save-leagueumpire/' . $page_data->umpid) }}" method="POST">
                        @csrf
                        <div class="umprinputammount">

                            <label for="" class="ascrdvt">umpire’s Payout $</label>
                            <input type="text" class="input-textasic" name="payout"
                                value="{{ $league_umpire->payout }}">

                        </div>
                        <div class="notest">
                            <textarea placeholder="Notes about umpire" name="notes" id="" class="netsb">{{ $league_umpire->notes }}</textarea>
                        </div>
                        <div class="dibcs">
                            <button class="addnew redbtn submit" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="overfow-tables">
            <h2 class="blocks-liost-texts">Blocklisted</h2>
            <div class="mionas-tabsle-csjas">
                @php
                    $blockedteamIDs = [];
                    $blockedgroundIDs = [];
                    $blockeddivisionIDs = [];
                @endphp
                @if (!checkToggleStatus($league_data->leagueid, 'teams'))
                    <table class="oevs-fles  {{ checkToggleStatus($league_data->leagueid, 'teams') ? 'disabled' : '' }}">
                        <thead>
                            <tr>
                                <th>Team</th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            @if ($blocked_teams->count() > 0)
                                @foreach ($blocked_teams as $blocked_team)
                                    @php
                                        $blockedteamIDs[] = $blocked_team->teamid;
                                    @endphp
                                    <tr>
                                        <td class="name-blu widthe-fsac">{{ $blocked_team->team->teamname }}</td>
                                        <td class="widvb-2s minase">
                                            <form action="{{ url('league/block-unblock-team/' . $page_data->umpid) }}"
                                                method="post">
                                                @csrf
                                                <input type="hidden" name="team_id"
                                                    value="{{ $blocked_team->teamid }}">
                                                <button class="deletebrns" type="submit"><i
                                                        class="fa-regular fa-trash-can"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                @endif
                <table class="oevs-fles">
                    <thead>
                        <tr>
                            <th>Ground</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($blocked_grounds->count() > 0)
                            @foreach ($blocked_grounds as $blocked_ground)
                                @php
                                    $blockedgroundIDs[] = $blocked_ground->locid;
                                @endphp
                                <tr>
                                    <td class="name-blu widthe-fsac">{{ $blocked_ground->ground->ground }}</td>
                                    <td class="widvb-2s">
                                        <form action="{{ url('league/block-unblock-ground/' . $page_data->umpid) }}"
                                            method="post">
                                            @csrf
                                            <input type="hidden" name="location_id"
                                                value="{{ $blocked_ground->locid }}">
                                            @if ($blocked_ground->leagueid !== 0)
                                                <button class="deletebrns" type="submit"><i
                                                        class="fa-regular fa-trash-can"></i></button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                @if (!checkToggleStatus($league_data->leagueid, 'divisions'))
                    <table
                        class="oevs-fles {{ checkToggleStatus($league_data->leagueid, 'divisions') ? 'disabled' : '' }}">
                        <thead>
                            <tr>
                                <th>Division</th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            @if ($blocked_divisions->count() > 0)
                                @foreach ($blocked_divisions as $blocked_division)
                                    @php
                                        $blockeddivisionIDs[] = $blocked_division->divid;
                                    @endphp
                                    <tr>
                                        <td class="name-blu widthe-fsac">{{ $blocked_division->division->name }}</td>
                                        <td class="widvb-2s minase">
                                            <form action="{{ url('league/block-unblock-division/' . $page_data->umpid) }}"
                                                method="post">
                                                @csrf
                                                <input type="hidden" name="divid"
                                                    value="{{ $blocked_division->divid }}">
                                                <button class="deletebrns" type="submit"><i
                                                        class="fa-regular fa-trash-can"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                @endif
                @php
                    $blockedteamIDsjson = json_encode($blockedteamIDs);
                    $blockedgroundIDsjson = json_encode($blockedgroundIDs);
                    $blockeddivisionIDsjson = json_encode($blockeddivisionIDs);
                @endphp
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="sendnoti" tabindex="-1" aria-labelledby="sendnotiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-loout-container">
                    <form action="{{ url('league/pay-bonus/' . $page_data->umpid) }}" method="POST">
                        @csrf
                        <h3 class="textfot-logout">Paid Bonus to {{ $page_data->name }}</h3>
                        <div class="displays">
                            <div class="dtaes">
                                <div class="labens">
                                    DATE
                                </div>
                                <div class="inputd-forthes">
                                    <input type="date" class="datescs" name="paydate" required>
                                </div>
                            </div>

                            <div class="dtaes">
                                <div class="labens">
                                    Bonus $
                                </div>
                                <div class="inputd-forthes">
                                    <input min="1" type="number" class="datescs bonus" name="payamt" required>
                                </div>
                            </div>
                        </div>

                        <div class="buttons-flex hyscs sc">
                            <div class="button1div"><button class="redbtn submit" type="submit"
                                    id="bonusUpdate">Update</button></div>
                            <div class="buttondiv-trans"><button type="button" class="cnclbtn buycnm"
                                    data-bs-dismiss="modal">Cancel</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editpoint" tabindex="-1" aria-labelledby="sendnotiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-loout-container">
                    <form action="{{ url('league/save-point/' . $page_data->umpid) }}" method="POST">
                        @csrf
                        <h3 class="textfot-logout">Score of {{ $page_data->name }}</h3>
                        <div class="displays">
                            <div class="dtaes">
                                <div class="labens">
                                    Score
                                </div>
                                <div class="inputd-forthes">
                                    <input min="0" type="number" class="datescs bonus" name="points" required
                                        value="{{ $league_umpire->points }}">
                                </div>
                            </div>
                        </div>

                        <div class="buttons-flex hyscs sc">
                            <div class="button1div"><button class="redbtn submit" type="submit">Save</button></div>
                            <div class="buttondiv-trans"><button type="button" class="cnclbtn buycnm"
                                    data-bs-dismiss="modal">Cancel</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>
    <!-- modal -->
    <script>
        $('#colors1').click(function(e) {
            $('#radio1s').click();
            $('#color-text1').addClass("active-class");
            $('#color-text2').removeClass("active-class");
            $('#color-text3').removeClass("active-class");


            $('#colors1').addClass("active-class");
            $('#colors2').removeClass("active-class");
            $('#colors3').removeClass("active-class");

            $('.teamselect').show();
            $('.groundselect').hide();
            $('.divisionselect').hide();
        });


        $('#colors2').click(function(e) {
            $('#radio2s').click();
            $('#color-text1').removeClass("active-class");
            $('#color-text2').addClass("active-class");
            $('#color-text3').removeClass("active-class");


            $('#colors1').removeClass("active-class");
            $('#colors2').addClass("active-class");
            $('#colors3').removeClass("active-class");

            $('.teamselect').hide();
            $('.groundselect').show();
            $('.divisionselect').hide();
        });

        $('#colors3').click(function(e) {
            $('#radio3s').click();
            $('#color-text1').removeClass("active-class");
            $('#color-text2').removeClass("active-class");
            $('#color-text3').addClass("active-class");


            $('#colors1').removeClass("active-class");
            $('#colors2').removeClass("active-class");
            $('#colors3').addClass("active-class");

            $('.teamselect').hide();
            $('.groundselect').hide();
            $('.divisionselect').show();
        });

        $(document).ready(function() {
            $('#colors2').click();
        });
    </script>
    <script>
        $(document).ready(function(e) {
            $('form').on('submit', (function(e) {
                e.preventDefault();
                $('#bonusUpdate').attr('disabled', true);
                $('#bonusUpdate').text('Updating...');
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
                            toastr.error('Something went wrong..!!');
                        }

                    },
                    error: function(response) {
                        toastr.error('Something went wrong..!!');
                    }
                });
            }));
        });
    </script>
    <script>
        let blockedTeams = {{ $blockedteamIDsjson }};
        let blockedGrounds = {{ $blockedgroundIDsjson }};
        let blockeddivisions = {{ $blockeddivisionIDsjson }};
        removeOptions("teamSelect", blockedTeams);
        removeOptions("groundSelect", blockedGrounds);
        removeOptions("divisionselect", blockeddivisions);

        function removeOptions(selectId, blockedValues) {
            const select = document.getElementById(selectId);
            for (let i = select.options.length - 1; i >= 0; i--) {
                if (blockedValues.includes(parseInt(select.options[i].value))) {
                    select.remove(i);
                }
            }
        }
    </script>
@endsection
