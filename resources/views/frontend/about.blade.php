@extends('frontend.layouts.app')

@section('title', 'About Us - Tausi Holiday & Getaway Homes')

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
                        <div class="subtitle id-color wow fadeInUp mb-2">Tausi Holiday & Getaway Homes</div>
                        <div class="clearfix"></div>
                        <h2 class="fs-60 fs-xs-8vw wow fadeInUp" data-wow-delay=".4s">Private Homes Designed for Comfort & Quiet</h2>
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
                            <div class="subtitle id-color wow fadeInUp" data-wow-delay=".2s">Tausi Holiday & Getaway Homes</div>
                            <h2 class="wow fadeInUp" data-wow-delay=".4s">Private Homes Designed for Comfort & Quiet</h2>
                            <p class="mb-0 wow fadeInUp" data-wow-delay=".6s">
                                Tausi offers fully furnished private houses ideal for families, couples, and small groups seeking a peaceful escape. Each home provides privacy, comfort, and a calm environment — with breakfast included and a simple flat nightly rate. Enjoy privacy, space, and flexibility — ideal for relaxing stays away from busy hotels.
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <section class="bg-color-op-1 rounded-1 m-2">
            <div class="container">
                <div class="row g-4 mb-4">
                    <div class="col-lg-6 offset-lg-3 text-center">
                        <div class="subtitle wow fadeInUp">What Makes Tausi Special</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">Simple Comforts That Make a Difference</h2>
                        <p class="wow fadeInUp" data-wow-delay=".4s">
                            Each home is designed with essential comforts in mind — no unnecessary luxury, just what matters most for a peaceful stay.
                        </p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay=".0s">
                        <div class="p-40 bg-white rounded-1 h-100 shadow-sm">
                            <div class="icon-box mb-3"><i class="fa fa-lightbulb" style="font-size: 32px; color: #decfbc;"></i></div>
                            <h3>Calm & Comfortable Spaces</h3>
                            <p>Thoughtfully designed interiors that prioritize comfort and tranquility. Rest assured knowing every space is clean, comfortable, and ready for you.</p>
                        </div>
                    </div>

                    <div class="col-lg-6 wow fadeInUp" data-wow-delay=".1s">
                        <div class="p-40 bg-white rounded-1 h-100 shadow-sm">
                            <div class="icon-box mb-3"><i class="fa fa-tree" style="font-size: 32px; color: #decfbc;"></i></div>
                            <h3>Private Outdoor Areas</h3>
                            <p>Your own outdoor space for morning coffee or evening relaxation. Secure parking, quiet gardens, and the freedom to enjoy your stay privately.</p>
                        </div>
                    </div>

                    <div class="col-lg-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="p-40 rounded-1 h-100 shadow-sm" style="background-color: #447f6a; color: #f7f3f8;">
                            <div class="icon-box mb-3"><i class="fa fa-utensils" style="font-size: 32px; color: #decfbc;"></i></div>
                            <h3 style="color: #f7f3f8;">Breakfast Included</h3>
                            <p style="color: #f7f3f8;">Start each day with a complimentary breakfast prepared for you. Simple, nourishing meals that set the tone for a great day ahead.</p>
                        </div>
                    </div>

                    <div class="col-lg-6 wow fadeInUp" data-wow-delay=".3s">
                        <div class="p-40 rounded-1 h-100 shadow-sm" style="background-color: #447f6a; color: #f7f3f8;">
                            <div class="icon-box mb-3"><i class="fa fa-wifi" style="font-size: 32px; color: #decfbc;"></i></div>
                            <h3 style="color: #f7f3f8;">Essential Services</h3>
                            <p style="color: #f7f3f8;">Reliable Wi-Fi, secure parking, and attentive on-request support. We're here when you need us, without being intrusive.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row g-4 mb-4">
                    <div class="col-lg-6 offset-lg-3 text-center">
                        <div class="subtitle id-color wow fadeInUp" data-wow-delay=".0s">Our Pricing</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">One Flat Rate for All</h2>
                        <p class="wow fadeInUp" data-wow-delay=".4s">
                            Transparent, straightforward pricing with no hidden fees. Same great experience for everyone.
                        </p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay=".0s">
                        <div class="p-40 bg-white rounded-1 h-100 shadow-sm">
                            <h3 class="mb-3">Every Night Includes</h3>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3"><i class="fa fa-check me-2" style="color: #decfbc;"></i>Entire house rental — no shared spaces</li>
                                <li class="mb-3"><i class="fa fa-check me-2" style="color: #decfbc;"></i>Breakfast prepared for you</li>
                                <li class="mb-3"><i class="fa fa-check me-2" style="color: #decfbc;"></i>Secure parking & Wi-Fi</li>
                                <li class="mb-3"><i class="fa fa-check me-2" style="color: #decfbc;"></i>Attentive on-request support</li>
                                <li class="mb-0"><i class="fa fa-check me-2" style="color: #decfbc;"></i>Calm, respectful environment</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="p-40 bg-color rounded-1 h-100 shadow-sm text-light">
                            <h3 class="mb-4 text-white">Our Rate</h3>
                            <div class="mb-4">
                                <span class="fs-48 fw-bold text-white">KES 25,000</span>
                                <span class="d-block">/night per house</span>
                            </div>
                            <p class="mb-4">Breakfast included. No surprises. One rate for everyone.</p>
                            <a href="{{ route('properties') }}" class="btn btn-light fx-slide"><span>View Homes</span></a>
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
                        (150+ Reviews)
                    </div>
                    <div class="col-lg-7">
                        <div class="owl-single-dots owl-carousel owl-theme">
                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">We booked a whole house for a family weekend and everything was exactly as described. The space was peaceful, breakfast was great, and the privacy made the stay very relaxing.</h3>
                                <span class="wow fadeInUp">Esther Mwangi, Family Guest</span>
                            </div>
                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">A quiet and comfortable place to stay while working remotely. Reliable Wi-Fi, clean spaces, and a simple booking process. I would definitely return.</h3>
                                <span class="wow fadeInUp">Daniel Otieno, Business Traveler</span>
                            </div>
                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">Perfect for a small group getaway. The house was spacious, well kept, and the environment was calm. Great value for the price.</h3>
                                <span class="wow fadeInUp">Miriam Karanja, Group Guest</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    </main>    
    
@push('scripts')
    <script src="{{ asset('assets/frontend/js/vendors.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/designesia.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/custom-swiper-1.js') }}"></script>
@endpush

@endsection