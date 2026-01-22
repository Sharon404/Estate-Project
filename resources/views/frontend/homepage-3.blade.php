@extends('frontend.layouts.app')
@section('title', 'Homepage - GrandStay')
@section('content')

    <main>
        <a href="#" id="back-to-top"></a>
        <!-- page preloader begin -->
        <div id="de-loader"></div>
        <!-- page preloader close -->
        
        <section class="bg-color-op-1 no-top no-bottom relative rounded-1 overflow-hidden mt-80 mt-sm-50 mx-2">
            <div class="container-fluid position-relative half-fluid">
              <div class="container">
                <div class="row g-4 gx-5">
                  <!-- Image -->
                  <div class="col-lg-6 position-lg-absolute right-half h-100">
                    <div class="de-gradient-edge-top dark"></div>
                    <div class="image" data-bgimage="url({{ asset('assets/frontend/images/misc/s6.webp') }}) center"></div>
                    <div class="abs bg-color text-white p-3 py-3 bottom-0 start-0 w-120px text-center m-5 z-2 rounded-1 wow fadeIn" data-wow-delay=".5s">
                        <h2 class="fs-72 mb-1">25</h2>
                        <div class="fw-500 text-uppercase lh-1-5 fs-12">
                            Year of Experience
                        </div>
                    </div>
                  </div>
                  <!-- Text -->
                  <div class="col-lg-6 relative">
                        <div class="me-lg-3">
                            <div class="spacer-double"></div>
                            <div class="spacer-single sm-hide"></div>
                            <div class="subtitle s2 mb-3 wow fadeInUp" data-wow-delay=".0s">Welcome to Rivora</div>
                            <h1 class="fs-96 lh-1 fs-xs-10vw wow fadeInUp">Ultimate Harmony of Luxury</h1>
                            <p class="wow fadeInUp" data-wow-delay=".4s">Transform your outdoor space with our expert garden services! From design to maintenance, we create beautiful, thriving gardens tailored to your vision. Let us bring your dream garden to life—professional, reliable, and passionate about nature.</p>
                            <a class="btn-main fx-slide wow fadeInUp" data-wow-delay=".8s" href="{{ route('properties') }}"><span>Explore Rooms</span></a>
                            <div class="spacer-double"></div>
                            <div class="spacer-single sm-hide"></div>
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
                        <div class="subtitle mb-0 id-color wow fadeInUp" data-wow-delay=".0s">Enjoy Your Stay</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">Explore Room</h2>
                        <p class="cwow fadeInUp" data-wow-delay=".4s">
                            Discover a curated selection of elegant rooms designed for comfort. From cozy spaces to refined suites, every detail is crafted for a relaxing stay.
                        </p>
                    </div>
                </div>

                <div class="row g-4">

                    <div class="col-md-4">
                        <a href="{{ route('property.single', ['id' => 1]) }}" class="d-block h-100 hover relative">
                            <div class="rounded-1 overflow-hidden">
                                <img src="{{ asset('assets/frontend/images/rooms/1.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                            </div>
                            <div class="pt-4">
                                <div class="d-flex mb-2 fs-15 justify-content-between">
                                    <div class="d-flex">    
                                        <div class="d-flex align-items-center me-3">
                                            <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">2 guests
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">28 ft
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="fs-20 fw-bold">$109</div><span>/night</span>
                                    </div>
                                </div>
                                <div class="relative">
                                    <h3 class="mb-2">Deluxe Room</h3>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="{{ route('property.single', ['id' => 2]) }}" class="d-block h-100 hover relative">
                            <div class="rounded-1 overflow-hidden">
                                <h3 class="abs bg-color rounded-3 text-white fs-20 lh-1 p-2 px-3 m-4 top-0 start-0 z-3">Best Selling</h3>
                                <img src="{{ asset('assets/frontend/images/rooms/2.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                            </div>
                            <div class="pt-4">
                                <div class="d-flex mb-2 fs-15 justify-content-between">
                                    <div class="d-flex">    
                                        <div class="d-flex align-items-center me-3">
                                            <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">2 guests
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">28 ft
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="fs-20 fw-bold">$129</div><span>/night</span>
                                    </div>
                                </div>
                                <div class="relative">
                                    <h3 class="mb-2">Superior Room</h3>
                                </div>
                            </div>
                        </a>
                    </div>           

                    <div class="col-md-4">
                        <a href="{{ route('property.single', ['id' => 3]) }}" class="d-block h-100 hover relative">
                            <div class="rounded-1 overflow-hidden">
                                <img src="{{ asset('assets/frontend/images/rooms/3.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                            </div>
                            <div class="pt-4">
                                <div class="d-flex mb-2 fs-15 justify-content-between">
                                    <div class="d-flex">    
                                        <div class="d-flex align-items-center me-3">
                                            <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">2 guests
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">28 ft
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="fs-20 fw-bold">$149</div><span>/night</span>
                                    </div>
                                </div>
                                <div class="relative">
                                    <h3 class="mb-2">Executive Room</h3>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="{{ route('property.single', ['id' => 4]) }}" class="d-block h-100 hover relative">
                            <div class="rounded-1 overflow-hidden">
                                <img src="{{ asset('assets/frontend/images/rooms/4.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                            </div>
                            <div class="pt-4">
                                <div class="d-flex mb-2 fs-15 justify-content-between">
                                    <div class="d-flex">    
                                        <div class="d-flex align-items-center me-3">
                                            <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">2 guests
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">28 ft
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="fs-20 fw-bold">$179</div><span>/night</span>
                                    </div>
                                </div>
                                <div class="relative">
                                    <h3 class="mb-2">Premium Suite</h3>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="{{ route('property.single', ['id' => 5]) }}" class="d-block h-100 hover relative">
                            <div class="rounded-1 overflow-hidden">
                                <img src="{{ asset('assets/frontend/images/rooms/5.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                            </div>
                            <div class="pt-4">
                                <div class="d-flex mb-2 fs-15 justify-content-between">
                                    <div class="d-flex">    
                                        <div class="d-flex align-items-center me-3">
                                            <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">4 guests
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">50 ft
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="fs-20 fw-bold">$229</div><span>/night</span>
                                    </div>
                                </div>
                                <div class="relative">
                                    <h3 class="mb-2">Family Suite</h3>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="{{ route('property.single', ['id' => 6]) }}" class="d-block h-100 hover relative">
                            <div class="rounded-1 overflow-hidden">
                                <img src="{{ asset('assets/frontend/images/rooms/6.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                            </div>
                            <div class="pt-4">
                                <div class="d-flex mb-2 fs-15 justify-content-between">
                                    <div class="d-flex">    
                                        <div class="d-flex align-items-center me-3">
                                            <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">4 guests
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">60 ft
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="fs-20 fw-bold">$309</div><span>/night</span>
                                    </div>
                                </div>
                                <div class="relative">
                                    <h3 class="mb-2">Luxury Suite</h3>
                                </div>
                            </div>
                        </a>
                    </div>       

                </div>
            </div>
        </section>


        <section class="text-light jarallax mx-2 rounded-1 overflow-hidden">
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
                        <p class="cwow fadeInUp" data-wow-delay=".4s">
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

                            <!-- TOTAL ROOMS -->
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
                                <div class="p-30 bg-dark-2 rounded-1 h-100" data-bgimage="url({{ asset('assets/frontend/images/misc/s2.webp') }}) center">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>


        <div class="container">
            <div class="row g-4 mb-2 justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="subtitle id-color wow fadeInUp">Exclusive Deals</div>
                    <h2 class="wow fadeInUp" data-wow-delay=".2s">Latest Hotel Offers</h2>
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
                                <h3>Romantic Stay</h3>
                                <p>20% Off Weekend Packages</p>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 wow fadeInUp" data-wow-delay=".4s">
                    <div class="overflow-hidden rounded-1">
                        <div class="p-40 bg-dark-2 text-light relative">
                            <a class="text-white" href="{{ route('offers') }}">
                                <h3>Early Bird Deal</h3>
                                <p>Save Up to 30% on Rooms</p>
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
                                <h3>Family Getaway</h3>
                                <p>Kids Stay & Eat Free</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <section>
            <div class="container">
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-6">
                        <div class="subtitle id-color">FAQ</div>
                        <h2 class="split">
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
                                    Do you offer airport pick-up or shuttle service?
                                </div>
                                <div class="accordion-section-content" id="accordion-b2">
                                    <p class="mb-0">
                                        Yes, we provide airport transfers and private shuttle services. Please contact us in advance to arrange transportation.
                                    </p>
                                </div>

                                <div class="accordion-section-title" data-tab="#accordion-b3">
                                    Are pets allowed in the hotel?
                                </div>
                                <div class="accordion-section-content" id="accordion-b3">
                                    <p class="mb-0">
                                        We welcome small pets in designated pet-friendly rooms. Additional cleaning fees may apply.
                                    </p>
                                </div>

                                <div class="accordion-section-title" data-tab="#accordion-b4">
                                    Do you have free Wi-Fi?
                                </div>
                                <div class="accordion-section-content" id="accordion-b4">
                                    <p class="mb-0">
                                        Yes, complimentary high-speed Wi-Fi is available throughout the hotel, including rooms and public areas.
                                    </p>
                                </div>

                                <div class="accordion-section-title" data-tab="#accordion-b5">
                                    What facilities are available for guests?
                                </div>
                                <div class="accordion-section-content" id="accordion-b5">
                                    <p class="mb-0">
                                        Guests can enjoy our swimming pool, fitness center, spa, restaurant, lounge bar, and business center. Facility access varies by room type.
                                    </p>
                                </div>

                                <div class="accordion-section-title" data-tab="#accordion-b6">
                                    Do you offer breakfast?
                                </div>
                                <div class="accordion-section-content" id="accordion-b6">
                                    <p class="mb-0">
                                        Yes, we offer daily breakfast with continental and international options. Breakfast is included for certain room packages.
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

        <section>
            <div class="container">
                <div class="row g-4 mb-2 justify-content-center">
                    <div class="col-lg-6 text-center">
                        <div class="subtitle id-color wow fadeInUp">Our Blog</div>
                        <h2 class="wow fadeInUp" data-wow-delay=".2s">News &amp; Articles</h2>
                    </div>
                </div>

                <div class="row g-4">

                    <div class="col-xl-3 col-lg-6">
                    <div class="overflow-hidden">
                        <div class="hover relative">
                            <div class="abs z-2 bg-blur text-white p-3 pb-2 m-4 text-center fw-600 rounded-3">
                                <div class="fs-36 fw-bold lh-1">20</div>
                                <span>Jun</span>
                            </div>
                            <div class="rounded-1 overflow-hidden">
                                <img src="{{ asset('assets/frontend/images/blog/1.jpg') }}" class="w-100 hover-scale-1-1" alt="">
                            </div>
                            <a href="{{ route('blog.single', ['id' => 1]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                        </div>

                        <div class="pt-4 relative">
                            <a href="{{ route('blog.single', ['id' => 1]) }}">
                                <h3>Top Hotel Amenities That Guests Love in 2025 Trends</h3>
                            </a>
                            <p>From smart-room technology to wellness features that elevate guest comfort and overall satisfaction.</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="overflow-hidden">
                        <div class="hover relative">
                            <div class="abs z-2 bg-blur text-white p-3 pb-2 m-4 text-center fw-600 rounded-3">
                                <div class="fs-36 fw-bold lh-1">19</div>
                                <span>Jun</span>
                            </div>
                            <div class="rounded-1 overflow-hidden">
                                <img src="{{ asset('assets/frontend/images/blog/2.jpg') }}" class="w-100 hover-scale-1-1" alt="">
                            </div>
                            <a href="{{ route('blog.single', ['id' => 2]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                        </div>

                        <div class="pt-4 relative">
                            <a href="{{ route('blog.single', ['id' => 2]) }}">
                                <h3>How to Choose the Perfect Room for Your Stay</h3>
                            </a>
                            <p>Learn how to choose the ideal hotel room based on comfort, layout, and travel needs for a better stay.</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="overflow-hidden">
                        <div class="hover relative">
                            <div class="abs z-2 bg-blur text-white p-3 pb-2 m-4 text-center fw-600 rounded-3">
                                <div class="fs-36 fw-bold lh-1">18</div>
                                <span>Jun</span>
                            </div>
                            <div class="rounded-1 overflow-hidden">
                                <img src="{{ asset('assets/frontend/images/blog/3.jpg') }}" class="w-100 hover-scale-1-1" alt="">
                            </div>
                            <a href="{{ route('blog.single', ['id' => 3]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                        </div>

                        <div class="pt-4 relative">
                            <a href="{{ route('blog.single', ['id' => 3]) }}">
                                <h3>The Art of Hospitality: Behind Our Signature Services</h3>
                            </a>
                            <p>Discover the thoughtful services and attention to detail that define exceptional hospitality experiences.</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="overflow-hidden">
                        <div class="hover relative">
                            <div class="abs z-2 bg-blur text-white p-3 pb-2 m-4 text-center fw-600 rounded-3">
                                <div class="fs-36 fw-bold lh-1">17</div>
                                <span>Jun</span>
                            </div>
                            <div class="rounded-1 overflow-hidden">
                                <img src="{{ asset('assets/frontend/images/blog/4.jpg') }}" class="w-100 hover-scale-1-1" alt="">
                            </div>
                            <a href="{{ route('blog.single', ['id' => 4]) }}" class="d-block abs w-100 h-100 top-0 start-0"></a>
                        </div>

                        <div class="pt-4 relative">
                            <a href="{{ route('blog.single', ['id' => 4]) }}">
                                <h3>Why Location Matters: Choosing a Hotel for Your Next Trip</h3>
                            </a>
                            <p>See how hotel location impacts convenience, accessibility, and overall travel experience.</p>
                        </div>
                    </div>
                </div>

                </div>
            </div>
        </section>

    </main>

    <!-- Javascript Files
    ================================================== -->
    @push('scripts')
    <script src="{{ asset('assets/frontend/js/vendors.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/designesia.js') }}"></script>

    <!-- swiper slider -->
    <script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/custom-swiper-1.js') }}"></script>

    <!-- form -->
    <script src="{{ asset('assets/frontend/js/moment.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/custom-datepicker.js') }}"></script>
    @endpush

@endsection