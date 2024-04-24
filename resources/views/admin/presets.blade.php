@extends('admin.layouts.main')
@section('main-container')
    <div class="grid-margin stretch-card w-100">
        <div class="card">
            <div class="alert alert-danger" style="display: none;" id="errors"></div>
            <div class="card-body">
                <h4 class="card-title">Point Preset</h4>
                <hr>
                <form class="forms-sample custom-width" action="{{ url('admin/add_preset') }}" method="post">
                    @csrf
                    <div class="form-group w-25  row">
                        <div class="col-12 mt-3">
                            <label class="old">Enter Preset Name</label>
                            <div class="d-flex">
                                <input name="name" type="text" placeholder="Name" class="form-control" required>
                                <button name="add_preset_btn" type="submit" class="btn btn-primary ms-2">Add</button>
                            </div>
                        </div>
                        
                    </div>
                </form>
                <div class="table-responsive mt-5">
                    <table class="table" id="example">
                        <thead>
                            <tr>
                                <th>Preset</th>
                                <th class="text-end">Edit</th>
                                <th class="text-end">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($page_data))
                                @foreach ($page_data as $data)
                                    <tr>
                                        <td id="preset_name{{ $data->presetid }}">{{ $data->presetname }}</td>
                                        <td class="text-end" id="preset_edit_btn{{ $data->presetid }}"
                                            data-preset_name="{{ $data->presetname }}"><a href="javascript:void(0)"
                                                type="button" class="btn color-blu p-0"
                                                onclick="update_preset_form({{ $data->presetid }})">Edit</a></td>
                                        <td class="text-end"><a href="{{ url('admin/delete_preset/' . $data->presetid) }}"
                                                type="button" class="btn text-danger p-0"
                                                onclick="return confirm('Are you sure you want to delete this preset? All the data related to this preset will be removed automatically.')">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">No Preset Found
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function update_preset_form(preset_id) {
            var preset_name = $('#preset_edit_btn' + preset_id).data('preset_name');
            var preset_input_field = '<input type="text" id="preset' + preset_id + '" class="form-control" value="' +
                preset_name + '">';
            var preset_edit_btn = "<button type='button' onclick='update_preset(" + preset_id +
                ")' class='btn btn-warning btn-rounded btn-fw'>Update</button>";
            $('#preset_name' + preset_id).html(preset_input_field);
            $('#preset_edit_btn' + preset_id).html(preset_edit_btn);
        }

        function update_preset(preset_id) {
            var url = "{{ url('admin/update_preset') }}" + '/' + preset_id;
            var preset = $('#preset' + preset_id).val();
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "post",
                url: url,
                data: {
                    "name": preset,
                    _token: token
                },
                success: function(response) {
                    window.location.reload();
                }
            });
        }
        $(document).ready(function(e) {
            $('form').on('submit', (function(e) {
                e.preventDefault();
                var url = '{{ url('/admin/add_preset') }}';
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
                        if (res.status == 1) {
                            window.location.replace(url);
                        } else {
                            $('#errors').show();
                            $('#errors').html(res.errors);
                        }

                    },
                    error: function(response) {

                        if (response.status === 422) {
                            var errorString = '';
                            var errors = response.responseJSON.errors;
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    var errorMessage = errors[key][0];
                                    errorString += '<p>' + errorMessage + '</p>';
                                }
                                $('html, body').animate({
                                    scrollTop: 0
                                }, 500);
                                $('#errors').show().delay(10000).hide(0);
                                $('#errors').html(errorString);
                            }
                        }
                    }
                });
            }));
        });
    </script>
@endsection
