@extends('general.layouts.main')
@section('main-container')
    <main>
        <div class="home-banner addpage">
            <div class="banner-tecxts">
                <h1 class="banner-title"><span>Products </span> Central</h1>
                <div class="texr-banne">The first scheduling solution exclusively for Baseball
                </div>
            </div>

            <div class="overkaycolor"></div>
            <div class="overkaycolor2"></div>
        </div>
        <div class="logo">
            <div class="logo-uc">
                <img src="{{ asset('storage/frontend/image/uc-logo.png') }}" alt="">
            </div>
        </div>

        <div class="container">
            <div class="twobuttondiv">
                <div class="button1">
                    <a href="https://demo.umpirecentral.com/demo-league"class="buton-signup">Demo Admin</a>
                </div>
                <div class="button1">
                    <a href="https://demo.umpirecentral.com/demo-umpire"class="buton-signup">Demo Umpire</a>
                </div>
            </div>


            <div class="accordioan-divs">

                @foreach ($faqs as $faq)
                <div class="accordion-section">
                    <div class="accordion-title">
                        {{ getFAQ($faq->section, 'question') }} <i class="fa-solid fa-caret-down"></i>
                    </div>
                    <div class="accordion-content" style="display: none">
                        {!! getFAQ($faq->section, 'answer') !!}
                    </div>
                </div>
                @endforeach



            </div>

            <div class="pagecakge">
                <h2 class="section-1s text-center mbasc">Packages</h2>
                <div class="packages-div">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="single-packages">
                                <div class="single-packagesas">
                                    <div class="pageskages-title">
                                        <h3 class="">Limited Features</h3>
                                        <div class="subheading">
                                            (Coming Soon)
                                        </div>
                                    </div>
                                </div>
                                <div class="text-editer-div">
                                    <ul>
                                        <li>Limited Features</li>
                                        <li>Limited Features</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="single-packages">
                                <div class="single-packagesas">
                                    <div class="pageskages-title">
                                        <h3 class="">Full Features</h3>
                                        <div class="subheading">
                                            (Coming Soon)
                                        </div>
                                    </div>
                                </div>

                                <div class="text-editer-div">
                                    <ul>
                                        <li>Limited Features</li>
                                        <li>Limited Features</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="single-packages">
                                <div class="single-packagesas">
                                    <div class="pageskages-title">
                                        <h3 class="">Tournament Features</h3>
                                        <div class="subheading">
                                            (Coming Soon)
                                        </div>
                                    </div>
                                </div>

                                <div class="text-editer-div">
                                    <ul>
                                        <li>Limited Features</li>
                                        <li>Limited Features</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="contact-frm-ad-page">

            <div class="container">
                <div class="contact-div-container">
                    <h2 class="section-1s text-center ">Packages</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="foms-input">
                                <label for="" class="inputlabel">Name <span>*</span></label>
                                <input type="text" class="cutominput" name="zipcode" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="foms-input">
                                <label for="" class="inputlabel">League Name <span>*</span></label>
                                <input type="text" class="cutominput" name="zipcode" >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="foms-input">
                                <label for="" class="inputlabel">Email <span>*</span></label>
                                <input type="email" class="cutominput" name="zipcode" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="foms-input">
                                <label for="" class="inputlabel">Phone <span class="option ">(Optional)</span></label>
                                <input type="tel" class="cutominput" name="zipcode" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="foms-input">
                                <label for="" class="inputlabel">Message/Notes</label>
                               <textarea name="" class="cutominput text-a4rea" id="" ></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="conas-recap">
                                <img src="{{ asset('storage/frontend/image/recapcha.png') }}" class="recaptwecg" alt="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="tehst-buton-submitfonm">

                                <button type="submit" class="buton-signup">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </main>
    <script>
        $(document).ready(function() {
            $('.accordion-title').click(function() {
                $('.accordion-content').not($(this).next('.accordion-content')).slideUp();
                $('.accordion-title').not($(this)).removeClass('open').addClass('close');
                $('.accordion-title i').not($(this).find('i')).removeClass('fa-caret-up').addClass(
                    'fa-caret-down');
                $(this).next('.accordion-content').slideToggle();
                $(this).find('i').toggleClass('fa-caret-down fa-caret-up');
                $(this).toggleClass('open close');
            });
        });


        const editorDivs = document.querySelectorAll('.text-editer-div');


        editorDivs.forEach(div => {
            const lastChild = div.lastElementChild;
            t
            if (lastChild) {
                lastChild.style.marginBottom = '0';
            }
        });
    </script>
@endsection
