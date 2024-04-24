@extends('league.layouts.main')
@section('main-container')
    <div class="body-content">
        <style>.checkbody {
            background: #fff;
          }</style>

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
            <div>
                <div class="d-flex sadfs">
                    <h3 class="report-qstn-div">League Application Questions:</h3>
                    <button onclick="add_league_application_question();" type="button" class="bluebtn green-bg noew">+ Add New</button>
                </div>
                <div>
                    <table class="table custormtarn">
                        <tbody id="tablecontents" data-url="{{ url('league/update_league_application_order') }}">
                            @if ($page_data)
                                @foreach ($page_data as $data)
                                    <tr class="row1" data-id="{{ $data->lqid }}">
                                        <td class="back-colors fix-widthe"> <span ><i class="fas fa-equals"></i></span></td>
                                        <td class="back-colors excerpt" id="qstn{{ $data->lqid }}">{{ $data->question }}</td>
                                        <td class="fix-widyj" ><a  data-id="{{ $data->lqid }}" href="javascript:void(0)"
                                                class="edit_question"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        </td>
                                        <td class="fix-widyj"><a  class="msyeb" href="{{ url('league/delete_application/' . $data->lqid) }}"
                                                onclick="return confirm('Are you sure you want to delete this question?')"><i class="fa-regular fa-trash-can"></i></a></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td align="center" colspan="9">No Question Found</td>
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
                    <h5 class="modalicons-title">Add/Edit League Application Question</h5>
                    <button type="button" class="btn-closes" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-x"></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('league/save_league_application_question') }}" method="POST" id="report_form">
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
        function add_league_application_question() {
            $('#report_form').attr('action', '{{ url('league/save_league_application_question') }}');
            $('[name="question"]').val('');
            $('#exampleModal').modal('show');
        }
        $('.edit_question').click(function() {
            var id = $(this).data('id');
            var qstn = $('#qstn' + id).text();
            $('#report_form').attr('action', '{{ url('league/update_league_application_question') }}/' + id);
            $('[name="question"]').val(qstn);
            $('#exampleModal').modal('show');

        });
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
    </script>
@endsection
