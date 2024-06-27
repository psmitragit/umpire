@extends('league.layouts.main')
@section('main-container')
    <div class="body-content me-customs">
        <div class="list-viw-contet mt-30px" id="list-conssst2">
            <div class="namphomediv">
                <h1 class="pageTitle">PAYOUT</h1>
                <a class="confirmCancel redbtn" style="padding: 5px 26px;"
                    data-text="Are you sure that you want to mark everyone as paid fully?" href="{{ url('league/pay-all') }}"
                    role="button">Pay All</a>
                <div class="mapbtns-div moasbflexs">
                    <div class="Admins">
                        <div class="inputs-srch">
                            <input placeholder="Search" name="srch" class="input-srch-field srch" type="text"
                                id="searchInput" oninput="filterTable('myTable');">
                            <button type="button" class="srch-mag-btn srch"> <img
                                    src="{{ asset('storage/league') }}/img/srch-icon.png" alt=""> </button>

                        </div>
                    </div>
                </div>
            </div>

            <div id="myTable">
                <table class="payout rowas-tabl" id="myDTable">
                    <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Owed
                            </th>
                            <th>Paid Amount $</th>
                            {{-- <th>Add Bonus $ (Optional)</th> --}}
                            <th>Adjusted amount $ (Optional)</th>
                            <th>Payment date</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$page_data->isEmpty())
                            @foreach ($page_data as $data)
                                <tr>
                                    <td class="color-prmths">{{ $data->umpire->name }}</td>
                                    <td class="team" id="new_owe{{ $data->id }}">$ {{ $data->owed ?? 0 }}</td>
                                    <td> <input required type="text" class="datescs bonus trasbsp"
                                            id="amount{{ $data->id }}"> </td>
                                    <td> <input type="text" class="datescs bonus trasbsp"
                                            id="bonus_amount{{ $data->id }}"> </td>
                                    <td> <input value="{{ date('Y-m-d') }}" type="date" class="datescs tsbns"
                                            id="paydate{{ $data->id }}"></td>
                                    <td><button data-id="{{ $data->id }}" class="redbtn  ass submit" onclick="demoWarning();"
                                            class="application butnts" type="button">Update</button></td>
                                    <td><button data-received="{{ $data->received ?? 0 }}"
                                            data-owed="{{ $data->owed ?? 0 }}" data-leagueid="{{ $data->leagueid }}"
                                            data-umpid="{{ $data->umpid }}" class="bluebtn  asc texns view-his"
                                            type="button" id="view-his{{ $data->id }}">View History</button></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                </table>

            </div>
        </div>
    </div>
    <div class="modal fade" id="payHisModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-hesn">
                    <div>
                        <h5 class="modalicons-title awc newmodaltitel">{{ $league_data->leaguename }}</h5>

                        <span class="modal-toptext">Paid out $<span id="received"></span></span><span
                            class="currnets">Current Owe $<span id="owed"></span></span>

                    </div>
                    <button type="button" class="btn-closes" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="overflowsc">
                        <table class="w-100">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>OWE</th>
                                </tr>
                            </thead>
                            <tbody id="payoutHtml">

                            </tbody>
                        </table>
                    </div>


                </div>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            filterTable('myTable');
        });
        $('.view-his').click(function() {
            var umpid = $(this).data('umpid');
            var leagueid = $(this).data('leagueid');
            var owed = $(this).data('owed');
            var received = $(this).data('received');
            $.ajax({
                url: "{{ url('league/view-payout-history') }}" + '/' + leagueid + '/' + umpid,
                success: function(res) {
                    $('#received').text(received);
                    $('#owed').text(owed);
                    $('#payoutHtml').html(res);
                    $('#payHisModal').modal('show');
                }
            });
        });
        $('.pay').click(function() {
            const element = $(this);
            $(element).text('Updating...');
            $(element).attr('disabled', true);
            var leagueumpire_id = $(element).data('id');
            var amount = $('#amount' + leagueumpire_id).val();
            var bonus_amount = $('#bonus_amount' + leagueumpire_id).val();
            var paydate = $('#paydate' + leagueumpire_id).val();
            var token = $('meta[name="csrf-token"]').attr('content');
            var url = '{{ url('league/payout') }}';
            $.ajax({
                type: "POST",
                dataType: "json",
                url: url,
                data: {
                    leagueumpire_id: leagueumpire_id,
                    amount: amount,
                    bonus_amount: bonus_amount,
                    paydate: paydate,
                    _token: token
                },
                success: function(data) {
                    if (data.hasOwnProperty('message')) {
                        $('#amount' + leagueumpire_id).val('');
                        $('#bonus_amount' + leagueumpire_id).val('');
                        $('#paydate' + leagueumpire_id).val(new Date().toISOString().slice(0, 10));
                        $('#new_owe' + leagueumpire_id).text('$ ' + data.new_owe);
                        $('#view-his' + leagueumpire_id).data('received', data.new_received);
                        $('#view-his' + leagueumpire_id).data('owed', data.new_owe);
                        toastr.success(data.message);
                        $(element).text('Update');
                        $(element).attr('disabled', false);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON.hasOwnProperty('errors')) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessages = [];
                        for (var field in errors) {
                            errorMessages.push(errors[field]);
                        }
                        toastr.error(errorMessages.join('<br>'), 'Validation Error');
                        $(element).text('Update');
                        $(element).attr('disabled', false);
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#myDTable').DataTable({
                "searching": false,
                "pageLength": -1,
                "lengthChange": false,
                "order": [[1, 'desc']],
                "columnDefs": [{
                        "orderable": true,
                        "targets": [0, 1]
                    },
                    {
                        "orderable": false,
                        "targets": "_all"
                    }
                ]
            });
        });
    </script>
@endsection
