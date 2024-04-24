@extends('league.layouts.main')
@section('main-container')
    <div class="body-content">
        <div class="namphomediv">
            <h1 class="pageTitle">New Applicant</h1>
            <div class="mapbtns-div">

            </div>
        </div>


        @if ($page_data->count() > 0)
            @foreach ($page_data as $data)
                <div class="row mb-4 align-items-center">
                    <div class="col-md-4">
                        <div class="text-names">{{ $data->umpire->name }}</div>
                    </div>
                    <div class="col-md-8 d-flex">
                        @php
                            $league_application_answers = $data->umpire
                                ->league_applications()
                                ->where('leagueid', $league_data->leagueid)
                                ->get();
                        @endphp
                        @if (!$league_application_answers->isEmpty())
                            <div class="col-md-auto margibens"><a href="javascript:void(0)"
                                    onclick="view_application({{ $data->umpire->umpid }})" class="application butnts">View
                                    Application</a></div>
                        @endif
                        <div class="col-md-auto margibens"><a
                                href="{{ url('league/approve-umpire/' . $data->umpire->umpid) }}"
                                class="application greenbtn confirmCancel">Approve</a></div>
                        <div class="col-md-auto margibens"><a
                                href="{{ url('league/decline-umpire/' . $data->umpire->umpid) }}"
                                class="application redsbtn confirmCancel">Decline</a></div>
                        <div class="col-md-auto "><a href="{{ url('league/interview-umpire/' . $data->umpire->umpid) }}"
                                class="application ylwsbtn confirmCancel">Interview</a></div>
                    </div>

                </div>
            @endforeach
        @endif

    </div>

    <!-- Modal -->
    <div class="modal fade" id="applicationAnswerModel" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-hesn">
                    <h5 class="modalicons-title">New Applicant</h5>
                    <button type="button" class="btn-closes" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-x"></i></button>
                </div>
                <div class="modal-body" id="applicationAnswerOutput">

                </div>

            </div>
        </div>
    </div>
    <!-- modal -->
    <script>
        function view_application(id) {
            $.ajax({
                url: "{{ url('league/view-application') }}" + '/' + id,
                success: function(res) {
                    $('#applicationAnswerOutput').html(res);
                    $('#applicationAnswerModel').modal('show');
                },
                error: function(res) {
                    toastr.error('Something went wrong.');
                }
            });
        }
    </script>
@endsection
