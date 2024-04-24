@extends('league.layouts.main')
@section('main-container')
<style>
    .sel2 {
  width: 90px;
}
</style>
    <div class="body-content">


        <div class="namphomediv">
            <h1 class="pageTitle">League Settings</h1>
            <div class="mapbtns-div">

            </div>
        </div>






        <!-- add all settings page menu  -->

        @include('league.layouts.settings_menubar')

        <!-- add all settings page menu  -->

        <!-- add all settings page  -->
        <div class="namevs-anes">

            @include('league.layouts.preset_selectBox')

            <div class="row">
                <div class="col-md-3">
                    @include('league.layouts.point_sidebar')
                </div>
                <div class="col-md-9">
                    <form class="forms-sample form" method="post" action="{{ url('league/save_location_preset') }}">
                        @csrf
                        <div class="headrepages-point">
                            Location
                        </div>
                        <div id="dynamic_field">
                            @if (!empty($page_data))
                                @foreach ($page_data as $key => $data)
                                    <section class="displayflesxsa tegss age" id="row{{ $key + 1 }}">
                                        <span class="text1s ">Location</span>
                                        <span class="pists">{!! location_select_box('league', $data->locid) !!}</span>
                                        <span class="text2s mr-0 "><select name="addless[]"
                                                class="noames-select custom-width-selector" id="" required>
                                                <option {{ $data->addless == '-' ? 'selected' : '' }} value="-">Deduct
                                                </option>
                                                <option {{ $data->addless == '+' ? 'selected' : '' }} value="+">Add
                                                </option>
                                            </select></span>
                                        <span class="pists"><input type="number" class="name-inpostya name_list"
                                                name="point[]" placeholder="00" required value="{{ $data->point }}"></span>
                                        <span class="tos">Points</span>
                                        <div class="delete-notifi">
                                            <button class="delete-notifixctasc btn_remove"><i
                                                    class="fa-regular fa-trash-can"></i></button>

                                        </div>
                                    </section>
                                @endforeach
                            @endif
                        </div>
                        <div class="buttonsubmit age">
                            <button class="bluebtn" id="add" type="button">+Add Row </button>
                            <button class="submit redbtn" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            var i = {{ count($page_data) + 1 }};
            $('#add').click(function() {
                i++;
                var html = '<section class="displayflesxsa tegss age" id="row' + i +
                    '">';
                html += '<span class="text1s ">Location</span>';
                html += '<span class="pists">';
                html += '{!! location_select_box("league") !!}';
                html += '</span><span class="text2s mr-0 ">';
                html +=
                    '<select name="addless[]" class="noames-select custom-width-selector" id="" required>';
                html += '<option value="-">Deduct</option><option value="+">Add</option>';
                html += '</select></span><span class="pists">';
                html +=
                    '<input type="number" class="name-inpostya name_list" name="point[]" placeholder="00" required>';
                html += '</span><span class="tos">Points</span>';
                html +=
                    '<div class="delete-notifi"><button class="delete-notifixctasc btn_remove"><i class="fa-regular fa-trash-can"></i></button></div></section>';
                $('#dynamic_field').append(html);
            });

            $(document).on('click', '.btn_remove', function() {
                $(this).closest('section').remove();
            });
        });
    </script>
    <script>
        $(document).ready(function(e) {
            $('.form').on('submit', (function(e) {
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
