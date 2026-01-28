@extends('frontend.layouts.app')
@section('title', 'Home - Tausi Holiday & Getaway Homes')
@section('content')

    <main>
        <a href="#" id="back-to-top"></a>
        <!-- page preloader begin -->
        <div id="de-loader"></div>
        <!-- page preloader close -->
        
        <section class="no-top no-bottom position-relative overflow-hidden mt-80 mt-sm-50 mx-2 rounded-1" style="min-height: 550px; background: #decfbc;">
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
                                KES 25,000 per night · Breakfast Included
                            </p>
                            <a href="{{ route('properties') }}" class="btn-main fx-slide wow fadeInUp" data-wow-delay=".4s">
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
                        <a href="{{ route('property.single', ['id' => 1]) }}" class="d-block h-100 hover relative">
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
                                    <p class="text-muted">Flat rate of KES 25,000 per house per night, with breakfast included.</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-12 text-center">
                        <a href="{{ route('properties') }}" class="btn-main fx-slide hover-white"><span>View Available Homes</span></a>
                    </div>            

                </div>
            </div>
        </section>

        <section aria-label="section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="bg-white p-40 rounded-1">
                            <!-- Reservation Form -->
                            <form name="contactForm" id="contact_form" method="get" action="{{ route('properties') }}">
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
                                        <div class="fs-18 text-dark fw-500 mb-10">Number of Homes</div>
                                        <select id="room-count" name="room_count" class="form-control">
                                            <option value="1" @selected(session('booking_data.room_count') == 1)>1</option>
                                            <option value="2" @selected(session('booking_data.room_count') == 2)>2</option>
                                            <option value="3" @selected(session('booking_data.room_count') == 3)>3</option>
                                            <option value="4" @selected(session('booking_data.room_count') == 4)>4</option>
                                            <option value="5" @selected(session('booking_data.room_count') == 5)>5</option>
                                            <option value="6" @selected(session('booking_data.room_count') == 6)>6</option>
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
                        <div class="subtitle id-color wow fadeInUp" data-wow-delay=".0s">BREAKFAST & HOSPITALITY</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">Simple Comforts That Make a Difference</h2>
                        <p class="cwow fadeInUp" data-wow-delay=".4s">
                            At Tausi Holiday & Getaway Homes, we focus on the essentials that matter most — privacy, comfort, and a warm hosting experience. Every stay includes breakfast, prepared fresh to help you start your day relaxed and refreshed.
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="h-100 rounded-1 mh-300 wow fadeInUp" data-bgimage="url({{ asset('assets/frontend/images/misc/s5.webp') }}) center"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="row g-4">

                            <!-- ENTIRE HOMES -->
                            <div class="col-md-6 wow fadeInUp" data-wow-delay=".0s">
                                <div class="p-30 bg-white rounded-1 h-100">
                                    <small class="text-uppercase border-bottom d-block">Breakfast Included</small>
                                    <div class="sm-hide spacer-double"></div>
                                    <div class="spacer-double"></div>
                                    <p class="mb-0">Freshly prepared daily breakfast</p>
                                    <p>Served in a calm, private setting</p>
                                </div>
                            </div>

                            <!-- HOME-STYLE HOSTING -->
                            <div class="col-md-6 wow fadeInUp" data-wow-delay=".2s">
                                <div class="p-30 bg-white rounded-1 h-100">
                                    <small class="text-uppercase border-bottom d-block">Home-Style Hosting</small>
                                    <div class="sm-hide spacer-double"></div>
                                    <div class="spacer-double"></div>
                                    <p class="mb-0">Quiet, respectful environment</p>
                                    <p>Attentive on-request support</p>
                                </div>
                            </div>

                            <!-- PRIVACY & COMFORT -->
                            <div class="col-md-6 wow fadeInUp" data-wow-delay=".4s">
                                <div class="p-30 bg-white rounded-1 h-100">
                                    <small class="text-uppercase border-bottom d-block">Ideal for families & small groups</small>
                                    <div class="sm-hide spacer-double"></div>
                                    <div class="spacer-double"></div>
                                    <p class="mb-0">Included in the nightly rate</p>
                                    <p>Quiet, respectful environment</p>
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

        <section class="bg-color-op-1 rounded-1 mx-2">
            <div class="container">
                <div class="row g-4 gx-5 align-items-center justify-content-between">
                    <div class="col-lg-6">
                        <div class="subtitle wow fadeInUp" data-wow-delay=".0s">Tausi Holiday & Getaway Homes</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">Private Homes Designed for Comfort & Quiet</h2>
                    </div>
                    <div class="col-lg-6">
                        <p class="wow fadeInUp" data-wow-delay=".4s">
                            At Tausi Holiday & Getaway Homes, we focus on the essentials that matter most — privacy, comfort, and a warm hosting experience. Every stay includes breakfast, prepared fresh to help you start your day relaxed and refreshed.
                        </p>
                    </div>
                </div>

                <div class="spacer-single"></div>

                <div class="row">
                  <div class="col-md-12 text-center">
                      <ul id="filters" class="wow fadeInUp" data-wow-delay="0s">
                        <li><a href="#" data-filter="*" class="selected">View All</a></li>
                          <li><a href="#" data-filter=".rooms">Our Homes</a></li>
                          <li><a href="#" data-filter=".dining">Dining</a></li>
                          <li><a href="#" data-filter=".facilities">Facilities</a><li>
                      </ul>
                  </div>
                </div>

                <div id="gallery" class="row g-3 wow fadeIn" data-wow-delay=".3s">

                    <div class="col-md-3 col-sm-6 col-12 item rooms">
                      <a href="{{ asset('assets/frontend/images/gallery/1.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/1.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item dining">
                      <a href="{{ asset('assets/frontend/images/gallery/6.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/6.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item facilities">
                      <a href="{{ asset('assets/frontend/images/gallery/9.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/9.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item rooms">
                      <a href="{{ asset('assets/frontend/images/gallery/3.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/3.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item dining">
                      <a href="{{ asset('assets/frontend/images/gallery/8.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/8.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item rooms">
                      <a href="{{ asset('assets/frontend/images/gallery/5.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/5.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item facilities">
                      <a href="{{ asset('assets/frontend/images/gallery/11.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/11.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item rooms">
                      <a href="{{ asset('assets/frontend/images/gallery/2.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/2.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item facilities">
                      <a href="{{ asset('assets/frontend/images/gallery/10.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/10.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item rooms">
                      <a href="{{ asset('assets/frontend/images/gallery/4.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/4.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item dining">
                      <a href="{{ asset('assets/frontend/images/gallery/7.webp') }}" class="image-popup d-block hover">
                          <div class="relative overflow-hidden rounded-1">
                              <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                              <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                              <img src="{{ asset('assets/frontend/images/gallery/7.webp') }}" class="w-100 hover-scale-1-2" alt="">
                          </div>
                      </a>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12 item facilities">
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

        

        <section class="bg-color-op-1 rounded-1 mx-2">
            <div class="container">
                <div class="row g-4 mb-2 justify-content-center">
                    <div class="col-lg-6 text-center">
                        <div class="subtitle id-color wow fadeInUp">Plan Your Stay</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">Your Quiet Escape Starts Here</h2>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-4 wow fadeInUp" data-wow-delay=".2s">
                        <div class="overflow-hidden rounded-1">
                            <div class="hover relative">
                                <h3 class="abs bg-color rounded-3 text-white fs-20 lh-1 p-2 px-3 m-4 top-0 start-0 z-3">20% OFF</h3>
                                <img src="{{ asset('assets/frontend/images/offers/1.webp') }}" class="w-100 hover-scale-1-1" alt="">
                                <a href="{{ route('offer.single', ['id' => 1]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                            </div>
                            <div class="p-40 bg-dark-2 text-light relative">
                                <a class="text-white" href="{{ route('offers') }}">
                                    <h3>Private House (2–3 Bedrooms)</h3>
                                    <p>Flat rate of KES 25,000 per house per night, with breakfast included.</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 wow fadeInUp" data-wow-delay=".4s">
                        <div class="overflow-hidden rounded-1">
                            <div class="p-40 bg-dark-2 text-light relative">
                                <a class="text-white" href="{{ route('offers') }}">
                                    <h3>Breakfast Included</h3>
                                    <p>Enjoy a complimentary breakfast prepared daily as part of your nightly rate.</p>
                                </a>
                            </div>
                            <div class="hover relative">
                                <h3 class="abs bg-color rounded-3 text-white fs-20 lh-1 p-2 px-3 m-4 bottom-0 start-0 z-3">30% OFF</h3>
                                <img src="{{ asset('assets/frontend/images/offers/2.webp') }}" class="w-100 hover-scale-1-1" alt="">
                                <a href="{{ route('offer.single', ['id' => 2]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
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
                                    <h3>Entire house — no shared spaces</h3>
                                    <p>Ideal for families, couples, and small groups seeking a calm environment.</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="bg-color-op-1 rounded-1 mx-2">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <!-- Content Column -->
                    <div class="col-lg-6">
                        <div class="subtitle id-color wow fadeInUp">Tausi Holiday & Getaway Homes</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">Private Homes Designed for Comfort & Quiet</h2>
                        <p class="wow fadeInUp" data-wow-delay=".4s">
                            Tausi offers fully furnished private houses ideal for families, couples, and small groups seeking a peaceful escape. Each home provides privacy, comfort, and a calm environment — with breakfast included and a simple flat nightly rate.
                        </p>
                        <div class="row g-3 my-4">
                            <div class="col-md-6 wow fadeInUp" data-wow-delay=".0s">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <div class="icon-box mb-3"><i class="fa fa-lightbulb" style="font-size: 24px; color: #decfbc;"></i></div>
                                    </div>
                                    <div>
                                        <h4 class="title">Calm & Comfortable Spaces</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 wow fadeInUp" data-wow-delay=".2s">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <div class="icon-box mb-3"><i class="fa fa-tree" style="font-size: 24px; color: #decfbc;"></i></div>
                                    </div>
                                    <div>
                                        <h4 class="title">Private Outdoor Areas</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2 wow fadeInUp" data-wow-delay=".4s"><i class="fa fa-check me-2" style="color: #decfbc;"></i>Entire house rental — no shared spaces</li>
                            <li class="mb-2 wow fadeInUp" data-wow-delay=".5s"><i class="fa fa-check me-2" style="color: #decfbc;"></i>KES 25,000 per night per house (breakfast included)</li>
                            <li class="mb-2 wow fadeInUp" data-wow-delay=".6s"><i class="fa fa-check me-2" style="color: #decfbc;"></i>Secure parking, Wi-Fi, and on-request services</li>
                        </ul>
                        <div class="wow fadeInUp" data-wow-delay=".8s">
                            <a href="{{ route('properties') }}" class="btn-main fx-slide"><span>View Homes</span></a>
                        </div>
                    </div>
                    <!-- Image Column -->
                    <div class="col-lg-6">
                        <div class="relative wow fadeInRight">
                            <img src="{{ asset('assets/frontend/images/misc/s5.webp') }}" class="w-100 rounded-1 mb-3" alt="Tausi Homes">
                            <img src="{{ asset('assets/frontend/images/misc/s3.webp') }}" class="w-100 rounded-1" alt="Tausi Outdoor">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row g-4 gx-5 justify-content-center">
                    <div class="col-lg-6 text-center">
                        <div class="subtitle id-color">Guest Feedback</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">What Our Guests Say</h2>
                    </div>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".0s">
                        <div class="p-30 bg-white rounded-1 h-100 shadow-sm">
                            <h4 class="mb-1">Esther Mwangi</h4>
                            <span class="text-muted d-block mb-3">Family Guest</span>
                            <p class="mb-0">We booked a whole house for a family weekend and everything was exactly as described. The space was peaceful, breakfast was great, and the privacy made the stay very relaxing.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".1s">
                        <div class="p-30 bg-white rounded-1 h-100 shadow-sm">
                            <h4 class="mb-1">Daniel Otieno</h4>
                            <span class="text-muted d-block mb-3">Business Traveler</span>
                            <p class="mb-0">A quiet and comfortable place to stay while working remotely. Reliable Wi-Fi, clean spaces, and a simple booking process. I would definitely return.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="p-30 bg-white rounded-1 h-100 shadow-sm">
                            <h4 class="mb-1">Miriam Karanja</h4>
                            <span class="text-muted d-block mb-3">Group Guest</span>
                            <p class="mb-0">Perfect for a small group getaway. The house was spacious, well kept, and the environment was calm. Great value for the price.</p>
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

        <!-- Blog section removed per Tausi reference -->

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