@extends('league.layouts.main')
@section('main-container')
    <style>
        .checkbody {
            background: #fff;
        }
    </style>
    <div class="body-content">


        <div class="namphomediv">
            <h1 class="pageTitle">League Settings</h1>
            <div class="mapbtns-div">

            </div>
        </div>




        <!-- add all settings page menu  -->

        @include('league.layouts.settings_menubar')

        <!-- add all settings page menu  -->

        <div class="alert alert-danger" style="display: none;" id="errors"></div>

        <div class="settings-body">
            <div class="row alisth-tev">
                <div class="col-6">
                    <div class="texbh">
                        <span class="game-lables">GAME REPORT REQUIRED</span>
                        <label class="switch">
                            <input type="checkbox" class="form-check-input report" type="checkbox"
                                id="flexSwitchCheckChecked" {{ $league_data->report !== 0 ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="row col-6 csue-end">
                    <div class="form-check col-auto">
                        <input onclick="save_report_settings(1);" class="form-check-input report" type="radio"
                            name="flexRadioDefault" id="flexRadioDefault1" {{ $league_data->report == 1 ? 'checked' : '' }}
                            value='1'>
                        <label class="form-check-label" id="labels-22s" for="flexRadioDefault1">
                            Every Game
                        </label>
                    </div>
                    <div class="form-check col-auto">
                        <input onclick="save_report_settings(2);" class="form-check-input report" type="radio"
                            name="flexRadioDefault" id="flexRadioDefault2" {{ $league_data->report == 2 ? 'checked' : '' }}
                            value='2'>
                        <label class="form-check-label" id="labels-21s" for="flexRadioDefault2">
                            Selected Games
                        </label>
                    </div>
                </div>
            </div>
            <div>
                <div class="d-flex sadfs">
                    <h3 class="report-qstn-div">Report Questions:</h3>
                    <button onclick="add_report_question();" type="button" class="bluebtn green-bg noew">+ Add New</button>
                </div>
                <div>
                    <table class="table custormtarn">
                        <tbody id="tablecontents" data-url="{{ url('league/update_report_order') }}">
                            @if ($page_data)
                                @foreach ($page_data as $data)
                                    <tr class="row1" data-id="{{ $data->rqid }}">
                                        <td class="back-colors fix-widthe">
                                            <span><i class="fas fa-equals"></i></span>
                                        </td>
                                        <td class="back-colors excerpt" id="qstn{{ $data->rqid }}">{{ $data->question }}</td>
                                        <td class="fix-widyj"><a data-id="{{ $data->rqid }}" href="javascript:void(0)"
                                                class="edit_question"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        </td>
                                        <td class="fix-widyj"><a href="{{ url('league/delete_report/' . $data->rqid) }}"
                                                onclick="return confirm('Are you sure you want to delete this question?')"
                                                class="msyeb"><i class="fa-regular fa-trash-can"></i></a></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td align="center" colspan="9">No Report Found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-hesn">
                    <h5 class="modalicons-title">Add/Edit Report Question</h5>
                    <button type="button" class="btn-closes" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-x"></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('league/save_report_question') }}" method="POST" id="report_form">
                        @csrf
                        <div class="modalqstnbalbe">
                            <textarea name="question" id="bio" placeholder="Write your question here..." required maxlength="200" oninput="updateCharacterCount()"></textarea>
                            <p id="characterCount">Characters remaining: <span
                                id="count"></span></p>
                        </div>
                        <div class="text-center submit-bten-modal">
                            <button class="submitbtns">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script>
        function add_report_question() {
            $('#report_form').attr('action', '{{ url('league/save_report_question') }}');
            $('[name="question"]').val('');
            $('#exampleModal').modal('show');
        }
        $('.edit_question').click(function() {
            var id = $(this).data('id');
            var qstn = $('#qstn' + id).text();
            $('#report_form').attr('action', '{{ url('league/update_report_question') }}/' + id);
            $('[name="question"]').val(qstn);
            $('#exampleModal').modal('show');

        });
        $(document).ready(function() {
            var checkbox = $("#flexSwitchCheckChecked");
            var radioButtons = $("input[name='flexRadioDefault']");

            function updateRadioButtonsState() {
                if (checkbox.prop("checked")) {
                    radioButtons.prop("disabled", false);
                } else {
                    radioButtons.prop("disabled", true);
                    radioButtons.prop("checked", false);
                    save_report_settings(0);
                }
            }
            updateRadioButtonsState();
            checkbox.change(function() {
                updateRadioButtonsState();
            });
        });

        function save_report_settings(val) {
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "POST",
                url: "{{ url('league/save_report_settings') }}",
                data: {
                    report: val,
                    _token: token
                },
                dataType: "json",
                success: function(res) {
                    if (res.status == 1) {
                        toastr.success("Success.");
                    } else if (res.status == 0) {
                        toastr.error("Something went wrong.");
                    }
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
                        window.location.reload();
                    },
                    error: function(response) {
                        window.location.reload();
                    }
                });
            }));
        });


        $('#labels-22s').click(function(e) {
            $('#labels-22s').addClass("font-bold");
            $('#labels-21s').removeClass("font-bold");

        });
        $('#labels-21s').click(function(e) {
            $('#labels-21s').addClass("font-bold");
            $('#labels-22s').removeClass("font-bold");

        });
        $('#flexRadioDefault2').click(function(e) {
            $('#labels-21s').addClass("font-bold");
            $('#labels-22s').removeClass("font-bold");

        });
        $('#flexRadioDefault1').click(function(e) {
            $('#labels-22s').addClass("font-bold");
            $('#labels-21s').removeClass("font-bold");
        });
    </script>
@endsection
