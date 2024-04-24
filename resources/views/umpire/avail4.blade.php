@extends('umpire.layouts.main')
@section('main-container')
    @php
        $month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

        $month2 = get_next_month($year, $month)['next_month'];
        $year2 = get_next_month($year, $month)['next_year'];

        $month3 = get_next_month($year2, $month2)['next_month'];
        $year3 = get_next_month($year2, $month2)['next_year'];

        $month4 = get_next_month($year3, $month3)['next_month'];
        $year4 = get_next_month($year3, $month3)['next_year'];

        $prev_data = get_prev_month($year, $month);
        $prev_data = get_prev_month($prev_data['prev_year'], $prev_data['prev_month']);
        $prev_data = get_prev_month($prev_data['prev_year'], $prev_data['prev_month']);
        $prev_data = get_prev_month($prev_data['prev_year'], $prev_data['prev_month']);
        $next_data = get_next_month($year4, $month4);

        $prev_month = $prev_data['prev_month'];
        $prev_year = $prev_data['prev_year'];

        $next_month = $next_data['next_month'];
        $next_year = $next_data['next_year'];

        if ($month >= 1) {
            $prev_link = url('umpire/avail/4') . "?month=$prev_month&year=$prev_year";
        }
        if ($month <= 12) {
            $next_link = url('umpire/avail/4') . "?month=$next_month&year=$next_year";
        }

        $avail_datetimes = [];
        if ($page_data->count() > 0) {
            foreach ($page_data as $data) {
                $avail_datetimes[$data->blockdate] = $data->blocktime;
            }
        }
        $games_data = [];
        if ($umpgames = get_umpire_games($umpire_data->umpid)) {
            foreach ($umpgames as $umpgame) {
                $gdate = date('Y-m-d', strtotime($umpgame->gamedate));
                $games_data[$gdate][] = ['gameid' => $umpgame->gameid];
            }
        }
        $cal_data = array_merge($avail_datetimes, $games_data);
    @endphp
    <div class="body-content">
        <div class="namphomediv">
            <h1 class="pageTitle">Availability </h1>
            <div class="mapbtns-div">
                <div class="d-flex">
                    <div class="texts1"> <span class="bg-primary"></span> Assigned Games</div>
                    <div class="texts1"><span class="bg-successs"></span> Available</div>
                    <div class="texts1"><span class="par-avlbe"></span> Partial Available</div>
                    <div class="texts1"><span class="not-avlbs"></span> Not Available</div>
                </div>
            </div>
        </div>


        <div class="displayflexv">
            <div class="date">
                <span class="bold-today">Today: </span>
                <?php
                echo '<a href="' . url('umpire/avail/4') . '">';
                $currentMonth = date('F');
                echo $currentMonth;
                echo ' ';
                $currentDay = date('j');
                echo $currentDay;
                echo ', ';
                $currentMonth = date('Y');
                echo $currentMonth;
                echo '</a>';
                ?>
            </div>
            <div class="buttons">
                <a onclick="window.location.replace('{{ url('umpire/avail') }}')" href="javascript:void(0)"
                    class="onebtn"><span class="fournaths"></span> 1 month view</a>
                <a onclick="window.location.replace('{{ url('umpire/avail/4') }}')" href="javascript:void(0)"
                    class="onebtn"><span class="active fournaths"></span> 4 month view</a>
            </div>

            <div class="previousbtnd">
                <div class="prev" id="previous"><a href="{{ $prev_link }}"><span class="arrown-btn"><i
                                class="fa-solid fa-angle-left"></i></span>
                        Previous</a></div>
                <div class="prev" id="next"><a href="{{ $next_link }}">Next<span class="arrown-btn active"><i
                                class="fa-solid fa-angle-right"></i></span> </a>
                </div>

            </div>
        </div>


        <div id="calendar-container">
            <div class="calendar">
                <div class="calendar-title">
                    @php
                        $date = new DateTime("$year-$month-01");
                        echo $date->format('F, Y');
                    @endphp
                </div>
                {!! displayCalendar($month, $year, $cal_data) !!}
            </div>
            <div class="calendar">
                <div class="calendar-title">
                    @php
                        $date = new DateTime("$year2-$month2-01");
                        echo $date->format('F, Y');
                    @endphp
                </div>
                {!! displayCalendar($month2, $year2, $cal_data) !!}
            </div>
            <div class="calendar">
                <div class="calendar-title">
                    @php
                        $date = new DateTime("$year3-$month3-01");
                        echo $date->format('F, Y');
                    @endphp
                </div>
                {!! displayCalendar($month3, $year3, $cal_data) !!}
            </div>
            <div class="calendar">
                <div class="calendar-title">
                    @php
                        $date = new DateTime("$year4-$month4-01");
                        echo $date->format('F, Y');
                    @endphp
                </div>
                {!! displayCalendar($month4, $year4, $cal_data) !!}
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="dateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body" id="dateModalbody">

                </div>

            </div>
        </div>
    </div>
    <!-- modal -->

    <script>
        $('.cal-dates').on('contextmenu tap', function(e) {
            e.preventDefault();
            let date = $(this).data('date');
            let game = $(this).hasClass('game-date');
            let token = $('meta[name="csrf-token"]').attr('content');

            if (!game) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('umpire/dateAvailInfo') }}",
                    data: {
                        date: date,
                        _token: token
                    },
                    success: function(res) {
                        $('#dateModalbody').html(res);
                        $('#dateModal').modal('show');
                    }
                });
            }
        });

        $('.cal-dates').click(function(e) {
            e.preventDefault();

            let date = $(this).data('date');
            let game = $(this).hasClass('game-date');
            let token = $('meta[name="csrf-token"]').attr('content');

            if (!game) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('umpire/makeAvailUnavail') }}",
                    data: {
                        date: date,
                        _token: token
                    },
                    success: function(res) {
                        if (res) {
                            window.location.reload();
                        }else{
                            toastr.error('Something went wrong..');
                        }
                    }
                });
            }
        });

        function uncheck_availtype() {
            $("input[name='avail_type']").prop("checked", false);
            $("#round-s1").removeClass("active-labes");
            $("#round-s2").removeClass("active-labes");
        };

        function uncheck_hours() {
            $(".hour_checkbox").prop("checked", false);
            var selectedValue = $("input[name='avail_type']:checked").val();

            if (selectedValue == 'fda') {
                $("#round-s1").addClass("active-labes");
                $("#round-s2").removeClass("active-labes");
            } else {

                $("#round-s2").addClass("active-labes");
                $("#round-s1").removeClass("active-labes");
            }

        };
    </script>
@endsection
