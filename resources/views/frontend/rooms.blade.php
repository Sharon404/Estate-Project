@extends('frontend.layouts.app')
@section('title', 'Rooms - GrandStay')
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
                        <h2 class="subtitle id-color wow fadeInUp mb-2">Enjoy Your Stay</h2>
                        <div class="clearfix"></div>
                        <h2 class="fs-60 fs-xs-8vw wow fadeInUp" data-wow-delay=".4s">Our Rooms</h2>
                    </div>
                </div>
            </div>
            <div class="crumb-wrapper">
                <ul class="crumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Rooms</li>
                </ul>
            </div>
            <div class="sw-overlay op-8"></div>
        </section>


        <section>
            <div class="container">

                <div class="row g-4">

                    <div class="col-md-6">
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

                    <div class="col-md-6">
                        <a href="#" class="d-block h-100 hover relative">
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

                    <div class="col-md-6">
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

                    <div class="col-md-6">
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

                    <div class="col-md-6">
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

                    <div class="col-md-6">
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


        <section class="text-light jarallax m-2 rounded-1 overflow-hidden">
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
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">Their campaign made our brand shine online. Outstanding creativity and flawless execution.</h3>
                                <span class="wow fadeInUp">Anna L., Paris</span>
                            </div>
                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">Our traffic grew beyond expectations. A truly data-driven and impactful partnership.</h3>
                                <span class="wow fadeInUp">Michael H., Toronto</span>
                            </div>
                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">The attention to our goals was amazing. Every ad reflected our brand perfectly.</h3>
                                <span class="wow fadeInUp">Nadia R., Dubai</span>
                            </div>
                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">Working with them was effortless and inspiring. The best digital agency experience.</h3>
                                <span class="wow fadeInUp">Tom S., Los Angeles</span>
                            </div>
                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">From SEO to ads, everything delivered results. A partner we truly trust.</h3>
                                <span class="wow fadeInUp">Elise K., Amsterdam</span>
                            </div>
                            <div class="item">
                                <span class="d-stars id-color d-block mb-3">
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                    <i class="icofont-star"></i>
                                </span>
                                <h3 class="mb-4 wow fadeInUp fs-40">Professional, creative, and results-driven. Our leads doubled in just two months.</h3>
                                <span class="wow fadeInUp">David M., Singapore</span>
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