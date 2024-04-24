@extends('admin.layouts.main')
@section('main-container')
    <div class="content-wrapper">
        <div class="alert alert-danger" style="display: none;" id="errors"></div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $title }}</h4>
                        <hr>
                        <div>
                            <select id="course" class="selectd"
                                onchange="filter_preset(this.value, 'time')">
                                @foreach ($all_presets as $option)
                                    <option {{ $selected_preset == $option->presetid ? 'selected' : '' }}
                                        value="{{ $option->presetid }}">{{ $option->presetname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <form class="forms-sample" method="post"
                            action="{{ url('admin/save_time/' . $preset->presetid) }}">
                            @csrf
                            <div class="masc-table">
                                    <table class="table table-bordered" id="dynamic_field">
                                        @if (!empty($page_data))
                                            @foreach ($page_data as $key => $data)
                                                <tr>
                                                    <td class="ascca"><span>GAME STARTS</span>{!! generateHourSelectBox('from[]', $data->from) !!}</td>
                                                    <td class="ascca"><span>&</span>{!! generateHourSelectBox('to[]', $data->to) !!}</td>
                                                    <td>
                                                        <select name="addless[]" class="selectd" id="" required>
                                                            <option {{ $data->addless == '-' ? 'selected' : '' }}
                                                                value="-">Deduct</option>
                                                            <option {{ $data->addless == '+' ? 'selected' : '' }}
                                                                value="+">Add</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" name="point[]" placeholder="00"
                                                            class="selectd name_list" required
                                                            value="{{ $data->point }}" /><span>Points</span></td>
                                                    <td><button type="button" name="remove"
                                                            class="delet-btn btn_remove">X</button></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td><span>GAME STARTS</span>{!! generateHourSelectBox('from[]') !!}</td>
                                                <td><span>&</span>{!! generateHourSelectBox('to[]') !!}</td>
                                                <td>
                                                    <select name="addless[]" id="" required>
                                                        <option value="-">
                                                            Deduct</option>
                                                        <option value="+">
                                                            Add</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" name="point[]" placeholder="00"
                                                        class="form-control name_list" required /><span>Points</span></td>
                                                <td><button type="button" name="remove"
                                                        class="btn btn-danger btn_remove">X</button></td>
                                            </tr>
                                        @endif
                                    </table>

                                <button type="button" name="add" id="add" class="btn btn-success mt-3">Add
                                    More</button>
                                <button type="submit" class="btn btn-success mt-3 ms-3">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            var i = {{ count($page_data) + 1 }};
            $('#add').click(function() {
                i++;
                $('#dynamic_field').append('<tr id="row' + i +
                    '" class="dynamic-added"><td class="ascca"><span>GAME STARTS</span>{!! generateHourSelectBox('from[]') !!}</td><td class="ascca"><span>&</span>{!! generateHourSelectBox('to[]') !!}</td> <td><select class="selectd" required name="addless[]" id=""><option value="-">Deduct</option><option value="+">Add</option></select></td><td><input type="number" name="point[]" placeholder="00" class="selectd name_list" required /><span>Points</span></td><td><button type="button" name="remove" id="' +
                    i + '" class="delet-btn btn_remove">X</button></td></tr>');
            });

            $(document).on('click', '.btn_remove', function() {
                $(this).closest('tr').remove();
            });
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
                        if (res.status == 1) {
                            toastr.success("Success.");
                        } else if (res.status == 0) {
                            toastr.error("Something went wrong.");
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
