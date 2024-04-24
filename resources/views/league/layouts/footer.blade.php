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
        $(this).attr('disabled', true);
    });
</script>
<script type="text/javascript">
    $(function() {
        $("#tablecontents").sortable({
            items: "tr",
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
            $('tr.row1').each(function(index, element) {
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
    function filterTable(tableid) {
        const dataTable = document.getElementById(tableid);
        const searchInput = document.getElementById('searchInput');
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
</script>
<script>
    $('.hambrgrbtn').click(function(e) {

        $('.sideholebar-lft').toggleClass('toggle-class');


    });
    $('.buttonx').click(function(e) {
        e.preventDefault();
        $('.sideholebar-lft').removeClass('toggle-class');
    });
</script>

<script>
    $(document).ready(function() {
        if (!$('table').hasClass('payout')) {
            $("table thead th").each(function(index) {
                var columnName = $(this).text().trim();
                if (columnName !== "") {
                    $("table tbody tr").each(function() {
                        var td = $(this).find("td:nth-child(" + (index + 1) + ")");
                        td.html("<span class='phone-labels'>" + columnName + "</span>" + td
                            .html());
                    });
                }
            });
        }
    });
</script>
<script>
    function updateCharacterCount() {
        var bioTextarea = $('#bio');
        var countSpan = $('#count');
        var maxLength = parseInt(bioTextarea.attr('maxlength'));
        var currentLength = bioTextarea.val().length;
        countSpan.text(maxLength - currentLength);

        if (currentLength > maxLength) {
            bioTextarea.val(bioTextarea.val().slice(0, maxLength));
        }
    }
    $(document).ready(function() {
        updateCharacterCount();
    });
</script>
<script>
    $(document).ready(function() {
      var maxChars = 50;
      $('.excerpt').each(function() {
        var text = $(this).text();
        if (text.length > maxChars) {
          var truncatedText = text.slice(0, maxChars) + '...';
          $(this).text(truncatedText);
        }
      });
    });
  </script>
<script src="{{ asset('storage/js/jquery-ui.min.js') }}"></script>
@if ($right_bar == 1)
    @include('league.layouts.rightbar')
@endif
</body>

</html>
