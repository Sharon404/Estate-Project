@extends('frontend.layouts.app')
@section('title', 'Rooms Slider - GrandStay')
@section('content')

    <main>
        <a href="#" id="back-to-top"></a>
        <!-- page preloader begin -->
        <div id="de-loader"></div>
        <!-- page preloader close -->
        
        <section class="no-top no-bottom relative rounded-1 overflow-hidden mt-80 mt-sm-50 mx-2 mb-2">
            <div class="mh-800">
                <div class="swiper">
                    <div class="swiper-wrapper">

                        <!-- Slide 1 -->
                        <div class="swiper-slide text-light">
                            <div class="swiper-inner" data-bgimage="url({{ asset('assets/frontend/images/rooms/1.jpg') }})">
                                <div class="sw-caption">
                                    <div class="container">
                                        <div class="row g-5 align-items-center">
                                            <div class="col-lg-5"> 
                                                <div class="sw-text-wrapper wow anim-order-1">
                                                    <h2 class="fs-60 mb-2">Studio</h2>
                                                    <span class="d-stars id-color d-block mb-4">
                                                        <i class="icofont-star"></i><i class="icofont-star"></i>
                                                        <i class="icofont-star"></i><i class="icofont-star"></i>
                                                        <i class="icofont-star"></i>
                                                    </span>
                                                    <p class="lead mb-4 text-white mb-0">
                                                        Compact, comfortable space perfect for solo travelers or couples, with essentials for a relaxing stay.
                                                    </p>
                                                    <div class="d-flex mb-2 fs-18 justify-content-between">
                                                        <div class="d-flex">    
                                                            <div class="d-flex align-items-center me-3">
                                                                <img src="{{ asset('assets/frontend/images/ui/user-light.webp') }}" class="w-15px me-2" alt="">2 guests
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ asset('assets/frontend/images/ui/floorplan-light.webp') }}" class="w-15px me-2" alt="">Private
                                                            </div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <div class="fs-20 fw-bold">KES 25,000</div><span>/night</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <img src="{{ asset('assets/frontend/images/rooms/1.jpg') }}" class="w-100 animated anim-order-3" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sw-overlay op-8"></div>
                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="swiper-slide text-light">
                            <div class="swiper-inner" data-bgimage="url({{ asset('assets/frontend/images/rooms/2.jpg') }})">
                                <div class="sw-caption">
                                    <div class="container">
                                        <div class="row g-5 align-items-center">
                                            <div class="col-lg-5"> 
                                                <div class="sw-text-wrapper wow anim-order-1">
                                                    <h2 class="fs-60 mb-2">Bedsitter</h2>
                                                    <span class="d-stars id-color d-block mb-4">
                                                        <i class="icofont-star"></i><i class="icofont-star"></i>
                                                        <i class="icofont-star"></i><i class="icofont-star"></i>
                                                        <i class="icofont-star"></i>
                                                    </span>
                                                    <p class="lead mb-4 text-white mb-0">
                                                        Cozy self-contained unit with sleeping and living space, ideal for short-term stays with everything you need for comfort.
                                                    </p>
                                                    <div class="d-flex mb-2 fs-18 justify-content-between">
                                                        <div class="d-flex">    
                                                            <div class="d-flex align-items-center me-3">
                                                                <img src="{{ asset('assets/frontend/images/ui/user-light.webp') }}" class="w-15px me-2" alt="">2 guests
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ asset('assets/frontend/images/ui/floorplan-light.webp') }}" class="w-15px me-2" alt="">Private
                                                            </div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <div class="fs-20 fw-bold">KES 25,000</div><span>/night</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <img src="{{ asset('assets/frontend/images/rooms/2.jpg') }}" class="w-100 animated anim-order-3" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sw-overlay op-8"></div>
                            </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="swiper-slide text-light">
                            <div class="swiper-inner" data-bgimage="url({{ asset('assets/frontend/images/rooms/3.jpg') }})">
                                <div class="sw-caption">
                                    <div class="container">
                                        <div class="row g-5 align-items-center">
                                            <div class="col-lg-5"> 
                                                <div class="sw-text-wrapper wow anim-order-1">
                                                    <h2 class="fs-60 mb-2">1 Bedroom House</h2>
                                                    <span class="d-stars id-color d-block mb-4">
                                                        <i class="icofont-star"></i><i class="icofont-star"></i>
                                                        <i class="icofont-star"></i><i class="icofont-star"></i>
                                                        <i class="icofont-star"></i>
                                                    </span>
                                                    <p class="lead mb-4 text-white mb-0">
                                                        Perfect for small families or groups, with separate living and sleeping areas. Privacy and comfort in an entire home.
                                                    </p>
                                                    <div class="d-flex mb-2 fs-18 justify-content-between">
                                                        <div class="d-flex">    
                                                            <div class="d-flex align-items-center me-3">
                                                                <img src="{{ asset('assets/frontend/images/ui/user-light.webp') }}" class="w-15px me-2" alt="">4 guests
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ asset('assets/frontend/images/ui/floorplan-light.webp') }}" class="w-15px me-2" alt="">Entire Home
                                                            </div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <div class="fs-20 fw-bold">KES 25,000</div><span>/night</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <img src="{{ asset('assets/frontend/images/rooms/3.jpg') }}" class="w-100 animated anim-order-3" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sw-overlay op-8"></div>
                            </div>
                        </div>

                        <!-- Slide 4 -->
                        <div class="swiper-slide text-light">
                            <div class="swiper-inner" data-bgimage="url({{ asset('assets/frontend/images/rooms/4.jpg') }})">
                                <div class="sw-caption">
                                    <div class="container">
                                        <div class="row g-5 align-items-center">
                                            <div class="col-lg-5"> 
                                                <div class="sw-text-wrapper wow anim-order-1">
                                                    <h2 class="fs-60 mb-2">2 Bedroom House</h2>
                                                    <span class="d-stars id-color d-block mb-4">
                                                        <i class="icofont-star"></i><i class="icofont-star"></i>
                                                        <i class="icofont-star"></i><i class="icofont-star"></i>
                                                        <i class="icofont-star"></i>
                                                    </span>
                                                    <p class="lead mb-4 text-white mb-0">
                                                        Spacious home perfect for families. Two bedrooms, full amenities, and complete privacy for your entire stay.
                                                    </p>
                                                    <div class="d-flex mb-2 fs-18 justify-content-between">
                                                        <div class="d-flex">    
                                                            <div class="d-flex align-items-center me-3">
                                                                <img src="{{ asset('assets/frontend/images/ui/user-light.webp') }}" class="w-15px me-2" alt="">6 guests
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ asset('assets/frontend/images/ui/floorplan-light.webp') }}" class="w-15px me-2" alt="">Entire Home
                                                            </div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <div class="fs-20 fw-bold">KES 25,000</div><span>/night</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <img src="{{ asset('assets/frontend/images/rooms/4.jpg') }}" class="w-100 animated anim-order-3" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sw-overlay op-8"></div>
                            </div>
                        </div>

                        <!-- Slide 5 -->
                        <div class="swiper-slide text-light">
                            <div class="swiper-inner" data-bgimage="url({{ asset('assets/frontend/images/rooms/5.jpg') }})">
                                <div class="sw-caption">
                                    <div class="container">
                                        <div class="row g-5 align-items-center">
                                            <div class="col-lg-5"> 
                                                <div class="sw-text-wrapper wow anim-order-1">
                                                    <h2 class="fs-60 mb-2">Family Suite</h2>
                                                    <span class="d-stars id-color d-block mb-4">
                                                        <i class="icofont-star"></i><i class="icofont-star"></i>
                                                        <i class="icofont-star"></i><i class="icofont-star"></i>
                                                        <i class="icofont-star"></i>
                                                    </span>
                                                    <p class="lead mb-4 text-white mb-0">
                                                        Spacious and family-friendly suite offering comfort and convenience for a relaxing stay together.
                                                    </p>
                                                    <div class="d-flex mb-2 fs-18 justify-content-between">
                                                        <div class="d-flex">    
                                                            <div class="d-flex align-items-center me-3">
                                                                <img src="{{ asset('assets/frontend/images/ui/user-light.webp') }}" class="w-15px me-2" alt="">4 guests
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ asset('assets/frontend/images/ui/floorplan-light.webp') }}" class="w-15px me-2" alt="">42 ft
                                                            </div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <div class="fs-20 fw-bold">KES 19,900</div><span>/night</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <img src="{{ asset('assets/frontend/images/rooms/5.jpg') }}" class="w-100 animated anim-order-3" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sw-overlay op-8"></div>
                            </div>
                        </div>

                        <!-- Slide 6 -->
                        <div class="swiper-slide text-light">
                            <div class="swiper-inner" data-bgimage="url({{ asset('assets/frontend/images/rooms/6.jpg') }})">
                                <div class="sw-caption">
                                    <div class="container">
                                        <div class="row g-5 align-items-center">
                                            <div class="col-lg-5"> 
                                                <div class="sw-text-wrapper wow anim-order-1">
                                                    <h2 class="fs-60 mb-2">Luxury Suite</h2>
                                                    <span class="d-stars id-color d-block mb-4">
                                                        <i class="icofont-star"></i><i class="icofont-star"></i>
                                                        <i class="icofont-star"></i><i class="icofont-star"></i>
                                                        <i class="icofont-star"></i>
                                                    </span>
                                                    <p class="lead mb-4 text-white mb-0">
                                                        An exclusive suite offering expansive space, premium amenities, and a truly refined atmosphere.
                                                    </p>
                                                    <div class="d-flex mb-2 fs-18 justify-content-between">
                                                        <div class="d-flex">    
                                                            <div class="d-flex align-items-center me-3">
                                                                <img src="{{ asset('assets/frontend/images/ui/user-light.webp') }}" class="w-15px me-2" alt="">2 guests
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ asset('assets/frontend/images/ui/floorplan-light.webp') }}" class="w-15px me-2" alt="">50 ft
                                                            </div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <div class="fs-20 fw-bold">KES 24,900</div><span>/night</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <img src="{{ asset('assets/frontend/images/rooms/6.jpg') }}" class="w-100 animated anim-order-3" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sw-overlay op-8"></div>
                            </div>
                        </div>


                    </div>

                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
                
            </div>
    </main>
    
    @push('scripts')
    <!-- Javascript Files
    ================================================== -->
    <script src="{{ asset('assets/frontend/js/vendors.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/designesia.js') }}"></script>

    <!-- swiper slider -->
    <script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/custom-swiper-2.js') }}"></script>

    <!-- form -->
    <script src="{{ asset('assets/frontend/js/moment.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/custom-datepicker.js') }}"></script>
    @endpush

@endsection