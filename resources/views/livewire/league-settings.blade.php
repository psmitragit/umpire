<div>
    <!-- Modal trigger button -->
    <button type="button" class="redbtn mx-auto" wire:click='manageSettings({{ $leagueRow->leagueid }})'>
        Toggle Settings
    </button>

    <div wire:ignore.self class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Toggle Settings</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
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
                        <div class="form-check form-switch">
                            <input {{ $disabled }} wire:model.live='toggle.{{ $key }}'
                                class="form-check-input position-relative m-0 me-2" type="checkbox" role="switch"
                                id="{{ $key }}">
                            <label class="form-check-label"
                                for="{{ $key }}">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary m-auto" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- modal --}}
</div>
@include('livewire.includes.event')
