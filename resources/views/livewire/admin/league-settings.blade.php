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
                            <a href="javascript:;" class="btn btn-dark">Manage settings</a>
                            <a href="{{ url('admin/edit_league/' . $data->leagueid) }}"
                                class="btn btn-warning">Edit</a>
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
</div>
