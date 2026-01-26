@extends('frontend.layouts.app')

@section('title', 'About Us')

@section('content')

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
                        <div class="subtitle id-color wow fadeInUp mb-2">Who We Are</div>
                        <div class="clearfix"></div>
                        <h2 class="fs-60 fs-xs-8vw wow fadeInUp" data-wow-delay=".4s">About Us</h2>
                    </div>
                </div>
            </div>
            <div class="crumb-wrapper">
                <ul class="crumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">About Us</li>
                </ul>
            </div>
            <div class="sw-overlay op-8"></div>
        </section>

        <section>
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <div class="relative">
                            <div class="w-100 pe-5 pb-5 wow scaleIn">
                                <img src="{{ asset('assets/frontend/images/misc/l1.webp') }}" class="w-100 rounded-1" alt="">
                            </div>
                            <img src="{{ asset('assets/frontend/images/misc/s3.webp') }}" class="w-40 rounded-1 abs end-0 bottom-0 z-2 soft-shadow wow scaleIn" data-wow-delay=".2s" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ps-lg-3">
                            <div class="subtitle id-color wow fadeInUp" data-wow-delay=".2s">About Our Hotel</div>
                            <h2 class="wow fadeInUp" data-wow-delay=".4s">Where Relaxation Meets Elegance</h2>
                            <p class="mb-0 wow fadeInUp" data-wow-delay=".6s">
                                Experience refined hospitality designed to make every stay memorable and effortless. Our hotel offers thoughtfully curated rooms, attentive service, and a welcoming atmosphere where comfort and convenience come together seamlessly. From restful nights to peaceful mornings, every detail is carefully arranged to ensure guests enjoy a relaxing, enjoyable, and truly satisfying stay throughout their visit.
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <section class="bg-color-op-1 rounded-1 m-2 mt-0">
            <div class="container">
                <div class="row g-4 mb-4">
                    <div class="col-lg-6 offset-lg-3 text-center">
                        <div class="subtitle wow fadeInUp">Our Team</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">Meet Our Professionals</h2>
                        <p class="wow fadeInUp" data-wow-delay=".4s">
                            Dedicated hospitality professionals working together to deliver seamless service and exceptional guest experiences throughout your stay.
                        </p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 wow fadeInUp" data-wow-delay=".4s">
                        <img src="{{ asset('assets/frontend/images/team/1.webp') }}" class="w-100 rounded-10px" alt="">
                        <div class="p-3 text-center">
                            <h3 class="mb-0">Thomas Bennett</h3>
                            <p class="mb-2">Guest Experience Manager</p>
                            <div class="social-icons">
                                <a href="#"><i class="bg-white id-color bg-hover-2 text-hover-white fa-brands fa-facebook-f"></i></a>
                                <a href="#"><i class="bg-white id-color bg-hover-2 text-hover-white fa-brands fa-x-twitter"></i></a>
                                <a href="#"><i class="bg-white id-color bg-hover-2 text-hover-white fa-brands fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 wow fadeInUp" data-wow-delay=".6s">
                        <img src="{{ asset('assets/frontend/images/team/2.webp') }}" class="w-100 rounded-10px" alt="">
                        <div class="p-3 text-center">
                            <h3 class="mb-0">Barbara Charline</h3>
                            <p class="mb-2">Housekeeping Supervisor</p>
                            <div class="social-icons">
                                <a href="#"><i class="bg-white id-color bg-hover-2 text-hover-white fa-brands fa-facebook-f"></i></a>
                                <a href="#"><i class="bg-white id-color bg-hover-2 text-hover-white fa-brands fa-x-twitter"></i></a>
                                <a href="#"><i class="bg-white id-color bg-hover-2 text-hover-white fa-brands fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 wow fadeInUp" data-wow-delay=".8s">
                        <img src="{{ asset('assets/frontend/images/team/3.webp') }}" class="w-100 rounded-10px" alt="">
                        <div class="p-3 text-center">
                            <h3 class="mb-0">Madison Jane</h3>
                            <p class="mb-2">Room Quality Specialist</p>
                            <div class="social-icons">
                                <a href="#"><i class="bg-white id-color bg-hover-2 text-hover-white fa-brands fa-facebook-f"></i></a>
                                <a href="#"><i class="bg-white id-color bg-hover-2 text-hover-white fa-brands fa-x-twitter"></i></a>
                                <a href="#"><i class="bg-white id-color bg-hover-2 text-hover-white fa-brands fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 wow fadeInUp" data-wow-delay="1s">
                        <img src="{{ asset('assets/frontend/images/team/4.webp') }}" class="w-100 rounded-10px" alt="">
                        <div class="p-3 text-center">
                            <h3 class="mb-0">Joshua Henry</h3>
                            <p class="mb-2">Guest Service Coordinator</p>
                            <div class="social-icons">
                                <a href="#"><i class="bg-white id-color bg-hover-2 text-hover-white fa-brands fa-facebook-f"></i></a>
                                <a href="#"><i class="bg-white id-color bg-hover-2 text-hover-white fa-brands fa-x-twitter"></i></a>
                                <a href="#"><i class="bg-white id-color bg-hover-2 text-hover-white fa-brands fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="text-light jarallax m-2 rounded-1 overflow-hidden">
            <img src="{{ asset('assets/frontend/images/background/1.webp') }}" class="jarallax-img" alt="">
            <div class="sw-overlay op-6"></div>
            <div class="container relative z-2">
                <div class="row g-4 gx-5 align-items-center wow fadeInUp">
                    <div class="col-lg-5 text-center ">
                        <h2 class="fs-96 mb-0">4.9</h2>
                        <span class="d-stars id-color d-block">
                            <i class="icofont-star"></i>
                            <i class="icofont-star"></i>
                            <i class="icofont-star"></i>
                            <i class="icofont-star"></i>
                            <i class="icofont-star"></i>
                        </span>
                        (300+ Reviews)
                    </div>
                    <div class="col-lg-7">
                        <div class="owl-single-dots owl-carousel owl-theme">
                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">A truly outstanding stay — warm service, beautiful rooms, and an atmosphere that feels unforgettable.</h3>
                                <span class="wow fadeInUp">Anna L., Paris</span>
                            </div>
                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">Everything exceeded expectations — from the amenities to the staff, truly a memorable hotel experience.</h3>
                                <span class="wow fadeInUp">Michael H., Toronto</span>
                            </div>
                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">Impeccable attention to detail. Every moment felt personal and thoughtfully crafted during our stay.</h3>
                                <span class="wow fadeInUp">Nadia R., Dubai</span>
                            </div>
                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">From check-in to check-out, the experience was effortless and luxurious. Highly recommended.</h3>
                                <span class="wow fadeInUp">Tom S., Los Angeles</span>
                            </div>
                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">Beautiful interiors, friendly staff, and great location. We loved every moment of our vacation.</h3>
                                <span class="wow fadeInUp">Elise K., Amsterdam</span>
                            </div>
                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">Exceptional hospitality and comfort. The perfect choice for a relaxing and refreshing getaway.</h3>
                                <span class="wow fadeInUp">David M., Singapore</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row g-4 mb-4 justify-content-center">
                    <div class="col-lg-6 text-center">
                        <div class="subtitle id-color wow fadeInUp" data-wow-delay=".0s">Welcome to GrandStay</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">Hotel Facilities</h2>
                        <p class="wow fadeInUp" data-wow-delay=".4s">
                            From premium rooms to full-service amenities, our team ensures a comfortable and memorable stay from check-in to check-out.
                        </p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="h-100 rounded-1 mh-300 wow fadeInUp" data-bgimage="url({{ asset('assets/frontend/images/misc/s1.webp') }}) center"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="row g-4">
                            <div class="col-md-6 wow fadeInUp" data-wow-delay=".0s">
                                <div class="p-30 bg-white rounded-1 h-100">
                                    <small class="text-uppercase border-bottom d-block">TOTAL ROOMS</small>
                                    <div class="sm-hide spacer-double"></div>
                                    <div class="spacer-double"></div>
                                    <h2 class="mb-0">
                                        <span class="timer" data-to="180" data-speed="3000">0</span>
                                        <span class="id-color">+</span>
                                    </h2>
                                    luxury rooms & suites
                                </div>
                            </div>
                            <div class="col-md-6 wow fadeInUp" data-wow-delay=".2s">
                                <div class="p-30 bg-white rounded-1 h-100">
                                    <small class="text-uppercase border-bottom d-block">YEARLY VISITORS</small>
                                    <div class="sm-hide spacer-double"></div>
                                    <div class="spacer-double"></div>
                                    <h2 class="mb-0">
                                        <span class="timer" data-to="8500" data-speed="3000">0</span>
                                        <span class="id-color">+</span>
                                    </h2>
                                    happy guests
                                </div>
                            </div>
                            <div class="col-md-6 wow fadeInUp" data-wow-delay=".4s">
                                <div class="p-30 bg-white rounded-1 h-100">
                                    <small class="text-uppercase border-bottom d-block">SIGNATURE MENU</small>
                                    <div class="sm-hide spacer-double"></div>
                                    <div class="spacer-double"></div>
                                    <h2 class="mb-0">
                                        <span class="timer" data-to="65" data-speed="3000">0</span>
                                        <span class="id-color">+</span>
                                    </h2>
                                    curated dishes & beverages
                                </div>
                            </div>
                            <div class="col-md-6 wow fadeInUp sm-hide d-md-block d-xs-none" data-wow-delay=".6s">
                                <div class="p-30 bg-dark-2 rounded-1 h-100" data-bgimage="url({{ asset('assets/frontend/images/misc/s2.webp') }}) center">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    </main>    
    
    <footer class="text-light section-dark m-2 mt-0 rounded-1">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-12">
                    <div class="d-lg-flex align-items-center justify-content-between text-center">
                        <div>
                            <h3 class="fs-20">Address</h3>
                            742 Evergreen Terrace<br>
                            Brooklyn, NY 11201
                        </div>
                        <div>
                            <img src="{{ asset('assets/frontend/images/logo-white.webp') }}" class="w-150px" alt=""><br>
                            <div class="social-icons mb-sm-30 mt-4">
                                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                <a href="#"><i class="fa-brands fa-youtube"></i></a>
                            </div>

                        </div>
                        <div>
                            <h3 class="fs-20">Contact Us</h3>
                            T. +929 333 9296<br>
                            M. bookings@tausivacations.com
                        </div>
                    </div>
                </div>                    
            </div>
        </div>
        <div class="subfooter">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        Copyright {{ date('Y') }} - Tausi Holiday & Getaway Homes. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </footer>

@push('scripts')
    <script src="{{ asset('assets/frontend/js/vendors.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/designesia.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/custom-swiper-1.js') }}"></script>
@endpush

@endsection