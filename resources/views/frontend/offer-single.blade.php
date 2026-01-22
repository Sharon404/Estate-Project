@extends('frontend.layouts.app')
@section('title', 'Offer Detail - GrandStay')
@section('content')


    <main>

    <main>
        <a href="#" id="back-to-top"></a>
        <!-- page preloader begin -->
        <div id="de-loader"></div>
        <!-- page preloader close -->
        

        <section class="jarallax text-light relative rounded-1 overflow-hidden mt-80 mt-sm-70 mx-2">
            <div class="de-gradient-edge-top"></div>
            <img src="{{ asset('assets/frontend/images/background/1.webp') }}" class="jarallax-img" alt="">
            <div class="container relative z-2">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <div class="subtitle id-color wow fadeInUp mb-2">20% Off Weekend Packages</div>
                        <div class="clearfix"></div>
                        <h2 class="fs-60 fs-xs-8vw wow fadeInUp" data-wow-delay=".4s">Romantic Stay</h2>
                    </div>
                </div>
            </div>
            <div class="crumb-wrapper">
                <ul class="crumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Blog</li>
                </ul>
            </div>
            <div class="sw-overlay op-8"></div>
        </section>

        <section>
            <div class="container">
                <div class="row g-5">

                    <!-- Main Content -->
                    <div class="col-lg-8">
                        <p class="text-muted mb-4">
                            
                        </p>

                        <p>
                            Enjoy a memorable romantic getaway designed for couples seeking comfort,
                            intimacy, and unforgettable moments. Our Romantic Stay package combines
                            elegant accommodation, fine dining, and thoughtful touches to make your
                            weekend truly special.
                        </p>

                        <h3 class="mt-4">What’s Included</h3>
                        <ul class="ul-check">
                            <li class="mb-2">Luxury room with romantic setup</li>
                            <li class="mb-2">Complimentary wine on arrival</li>
                            <li class="mb-2">Candlelight dinner for two</li>
                            <li class="mb-2">Late check-out (subject to availability)</li>
                            <li class="mb-2">Daily breakfast</li>
                        </ul>

                        <h3 class="mt-4">Terms & Conditions</h3>
                        <ul class="ul-check">
                            <li>Valid for weekend stays only</li>
                            <li>Advance reservation required</li>
                            <li>Offer cannot be combined with other promotions</li>
                            <li>Subject to availability</li>
                        </ul>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">

                        <div class="bg-white rounded-1 p-40">
                            <h3 class="mb-4">Book This Offer</h3>

                            <div class="mb-3">
                                <h2 class="mb-0">
                                    20% OFF
                                </h2>
                                Limited time weekend offer
                            </div>

                            <div class="mb-3">
                                <a href="{{ route('reservation') }}" class="btn-main fx-slide w-100"><span>Book Now</span></a>
                                <a href="{{ route('contact') }}" class="btn-main bg-light text-dark fx-slide w-100"><span>Contact Us</span></a>
                            </div>

                            <p class="small text-muted mb-0">
                                Need help? Call us anytime or send an inquiry through our contact page.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        
    </main>

    @push('scripts')
    <!-- Javascript Files
    ================================================== -->
    <script src="{{ asset('assets/frontend/js/vendors.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/designesia.js') }}"></script>

    <!-- swiper slider -->
    <script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/custom-swiper-1.js') }}"></script>
    @endpush

@endsection