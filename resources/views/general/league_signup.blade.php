@extends('general.layouts.main')
@section('main-container')
    <main>
        <div class="banner-inner" style="background-image: url()">
            <img src="{{ asset('storage/frontend/image/creat-acc.jpg') }}" class="w-100 phoneclass" alt="">
            <div class="innerbanner-conatne container">
                <div class="innerbannerss">
                    <h1 class="banner-title innerbanner-title"><span>League</span> Owner</h1>
                    <div class="texr-banne">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Praesentium nemo
                        temporibus natus asperiores doloribus facere iste tenetur culpa reiciendis animi.</div>
                </div>

            </div>
        </div>
        <div class="logo innerpage">
            <a href="{{ url('/') }}" class="logo-uc">
                <img src="{{ asset('storage/frontend/image/uc-logo.png') }}" alt="">
            </a>
        </div>


        <div class="sec-2-inner">


            <div class="container new-container">


                <div class="email-otp-boxsc">
                    <div class="heafres">
                        <h3 class="subtitle">League Owner</h3>
                        <h2 class="section-1s">Create an account</h2>


                        <div class="texts-sec2 verify">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                            incididunt ut labore et dolore magna aliqua.
                        </div>
                    </div>
                    <form class="account-crt-frm" action="{{ url('league-signup') }}" method="POST">
                        @csrf
                        <div class="foms-input">
                            <label for="" class="inputlabel">League Name<span>*</span></label>
                            <input type="text" class="cutominput" name="leaguename">
                        </div>


                        <div class="foms-input">
                            <label for="" class="inputlabel">League Owner Name <span class="dif-color">(Your
                                    Name)</span><span>*</span></label>
                            <input type="text" class="cutominput" name="name">
                        </div>

                        <div class="foms-input">
                            <label for="" class="inputlabel">Phone No.</label>
                            <input type="tel" class="cutominput" name="phone">
                        </div>


                        <div class="foms-input">
                            <!-- <label for="" class="inputlabel">Email<span>*</span> </label>
                                    <input type="email" class="cutominput"> -->

                            <label for="" class="inputlabel">Email<span>*</span> </label>
                            <input value="{{ $email }}" disabled type="email" class="cutominput" id="emailInput" name="">
                            <input type="hidden" name="email" value="{{ $email }}">
                            <span class="error-message" id="emailError"></span>
                        </div>



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
                        <div class="agreements-s">
                            <input value="1" type="checkbox" name="terms_and_privacy" id="tandp" required>
                            <label for="tandp">I Agree to <a target="_blank" href="{{ url('privacy-policy') }}">Privacy Policy</a> and <a target="_blank" href="{{ url('terms-of-use') }}">Terms of Uses</a></label>
                            <span class="error-message" id="tandperror"></span>
                        </div>
                        <div class="alert alert-danger" style="display: none;" id="errors"></div>

                        <div class="verifu pt-0 mart-top">
                            <div class="submitbtn"> <button type="submit" id="submitButton" class="buton-signup noshadow"
                                    disabled>Create my Account</button></div>
                            <div class="emnails-tetx">
                                Already have an account? <a href="{{ url('league-login') }}" class="resend">Sign In</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script>
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('confirmPassword');
        const passwordError = document.getElementById('passwordError');
        const confirmPasswordError = document.getElementById('confirmPasswordError');
        const submitButton = document.getElementById('submitButton');

        function checkPasswordsMatchAndValidate(checkStatus) {
            
            
            const password = passwordField.value;
            const confirmPassword = confirmPasswordField.value;

            if (password !== confirmPassword) {
                confirmPasswordError.textContent = "Passwords do not match";
                confirmPasswordError.classList.add('error-text');
                confirmPasswordError.classList.remove('success-text');
                submitButton.setAttribute('disabled', 'disabled');
                if(!checkStatus){
                confirmPasswordField.setAttribute('disabled', 'disabled'); // Disable the Confirm Password field
                }
            } else {
                confirmPasswordError.textContent = "Passwords match!";
                confirmPasswordError.classList.remove('error-text');
                confirmPasswordError.classList.add('success-text');
                enableSubmitButton();
            }

            const uppercaseRegex = /[A-Z]/;
            const numberRegex = /[0-9]/;
            const specialCharRegex = /[!@#$%^&*()_+[\]{};':"\\|,.<>/?]+/;
            
            
if(!checkStatus){
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
        }

        function enableSubmitButton() {
            if (passwordError.classList.contains('success-text') && confirmPasswordError.classList.contains(
                    'success-text')) {
                submitButton.removeAttribute('disabled');
            }
        }

        
             passwordField.addEventListener('input', (event) => {
    checkPasswordsMatchAndValidate(false);
});
        confirmPasswordField.addEventListener('input', (event) => {
    checkPasswordsMatchAndValidate(true);
});

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
                emailError.textContent = "Valid email!";
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
    <script>
        $(document).ready(function(e) {
            $('form').on('submit', (function(e) {
                e.preventDefault();
                var url = '{{ url('/league-login') }}';
                $.ajax({
                    url: $(this).attr('action'),
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: new FormData(this),
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 1) {
                            window.location.replace(url);
                        } else {
                            $('#errors').show();
                            $('#errors').html(res.errors);
                        }

                    },
                    error: function(response) {

                        if (response.status === 422) {
                            var errorString = '';
                            var errors = response.responseJSON.errors;
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    var errorMessage = errors[key][0];
                                    errorString += '<p>' + errorMessage + '</p>';
                                }
                                $('#errors').show().delay(5000).hide(0);
                                $('#errors').html(errorString);
                            }
                        }
                    }
                });
            }));
        });
    </script>
@endsection
