@extends('umpire.layouts.main')
@section('main-container')
    <div class="body-content">
        <div class="namphomediv">
            <h1 class="pageTitle">Notifications</h1>
            <div class="mapbtns-div">
                <div class="gureicons" data-bs-target="#exampleModal" data-bs-toggle="modal"><i class="fa-solid fa-gear"></i>
                </div>
            </div>
        </div>
        <div class="white-background-crad-div sdve row" id="myTable">
            @if ($page_data)
                @foreach ($page_data as $data)
                    <div class="repoetes">
                        <div class="flex-fornoti col-md-2">
                            <div class="dates-noti">{{ date('D m/d/y', strtotime($data->created_at)) }}</div>
                        </div>
                        <div class="noti-msga d-flex col-lg-{{ $data->type == 0 ? '9' : '10' }}">
                            <span class="notiicons">{!! $data->icon->code !!}</span>
                            <span class="notimsgdiv">{{ $data->msg }}</span>
                        </div>
                        @if ($data->type == 0)
                            <div class="delete-notifi col-md-1 text-end">
                                <a onclick="demoWarning();" href="javascript:;"
                                    class="delete-notifixctasc"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        @endif
                    </div>
                @endforeach
                @if ($page_data->count() > 5)
                    <button id="toggleButton">Show all</button>
                @endif
            @endif
        </div>


        <!-- Modal -->

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-hesn">
                        <h5 class="modalicons-title">Email Notifications Settings</h5>
                        <button type="button" class="btn-closes" data-bs-dismiss="modal" aria-label="Close"><i
                                class="fa-solid fa-x"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="toptext-modal">
                            You will receive email for your selected services
                        </div>


                        <form action="{{ url('umpire/save-email-settings') }}" method="POST">
                            @csrf
                            <div class="checkbosa">
                                <input {{ $email_settings->schedule_game == 1 ? 'checked' : '' }} type="checkbox"
                                    class="checkbox-big" name="schedule_game" id="check1">
                                <label for="check1">Scheduled on a game</label>
                            </div>

                            <div class="checkbosa">
                                <input {{ $email_settings->payment == 1 ? 'checked' : '' }} type="checkbox"
                                    class="checkbox-big" name="payment" id="check2">
                                <label for="check2">Payment received</label>
                            </div>

                            <div class="checkbosa">
                                <input {{ $email_settings->message == 1 ? 'checked' : '' }} type="checkbox"
                                    class="checkbox-big" name="message" id="check3">
                                <label for="check3">Message from league admin</label>
                            </div>



                            <div class="checkbosa">
                                <input {{ $email_settings->application == 1 ? 'checked' : '' }} type="checkbox"
                                    class="checkbox-big" name="application" id="check4">
                                <label for="check4">League application response</label>
                            </div>

                            <div class="checkbosa">
                                <input {{ $email_settings->cancel_game == 1 ? 'checked' : '' }} type="checkbox"
                                    class="checkbox-big" name="cancel_game" id="check5">
                                <label for="check5">Canceled games</label>
                            </div>

                            <div class="buttons-flex">
                                <div class="button1div"><button class="redbtn submit">Submit</button></div>
                                <div class="buttondiv-trans"><button type="button" class="cnclbtn" data-bs-dismiss="modal">
                                        Cancel</button></div>
                            </div>
                        </form>



                    </div>

                </div>
            </div>
        </div>
        <!-- modal -->
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('myTable');
            const button = document.getElementById('toggleButton');
            let showAllRows = false;
            let rowsToShow = 5;

            function toggleRows() {
                const rows = table.querySelectorAll('.repoetes');
                for (let i = 0; i < rows.length; i++) {
                    if (showAllRows || i < rowsToShow) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
                button.innerHTML = showAllRows ?
                    '<i class="fa-solid fa-angle-up"></i>' :
                    '<i class="fa-solid fa-angle-down"></i>';

                showAllRows = !showAllRows;
            }
            toggleRows();
            button.addEventListener('click', toggleRows);
        });
    </script>
@endsection
