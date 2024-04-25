@extends('admin.layouts.main')
@section('main-container')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center ">
                            <div class="col-md-3">
                                <h4 class="card-title">{{ $title }}</h4>
                            </div>
                            <div class="col-md-9 ">
                                <form class="d-flex align-baseline  justify-content-end" style="align-items: end;"
                                    action="{{ url('admin/sent_invite_league_admin') }}" method="post">
                                    @csrf
                                    <div class="">
                                        <input required type="text" name="email" class="form-control widths"
                                            placeholder="invite@league.com">
                                    </div>
                                    <select class="form-control widths" name="leagueid" id="" required>
                                        <option value="">Select</option>
                                        @if (!$leagues->isEmpty())
                                            @foreach ($leagues as $league)
                                                <option value="{{ $league->leagueid }}">{{ $league->leaguename }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="ms-2 ">
                                        <button type="submit" class="btn btn-success">Invite league admin</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>League Name</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$page_data->isEmpty())
                                        @foreach ($page_data as $data)
                                            <tr>
                                                <td>{{ $data->email }}</td>
                                                <td>{{ $data->league->leaguename }} </td>
                                                <td class="text-end">
                                                    <a href="{{ url('admin/login-as-league-admin/' . $data->uid) }}"
                                                        class="btn btn-dark openInNewWindow">Login</a>
                                                    @if ($data->status == 0)
                                                        <a href="{{ url('admin/league_admin_status/' . $data->uid . '/1') }}"
                                                            class="btn btn-success"
                                                            onclick="return confirm('Are you sure you want to activate this league admin?')">Activate
                                                        </a>
                                                    @elseif ($data->status == 1)
                                                        <a href="{{ url('admin/league_admin_status/' . $data->uid . '/0') }}"
                                                            class="btn btn-danger"
                                                            onclick="return confirm('Are you sure you want to deactivate this league admin?')">Deactivate</a>
                                                    @endif
                                                    <a href="{{ url('admin/delete-league-admin/' . $data->uid) }}"
                                                        class="btn btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.openInNewWindow').click(function(event) {
                event.preventDefault();
                var link = $(this).attr('href');
                var urlToOpen = '{{ url('league') }}';
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
