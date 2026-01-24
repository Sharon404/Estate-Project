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

                    <!-- Review 1 -->
                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/1.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Emily Johnson</div>
                                        <small>12 March 2025</small>
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
                                "Beautiful room, spotless bathroom, and incredibly comfortable bed. The romantic setup made our anniversary truly special."
                            </p>
                        </div>
                    </div>

                    <!-- Review 2 -->
                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/2.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Michael Brown</div>
                                        <small>28 February 2025</small>
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
                                "Exceptional service from check-in to check-out. Staff were warm, attentive, and always ready to help."
                            </p>
                        </div>
                    </div>

                    <!-- Review 3 -->
                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/3.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Sophia Lee</div>
                                        <small>18 February 2025</small>
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
                                "The breakfast was outstanding with plenty of options. Dining with a view made mornings unforgettable."
                            </p>
                        </div>
                    </div>

                    <!-- Review 4 -->
                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/4.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Daniel Martinez</div>
                                        <small>02 February 2025</small>
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
                                "Perfect location—quiet yet close to everything. Ideal for both relaxing and exploring the city."
                            </p>
                        </div>
                    </div>

                    <!-- Review 5 -->
                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/5.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Olivia Wilson</div>
                                        <small>25 January 2025</small>
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
                                "Spa facilities were top-notch. Exactly what we needed after a long day of travel."
                            </p>
                        </div>
                    </div>

                    <!-- Review 6 -->
                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/6.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">James Anderson</div>
                                        <small>10 January 2025</small>
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
                                "Elegant interior, calming atmosphere, and excellent soundproofing. Slept incredibly well."
                            </p>
                        </div>
                    </div>


                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/7.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Laura Bennett</div>
                                        <small>22 December 2024</small>
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
                                "From the moment we arrived, everything felt thoughtfully curated. The room was beautifully designed, exceptionally clean, and incredibly comfortable. The romantic touches made our stay feel truly special and memorable."
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/8.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Thomas Müller</div>
                                        <small>18 December 2024</small>
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
                                "The hotel strikes a perfect balance between luxury and warmth. Staff were professional yet genuinely friendly, and every request was handled quickly. The overall experience exceeded our expectations."
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/9.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Isabella Rossi</div>
                                        <small>10 December 2024</small>
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
                                "Every detail felt intentional, from the soft lighting to the premium bedding. Breakfast was excellent with a wide selection, and the dining area created a relaxed, elegant atmosphere."
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/10.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Daniel Wong</div>
                                        <small>05 December 2024</small>
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
                                "The location was ideal—peaceful and quiet, yet close enough to major attractions. The room was spacious, well-maintained, and perfect for relaxing after a long day."
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/11.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Sophie Laurent</div>
                                        <small>30 November 2024</small>
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
                                "We stayed for a weekend and wished we could extend our stay. The ambiance, service quality, and attention to comfort made this one of our best hotel experiences."
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 item">
                        <div class="bg-white rounded-1 p-30">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <img class="w-40px circle me-3" alt="" src="{{ asset('assets/frontend/images/testimonial/12.webp') }}">
                                    <div class="mt-2">
                                        <div class="text-dark fw-bold lh-1">Alexander Novak</div>
                                        <small>22 November 2024</small>
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
                                "This hotel delivers a refined, comfortable experience without feeling overly formal. Everything—from the room to the service—felt polished and thoughtfully executed."
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