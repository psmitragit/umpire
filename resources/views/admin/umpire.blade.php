@extends('admin.layouts.main')
@section('main-container')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="col-md-3">
                                <h4 class="card-title">{{ $title }}</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$page_data->isEmpty())
                                        @foreach ($page_data as $data)
                                            <tr>
                                                <td>{{ $data->name }}</td>
                                                <td>{{ $data->user->email }}</td>
                                                <td>{{ $data->phone }}</td>
                                                <td class="text-end">
                                                    <a href="{{ url('admin/login-as-umpire/' . $data->user->uid) }}"
                                                        class="btn btn-dark openInNewWindow">Login</a>
                                                    <a data-id="{{ $data->umpid }}"
                                                        href="{{ url('admin/assign_league/' . $data->umpid) }}"
                                                        class="btn btn-warning assignLeague">Assign League</a>
                                                    @if ($data->status == 0)
                                                        <a href="{{ url('admin/umpire_status/' . $data->umpid . '/1') }}"
                                                            class="btn btn-success"
                                                            onclick="return confirm('Are you sure you want to activate this umpire?')">Activate</a>
                                                    @elseif ($data->status == 1)
                                                        <a href="{{ url('admin/umpire_status/' . $data->umpid . '/0') }}"
                                                            class="btn btn-danger"
                                                            onclick="return confirm('Are you sure you want to deactivate this umpire?')">Deactivate</a>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td align="center" colspan="9">No Umpire Found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="leagueModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" id="assignform" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign League</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" >
                        <div class="row" id="output"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        $('.assignLeague').click(function(e) {
            e.preventDefault();
            var formurl = $(this).attr('href');
            var id = $(this).data('id');
            $.ajax({
                url: '{{ url('admin/get_leaguelist') }}' + '/' + id,
                success: function(res) {
                    $('#assignform').attr('action', formurl);
                    $('#output').html(res);
                    $('#leagueModal').modal('show');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.openInNewWindow').click(function(event) {
                event.preventDefault();
                var link = $(this).attr('href');
                var urlToOpen = '{{ url('umpire') }}';
                var id = $(this).data('id');
                $.ajax({
                    url: link,
                    success: function(res) {
                        window.open(urlToOpen, '_blank', 'width=600,height=400');
                    }
                });

            });
        });
    </script>
@endsection
