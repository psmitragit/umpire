@extends('general.layouts.main')
@section('main-container')
    <main>
        <div class="banner-inner" style="background-image: url()">
            <img src="{{ asset('storage/frontend/image/emailvar.jpg') }}" class="w-100 phoneclass" alt="">
            <div class="innerbanner-conatne container">
                <div class="innerbannerss">
                    <h1 class="banner-title innerbanner-title"><span>Privacy </span> Policy</h1>
                    {{-- <div class="texr-banne">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Praesentium nemo
                temporibus natus asperiores doloribus facere iste tenetur culpa reiciendis animi.</div> --}}
                </div>

            </div>
        </div>
        <div class="logo innerpage">
            <a href="{{ url('/') }}" class="logo-uc">
                <img src="{{ asset('storage/frontend/image/uc-logo.png') }}" alt="">
            </a>
        </div>
        <div class="sec-2-inner hsab">
            <div class="container">
                <div style="color: #7b8085; " class="lastupdast">
                    <strong>
                        Last updated: 12/4/23
                    </strong>
                </div>
                <h2 style="color: #7b8085; " class="cah">
                    <strong>
                        Contact Information
                    </strong>
                </h2>
                <div style="color: #7b8085;" class="hen-text">
                    <strong>
                        Owner: Blaize Berry
                    </strong>
                </div>
                <div style="color: #7b8085;" class="hen-text">
                    <strong>
                        Email: <a href="mailto:support@umpirecentral.com">support@umpirecentral.com</a> 
                    </strong>
                </div>
                <div class="texts-sec2">
                   <h4> Introduction:</h4>
                    <p class="mb-4">Welcome to Umpire Central. We respect your privacy and are committed to protecting
                        your personal
                        data.
                        This
                        Privacy Policy will inform you as to how we look after your personal data when you visit our website
                        and
                        tell you about your privacy rights and how the law protects you.
                    </p>


                    <h4> 1. Data Collection:</h4>
                    <p class="mb-4"> We collect the following personal information from you:

                        For Umpire Sign-up: Name, email, phone number (optional), date of birth, zip code, and password
                        creation.
                        For League Owner Sign-up: League name, legal name of the league owner, phone number (optional),
                        email,
                        and
                        password creation.
                        Additional Information for Umpires: Days/hours of availability, and a personal bio for display
                        purposes.
                    </p>
                    <h4> 2. Use of Personal Data:</h4>
                    <p class="mb-4">  The information we collect is primarily used to facilitate the scheduling process and to communicate
                    with
                    our users to provide our services. The personal bios provided by umpires are used to aid league
                    administrators in selecting appropriate officials for their games.
                </p>
                <h4>  3. Data Sharing and Disclosure:</h4>
                    <p class="mb-4">  Currently, we do not share any user data with third parties.</p>

                    <h4>  4. User Rights:</h4>
                    <p class="mb-4">   At this time, we do not have a mechanism for users to delete their data or accounts. We will inform our
                    users if these options become available.
                    </p>
                    <h4> 5. Data Retention:</h4>
                    <p class="mb-4">   We retain personal data for as long as your account is active or as needed to provide you services. If
                    the
                    site is permanently taken offline, we will dispose of your data securely.</p>

                    <h4> 6. Data Security:</h4>
                    <p class="mb-4">   We implement standard industry practices to protect your personal data from unauthorized access, use, or
                    disclosure, including secure password hashing.</p>

                    <h4>   7. International Data Transfers:</h4>
                    <p class="mb-4">  As we are based solely in the USA, we do not transfer data internationally.</p>

                    8. Third-Party Services:</h3>
                    <p class="mb-4">  We utilize the Google Maps API, which does not grant Google access to personal data from our users
                    through
                    our service.</p>

                    <h4>   9. Cookies and Tracking Technologies:</h4>
                    <p class="mb-4">  We use cookies solely for the purpose of auto-login to our site.</p>

                    <h4>   10. Children’s Privacy:</h4>
                    <p class="mb-4">  Our service is not intended for children under the age of 13, and we do not knowingly collect data from
                    children.</p>

                    <h4>   11. Changes to This Privacy Policy:</h4>
                    <p class="mb-4">    We may update this policy from time to time. The latest version will always be posted on this page. If
                    we
                    make significant changes, we will notify you by email.</p>

                    <h4>   12. Contact Us:</h4>
                    <p class="mb-4">   For any questions or concerns regarding your privacy, please contact us at <a href="mailto:support@umpirecentral.com">support@umpirecentral.com</a>.
                    </p>
                </div>
            </div>
        </div>
    </main>
@endsection
