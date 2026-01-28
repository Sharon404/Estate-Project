@extends('frontend.layouts.app')
@section('title', 'Testimonials - Tausi Holiday & Getaway Homes')
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
                        <h2 class="fs-60 fs-xs-8vw wow fadeInUp" data-wow-delay=".4s">Testimonials</h2>
                    </div>
                </div>
            </div>
            <div class="crumb-wrapper">
                <ul class="crumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Testimonials</li>
                </ul>
            </div>
            <div class="sw-overlay op-8"></div>
        </section>

        <section aria-label="section">
            <div class="container">
                <div class="row g-4" id="gallery">

                    <!-- Review 1 - Sarah M. -->
                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/1.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Sarah M.</div>
                                        <small>Nairobi</small>
                                    </div>
                                </div>
                                <img src="{{ asset('assets/frontend/images/misc/google-icon.svg') }}" class="w-30px" alt="">
                            </div>

                            <div class="de-rating-ext mb-2">
                                <span class="d-stars">
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <span class="ms-2 text-white">5.0</span>
                            </div>

                            <p>
                                "The breakfast was amazing and prepared fresh every morning. We felt completely at home!"
                            </p>
                        </div>
                    </div>

                    <!-- Review 2 - James K. -->
                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/2.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">James K.</div>
                                        <small>Kisumu</small>
                                    </div>
                                </div>
                                <img src="{{ asset('assets/frontend/images/misc/google-icon.svg') }}" class="w-30px" alt="">
                            </div>

                            <div class="de-rating-ext mb-2">
                                <span class="d-stars">
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <span class="ms-2 text-white">5.0</span>
                            </div>

                            <p>
                                "Perfect for our family getaway. The entire home was clean, comfortable, and the hosts were so warm and welcoming."
                            </p>
                        </div>
                    </div>

                    <!-- Review 3 - Emily W. -->
                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/3.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Emily W.</div>
                                        <small>Nairobi</small>
                                    </div>
                                </div>
                                <img src="{{ asset('assets/frontend/images/misc/google-icon.svg') }}" class="w-30px" alt="">
                            </div>

                            <div class="de-rating-ext mb-2">
                                <span class="d-stars">
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <span class="ms-2 text-white">5.0</span>
                            </div>

                            <p>
                                "Nanyuki location is beautiful. We appreciated the quiet, respectful environment and excellent hospitality."
                            </p>
                        </div>
                    </div>

                    <!-- Review 4 - Peter M. -->
                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/4.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Peter M.</div>
                                        <small>Kenyatta</small>
                                    </div>
                                </div>
                                <img src="{{ asset('assets/frontend/images/misc/google-icon.svg') }}" class="w-30px" alt="">
                            </div>

                            <div class="de-rating-ext mb-2">
                                <span class="d-stars">
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <span class="ms-2 text-white">5.0</span>
                            </div>

                            <p>
                                "Having the entire home to ourselves was wonderful. Privacy, comfort, and breakfast included - what more could we ask for?"
                            </p>
                        </div>
                    </div>

                    <!-- Review 5 - Lisa T. -->
                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/5.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Lisa T.</div>
                                        <small>Thika</small>
                                    </div>
                                </div>
                                <img src="{{ asset('assets/frontend/images/misc/google-icon.svg') }}" class="w-30px" alt="">
                            </div>

                            <div class="de-rating-ext mb-2">
                                <span class="d-stars">
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <span class="ms-2 text-white">5.0</span>
                            </div>

                            <p>
                                "Excellent value for money. The home is well-maintained and the hosts are attentive without being intrusive."
                            </p>
                        </div>
                    </div>

                    <!-- Review 6 - David R. -->
                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/6.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">David R.</div>
                                        <small>Mombasa</small>
                                    </div>
                                </div>
                                <img src="{{ asset('assets/frontend/images/misc/google-icon.svg') }}" class="w-30px" alt="">
                            </div>

                            <div class="de-rating-ext mb-2">
                                <span class="d-stars">
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </span>
                                <span class="ms-2 text-white">5.0</span>
                            </div>

                            <p>
                                "Our favorite getaway spot! The peaceful surroundings and home-style hospitality make it perfect for relaxation."
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