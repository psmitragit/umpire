@extends('general.layouts.main')
@section('main-container')
    <style>
        body {
            background: #fafafd;
        }
    </style>
    <main>
        <div class="container-fluid">
            <div class="loginscreen">
                <div class="row flex-wrap-reverse">
                    <div class="col-md-6 px-0">
                        <div class="matginsyp">
                            <div class="loinglogo">
                                <a href="{{ url('/') }}" class="logo-index"><img
                                        src="{{ asset('storage/frontend/image/logo-login.png') }}" alt=""></a>
                            </div>
                            <h3 class="subtitle">League Owner</h3>
                            <h2 class="section-1s">Login here</h2>


                            <div class="texts-sec2 verify">
                                Welcome back ! Please enter your details
                            </div>

                            <form action="{{ url('league-login') }}" method="POST">
                                @csrf
                                <div class="foms-input">
                                    <!-- <label for="" class="inputlabel">Email<span>*</span> </label>
                                                <input type="email" class="cutominput"> -->

                                    <label for="" class="inputlabel">Email<span>*</span> </label>
                                    <input type="email" class="cutominput" id="emailInput" name="username">
                                    @if (session('error_message_uname'))
                                        <span class="text-danger">{{ session('error_message_uname') }}</span>
                                    @endif
                                    <span class="error-message" id="emailError"></span>
                                </div>

                                <div class="foms-input">
                                    <label class="inputlabel" for="password">Password <span>*</span></label>
                                    <input type="password" id="password" class="cutominput" required name="password">
                                    @if (session('error_message_psw'))
                                        <span class="text-danger">{{ session('error_message_psw') }}</span>
                                    @endif
                                    <span class="show-password-icon"
                                        onclick="togglePasswordVisibility('password', 'passwordToggleIcon')">
                                        <i class="fas fa-eye" id="passwordToggleIcon"></i>
                                    </span>
                                </div>


                                <div class="verifu pt-0  mb-50px mart-top">
                                    <div class="submitbtn">
                                        <button type="submit" id="submitButtons"
                                            class="buton-signup noshadow">Login</button>
                                    </div>
                                    <div class="emnails-tetx">
                                        <a href="javascript:void(0)" onclick="$('#forgetPasswordModel').modal('show')"
                                            class="resend">Forget Password?</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="col-md-6 px-0">
                        <img class="fulling" src="{{ asset('storage/frontend/image/leaguadmin-login.jpg') }}"
                            alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="forgetPasswordModel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
                <form action="{{ url('forget-password') }}" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitleId">
                                Forget Password
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <input required type="email" class="form-control" name="email" placeholder="Email">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Get Reset Link
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            var input = document.getElementById(inputId);
            var icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        const emailInput = document.getElementById('emailInput');
        const emailError = document.getElementById('emailError');

        function validateEmail() {
            const email = emailInput.value;
            const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

            if (emailRegex.test(email)) {
                emailError.textContent = "";
                emailError.classList.remove('error-text');
                emailError.classList.add('success-text');
            } else {
                emailError.textContent = "Invalid email format.";
                emailError.classList.add('error-text');
                emailError.classList.remove('success-text');
            }
        }

        emailInput.addEventListener('input', validateEmail);
    </script>
@endsection
