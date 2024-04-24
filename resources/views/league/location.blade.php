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
            <div class="headre-forAddadmin">Add Locations for the league</div>

            <button class="bluebtn green-bg" onclick="add_location();">+Add Row </button>
        </div>

        <div class="row">
            <div class="col-md-10 col-10 row justify-content-between">
                <div class="col-md-6 col-3">
                    <div class="addset-loaction">Location</div>
                </div>
                <div class="col-md-3 col-3">
                    <div class="addset-loaction">Latitude</div>
                </div>
                <div class="col-md-3 col-3">
                    <div class="addset-loaction">Longitude</div>
                </div>
            </div>
        </div>

        @if ($page_data)
            @foreach ($page_data as $data)
                <div class="row margusn">
                    <div class="col-md-10 col-7 row justify-content-between sc" >

                        <div class="col-md-6">
                            <div class="tetx-foredit" id="ground{{ $data->locid }}"><span class="oversacs">{{ $data->ground }}</span></div>
                        </div>
                        <div class="col-md-3">
                            <div class="tetx-foredit" id="lat{{ $data->locid }}"><span class="overs">{{ $data->latitude }}</span></div>
                        </div>
                        <div class="col-md-3">
                            <div class="tetx-foredit" id="lon{{ $data->locid }}"><span class="overs">{{ $data->longitude }}</span></div>
                        </div>
                    </div>
                    <div class="col-md-1 col-2"><button data-id="{{ $data->locid }}"
                            class="delete-notifixctasc blue-bgs ac edit_question"><i
                                class="fa-solid fa-pencil"></i></button></div>
                    <div class="col-md-1 col-2"><a href="{{ url('league/delete_location/' . $data->locid) }}"
                            onclick="return confirm('Are you sure you want to delete this location?')"
                            class="delete-notifixctasc addbgs asd"><i class="fa-regular fa-trash-can"></i></a></div>
                </div>
            @endforeach
        @endif






    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-hesn">
                    <h5 class="modalicons-title">Add/Edit Location</h5>
                    <button type="button" class="btn-closes" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-x"></i></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="location_form">
                        @csrf
                        <div class="modalqstnbalbe">
                            <input type="text" name="ground" id="locationInput" placeholder="Enter location" required>
                            <input type="text" name="latitude" id="latitudeInput" placeholder="Enter Latitude" required>
                            <input type="text" name="longitude" id="longitudeInput" placeholder="Enter Longitude" required>
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
        function add_location() {
            $('#location_form').attr('action', '{{ url('league/save_location') }}');
            $('[name="ground"]').val('');
            $('[name="latitude"]').val('');
            $('[name="longitude"]').val('');
            $('#exampleModal').modal('show');
        }
        $('.edit_question').click(function() {
            var id = $(this).data('id');
            var ground = $('#ground' + id).text();
            var lat = $('#lat' + id).text();
            var lon = $('#lon' + id).text();
            $('#location_form').attr('action', '{{ url('league/update_location') }}/' + id);
            $('[name="ground"]').val(ground);
            $('[name="latitude"]').val(lat);
            $('[name="longitude"]').val(lon);
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
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&libraries=places&callback=initAutocomplete"
        async defer></script>
    <script>
        function initAutocomplete() {
            var locationInput = document.getElementById('locationInput');
            var autocomplete = new google.maps.places.Autocomplete(locationInput);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    console.log('Place details not found for input: ' + locationInput.value);
                    return;
                }
                document.getElementById('latitudeInput').value = place.geometry.location.lat();
                document.getElementById('longitudeInput').value = place.geometry.location.lng();
            });
        }
    </script>
@endsection
