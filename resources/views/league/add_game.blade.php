@extends('league.layouts.main')
@section('main-container')
    <div class="body-content ">
        <div class="alert alert-danger" style="display: none;" id="errors"></div>
        <form action="{{ !empty($page_data) ? url('league/edit-game/' . $page_data->gameid) : url('league/add-game') }}"
            method="POST">
            @csrf
            <div class="namphomediv">
                <h1 class="pageTitle">Add New Game</h1>
                <div class="mapbtns-div">
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-md-3 margin-bottom-custom">
                    <div class="textforthe-add">Select Date</div>
                </div>
                <div class="col-md-3 margin-bottom-custom">
                    <div class="img-anddatefield sewel">
                        <input value="{{ !empty($page_data) ? date('m/d/Y', strtotime($page_data->gamedate)) : '' }}"
                            type="text" name="gamedate" id="datepicker" class="dates-peksr" placeholder="From" required>
                        <span class="date-icons" id="date-icons1"><img
                                src="{{ asset('storage/league') }}/img/calendericon.png" alt=""
                                class="caledericons-img"></span>
                    </div>
                </div>

                {{-- prev select time section --}}
                <div style="display: none">
                    {!! generateHourSelectBox('gametime') !!}
                </div>
                {{-- prev select time section

                {{-- new select time section --}}
                <div class="col-md-3 margin-bottom-custom">
                    <div class="textforthe-add">Game Time</div>
                </div>
                <div class="col-md-3 margin-bottom-custom">

                    <div class="selects">
                        @php
                            if (!empty($page_data)) {
                                $value = date('h:i', strtotime($page_data->gamedate_toDisplay));
                                $value_select = date('A', strtotime($page_data->gamedate_toDisplay));
                            } else {
                                $value = '';
                            }
                        @endphp
                        <div class="d-flex">
                            <input value="{{ $value }}" type="text" class="selector-names" name="gameHour"
                                required>
                            <select name="ampm" class="selector-names" id="" required>
                                <option {{ !empty($page_data) && $value_select == 'PM' ? 'selected' : '' }} value="PM">
                                    PM
                                </option>
                                <option {{ !empty($page_data) && $value_select == 'AM' ? 'selected' : '' }} value="AM">
                                    AM
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- new select time section --}}

                <div
                    class="col-md-3 margin-bottom-custom {{ checkToggleStatus($league_data->leagueid, 'teams') ? 'd-none' : '' }}">
                    <div class="textforthe-add">Select team One</div>
                </div>
                <div
                    class="col-md-3 margin-bottom-custom {{ checkToggleStatus($league_data->leagueid, 'teams') ? 'd-none' : '' }}">
                    <select name="hometeam" class="selector-names" id="" onchange="updateAwayTeam()">
                        <option value="" selected>Select</option>
                        @if (!$league_data->teams->isEmpty())
                            @foreach ($league_data->teams as $team)
                                <option
                                    {{ !empty($page_data) && $page_data->hometeamid == $team->teamid ? 'selected' : '' }}
                                    value="{{ $team->teamid }}">{{ $team->teamname }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div
                    class="col-md-3 margin-bottom-custom {{ checkToggleStatus($league_data->leagueid, 'teams') ? 'd-none' : '' }}">
                    <div class="textforthe-add">Select Team two</div>
                </div>
                <div
                    class="col-md-3 margin-bottom-custom {{ checkToggleStatus($league_data->leagueid, 'teams') ? 'd-none' : '' }}">

                    <div class="selects">
                        <select id="awayteam" name="awayteam" class="selector-names" id="" disabled>
                            <option value="" selected>Select</option>
                            @if (!$league_data->teams->isEmpty())
                                @foreach ($league_data->teams as $team)
                                    <option
                                        {{ !empty($page_data) && $page_data->awayteamid == $team->teamid ? 'selected' : '' }}
                                        value="{{ $team->teamid }}">{{ $team->teamname }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>


                <div class="col-md-3 margin-bottom-custom">
                    <div class="textforthe-add">Select Location</div>
                </div>
                <div class="col-md-3 margin-bottom-custom">
                    <select name="gamelocation" class="selector-names font-small" id="" required>
                        <option value="" selected>Select</option>
                        @if (!$locations->isEmpty())
                            @foreach ($locations as $location)
                                <option {{ !empty($page_data) && $page_data->locid == $location->locid ? 'selected' : '' }}
                                    value="{{ $location->locid }}">{{ $location->ground }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3 margin-bottom-custom">
                    <div class="textforthe-add">Players age</div>
                </div>
                <div class="col-md-3 margin-bottom-custom">

                    <div class="selects">

                        <input {{ checkToggleStatus($league_data->leagueid, 'age') ? 'readonly' : '' }}
                            value="{{ !empty($page_data) ? $page_data->playersage : 0 }}" type="number" min="1"
                            class="selector-names" name="playersage" required>
                    </div>
                </div>




                <div class="col-md-3 margin-bottom-custom">
                    <div class="textforthe-add">
                        No. of Umpires
                    </div>
                </div>
                <div class="col-md-9 margin-bottom-custom">
                    <input type="radio" name="umpreqd" value="1" class="d-none" id="redio1">
                    <span class="radiobtn-span" id="radio-sapn1"></span>
                    <label for="redio1" class="labels"><span class="round-radio"></span> One</label>

                    @if (checkToggleStatus($league_data->leagueid, 'umpire_2'))
                        {{-- leave blank --}}
                    @else
                        <input type="radio" name="umpreqd" value="2" class="d-none" id="redio2">
                        <span class="radiobtn-span ms-4" id="radio-sapn2"></span>
                        <label for="redio2" class="labels"><span class="round-radio"></span> Two</label>
                    @endif
                    @if (checkToggleStatus($league_data->leagueid, 'umpire_3'))
                        {{-- leave blank --}}
                    @else
                        <input type="radio" name="umpreqd" value="3" class="d-none" id="redio3">
                        <span class="radiobtn-span ms-4" id="radio-sapn3"></span>
                        <label for="redio3" class="labels"><span class="round-radio"></span> Three</label>
                    @endif
                    @if (checkToggleStatus($league_data->leagueid, 'umpire_4'))
                        {{-- leave blank --}}
                    @else
                        <input type="radio" name="umpreqd" value="4" class="d-none" id="redio4">
                        <span class="radiobtn-span ms-4" id="radio-sapn4"></span>
                        <label for="redio4" class="labels"><span class="round-radio"></span> Four</label>
                    @endif

                </div>


                <div class="col-md-3 margin-bottom-custom">
                    <div class="textforthe-add">
                        Required Report
                    </div>
                </div>

                <div class="col-md-9 margin-bottom-custom">
                    <input value="1" type="radio" name="report" class="d-none" id="redio5">
                    <span class="radiobtn-span" id="radio-sapn5"></span>
                    <label for="redio5" class="labels"><span class="round-radio"></span> Yes</label>



                    <input value="0" type="radio" name="report" class="d-none" id="redio6">
                    <span class="radiobtn-span ms-4" id="radio-sapn6"></span>
                    <label for="redio6" class="labels"><span class="round-radio"></span> No</label>

                </div>

                <div class="col-md-12 margin-bottom-custom">
                    <div class="boldtext">Umpires Payout</div>
                </div>




                <div class="col-md-3 margin-bottom-custom">
                    <div class="textforthe-add">Primary Umpire $</div>
                </div>
                <div class="col-md-3 margin-bottom-custom">
                    <input value="{{ !empty($page_data) ? $page_data->ump1pay : $league_data->defaultpay }}"
                        type="text" class="selector-names" name="ump1pay" required>
                </div>
                <div class="col-md-3 margin-bottom-custom">
                    <div class="textforthe-add">2nd/3rd/4th Umpire $</div>
                </div>
                <div class="col-md-3 margin-bottom-custom">

                    <div class="selects">

                        <input value="{{ !empty($page_data) ? $page_data->ump234pay : '' }}" type="text"
                            class="selector-names" name="ump234pay" disabled>
                    </div>
                </div>






                <div class="col-md-3 margin-bottom-custom">
                    <div class="textforthe-add">Primary Umpire Bonus $</div>
                </div>
                <div class="col-md-3 margin-bottom-custom">
                    <input value="{{ !empty($page_data) ? $page_data->ump1bonus : '' }}" type="text"
                        class="selector-names" name="ump1bonus">
                </div>
                <div class="col-md-3 margin-bottom-custom">
                    <div class="textforthe-add">2nd/3rd/4th Umpire Bonus $</div>
                </div>
                <div class="col-md-3 margin-bottom-custom">

                    <div class="selects">

                        <input value="{{ !empty($page_data) ? $page_data->ump234bonus : '' }}" disabled type="text"
                            class="selector-names" name="ump234bonus">
                    </div>
                </div>









            </div>

            <div class="buttons-flex hyscs">
                <div class="buttondiv-trans"><a href="{{ url('league/games') }}" class="cnclbtn buycnm">Cancel</a></div>
                <div class="button1div ms-5"><button class="redbtn submit" type="submit">Save</button></div>
            </div>









        </form>

    </div>

    <script>
        jQuery(document).ready(function() {
            jQuery('#datepicker').datepicker({
                format: 'yyyy-mm-dd',
            });
        });

        $('#date-icons1').click(function(e) {
            $("#datepicker").focus()

        });

        $('#radio-sapn1').click(function() {
            $('#redio1').click()

            $('#radio-sapn1').addClass('actives');
            $('#radio-sapn2').removeClass('actives');
            $('#radio-sapn3').removeClass('actives');
            $('#radio-sapn4').removeClass('actives');
        });
        $('#radio-sapn2').click(function() {
            $('#redio2').click()
            $('#radio-sapn2').addClass('actives');
            $('#radio-sapn1').removeClass('actives');
            $('#radio-sapn3').removeClass('actives');
            $('#radio-sapn4').removeClass('actives');
        });
        $('#radio-sapn3').click(function() {

            $('#redio3').click()
            $('#radio-sapn3').addClass('actives');
            $('#radio-sapn1').removeClass('actives');
            $('#radio-sapn2').removeClass('actives');
            $('#radio-sapn4').removeClass('actives');
        });
        $('#radio-sapn4').click(function() {

            $('#redio4').click()
            $('#radio-sapn4').addClass('actives');
            $('#radio-sapn1').removeClass('actives');
            $('#radio-sapn2').removeClass('actives');
            $('#radio-sapn3').removeClass('actives');
        });
        $('#radio-sapn5').click(function() {

            $('#redio5').click()
            $('#radio-sapn5').addClass('actives');
            $('#radio-sapn6').removeClass('actives');

        });

        $('#radio-sapn6').click(function() {

            $('#redio6').click()
            $('#radio-sapn6').addClass('actives');
            $('#radio-sapn5').removeClass('actives');

        });
    </script>
    @if ($league_data->report == 0)
        <script>
            $(document).ready(function() {
                $('#radio-sapn6').click();
                $('#radio-sapn5').css('pointer-events', 'none');
            });
        </script>
    @elseif ($league_data->report == 1)
        <script>
            $(document).ready(function() {
                $('#radio-sapn5').click();
                $('#radio-sapn6').css('pointer-events', 'none');
            });
        </script>
    @elseif ($league_data->report == 2)
        @if (!empty($page_data))
            @if ($page_data->report == 1)
                <script>
                    $('#radio-sapn5').click();
                </script>
            @else
                <script>
                    $('#radio-sapn6').click();
                </script>
            @endif
        @endif
    @endif
    <script>
        $(document).ready(function() {
            @if (!empty($page_data))
                updateAwayTeam(false);
                $('#radio-sapn{{ $page_data->umpreqd }}').click();
            @else
                $('#radio-sapn1').click();
            @endif
        });

        function updateAwayTeam(reset = true) {
            var awayteamSelect = document.getElementById("awayteam");
            var selectedHomeTeam = $('[name="hometeam"]').val();
            if (reset == true) {
                awayteamSelect.selectedIndex = 0;
            }
            awayteamSelect.disabled = false;
            for (var i = 0; i < awayteamSelect.options.length; i++) {
                if (awayteamSelect.options[i].value === selectedHomeTeam) {
                    awayteamSelect.options[i].disabled = true;
                } else {
                    awayteamSelect.options[i].disabled = false;
                }
            }
        }
        $('[name="umpreqd"]').change(function() {
            val = $(this).val();
            if (val > 1) {
                $('[name="ump234pay"]').attr('disabled', false);
                $('[name="ump234pay"]').attr('required', true);
                $('[name="ump234bonus"]').attr('disabled', false);
            } else {
                $('[name="ump234pay"]').attr('disabled', true);
                $('[name="ump234pay"]').attr('required', false);
                $('[name="ump234bonus"]').attr('disabled', true);
                $('[name="ump234pay"]').val('');
                $('[name="ump234bonus"]').val('');
            }
        });
    </script>
    <script>
        $(document).ready(function(e) {
            $('form').on('submit', (function(e) {
                e.preventDefault();
                var url = '{{ url('/league/games') }}';
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
                            window.location.replace(url);
                        } else {
                            $('html, body').animate({
                                scrollTop: 0
                            });
                            $('#errors').show().delay(5000).hide(0);
                            $('#errors').html(res.errors);
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
    <script>
        $(document).ready(function() {
            updateSelect();
            $('input[name="gameHour"]').inputmask("99:99", {
                placeholder: "hh:mm",
                hourFormat: 12,
                alias: "datetime",
                inputFormat: "HH:MM",
                oncomplete: updateSelect,
                onKeyValidation: updateSelect
            });
        });

        function updateSelect() {
            var value = $('input[name="gameHour"]').val();
            var hours = parseInt(value.split(':')[0], 10);
            var minutes = parseInt(value.split(':')[1], 10);
            if (hours > 12 || minutes > 59) {
                this.value = '';
                toastr.error(
                    'Invalid time. Hours must be 12 or below, and minutes must be 59 or below.'
                );
            } else {
                $('[name="gametime"]').val(hours);
            }
        }
    </script>
@endsection
