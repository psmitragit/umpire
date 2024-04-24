@extends('admin.layouts.main')
@section('main-container')
    <div class="content-wrapper">
        <div class="alert alert-danger" style="display: none;" id="errors"></div>
        <form action="{{ url('change-password/' . $admin_data->uid) }}" class="width-customed form manuis" method="POST">
            <h2 class="text-center">Change Password</h2>
            @csrf
            <div class="form-grp">
                <label for="">Current Password <span class="text-danger">*</span></label>
                <input type="password" class="textname" id="passwordInput" name="old_password" required>
                <span class="seepws toggle-span"><i class="fa-solid fa-lock" id="showPasswordIcon"></i></span>
            </div>

            <hr class="customhr">
            <div class="password-container">
                <label for="">Enter New Password <span class="text-danger">*</span></label>
                <input type="password" class="textname" id="password1" class="password-input" name="password" required>

                <span class="toggle-span">
                    <i id="toggleSpan1" class="fas fa-eye"></i>
                </span>
            </div>

            <div class="password-container">
                <label for="">Re-Enter New Password <span class="text-danger">*</span></label>
                <input type="password" class="textname" id="password2" class="password-input" name="password_confirmation"
                    required>

                <span class="toggle-span">
                    <i id="toggleSpan2" class="fas fa-eye"></i>
                </span>
            </div>
            <div class="ascwvs"><button class="btn btn-primary" type="submit">Submit</button></div>
        </form>
    </div>
    <script>
        const passwordInput = document.getElementById('passwordInput');
        const showPasswordIcon = document.getElementById('showPasswordIcon');

        showPasswordIcon.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showPasswordIcon.classList.remove('fa-lock');
                showPasswordIcon.classList.add('fa-lock-open');
            } else {
                passwordInput.type = 'password';
                showPasswordIcon.classList.add('fa-lock');
                showPasswordIcon.classList.remove('fa-lock-open');
            }
        });


        const password1 = document.getElementById('password1')
        const toggleSpan1 = document.getElementById('toggleSpan1')
        const password2 = document.getElementById('password2')
        const toggleSpan2 = document.getElementById('toggleSpan2')

        toggleSpan2.addEventListener("click", function() {
            if (password2.type == 'password') {
                password2.type = 'text';
                toggleSpan2.classList.add('fa-eye-slash')
                toggleSpan2.classList.remove('fa-eye')

            } else {
                password2.type = 'password';
                toggleSpan2.classList.remove('fa-eye-slash')
                toggleSpan2.classList.add('fa-eye')
            }
        })


        toggleSpan1.addEventListener("click", function() {
            if (password1.type == 'password') {
                password1.type = 'text';
                toggleSpan1.classList.add('fa-eye-slash')
                toggleSpan1.classList.remove('fa-eye')

            } else {
                password1.type = 'password';
                toggleSpan1.classList.remove('fa-eye-slash')
                toggleSpan1.classList.add('fa-eye')
            }
        })
    </script>
    <script>
        $(document).ready(function(e) {
            $('.form').on('submit', (function(e) {
                e.preventDefault();
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
                            toastr.success('Success');
                            setTimeout(() => {
                                window.location.replace(
                                    '{{ url('admin/logout') }}');
                            }, 2000);
                        } else {
                            toastr.error(res.message);
                        }
                    },
                    error: function(response) {
                        toastr.error('Something went wrong..!!');
                    }
                });
            }));
        });
    </script>
@endsection
