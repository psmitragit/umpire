@extends('umpire.layouts.main')
@section('main-container')
    @php
        $oweReceived = getUmpireOweReceived($umpire_data->umpid);
    @endphp
    <div class="body-content">


        <div class="namphomediv">
            <h1 class="pageTitle">My Earnings</h1>

            <div class="lastUpdate">
                Last update on: <span class="bold-texts">{{ $last_update }}</span>
            </div>


        </div>


        <div class="row">
            <div class="col-md-7 ">
                <div class="bg-white casc">
                    <div class="custom-flexs">
                        <div class="years-pi-chst">Total Earning</div>
                        <div class="thisyear">
                            <select id="bar-year" class="barchart">
                                @php
                                    $currentYear = date('Y');
                                @endphp
                                @for ($year = 2023; $year <= $currentYear; $year++)
                                    @php
                                        if ($year == $currentYear) {
                                            $year_label = 'This year';
                                        } else {
                                            $year_label = $year;
                                        }
                                    @endphp
                                    <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                        {{ $year_label }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <canvas id="bar-chart"></canvas>
                </div>
            </div>
            <div class="col-md-5">

                <div class="bg-white casc pichnatpadding">
                    <div class="years-pi-chst">League Wise Earning</div>
                    <canvas id="pie-chart"></canvas>
                </div>
            </div>
        </div>


        <div class="d-flex">
            <div class="Pendingtext">Pending: <strong class="text-danger">${{ $oweReceived['total_pending'] }}</strong>
            </div>
            <div class="Pendingtext">Received: <strong class="text-success">${{ $oweReceived['total_received'] }}</strong>
            </div>
        </div>



        <form action="{{ url('umpire/earning') }}" method="GET">
            <div class="d-flex align-items-center justify-content-between">
                <h2 class="erningpagetable-header">Earning Details</h2>
                <div class="date-fiels-div d-flex">
                    <div class="img-anddatefield">
                        <input type="text" id="datepicker" placeholder="From" name="fromdate" required
                            value="{{ $fromdate !== null ? date('m/d/Y', strtotime($fromdate)) : '' }}">
                        <span class="date-icons" id="date-icons1"><img
                                src="{{ asset('storage/umpire') }}/img/calendericon.png" alt=""
                                class="caledericons-img"></span>
                    </div>
                    <div class="img-anddatefield">
                        <input type="text" id="datepicker3" placeholder="To" name="todate" required
                            value="{{ $todate !== null ? date('m/d/Y', strtotime($todate)) : '' }}">
                        <span class="date-icons" id="date-icons2"><img
                                src="{{ asset('storage/umpire') }}/img/calendericon.png" alt=""
                                class="caledericons-img"></span>
                    </div>
                    <div class="gobtn">
                        <button class="gosubmitbtn" type="submit">Go</button>
                        @if ($fromdate !== null && $todate !== null)
                            <a href="{{ url('umpire/earning') }}">Reset</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
        <div class="tibask-erning">
            <table class="rowas-tabl" id="myTable">
                <thead>
                    <tr>
                        <th>
                            Date
                        </th>

                        <th>Particular</th>

                        <th>LEAGUE</th>
                        <th>AMOUNT</th>
                        <th>RECEIVED</th>
                        <th>PENDING</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$payouts->isEmpty())
                        @php
                            $previous_pending = 0;
                        @endphp
                        @foreach ($payouts as $payout)
                            @php
                                $amount = '__';
                                $received = '__';
                                if ($payout->pmttype == 'game') {
                                    $game = $payout->game;
                                    $particular = '<span class="team">' . $game->hometeam->teamname . ' vs ' . $game->awayteam->teamname . '</span>';
                                    $amount = '$' . $payout->payamt;
                                } elseif ($payout->pmttype == 'payout') {
                                    $particular = '<span class="team">Payout</span>';
                                    $received = '$' . $payout->payamt;
                                } elseif ($payout->pmttype == 'bonus') {
                                    $particular = '<span class="text-success">Bonus</span>';
                                    $received = '$' . $payout->payamt;
                                }
                            @endphp
                            <tr>
                                <td>{{ date('D m/d/y', strtotime($payout->paydate)) }}</td>
                                <td>{!! $particular !!}</td>
                                <td>{{ $payout->league->leaguename }}</td>
                                <td class="team">{{ $amount }}</td>
                                <td class="text-success">{{ $received }}</td>
                                <td class="time-table">${{ $payout->ump_pending }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script>
        jQuery(document).ready(function() {
            jQuery('#datepicker').datepicker({
                format: 'yyyy-mm-dd',
            });
        });
        jQuery(document).ready(function() {
            jQuery('#datepicker3').datepicker({
                format: 'yyyy-mm-dd',
            });
        });

        $('#date-icons1').click(function(e) {
            $("#datepicker").focus()

        });
        $('#date-icons2').click(function(e) {
            $("#datepicker3").focus()

        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script>
        $(document).ready(function() {
            var year = $('#bar-year').val();
            barchart(year);
            piechart();
        });
        $('#bar-year').change(function() {
            var year = $(this).val();
            barchart(year);
        });

        var existingChart = null;

        function barchart(year = null) {
            if (existingChart) {
                existingChart.destroy();
            }

            var url = '{{ url('umpire/barchart') }}';
            if (year !== null) {
                url += '?year=' + year;
            }

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var payouts = data;
                    var labels = Object.keys(payouts);
                    var values = Object.values(payouts);

                    var ctx = document.getElementById('bar-chart').getContext('2d');
                    existingChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Payout Amount',
                                data: values,
                                backgroundColor: '#161d2d',
                                hoverBackgroundColor: '#c01414',
                                borderWidth: 0,

                            }]
                        },
                        options: {
                            scales: {
                                x: {
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0)' // Make x-axis grid lines transparent
                                    },
                                    ticks: {
                                        color: '#989ca7'
                                    },
                                    color: 'rgba(0, 0, 0, 0)' // Make x-axis line transparent
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0)' // Make y-axis grid lines transparent
                                    },
                                    ticks: {
                                        callback: function(value, index, values) {
                                            return '$' + value;
                                        },
                                        color: '#989ca7'
                                    },
                                    color: 'rgba(0, 0, 0, 0)' // Make y-axis line transparent
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: '',
                                    align: 'start',
                                    font: {
                                        size: 20,
                                        color: 'black'
                                    }
                                },
                                background: {
                                    color: 'white'
                                }
                            }
                        }
                    });
                }
            });
        }

        function piechart() {
            var url = '{{ url('umpire/piechart') }}';
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var labels = data.map(function(item) {
                        return item.label;
                    });

                    var values = data.map(function(item) {
                        return item.value;
                    });

                    var colors = data.map(function(item) {
                        return item.color;
                    });


                    // Create the pie chart
                    var ctx = document.getElementById('pie-chart').getContext('2d');
                    var pieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: values,
                                backgroundColor: colors,
                            }],
                        },
                        options: {
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom', // Display the legend at the bottom
                                },
                            },
                            title: {
                                display: true,
                                text: 'Pie Chart',
                            },
                            responsive: false, // Enable responsiveness
                            maintainAspectRatio: true, // Disable aspect ratio
                            height: 160, // Set the height
                            width: 160 , // Set the width
                        },
                    });
                }
            });
        }
    </script>
@endsection
