@extends('admin.layouts.main')
@section('main-container')
    <style>
        body {
            background: #f6f9f3 !important;
            font-family: 'Manrope', sans-serif;
        }
        .loginh2s {
  margin-bottom: 20px;
}
    </style>
    <div class=" container">
        <div class="loginfields">
            <div class="">
                <div class="">
                    @error('loginError')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
<div class="logo-login">
    <img src="{{ asset('storage/images/logo-login.png') }}" alt="">
</div>
                    <h4 class="logintext">Super Admin</h4>
                    <h2 class="loginh2s">Login to Your Account</h2>
                    <form class="forms-sample" action="{{ url('admin/login') }}" method="post">
                        @csrf
                        <div class="texts">
                            <label class="fomtc-name" for="exampleInputUsername1">Username</label>
                            <input name="username" type="text" class="input-nmae" id="exampleInputUsername1">
                        </div>
                        <div class="texts">
                            <label class="fomtc-name" for="exampleInputPassword1">Password</label>
                            <input name="password" type="password" class="input-nmae" id="exampleInputPassword1">
                        </div>
                        <div class="text-center">
                            <button name="login" type="submit" class="submit-btn ">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="fix-footer">
        <div class="text-divs">
            © Umpire Central {{ date('Y') }}
        </div>
    </div>
@endsection
