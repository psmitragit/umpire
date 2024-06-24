<div>
    <div class="modal-body">
        @foreach ($toggle as $key => $value)
            @php
                $disabled = '';
                if ($toggleStatus = checkToggleStatus($leagueRow->leagueid, $key)) {
                    if ($toggleStatus->toggled_by == 0) {
                        $disabled = 'disabled';
                    }
                }
            @endphp
            <div class="form-check form-switch d-flex">
                <input {{ $disabled }} wire:model.live='toggle.{{ $key }}'
                    class="form-check-input position-relative m-0 me-2" type="checkbox" role="switch"
                    id="{{ $key }}">
                <label class="form-check-label"
                    for="{{ $key }}">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                @if ($value)
                    &nbsp;&nbsp;<small class="text-danger">(Locked)</small>
                @else
                    &nbsp;&nbsp;<small class="text-success">(Unlocked)</small>
                @endif
            </div>
        @endforeach
        <button type="button" wire:click='applySettings'>Apply</button>
    </div>
</div>
@include('livewire.includes.event')
