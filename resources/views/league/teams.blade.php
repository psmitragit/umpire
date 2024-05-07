@extends('league.layouts.main')
@section('main-container')
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



        <div class="namevs-aneswz">
            <div class="headre-forAddadmin">Add teams for the league</div>

            <button class="bluebtn green-bg" onclick="add_team();">+Add Row </button>
        </div>



        @if ($page_data)
            @foreach ($page_data as $data)
                <div class="row margusn">
                    <div class="col-lg-10 col-md-10 col-7">
                        <div class="tetx-foredit" id="qstn{{ $data->teamid }}">{{ $data->teamname }}</div>
                    </div>
                    <div class="col-lg-1  col-2 col-md-1"><button data-divid="{{ $data->divid }}"
                            data-id="{{ $data->teamid }}" class="delete-notifixctasc blue-bgs ac edit_question"><i
                                class="fa-solid fa-pencil"></i></button></div>
                    <div class="col-lg-1 col-2 col-md-1"><a href="{{ url('league/delete_team/' . $data->teamid) }}"
                            onclick="return confirm('Are you sure you want to delete this team?')"
                            class="delete-notifixctasc addbgs asd"><i class="fa-regular fa-trash-can"></i></a></div>
                </div>
            @endforeach
        @endif






    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-hesn">
                    <h5 class="modalicons-title">Add/Edit Team</h5>
                    <button type="button" class="btn-closes" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-x"></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('league/save_team') }}" method="POST" id="report_form">
                        @csrf
                        <div class="selcta">
                            <select name="divid" class="mayeb" id="">
                                <option value="">Select</option>
                                @foreach ($league_data->divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modalqstnbalbe">
                            <textarea name="question" id="" placeholder="Write your team name here..." required></textarea>
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
        function add_team() {
            $('#report_form').attr('action', '{{ url('league/save_team') }}');
            $('[name="question"]').val('');
            $('[name="divid"]').val('');
            $('#exampleModal').modal('show');
        }
        $('.edit_question').click(function() {
            var id = $(this).data('id');
            var divid = $(this).data('divid');
            var qstn = $('#qstn' + id).text();
            $('#report_form').attr('action', '{{ url('league/update_team') }}/' + id);
            $('[name="question"]').val(qstn);
            $('[name="divid"]').val(divid);
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
