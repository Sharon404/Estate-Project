@extends('frontend.layouts.app')
@section('title', 'Offers - GrandStay')
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
                    <div class="col-lg-6 text-center">
                        <div class="subtitle id-color wow fadeInUp mb-2">Enjoy Your Stay</div>
                        <div class="clearfix"></div>
                        <h2 class="fs-60 fs-xs-8vw wow fadeInUp" data-wow-delay=".4s">Offers</h2>
                    </div>
                </div>
            </div>
            <div class="crumb-wrapper">
                <ul class="crumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Offers</li>
                </ul>
            </div>
            <div class="sw-overlay op-8"></div>
        </section>


        <section class="relative lines-deco">
            <div class="container">
                <div class="row g-4">
                    <!-- Item 1 -->
                    <div class="col-lg-4">
                        <div class="overflow-hidden rounded-1">
                            <div class="hover relative">
                                <h3 class="abs bg-color rounded-3 text-white fs-20 lh-1 p-2 px-3 m-4 top-0 start-0 z-3">20% OFF</h3>
                                <img src="{{ asset('assets/frontend/images/offers/1.webp') }}" class="w-100 hover-scale-1-1" alt="">
                                <a href="{{ route('offer.single', ['id' => 1]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>
                            <div class="p-40 bg-dark-2 text-light relative">
                                <a class="text-white" href="{{ route('offers') }}">
                                    <h3>Romantic Stay</h3>
                                    <p>20% Off Weekend Packages</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="col-lg-4">
                        <div class="overflow-hidden rounded-1">
                            <div class="hover relative">
                                <h3 class="abs bg-color rounded-3 text-white fs-20 lh-1 p-2 px-3 m-4 top-0 start-0 z-3">30% OFF</h3>
                                <img src="{{ asset('assets/frontend/images/offers/2.webp') }}" class="w-100 hover-scale-1-1" alt="">
                                <a href="{{ route('offer.single', ['id' => 2]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>
                            <div class="p-40 bg-dark-2 text-light relative">
                                <a class="text-white" href="{{ route('offers') }}">
                                    <h3>Early Bird Deal</h3>
                                    <p>Save Up to 30% on Rooms</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="col-lg-4">
                        <div class="overflow-hidden rounded-1">
                            <div class="hover relative">
                                <img src="{{ asset('assets/frontend/images/offers/3.webp') }}" class="w-100 hover-scale-1-1" alt="">
                                <a href="{{ route('offer.single', ['id' => 3]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>
                            <div class="p-40 bg-dark-2 text-light relative">
                                <a class="text-white" href="{{ route('offers') }}">
                                    <h3>Family Getaway</h3>
                                    <p>Kids Stay & Eat Free</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Item 4 -->
                    <div class="col-lg-4">
                        <div class="overflow-hidden rounded-1">
                            <div class="hover relative">
                                <h3 class="abs bg-color rounded-3 text-white fs-20 lh-1 p-2 px-3 m-4 top-0 start-0 z-3">15% OFF</h3>
                                <img src="{{ asset('assets/frontend/images/offers/4.webp') }}" class="w-100 hover-scale-1-1" alt="">
                                <a href="{{ route('offer.single', ['id' => 4]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>
                            <div class="p-40 bg-dark-2 text-light relative">
                                <a class="text-white" href="{{ route('offers') }}">
                                    <h3>Spa Escape</h3>
                                    <p>Complimentary Spa Session</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Item 5 -->
                    <div class="col-lg-4">
                        <div class="overflow-hidden rounded-1">
                            <div class="hover relative">
                                <img src="{{ asset('assets/frontend/images/offers/5.webp') }}" class="w-100 hover-scale-1-1" alt="">
                                <a href="{{ route('offer.single', ['id' => 5]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>
                            <div class="p-40 bg-dark-2 text-light relative">
                                <a class="text-white" href="{{ route('offers') }}">
                                    <h3>Long Stay Offer</h3>
                                    <p>Save More on 5+ Nights</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Item 6 -->
                    <div class="col-lg-4">
                        <div class="overflow-hidden rounded-1">
                            <div class="hover relative">
                                <img src="{{ asset('assets/frontend/images/offers/6.webp') }}" class="w-100 hover-scale-1-1" alt="">
                                <a href="{{ route('offer.single', ['id' => 6]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>
                            <div class="p-40 bg-dark-2 text-light relative">
                                <a class="text-white" href="{{ route('offers') }}">
                                    <h3>Business Package</h3>
                                    <p>Free Breakfast & Airport Pickup</p>
                                </a>
                            </div>
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