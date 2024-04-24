@extends('general.layouts.main')
@section('main-container')
    <style>
        .reset-pw {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            padding: 50px;
            border: 1px solid #ce8789;
        }
    </style>
    <main>
        <div class="banner-inner" style="background-image: url({{ asset('storage/frontend/image/emailvar.jpg') }})">

            <div class="innerbanner-conatne container">
                <div class="innerbannerss">
                    <h1 class="banner-title innerbanner-title"><span>Reset</span> Password</h1>
                    <div class="texr-banne">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Praesentium nemo
                        temporibus natus asperiores doloribus facere iste tenetur culpa reiciendis animi.</div>
                </div>

            </div>
        </div>
        <div class="logo innerpage">
            <div class="logo-uc">
                <img src="{{ asset('storage/frontend/image/uc-logo.png') }}" alt="">
            </div>
        </div>
        <div class="sec-2-inner">


            <div class="container">
                <div class="reset-pw">
                    <h3 class="subtitle">Umpire Central</h3>
                    <h2 class="section-1s">Reset Password</h2>
                    <form action="{{ url('reset-password/' . $id) }}" method="POST">
                        @csrf
                        <div class="foms-input">
                            <label class="inputlabel" for="password">Password <span>*</span></label>
                            <input type="password" id="password" class="cutominput" required name="password">
                            <span class="show-password-icon"
                                onclick="togglePasswordVisibility('password', 'passwordToggleIcon')">
                                <i class="fas fa-eye" id="passwordToggleIcon"></i>
                            </span>
                            <br>
                            <span class="error-message" id="passwordError"></span>
                        </div>
                        <div class="foms-input">

                            <label class="inputlabel" for="confirmPassword">Confirm Password <span>*</span></label>
                            <input type="password" id="confirmPassword" class="cutominput" disabled required
                                name="password_confirmation">
                            <span class="show-password-icon"
                                onclick="togglePasswordVisibility('confirmPassword', 'confirmPasswordToggleIcon')">
                                <i class="fas fa-eye" id="confirmPasswordToggleIcon"></i>
                            </span>
                            <br>
                            <span class="error-message" id="confirmPasswordError"></span>

                        </div>
                        <div class="verifu">
                            <div class="tes">
                                <button id="submitButton" class="buton-signup">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <script>
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('confirmPassword');
            const passwordError = document.getElementById('passwordError');
            const confirmPasswordError = document.getElementById('confirmPasswordError');
            const submitButton = document.getElementById('submitButton');

            function checkPasswordsMatchAndValidate() {
                const password = passwordField.value;
                const confirmPassword = confirmPasswordField.value;

                if (password !== confirmPassword) {
                    confirmPasswordError.textContent = "Passwords do not match";
                    confirmPasswordError.classList.add('error-text');
                    confirmPasswordError.classList.remove('success-text');
                    submitButton.setAttribute('disabled', 'disabled');
                    confirmPasswordField.setAttribute('disabled', 'disabled'); // Disable the Confirm Password field
                } else {
                    confirmPasswordError.textContent = "Passwords match!";
                    confirmPasswordError.classList.remove('error-text');
                    confirmPasswordError.classList.add('success-text');
                    enableSubmitButton();
                }

                const uppercaseRegex = /[A-Z]/;
                const numberRegex = /[0-9]/;
                const specialCharRegex = /[!@#$%^&*()_+[\]{};':"\\|,.<>/?]+/;

                if (
                    password.length >= 8 &&
                    uppercaseRegex.test(password) &&
                    numberRegex.test(password) &&
                    specialCharRegex.test(password)
                ) {
                    passwordError.textContent = "Password is Strong!";
                    passwordError.classList.remove('error-text');
                    passwordError.classList.add('success-text');
                    confirmPasswordField.removeAttribute('disabled'); // Enable the Confirm Password field
                    enableSubmitButton();
                } else {
                    passwordError.textContent =
                        "Password must have at least 8 characters, one uppercase letter (ABCD...), one number (123456789), and one special character (@$!%*?&#).";
                    passwordError.classList.add('error-text');
                    passwordError.classList.remove('success-text');
                    submitButton.setAttribute('disabled', 'disabled');
                    confirmPasswordField.setAttribute('disabled', 'disabled'); // Disable the Confirm Password field
                }
            }

            function enableSubmitButton() {
                if (passwordError.classList.contains('success-text') && confirmPasswordError.classList.contains(
                        'success-text')) {
                    submitButton.removeAttribute('disabled');
                }
            }

            passwordField.addEventListener('input', checkPasswordsMatchAndValidate);
            confirmPasswordField.addEventListener('input', checkPasswordsMatchAndValidate);

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
        </script>

    </main>
@endsection
