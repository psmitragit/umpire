<form action="{{ url('league/set_preset') }}" method="POST" id="apply_preset_form">
    @csrf
    <div class="textaligns-flex">
        <div class="labele-div">Select Preset</div>
        <div class="green-select">
            <select name="preset_id" class="greenselector" id="">
                <option value="" selected>Select</option>
                @foreach ($all_presets as $option)
                    <option value="{{ $option->presetid }}">
                        {{ $option->presetname }}</option>
                @endforeach
            </select>
        </div>
        <div class="redbuttons">
            <button class="red-aply" data-bs-toggle="modal" data-bs-target="#sendnoti" type="button">Apply</button>
        </div>
    </div>
</form>
<div class="modal fade" id="sendnoti" tabindex="-1" aria-labelledby="sendnotiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-loout-container">
                <h3 class="textfot-logout">This will overwrite all your point settings (if any).Are you sure you want to
                    proceed?</h3>
                <div class="buttons-flex hyscs">
                    <div class="button1div"><button class="redbtn submit"
                        onclick="demoWarning();">Apply</button>
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
        $('#apply_preset_form').on('submit', (function(e) {
            e.preventDefault();
            if ($('[name="preset_id"]').val() == '') {
                $('#sendnoti').modal('hide');
                toastr.error('Please select a preset first');
                return;
            }
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
