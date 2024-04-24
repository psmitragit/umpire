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
                                onchange="filter_preset(this.value, 'age-of-players')">
                                @foreach ($all_presets as $option)
                                    <option {{ $selected_preset == $option->presetid ? 'selected' : '' }}
                                        value="{{ $option->presetid }}">{{ $option->presetname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <form class="forms-sample" method="post"
                            action="{{ url('admin/save_age_of_players/' . $preset->presetid) }}">
                            @csrf
                            <div class="masc-table">
                                <table class="table table-bordered" id="dynamic_field">
                                    @if (!empty($page_data))
                                        @foreach ($page_data as $key => $data)
                                            <tr id="row{{ $key + 1 }}">
                                                <td><span>From</span><input type="number" name="from[]" placeholder="00"
                                                        class="selectd name_list" required
                                                        value="{{ $data->from }}" /></td>
                                                <td><span>To</span><input type="number" name="to[]" placeholder="00"
                                                        class="selectd name_list to-input" required
                                                        value="{{ $data->to }}" /></td>
                                                <td>
                                                    <select class="selectd" name="addless[]" id="" required>
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
            function makeFromFieldsReadonly() {
                $('input[name="from[]"]').not(':first').prop('readonly', true);
            }

            makeFromFieldsReadonly();
            var i = {{ count($page_data) }};

            function updateNextFromValue(currentRow) {
                var currentToInput = currentRow.find('input[name="to[]"]');
                var nextRow = currentRow.next();
                var nextFromInput = nextRow.find('input[name="from[]"]');
                if (currentToInput.val() !== '') {
                    var newFromValue = parseInt(currentToInput.val()) + 1;
                    nextFromInput.val(newFromValue);
                } else {
                    nextFromInput.val('');
                }
            }

            function validateToInput(toInput, fromInput) {
                var toValue = parseInt(toInput.val());
                var fromValue = parseInt(fromInput.val());

                if (!isNaN(toValue) && !isNaN(fromValue) && toValue < fromValue) {
                    toInput.val(fromValue);
                }
            }

            $(document).on('input', 'input[name="to[]"]', function() {
                var currentRow = $(this).closest('tr');
                updateNextFromValue(currentRow);
                validateToInput($(this), currentRow.find('input[name="from[]"]'));
            });

            $('#add').click(function() {
                i++;
                if (i !== 1) {
                    var readonly = 'readonly';
                } else {
                    var readonly = '';
                }
                var lastToInput = $('#row' + (i - 1) + ' input[name="to[]"]');
                var newFromValue = lastToInput.val() !== '' ? parseInt(lastToInput.val()) + 1 : '';

                $('#dynamic_field').append('<tr id="row' + i +
                    '" class="dynamic-added"><td><span>From</span><input type="number" name="from[]" value="' +
                    newFromValue +
                    '" placeholder="00" class="selectd name_list" required ' + readonly +
                    ' /></td><td><span>To</span><input type="number" name="to[]" placeholder="00" class="selectd name_list to-input" required /></td><td><select class="selectd" required name="addless[]"><option value="-">Deduct</option><option value="+">Add</option></select></td><td><input type="number" name="point[]" placeholder="00" class="selectd name_list" required /><span>Points</span></td><td><button type="button" name="remove" class="delet-btn btn_remove">X</button></td></tr>'
                );

                $('#row' + i + ' input[name="to[]"]').on('input', function() {
                    var currentRow = $(this).closest('tr');
                    updateNextFromValue(currentRow);
                    validateToInput($(this), currentRow.find('input[name="from[]"]'));
                });
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
