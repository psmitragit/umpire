@extends('general.layouts.main')
@section('main-container')
    <main>
        <div class="banner-inner" style="background-image: url({{ asset('storage/frontend/image/emailvar.jpg') }})">

            <div class="innerbanner-conatne container">
                <div class="innerbannerss">
                    <h1 class="banner-title innerbanner-title"><span>Umpire</span> Join Now</h1>
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
                <div class="email-otp-box">
                    <h3 class="subtitle">Umpire Central</h3>
                    <h2 class="section-1s">Verify your Email</h2>
                    <div class="texts-sec2 verify">
                        Check your email id for <strong>6</strong> digit verification code and enter below
                    </div>
                    <form action="{{ url('verify-otp/' . $id) }}" method="POST">
                        @csrf
                        <div class="OPT-inputs">
                            <input type="text" class="otp-input" maxlength="1"
                                oninput="moveToNextOrPrevious(this, 'box2', 'box1')" id="box1" name="otp[]" />
                            <input type="text" class="otp-input" maxlength="1"
                                oninput="moveToNextOrPrevious(this, 'box3', 'box2')" id="box2" name="otp[]" />
                            <input type="text" class="otp-input" maxlength="1"
                                oninput="moveToNextOrPrevious(this, 'box4', 'box3')" id="box3" name="otp[]" />
                            <input type="text" class="otp-input" maxlength="1"
                                oninput="moveToNextOrPrevious(this, 'box5', 'box4')" id="box4" name="otp[]" />
                            <input type="text" class="otp-input" maxlength="1"
                                oninput="moveToNextOrPrevious(this, 'box6', 'box5')" id="box5" name="otp[]" />
                            <input type="text" class="otp-input" maxlength="1"
                                oninput="moveToNextOrPrevious(this, 'box6', 'box5')" id="box6" name="otp[]" />
                        </div>
                        <div class="verifu">
                            <div class="tes">
                                <button class="buton-signup">Verify</button>
                            </div>
                            <div class="emnails-tetx">
                                <a href="{{ url('resend-otp/' . $id) }}" class="resend">Resend verification code</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>


    </main>


    <script>
        // JavaScript function to move focus to the next or previous input box
        function moveToNextOrPrevious(input, nextBoxId, previousBoxId) {
            if (input.value.length === 1) {
                document.getElementById(nextBoxId).focus();
            } else if (input.value.length === 0) {
                document.getElementById(previousBoxId).focus();
            }
        }

        // Handle pasting into the first input box
        document.getElementById('box1').addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedText = e.clipboardData.getData('text');
            for (let i = 0; i < pastedText.length; i++) {
                if (i < 6) {
                    document.getElementById(`box${i + 1}`).value = pastedText.charAt(i);
                    if (i < 5) {
                        document.getElementById(`box${i + 2}`).focus();
                    }
                }
            }
        });
    </script>
@endsection
