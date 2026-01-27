@extends('frontend.layouts.app')
@section('title', 'Home - Tausi Holiday & Getaway Homes')
@section('content')

    <main>
        <a href="#" id="back-to-top"></a>
        <!-- page preloader begin -->
        <div id="de-loader"></div>
        <!-- page preloader close -->
        
        <section id="home" class="no-top no-bottom position-relative overflow-hidden mt-80 mt-sm-50 mx-2 rounded-1" style="min-height: 550px; background: #decfbc;">
            <!-- Background Swiper -->
            <div class="swiper-hero position-absolute w-100 h-100 top-0 start-0">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                    <div class="swiper-inner w-100 h-100" style="background-image: url('{{ asset('assets/frontend/images/misc/h1.jpg') }}'); background-size: cover; background-position: center; opacity: 0.4;"></div>
                </div>
                <div class="swiper-slide">
                    <div class="swiper-inner w-100 h-100" style="background-image: url('{{ asset('assets/frontend/images/misc/h2.jpg') }}'); background-size: cover; background-position: center; opacity: 0.4;"></div>
                </div>
                <div class="swiper-slide">
                    <div class="swiper-inner w-100 h-100" style="background-image: url('{{ asset('assets/frontend/images/misc/h3.jpg') }}'); background-size: cover; background-position: center; opacity: 0.4;"></div>
                </div>
              </div>
                            <div class="swiper-hero-pagination"></div>
            </div>


            <!-- Content -->
            <div class="position-relative d-flex align-items-end h-100" style="min-height: 550px; z-index: 2;">
                <div class="container py-5">
                    <div class="row g-4">
                        <div class="col-12">
                            <h1 class="text-dark wow fadeInUp mb-3" style="font-size: clamp(2.5rem, 6vw, 5rem); line-height: 1.1; font-weight: 700; max-width: 800px;">
                                AN ENTIRE HOUSE<br><span style="color: #decfbc;">JUST FOR YOU</span>
                            </h1>
                            <p class="text-dark wow fadeInUp mb-3" data-wow-delay=".2s" style="font-size: 1.125rem; max-width: 600px;">
                                KES 25,000 per night · Breakfast included
                            </p>
                            <a href="#booking" class="btn-main fx-slide wow fadeInUp" data-wow-delay=".4s">
                                <span>Book Your Stay</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">

                <div class="row g-4 mb-4 justify-content-center">
                    <div class="col-lg-6 text-center">
                        <div class="subtitle mb-0 id-color wow fadeInUp" data-wow-delay=".0s">Simple Pricing</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">One Flat Rate Entire Home Stay</h2>
                        <p class="cwow fadeInUp" data-wow-delay=".4s">
                            KES 25,000 per night · Breakfast Included
                        </p>
                    </div>
                </div>

                <div class="row g-4">

                    <div class="col-lg-8 offset-lg-2">
                        <a href="#pricing" class="d-block h-100 hover relative">
                            <div class="rounded-1 overflow-hidden">
                                <img src="{{ asset('assets/frontend/images/rooms/1.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                            </div>
                            <div class="pt-4">
                                <div class="d-flex mb-2 fs-15 justify-content-between">
                                    <div class="d-flex">    
                                        <div class="d-flex align-items-center me-3">
                                            <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">4-6 guests
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">Entire Home
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="fs-20 fw-bold">KES 25,000</div><span>/night</span>
                                    </div>
                                </div>
                                <div class="relative">
                                    <h3 class="mb-2">Tausi Holiday Home - Nanyuki</h3>
                                    <p class="text-muted">Breakfast & hospitality included</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-12 text-center">
                        <a href="#pricing" class="btn-main fx-slide hover-white"><span>View Available Homes</span></a>
                    </div>            

                </div>
            </div>
        </section>


        <section class="text-light jarallax jarallax-reviews mx-2 rounded-1 overflow-hidden">
            <img src="{{ asset('assets/frontend/images/background/1.webp') }}" class="jarallax-img" alt="">
            <div class="sw-overlay op-6"></div>
            <div class="container relative z-2">
                <div class="row g-4 gx-5 align-items-center">
                    <div class="col-lg-5 text-center">
                        <h2 class="fs-96 mb-0">4.9</h2>
                        <span class="d-stars id-color d-block wow fadeInUp">
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
                                <h3 class="mb-4 wow fadeInUp fs-40">The breakfast was amazing and prepared fresh every morning. We felt completely at home!</h3>
                                <span class="wow fadeInUp">Sarah M., Nairobi</span>
                            </div>

                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">Perfect for our family getaway. The entire home was clean, comfortable, and the hosts were so warm and welcoming.</h3>
                                <span class="wow fadeInUp">James K., Kisumu</span>
                            </div>

                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">Nanyuki location is beautiful. We appreciated the quiet, respectful environment and excellent hospitality.</h3>
                                <span class="wow fadeInUp">Emily W., Nairobi</span>
                            </div>

                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">Having the entire home to ourselves was wonderful. Privacy, comfort, and breakfast included - what more could we ask for?</h3>
                                <span class="wow fadeInUp">Peter M., Kenyatta</span>
                            </div>

                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">Excellent value for money. The home is well-maintained and the hosts are attentive without being intrusive.</h3>
                                <span class="wow fadeInUp">Lisa T., Thika</span>
                            </div>

                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">Our favorite getaway spot! The peaceful surroundings and home-style hospitality make it perfect for relaxation.</h3>
                                <span class="wow fadeInUp">David R., Mombasa</span>
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
                        <div class="subtitle id-color wow fadeInUp" data-wow-delay=".0s">What's Included</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">What's Included in Your Stay</h2>
                        <p class="cwow fadeInUp" data-wow-delay=".4s">
                            Each Tausi home comes with everything you need for a comfortable, worry-free stay — from fully furnished bedrooms to included breakfast every morning.
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="h-100 rounded-1 mh-300 wow fadeInUp" data-bgimage="url({{ asset('assets/frontend/images/misc/s5.webp') }}) center"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="row g-4">

                            <!-- TOTAL HOMES -->
                            <div class="col-md-6 wow fadeInUp" data-wow-delay=".0s">
                                <div class="p-30 bg-white rounded-1 h-100">
                                    <small class="text-uppercase border-bottom d-block">PRIVATE HOMES</small>
                                    <div class="sm-hide spacer-double"></div>
                                    <div class="spacer-double"></div>
                                    <h2 class="mb-0">
                                        <span class="timer" data-to="8" data-speed="3000">0</span>
                                        <span class="id-color">+</span>
                                    </h2>
                                    fully furnished homes
                                </div>
                            </div>

                            <!-- YEARLY VISITORS -->
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

                            <!-- RESTAURANT MENU ITEMS -->
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

                            <!-- STAFF COUNT / SERVICE IMAGE -->
                            <div class="col-md-6 wow fadeInUp sm-hide d-md-block d-xs-none" data-wow-delay=".6s">
                                <div class="p-30 bg-dark-2 rounded-1 h-100" data-bgimage="url({{ asset('assets/frontend/images/misc/s3.webp') }}) center">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="about" class="bg-color-op-1 rounded-1 mx-2">
            <div class="container">
                <div class="row g-4 gx-5 align-items-center justify-content-between">
                    <div class="col-lg-6">
                        <div class="subtitle wow fadeInUp" data-wow-delay=".0s">About Tausi</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">Private Homes Designed for Comfort & Quiet</h2>
                    </div>
                    <div class="col-lg-6">
                        <p class="wow fadeInUp" data-wow-delay=".4s">
                            Tausi offers fully furnished private houses ideal for families, couples, and small groups seeking a peaceful escape. 
                            Each home provides privacy, comfort, and a calm environment — with breakfast included and a simple flat nightly rate. 
                            From quiet mornings with freshly prepared breakfast to restful evenings in complete privacy, every detail is carefully 
                            designed to ensure your stay is seamless, comfortable, and truly memorable.
                        </p>
                    </div>
                </div>

                <div class="spacer-single"></div>

                <div class="row">
                  <div class="col-md-12 text-center">
                      <ul id="filters" class="wow fadeInUp" data-wow-delay="0s">
                        <li><a href="#" data-filter="*" class="selected">View All</a></li>
                          <li><a href="#" data-filter=".homes">Homes</a></li>
                          <li><a href="#" data-filter=".dining">Breakfast</a></li>
                          <li><a href="#" data-filter=".facilities">Facilities</a><li>
                      </ul>
                  </div>
                </div>

                <div id="gallery" class="row g-3 wow fadeIn" data-wow-delay=".3s">

                    <div class="col-md-3 col-sm-6 col-12 item homes">
                      <a href="{{ asset('assets/frontend/images/gallery/1.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/1.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item breakfast">
                      <a href="{{ asset('assets/frontend/images/gallery/6.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/6.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item amenities">
                      <a href="{{ asset('assets/frontend/images/gallery/9.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/9.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item homes">
                      <a href="{{ asset('assets/frontend/images/gallery/3.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/3.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item breakfast">
                      <a href="{{ asset('assets/frontend/images/gallery/8.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/8.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item homes">
                      <a href="{{ asset('assets/frontend/images/gallery/5.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/5.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item amenities">
                      <a href="{{ asset('assets/frontend/images/gallery/11.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/11.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item homes">
                      <a href="{{ asset('assets/frontend/images/gallery/2.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/2.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item amenities">
                      <a href="{{ asset('assets/frontend/images/gallery/10.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/10.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item homes">
                      <a href="{{ asset('assets/frontend/images/gallery/4.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/4.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item breakfast">
                      <a href="{{ asset('assets/frontend/images/gallery/7.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/7.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item amenities">
                      <a href="{{ asset('assets/frontend/images/gallery/12.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/12.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    </div>
            </div>
        </section>

        <section id="booking" aria-label="section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="bg-white p-40 rounded-1">
                            <!-- Reservation Form -->
                            <form name="contactForm" id="contact_form" method="post" action="{{ route('booking.store') }}">
                                @csrf
                                @if (session('booking_data'))
                                    <div class="alert alert-info mb-4" role="alert">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Edit Your Reservation</strong> - Update your dates, rooms, or guest information below.
                                    </div>
                                @endif

                                <!-- Hidden fields for guest info -->
                                <input type="hidden" id="name" name="name" value="{{ session('booking_data.guest_full_name', '') }}">
                                <input type="hidden" id="email" name="email" value="{{ session('booking_data.guest_email', '') }}">
                                <input type="hidden" id="phone" name="phone" value="{{ session('booking_data.guest_phone', '') }}">
                                <input type="hidden" id="message" name="message" value="{{ session('booking_data.special_requests', '') }}">
                                <input type="hidden" id="date-picker" name="date_picker">
                                <input type="hidden" id="adult" name="adult">
                                <input type="hidden" id="children" name="children">
                                <input type="hidden" class="room-type" name="room_type">
                                <input type="hidden" id="room-count" name="room_count">
                                <input type="hidden" id="property_id" name="property_id">

                                <div class="row g-4 align-items-end">
                                    
                                    <div class="col-md-1-5">
                                        <div class="fs-18 text-dark fw-500 mb-10">Check In</div>
                                        <input type="text" id="checkin" class="form-control" value="{{ session('booking_data.check_in', '') }}" required>
                                    </div>

                                    <div class="col-md-1-5">
                                        <div class="fs-18 text-dark fw-500 mb-10">Check Out</div>
                                        <input type="text" id="checkout" class="form-control" value="{{ session('booking_data.check_out', '') }}" required>
                                    </div>

                                    <div class="col-md-1-5">
                                        <div class="fs-18 text-dark fw-500 mb-10">Guests</div>
                                        <select id="room-count" class="form-control">
                                            <option value="1" @selected(session('booking_data.room_count') == 1)>1</option>
                                            <option value="2" @selected(session('booking_data.room_count') == 2)>2</option>
                                            <option value="3" @selected(session('booking_data.room_count') == 3)>3</option>
                                            <option value="4" @selected(session('booking_data.room_count') == 4)>4</option>
                                            <option value="5" @selected(session('booking_data.room_count') == 5)>5</option>
                                            <option value="6" @selected(session('booking_data.room_count') == 6)>6</option>
                                            <option value="7" @selected(session('booking_data.room_count') == 7)>7</option>
                                            <option value="8" @selected(session('booking_data.room_count') == 8)>8</option>
                                            <option value="9" @selected(session('booking_data.room_count') == 9)>9</option>
                                            <option value="10" @selected(session('booking_data.room_count') == 10)>10</option>
                                        </select>
                                    </div>

                                    <div class="col-md-1-5">
                                        <div class="fs-18 text-dark fw-500 mb-10">Guests</div>
                                        <input type="hidden" name="adult" id="adult">
                                        <input type="hidden" name="children" id="children">
                                        <select id="guests" class="form-control">
                                            <option value="1" @selected(session('booking_data.adult') == 1)>1</option>
                                            <option value="2" @selected(session('booking_data.adult') == 2)>2</option>
                                            <option value="3" @selected(session('booking_data.adult') == 3)>3</option>
                                            <option value="4" @selected(session('booking_data.adult') == 4)>4</option>
                                            <option value="5" @selected(session('booking_data.adult') == 5)>5</option>
                                            <option value="6" @selected(session('booking_data.adult') == 6)>6</option>
                                            <option value="7" @selected(session('booking_data.adult') == 7)>7</option>
                                            <option value="8" @selected(session('booking_data.adult') == 8)>8</option>
                                            <option value="9" @selected(session('booking_data.guests') == 9)>9</option>
                                            <option value="10" @selected(session('booking_data.guests') == 10)>10</option>
                                        </select>
                                    </div>


                                    <div class="col-md-1-5">
                                        <div id='submit'>
                                            <input type='submit' id='send_message' value='Check Availability' class="btn-main w-100">
                                        </div>
                                    </div>

                                </div>

                            </form>

                            <!-- Guest Details Form (shown when editing) -->
                            @if (session('booking_data'))
                                <form id="guest-details-form" method="post" action="#" style="display: none;">
                                    <div class="row g-4 mt-4 pt-4 border-top">
                                        <div class="col-12">
                                            <h5 class="mb-4">Guest Information</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" id="guest_full_name" class="form-control" value="{{ session('booking_data.guest_full_name', '') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" id="guest_email" class="form-control" value="{{ session('booking_data.guest_email', '') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Phone</label>
                                            <input type="tel" id="guest_phone" class="form-control" value="{{ session('booking_data.guest_phone', '') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Special Requests</label>
                                            <textarea id="special_requests" class="form-control" rows="3">{{ session('booking_data.special_requests', '') }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <button type="button" class="btn-main" onclick="submitGuestDetails()">Update & Proceed</button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="cancelEditMode()">Cancel</button>
                                        </div>
                                    </div>
                                </form>

                                <script>
                                    function enterEditMode() {
                                        document.getElementById('contact_form').style.display = 'none';
                                        document.getElementById('guest-details-form').style.display = 'block';
                                    }

                                    function cancelEditMode() {
                                        location.reload();
                                    }

                                    function submitGuestDetails() {
                                        // Store guest details in session or localStorage
                                        const guestData = {
                                            full_name: document.getElementById('guest_full_name').value,
                                            email: document.getElementById('guest_email').value,
                                            phone: document.getElementById('guest_phone').value,
                                            special_requests: document.getElementById('special_requests').value,
                                        };
                                        localStorage.setItem('guestDetails', JSON.stringify(guestData));
                                        // Trigger availability check with updated data
                                        document.getElementById('contact_form').submit();
                                    }

                                    // Auto-enter edit mode on page load
                                    document.addEventListener('DOMContentLoaded', function() {
                                        enterEditMode();
                                    });
                                </script>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="pricing" class="bg-color-op-1 rounded-1 mx-2">
            <div class="container">
                <div class="row g-4 mb-2 justify-content-center">
                    <div class="col-lg-6 text-center">
                        <div class="subtitle id-color wow fadeInUp">Simple Pricing</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">One Flat Rate Entire Home Stay</h2>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-4 wow fadeInUp" data-wow-delay=".2s">
                        <div class="overflow-hidden rounded-1">
                            <div class="hover relative">
                                <h3 class="abs bg-color rounded-3 text-white fs-20 lh-1 p-2 px-3 m-4 top-0 start-0 z-3">KES 25,000</h3>
                                <img src="{{ asset('assets/frontend/images/offers/1.webp') }}" class="w-100 hover-scale-1-1" alt="">
                                <a href="#booking" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>
                            <div class="p-40 bg-dark-2 text-light relative">
                                <a class="text-white" href="#booking">
                                    <h3>2-3 Bedroom Home</h3>
                                    <p>Per Night • Breakfast Included</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 wow fadeInUp" data-wow-delay=".4s">
                        <div class="overflow-hidden rounded-1">
                            <div class="p-40 bg-dark-2 text-light relative">
                                <a class="text-white" href="#booking">
                                    <h3>3-4 Bedroom Home</h3>
                                    <p>Per Night • Breakfast Included</p>
                                </a>
                            </div>
                            <div class="hover relative">
                                <h3 class="abs bg-color rounded-3 text-white fs-20 lh-1 p-2 px-3 m-4 bottom-0 start-0 z-3">KES 25,000</h3>
                                <img src="{{ asset('assets/frontend/images/offers/2.webp') }}" class="w-100 hover-scale-1-1" alt="">
                                <a href="#booking" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 wow fadeInUp" data-wow-delay=".6s">
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
                </div>

            </div>
        </section>


        <section>
            <div class="container">
                <div class="row g-4 gx-5 justify-content-center">
                    <div class="col-lg-6">
                        <div class="subtitle id-color">FAQ</div>
                        <h2 class="wow fadeInUp">
                            Everything You Need to Know About Staying With Us
                        </h2>
                    </div>

                    <div class="col-lg-6">
                        <div class="accordion title-boxed wow fadeInUp">
                            <div class="accordion-section">

                                <div class="accordion-section-title" data-tab="#accordion-b1">
                                    What time is check-in and check-out?
                                </div>
                                <div class="accordion-section-content" id="accordion-b1">
                                    <p class="mb-0">
                                        Check-in starts at 2:00 PM and check-out is at 12:00 PM. Early check-in and late check-out are available upon request and subject to availability.
                                    </p>
                                </div>

                                <div class="accordion-section-title" data-tab="#accordion-b2">
                                    What's included in the stay?
                                </div>
                                <div class="accordion-section-content" id="accordion-b2">
                                    <p class="mb-0">
                                        Every Tausi home includes full furnishings, all linens and towels, fully equipped kitchen, Wi-Fi, breakfast ingredients or daily-prepared breakfast, and secure parking.
                                    </p>
                                </div>

                                <div class="accordion-section-title" data-tab="#accordion-b3">
                                    Do you offer airport pick-up or shuttle service?
                                </div>
                                <div class="accordion-section-content" id="accordion-b3">
                                    <p class="mb-0">
                                        Yes, we provide pick-up and drop-off services on request. Please contact us in advance to arrange your transportation from Nanyuki town or the nearest airport.
                                    </p>
                                </div>

                                <div class="accordion-section-title" data-tab="#accordion-b4">
                                    Are pets allowed in the homes?
                                </div>
                                <div class="accordion-section-content" id="accordion-b4">
                                    <p class="mb-0">
                                        We welcome small, well-behaved pets in Tausi homes. Please inform us in advance so we can prepare appropriately.
                                    </p>
                                </div>

                                <div class="accordion-section-title" data-tab="#accordion-b5">
                                    Is Wi-Fi available?
                                </div>
                                <div class="accordion-section-content" id="accordion-b5">
                                    <p class="mb-0">
                                        Yes, complimentary high-speed Wi-Fi is available throughout all Tausi homes, perfect for staying connected during your stay.
                                    </p>
                                </div>

                                <div class="accordion-section-title" data-tab="#accordion-b6">
                                    How many guests can each home accommodate?
                                </div>
                                <div class="accordion-section-content" id="accordion-b6">
                                    <p class="mb-0">
                                        Our 2-3 bedroom homes comfortably accommodate 4-6 guests, while our 3-4 bedroom homes can sleep 6-8 guests. All homes are perfect for families or small groups seeking privacy.
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <section class="p-0 mx-2" aria-label="section">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <a class="d-block hover popup-youtube overflow-hidden rounded-1" href="https://www.youtube.com/watch?v=C6rf51uHWJg">
                            <div class="relative overflow-hidden">
                                <div class="absolute start-0 w-100 abs-middle fs-36 text-white text-center z-2">
                                    <div class="player bg-color no-border circle wow scaleIn"><span></span></div>
                                </div> 
                                <div class="absolute w-100 h-100 top-0 bg-dark hover-op-05"></div>
                                <img src="{{ asset('assets/frontend/images/background/2.webp') }}" class="w-100 hover-scale-1-1" alt="">
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section id="testimonial">
            <div class="container">
                <div class="row g-4 mb-4 justify-content-center">
                    <div class="col-lg-6 text-center">
                        <div class="subtitle id-color wow fadeInUp">Guest Experiences</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">What Our Guests Say</h2>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-xl-4 col-lg-6 wow fadeInUp">
                        <div class="p-4 bg-white rounded-1">
                            <div class="mb-3">
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                            </div>
                            <p class="mb-3">"Our stay at Tausi was absolutely perfect. The home was beautifully furnished, spotless, and felt like a true retreat. The included breakfast was delicious, and the whole experience was seamless."</p>
                            <h5 class="mb-0">Sarah & James</h5>
                            <small>Nanyuki, Kenya</small>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="p-4 bg-white rounded-1">
                            <div class="mb-3">
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                            </div>
                            <p class="mb-3">"We needed a private space away from the hustle. Tausi provided exactly that – calm, comfort, and all the amenities we could ask for. Highly recommended for families!"</p>
                            <h5 class="mb-0">Margaret Ochieng</h5>
                            <small>Nairobi, Kenya</small>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 wow fadeInUp" data-wow-delay=".4s">
                        <div class="p-4 bg-white rounded-1">
                            <div class="mb-3">
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                            </div>
                            <p class="mb-3">"The pick-up service was convenient, the home was lovely, and we felt genuinely welcomed. This is perfect for a quiet family getaway. We're definitely coming back!"</p>
                            <h5 class="mb-0">David Kipchoge</h5>
                            <small>Mombasa, Kenya</small>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 wow fadeInUp" data-wow-delay=".1s">
                        <div class="p-4 bg-white rounded-1">
                            <div class="mb-3">
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                            </div>
                            <p class="mb-3">"As couples seeking privacy and comfort, Tausi was ideal. The entire home setup, Wi-Fi connectivity, and breakfast made our romantic weekend unforgettable."</p>
                            <h5 class="mb-0">Lisa & Mike</h5>
                            <small>Nairobi, Kenya</small>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 wow fadeInUp" data-wow-delay=".3s">
                        <div class="p-4 bg-white rounded-1">
                            <div class="mb-3">
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                            </div>
                            <p class="mb-3">"We brought our extended family of 8, and Tausi's larger home accommodated everyone comfortably. The communal spaces were perfect for our reunion."</p>
                            <h5 class="mb-0">Paul & Family</h5>
                            <small>Eldoret, Kenya</small>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 wow fadeInUp" data-wow-delay=".5s">
                        <div class="p-4 bg-white rounded-1">
                            <div class="mb-3">
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                                <i class="fa fa-star id-color"></i>
                            </div>
                            <p class="mb-3">"Outstanding value for money. The flat rate pricing is transparent, and you get everything advertised. No hidden costs, just genuine hospitality."</p>
                            <h5 class="mb-0">Rachel Kamau</h5>
                            <small>Nanyuki, Kenya</small>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="contact" class="bg-light py-5">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-lg-12 text-center">
                        <h2 class="wow fadeInUp mb-3">Get in Touch</h2>
                        <p class="wow fadeInUp fs-18">Have questions about our homes? We're here to help!</p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-4 text-center wow fadeInUp" data-wow-delay=".2s">
                        <div class="mb-3">
                            <i class="fa fa-phone fs-40 id-color"></i>
                        </div>
                        <h4 class="mb-2">Call Us</h4>
                        <p class="mb-0"><a href="tel:+254718756254" class="text-decoration-none">+254 718 756 254</a><br>WhatsApp: <a href="https://wa.me/254718756254" class="text-decoration-none">+254 718 756 254</a></p>
                    </div>

                    <div class="col-lg-4 text-center wow fadeInUp" data-wow-delay=".4s">
                        <div class="mb-3">
                            <i class="fa fa-envelope fs-40 id-color"></i>
                        </div>
                        <h4 class="mb-2">Email Us</h4>
                        <p class="mb-0"><a href="mailto:bookings@tausivacations.com" class="text-decoration-none">bookings@tausivacations.com</a></p>
                    </div>

                    <div class="col-lg-4 text-center wow fadeInUp" data-wow-delay=".6s">
                        <div class="mb-3">
                            <i class="fa fa-map-marker fs-40 id-color"></i>
                        </div>
                        <h4 class="mb-2">Visit Us</h4>
                        <p class="mb-0">Nanyuki, Kenya</p>
                    </div>
                </div>
            </div>
        </section>

    @push('scripts')
        <script src="{{ asset('assets/frontend/js/vendors.js') }}"></script>
        <script src="{{ asset('assets/frontend/js/designesia.js') }}"></script>
        <script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>
        <script src="{{ asset('assets/frontend/js/custom-swiper-1.js') }}"></script>
        <script src="{{ asset('assets/frontend/js/moment.js') }}"></script>
        <script src="{{ asset('assets/frontend/js/hero-swiper.js') }}"></script>
        <script src="{{ asset('assets/frontend/js/daterangepicker.js') }}"></script>
        <script src="{{ asset('assets/frontend/js/custom-datepicker.js') }}"></script>
    @endpush

@endsection