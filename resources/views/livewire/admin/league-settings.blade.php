<div class="table-responsive">
    <table id="datatable" class="table table-hover">
        <thead>
            <tr>
                <th>League Name</th>
                <th>League Owner Name</th>
                <th>Contact Number</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @if (!$page_data->isEmpty())
                @foreach ($page_data as $data)
                    <tr>
                        <td>{{ $data->leaguename }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->phone }}</td>
                        <td class="text-end">
                            <a onclick="@this.call('manageSettings', {{ $data->leagueid }})" href="javascript:;"
                                class="btn btn-dark">Manage settings</a>
                            <a href="{{ url('admin/edit_league/' . $data->leagueid) }}" class="btn btn-warning">Edit</a>
                            @if ($data->status == 0)
                                <a href="{{ url('admin/league_status/' . $data->leagueid . '/1') }}"
                                    class="btn btn-success"
                                    onclick="return confirm('Are you sure you want to activate this league?')">Activate</a>
                            @elseif ($data->status == 1)
                                <a href="{{ url('admin/league_status/' . $data->leagueid . '/0') }}"
                                    class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to deactivate this league?')">Deactivate</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td align="center" colspan="9">No League Found</td>
                </tr>
            @endif
        </tbody>
    </table>
    {{-- modal --}}
    <div wire:ignore.self class="modal fade" id="settingsModal" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
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
                        <div class="form-check form-switch d-flex">
                            <input wire:model.live='toggle.{{ $key }}'
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
