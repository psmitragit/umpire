<div>
    <div class="thest-eyh">
        @foreach ($toggle as $key => $value)
            @php
                $disabled = '';
                if ($toggleStatus = checkToggleStatus($leagueRow->leagueid, $key)) {
                    if ($toggleStatus->toggled_by == 0) {
                        $disabled = 'disabled';
                    }
                }
            @endphp
            <div class="form-check form-switch d-flex masb">
                <input {{ $disabled }} wire:model.live='toggle.{{ $key }}'
                    class="form-check-input position-relative m-0 me-2" type="checkbox" role="switch"
                    id="{{ $key }}">
                <label class="form-check-label"
                    for="{{ $key }}">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
            </div>
        @endforeach
    </div>
    <button type="button" onclick="demoWarning();" class="redbtn submit mx-auto">Apply</button>
</div>
@include('livewire.includes.event')
