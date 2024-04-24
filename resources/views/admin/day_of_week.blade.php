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
                            <select id="course" class="selectd" onchange="filter_preset(this.value, 'day-of-week')">
                                @foreach ($all_presets as $option)
                                    <option {{ $selected_preset == $option->presetid ? 'selected' : '' }}
                                        value="{{ $option->presetid }}">{{ $option->presetname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <form class="forms-sample" method="post"
                            action="{{ url('admin/save_day_of_week/' . $preset->presetid) }}">
                            @csrf
                            <div class="mt-5">
                                <table class="table table-bordered" id="dynamic_field">
                                    @php
                                        $days = [
                                            'MON' => 'MONDAY',
                                            'TUE' => 'TUESDAY',
                                            'WED' => 'WEDNESDAY',
                                            'THU' => 'THURSDAY',
                                            'FRI' => 'FRIDAY',
                                            'SAT' => 'SATURDAY',
                                            'SUN' => 'SUNDAY',
                                        ];
                                        $i = 0;
                                    @endphp
                                    @foreach ($days as $k => $val)
                                        <tr>
                                            <td><span>{{ $val }}</span><input type="hidden" name="dayname[]"
                                                    placeholder="00" value="{{ $k }}"
                                                    class="form-control name_list" required /></td>
                                            <td class="ascca">
                                                <select name="addless[]" id="" required>
                                                    <option
                                                        {{ !$page_data->isEmpty() && $page_data[$i]->addless == '-' ? 'selected' : '' }}
                                                        value="-">Deduct</option>
                                                    <option
                                                        {{ !$page_data->isEmpty() && $page_data[$i]->addless == '+' ? 'selected' : '' }}
                                                        value="+">Add</option>
                                                </select>
                                            </td>
                                            <td class="text-end"><input value="{{ !$page_data->isEmpty() ? $page_data[$i]->point : '' }}"
                                                    type="number" name="point[]" placeholder="00"
                                                    class="selectd name_list" required /><span class="ms-2">Points</span></td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                </table>
                                <button type="submit" class="btn btn-success mt-3 ms-3">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
