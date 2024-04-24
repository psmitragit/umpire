<div class="modal fade" id="confirmCancelModel" tabindex="-1" aria-labelledby="sendnotiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-loout-container">
                <h3 class="textfot-logout" id="cctext"></h3>
                <div class="buttons-flex hyscs">
                    <div class="button1div"><a id="confirmLink" class="redbtn submit" type="button">Confirm</a>
                    </div>
                    <div class="buttondiv-trans"><button class="cnclbtn buycnm" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.normalLinkLoader').click(function() {
        $(this).text('Loading...');
        // $(this).attr('disabled', true);
        $(this).css('pointer-events', 'none');
    });
</script>
<script>
    $('.confirmCancel').click(function(e) {
        e.preventDefault();
        var text = $(this).data("text");
        var href = $(this).attr("href");
        if (!text || text == '') {
            text = 'Are you sure ?';
        }
        $('#cctext').html(text);
        $('#confirmLink').attr("href", href);
        $('#confirmCancelModel').modal('show');
        $('#confirmLink').on('click', function(e) {
            e.preventDefault();
            $('#confirmLink').text('Loading...');
            $('#confirmLink').attr('disabled', true);
            window.location.replace($(this).attr("href"));
        });
    });


    function opnemodals(e, ajax = false) {
        var text = $(e).data("text");
        var href = $(e).attr("href");
        if (!text || text == '') {
            text = 'Are you sure ?';
        }
        $('#cctext').html(text);
        $('#confirmLink').attr("href", href);
        $('#confirmCancelModel').modal('show');
        if (ajax) {
            $('#confirmLink').on('click', function(e) {
                e.preventDefault();
                $('#confirmLink').text('Loading...');
                $('#confirmLink').attr('disabled', true);
                if (!$(this).hasClass('noAjax')) {
                    $.ajax({
                        type: "GET",
                        url: href,
                        dataType: "json",
                        success: function(res) {
                            if (res.status == 2) {

                                var text =
                                    'Hey there! It looks like you\'re already committed to another game on this date. Are you sure you want to join this one as well?';

                                var href = '{{ url('umpire/same-game-assign') }}' + '/' + res
                                    .gameid +
                                    '/' + res.pos;

                                $('#confirmLink').text('Confirm');
                                $('#confirmLink').attr('disabled', false);
                                $('#cctext').html(text);
                                $('#confirmLink').attr("href", href);
                                $('#confirmLink').addClass("noAjax");
                                $('#confirmCancelModel').modal('show');
                            } else {
                                window.location.reload();
                            }
                        },
                        error: function(res) {
                            window.location.reload();
                        }
                    });
                } else {
                    window.location.replace($(this).attr("href"));
                }
            });
        }
    }
</script>
<script type="text/javascript">
    $(function() {
        $("#tablecontents").sortable({
            items: "section",
            cursor: 'move',
            opacity: 0.6,
            update: function() {
                sendOrderToServer();
            }
        });
        $(document).ready(function() {
            sendOrderToServer();
        });

        function sendOrderToServer() {
            var url = $('#tablecontents').data('url');
            var order = [];
            var token = $('meta[name="csrf-token"]').attr('content');
            $('section.row1').each(function(index, element) {
                order.push({
                    id: $(this).attr('data-id'),
                    position: index + 1
                });
            });
            $.ajax({
                type: "POST",
                dataType: "json",
                url: url,
                data: {
                    order: order,
                    _token: token
                },
                success: function(response) {}
            });
        }
    });
</script>
<script>
    const dataTable = document.getElementById('myTable2');
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', filterTable);

    function filterTable() {
        $('#toggleButton2').hide();
        const searchValue = searchInput.value.toLowerCase();
        const rows = dataTable.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const rowData = row.textContent.toLowerCase();

            if (rowData.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }
</script>
@if (isset($location_details))
    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: 39.8283,
                    lng: -98.5795
                }, // Centered around the United States
                zoom: 13, // Adjust the zoom level as needed
                mapTypeId: 'satellite' // Set the map type to satellite
            });

            var locations = @json($location_details);

            if (locations.length > 0) {
                var defaultLocation = locations[0]; // Get the first location from the array

                var lat = parseFloat(defaultLocation.latitude);
                var lng = parseFloat(defaultLocation.longitude);

                var defaultMarker = new google.maps.Marker({
                    position: {
                        lat: lat,
                        lng: lng
                    },
                    map: map
                });

                // Center the map around the default location
                map.setCenter({
                    lat: lat,
                    lng: lng
                });

                // Add markers for all locations
                locations.slice(1).forEach(function(location) {
                    var lat = parseFloat(location.latitude);
                    var lng = parseFloat(location.longitude);

                    var marker = new google.maps.Marker({
                        position: {
                            lat: lat,
                            lng: lng
                        },
                        map: map
                    });
                });
            }
        }
    </script>



    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&callback=initMap" async defer>
    </script>

    <script>
        // Initialize the map when the page is loaded
        initMap();
    </script>
@endif
<script src="{{ asset('storage/js/jquery-ui.min.js') }}"></script>
@if ($right_bar == 1)
    @include('umpire.layouts.rightbar')
@endif
</body>

</html>
