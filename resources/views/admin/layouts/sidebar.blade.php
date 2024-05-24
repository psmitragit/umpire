<div class="container-fluid page-body-wrapper">
    <style>
        .sidebar .nav.sub-menu .nav-item::before {
            content: '';
            width: 0px;
            height: 0px;
        }

        .form-control.widths {
            width: 177px;
            margin-left: 14px;
            height: 32px;
        }

        #sidebar {
            width: 248px;
            padding-right: 22px;
        }

        .sidebar .nav .nav-item.active>.nav-link i.menu-arrow::before {
            content: "\F142";
        }

        .selectd {
            width: auto;
            height: 32px !important;
            background: #fff;
            border: 1px solid #00000017 !important;
            border-radius: 5px;
            padding: 0px 9px !important;
        }

        .ascca select {
            width: auto;
            height: 32px !important;
            background: #fff;
            border: 1px solid #00000017 !important;
            border-radius: 5px;
            padding: 0px 9px !important;
        }

        .mian-foir {
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
            margin-top: 34px;
        }

        .masc-table {
            margin-top: 40px;
        }

        .masc-table td {
            padding-left: 0 !important;
            padding-right: 0px;
        }

        .delet-btn.btn_remove {
            padding: 10px 15px !important;
            background: red;
            border: none;
            color: #fff;
            border-radius: 5px;
            font-weight: bold;
        }

        .width-customed.form.manuis {
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 50px;
        }

        .form-grp,
        .password-container {
            position: relative;
        }

        .form-grp input,
        .password-container input {
            width: 100%;
            background: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }

        .seepws.toggle-span,
        .toggle-span {
            position: absolute;
            right: 14px;
            bottom: 12px;
            cursor: pointer;
        }

        .customhr {
            margin-top: 35px;
            margin-bottom: 35px;
        }

        .form-grp label,
        .password-container label {
            margin-bottom: 8px;
        }

        .navbar-toggler.align-self-center {
            display: none;
        }

        .ascwvs {
            text-align: center;
            margin-top: 20px;
        }

        .password-container {
            margin-bottom: 12px;
        }

        .main-leagues label {
            margin-left: 5px;
            font-weight: bold;
        }
    </style>
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/admin/league') }}">
                    <i class="fa-solid fa-baseball-bat-ball menu-icon"></i>
                    <span class="menu-title">Manage Leagues</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/admin/admin-list') }}">
                    <i class="fa-solid fa-people-roof menu-icon"></i>
                    <span class="menu-title">Manage League<br> Admins</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/admin/members') }}">
                    <i class="fa-solid fa-users-rectangle menu-icon"></i>
                    <span class="menu-title">Manage Umpires</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false"
                    aria-controls="ui-basic">
                    <i class="fa-solid fa-gear menu-icon "></i>
                    <span class="menu-title">Point Presets</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ url('/admin/add_preset') }}">Add Point
                                Preset</a></li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ url('admin/point-preset/schedule-on-any-game') }}">Schedule on any game</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ url('admin/point-preset/age-of-players') }}">Age of Players</a></li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ url('admin/point-preset/location') }}">Location</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/point-preset/pay') }}">Pay</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/point-preset/time') }}">Time</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/point-preset/day-of-week') }}">Day
                                of Week</a></li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ url('admin/point-preset/umpire-position') }}">Umpire Position</a></li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ url('admin/point-preset/umpire-duration') }}">Umpire Duration</a></li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ url('admin/point-preset/total-game') }}">Total Game</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                @if (env('APP_ENV') !== 'production')
                    <a class="nav-link" href="https://umpirecentral.com/admin/direct-login" target="_blank">
                        <i class="fa fa-globe menu-icon"></i>
                        <span class="menu-title">Live Site</span>
                    </a>
                @else
                    <a class="nav-link" href="https://stg.umpirecentral.com/admin/direct-login" target="_blank">
                        <i class="fa fa-globe menu-icon"></i>
                        <span class="menu-title">Staging Site</span>
                    </a>
                @endif
            </li>
        </ul>
    </nav>
