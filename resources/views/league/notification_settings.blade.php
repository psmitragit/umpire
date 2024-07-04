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

        <!-- add all settings page  -->
        <div class="namevs-anes">
            <form action="{{ url('league/send_notification') }}" method="POST">
                @csrf
                <h2 class="notification-adminh2">
                    Message to Umpires
                </h2>
                <div class="text-area-notimsg">
                    <textarea name="message" required id="expand-text-area" class="text-are-noti-admin bignot"></textarea>
                </div>
                <div class="sendbuttonsave">
                    <button class="redbtn submit ms-auto" data-bs-toggle="modal" data-bs-target="#sendnoti"
                        type="button">Send</button>
                </div>
            </form>
        </div>

        @if ($page_data)
            @foreach ($page_data as $data)
                <div class="tecxt-notifications">

                    <div class="datefirnotifi">
                        {{ Illuminate\Support\Carbon::parse($data->created_at)->format('D n/j/y, g:i a') }}
                    </div>
                    <div class="messagefornotifi">
                        {{ $data->leaguemsg }}
                    </div>
                </div>
            @endforeach
        @endif




    </div>


    <div class="modal fade" id="sendnoti" tabindex="-1" aria-labelledby="sendnotiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-loout-container">
                    <h3 class="textfot-logout">Are you sure you want to send the message?</h3>
                    <div class="buttons-flex hyscs">
                        <div class="button1div"><button class="redbtn submit" onclick="$('form').submit();">Confirm</button>
                        </div>
                        <div class="buttondiv-trans"><button class="cnclbtn buycnm" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(e) {
            $('form').on('submit', (function(e) {
                e.preventDefault();
                $('.submit').css('pointer-events', 'none');
                $('.submit').text('Sending....');
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
