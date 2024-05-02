<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
	<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
		<div class="me-3">
			<button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
				<span class="icon-menu"></span>
			</button>
		</div>
		<div>
			<a class="navbar-brand brand-logo">
				<img src="{{ asset('storage/images/user.png') }}" alt="logo" />
			</a>
			<a class="navbar-brand brand-logo-mini">
				<img src="{{ asset('storage/images/user.png') }}" alt="logo" />
			</a>
		</div>
	</div>
	<div class="navbar-menu-wrapper d-flex align-items-top">
		<ul class="navbar-nav">
			<li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
				<h1 class="welcome-text">Welcome, <span class="text-black fw-bold">Super Admin</span></h1>
			</li>
            <li style="font-size: 20px;margin-left:50px;" class="nav-item font-weight-semibold d-none d-lg-block">
				<div class="form-check form-switch" style="margin-left: 50px!important">
                    @php
                        $feedBackStatus = (int)getMetaValue('SHOW_FEEDBACK_OPTION');
                    @endphp
                    <input onchange="window.location.replace('{{ url('admin/toggle-feedback-option') }}')" class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" {{ $feedBackStatus == 1 ? 'checked' : '' }}>
                    <label style="font-size: 20px; margin-left: 10px;" class="form-check-label" for="flexSwitchCheckChecked">Feedback option</label>
                  </div>
			</li>
		</ul>
		<ul class="navbar-nav ms-auto">

			<li class="nav-item dropdown d-none d-lg-block user-dropdown">
				<a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
					<img class="img-xs rounded-circle" src="{{ asset('storage/images/user.png') }}" alt="Profile image"> </a>
				<div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
					<div class="dropdown-header text-center">
						<img class="img-xs rounded-circle" src="{{ asset('storage/images/user.png') }}" alt="Profile image">
						<p class="mb-1 mt-3 font-weight-semibold">Super Admin</p>
						<p class="fw-light text-muted mb-0">{{ $admin_data->email }}</p>
					</div>
					<a class="dropdown-item" href="{{ url('admin/logout') }}"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
				</div>
			</li>
		</ul>
		<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
			<span class="mdi mdi-menu"></span>
		</button>
	</div>
</nav>
