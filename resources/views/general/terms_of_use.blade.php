@extends('general.layouts.main')
@section('main-container')
<main>
    <div class="banner-inner" style="background-image: url()">
        <img src="{{ asset('storage/frontend/image/emailvar.jpg') }}" class="w-100 phoneclass" alt="">
        <div class="innerbanner-conatne container">
            <div class="innerbannerss">
                <h1 class="banner-title innerbanner-title"><span>terms of </span> use</h1>
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
            {{-- <h2 style="color: #7b8085; " class="cah">
                <strong>
                    Terms of Use for Umpire Central

                </strong>
            </h2> --}}
           
            <div class="texts-sec2">
               <h4>1. Acceptance of Terms</h4>
                <p class="mb-4">By accessing and using the Umpire Central website, you accept and agree to be bound by these Terms of Use and our Privacy Policy.

                </p>

                <h4> 2. Description of Service</h4>
                <p class="mb-4"> Umpire Central provides a platform for scheduling umpires for baseball games, offers a game report feature, and tracks payments owed to umpires by leagues.</p>
                
                <h4> 3. User Accounts</h4>
                <p class="mb-4">
                Users are required to register for an account by providing accurate and complete information. Each user may only create one account. Misuse or fraudulent activity may result in account deactivation.
                </p>
                <h4> 
                4. Privacy Policy</h4>
                <p class="mb-4">
                Please refer to our Privacy Policy for information on how we collect, use, and share your personal information.
                </p>
                <h4>
                5. Prohibited Activities</h4>
                <p class="mb-4">
                Users are prohibited from posting external links on our platform. Such actions may lead to account deactivation without refund.</p>
                <h4> 
                6. Intellectual Property Rights</h4>
                <p class="mb-4">
                We own all intellectual property rights on the Umpire Central website. Unauthorized use may be a violation of these rights.
                </p>
                <h4> 
                7. User Content</h4>
                <p class="mb-4">
                Users are expected to use the Umpire Central platform in a manner that respects the intellectual property rights of others.
                </p>
                <h4> 
                8. Third-Party Links</h4>
                <p class="mb-4">
                Umpire Central is not responsible for the content of any linked third-party websites.
                </p>
                <h4> 
                9. Termination of Use</h4>
                <p class="mb-4">
                If an account is disabled, we retain the right to keep the account data and content.
                </p>
                <h4> 
                10. Disclaimers</h4>
                <p class="mb-4">
                The services of Umpire Central are provided "as is." We disclaim all warranties to the fullest extent permissible by law.
                </p>
                <h4> 
                11. Limitation of Liability</h4>
                <p class="mb-4">
                Umpire Central is not responsible for any financial transactions or obligations between leagues and umpires, including but not limited to payment of fees.
                </p>
                <h4> 
                12. Indemnification</h4>
                <p class="mb-4">
                Users agree to indemnify and hold harmless Umpire Central from any claims resulting from their use of the website.
                </p>
                <h4> 
                13. Governing Law</h4>
                
                <p class="mb-4">
                These Terms of Use shall be governed by the laws of the State of Michigan, USA.
                </p>
                <h4> 
                14. Changes to Terms</h4>
                <p class="mb-4">
                Users will be notified of any changes to these Terms of Use via email.
                </p>
                <h4>
                15. Contact Information</h4>
                <p class="mb-4">
                For questions or concerns about these Terms, please contact us at <a href="mailto:support@umpirecentral.com">support@umpirecentral.com</a>.
                </p>
            </div>
        </div>
    </div>



</main>

@endsection
