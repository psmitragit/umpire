</div>
</div>
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
            var url = $('#datatable').data('url');
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
    tinymce.init({
        selector: 'textarea.tinymce',
        height: 300
    });
    $(document).ready(function() {
        if ($('#datatable tbody tr').length > 1) {
            $('#datatable').DataTable({
                "order": []
            });
        }
    });
    $(document).ready(function() {
        $('.multi_select').select2();
    });

    function filter_preset(val, type) {
        try {
            var url = '{{ url('admin') }}' + '/point-preset/' + type + '?preset=' + val;
            window.location.replace(url);
        } catch (error) {
            console.error(error);
        }

    }
</script>
<!-- plugins:js -->
<script src="{{ asset('storage/templete/vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="{{ asset('storage/templete/vendors/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('storage/templete/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('storage/templete/vendors/progressbar.js/progressbar.min.js') }}"></script>

<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset('storage/templete/js/off-canvas.js') }}"></script>
<script src="{{ asset('storage/templete/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('storage/templete/js/template.js') }}"></script>
<script src="{{ asset('storage/templete/js/settings.js') }}"></script>
<script src="{{ asset('storage/templete/js/todolist.js') }}"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="{{ asset('storage/templete/js/jquery.cookie.js') }}" type="text/javascript"></script>
<script src="{{ asset('storage/templete/js/dashboard.js') }}"></script>
<script src="{{ asset('storage/templete/js/Chart.roundedBarCharts.js') }}"></script>
<script src="{{ asset('storage/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('storage/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('storage/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('storage/js/select2.min.js') }}"></script>
</body>

</html>
