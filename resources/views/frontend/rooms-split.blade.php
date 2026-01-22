@extends('frontend.layouts.app')
@section('title', 'Rooms Split - GrandStay')
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

        <div class="rounded-1 overflow-hidden m-2">
            <!-- Deluxe Room -->
            <section class="p-0">
               <div class="container-fluid position-relative half-fluid">
                  <div class="container">
                     <div class="row">
                        <div class="col-lg-6 position-lg-absolute right-half h-100">
                           <div class="triangle-bottomright-dark"></div>
                           <div class="image" data-bgimage="url({{ asset('assets/frontend/images/rooms/1.jpg') }}) center"></div>
                        </div>
                        <div class="col-lg-6">
                           <div class="me-lg-3 py-5">
                              <div class="col-lg-8">
                                 <h3 class="m-0">Deluxe Room</h3>
                                 <span class="d-stars id-color d-block mb-4">
                                 <i class="icofont-star"></i><i class="icofont-star"></i>
                                 <i class="icofont-star"></i><i class="icofont-star"></i>
                                 <i class="icofont-star"></i>
                                 </span>
                                 <p class="pb-0">
                                    Elegant and cozy room designed for comfort and relaxation, featuring tasteful interiors and a warm, inviting atmosphere throughout.
                                 </p>
                                 <div class="fs-15">
                                    <div class="d-flex align-items-center mb-1">
                                       <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">2 guests
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                       <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">28 ft
                                    </div>
                                 </div>
                                 <div class="spacer-single"></div>
                                 <div class="d-flex">
                                    <div class="d-flex align-items-center me-5">
                                       <div class="fs-24 fw-bold mb-0 me-1">$109</div>
                                       <span>/night</span>
                                    </div>
                                    <a href="{{ route('property.single', ['id' => 1]) }}" class="btn-main fx-slide hover-white"><span>Select Room</span></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <!-- Superior Room -->
            <section class="p-0">
               <div class="container-fluid position-relative half-fluid">
                  <div class="container">
                     <div class="row">
                        <div class="col-lg-6 position-lg-absolute left-half h-100">
                           <div class="triangle-bottomright-dark"></div>
                           <div class="image" data-bgimage="url({{ asset('assets/frontend/images/rooms/2.jpg') }}) center"></div>
                        </div>
                        <div class="col-lg-6 offset-lg-7">
                           <div class="me-lg-3 py-5">
                              <div class="col-lg-8">
                                 <h3 class="m-0">Superior Room</h3>
                                 <span class="d-stars id-color d-block mb-4">
                                 <i class="icofont-star"></i><i class="icofont-star"></i>
                                 <i class="icofont-star"></i><i class="icofont-star"></i>
                                 <i class="icofont-star"></i>
                                 </span>
                                 <p class="pb-0">
                                    Refined and spacious room offering modern design details and enhanced comfort for a more enjoyable stay.
                                 </p>
                                 <div class="fs-15">
                                    <div class="d-flex align-items-center mb-1">
                                       <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">2 guests
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                       <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">30 ft
                                    </div>
                                 </div>
                                 <div class="spacer-single"></div>
                                 <div class="d-flex">
                                    <div class="d-flex align-items-center me-5">
                                       <div class="fs-24 fw-bold mb-0 me-1">$129</div>
                                       <span>/night</span>
                                    </div>
                                    <a href="{{ route('property.single', ['id' => 2]) }}" class="btn-main fx-slide hover-white"><span>Select Room</span></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <!-- Executive Room -->
            <section class="p-0">
               <div class="container-fluid position-relative half-fluid">
                  <div class="container">
                     <div class="row">
                        <div class="col-lg-6 position-lg-absolute right-half h-100">
                           <div class="triangle-bottomright-dark"></div>
                           <div class="image" data-bgimage="url({{ asset('assets/frontend/images/rooms/3.jpg') }}) center"></div>
                        </div>
                        <div class="col-lg-6">
                           <div class="me-lg-3 py-5">
                              <div class="col-lg-8">
                                 <h3 class="m-0">Executive Room</h3>
                                 <span class="d-stars id-color d-block mb-4">
                                 <i class="icofont-star"></i><i class="icofont-star"></i>
                                 <i class="icofont-star"></i><i class="icofont-star"></i>
                                 <i class="icofont-star"></i>
                                 </span>
                                 <p class="pb-0">
                                    Designed for business travelers, combining comfort, functionality, and a calm environment for productivity.
                                 </p>
                                 <div class="fs-15">
                                    <div class="d-flex align-items-center mb-1">
                                       <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">2 guests
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                       <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">32 ft
                                    </div>
                                 </div>
                                 <div class="spacer-single"></div>
                                 <div class="d-flex">
                                    <div class="d-flex align-items-center me-5">
                                       <div class="fs-24 fw-bold mb-0 me-1">$149</div>
                                       <span>/night</span>
                                    </div>
                                    <a href="{{ route('property.single', ['id' => 3]) }}" class="btn-main fx-slide hover-white"><span>Select Room</span></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <!-- Premium Suite -->
            <section class="p-0">
               <div class="container-fluid position-relative half-fluid">
                  <div class="container">
                     <div class="row">
                        <div class="col-lg-6 position-lg-absolute left-half h-100">
                           <div class="triangle-bottomright-dark"></div>
                           <div class="image" data-bgimage="url({{ asset('assets/frontend/images/rooms/4.jpg') }}) center"></div>
                        </div>
                        <div class="col-lg-6 offset-lg-7">
                           <div class="me-lg-3 py-5">
                              <div class="col-lg-8">
                                 <h3 class="m-0">Premium Suite</h3>
                                 <span class="d-stars id-color d-block mb-4">
                                 <i class="icofont-star"></i><i class="icofont-star"></i>
                                 <i class="icofont-star"></i><i class="icofont-star"></i>
                                 <i class="icofont-star"></i>
                                 </span>
                                 <p class="pb-0">
                                    Luxury suite with generous living space and refined details for an elevated stay experience.
                                 </p>
                                 <div class="fs-15">
                                    <div class="d-flex align-items-center mb-1">
                                       <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">2 guests
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                       <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">38 ft
                                    </div>
                                 </div>
                                 <div class="spacer-single"></div>
                                 <div class="d-flex">
                                    <div class="d-flex align-items-center me-5">
                                       <div class="fs-24 fw-bold mb-0 me-1">$179</div>
                                       <span>/night</span>
                                    </div>
                                    <a href="{{ route('property.single', ['id' => 4]) }}" class="btn-main fx-slide hover-white"><span>Select Room</span></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <!-- Family Suite -->
            <section class="p-0">
               <div class="container-fluid position-relative half-fluid">
                  <div class="container">
                     <div class="row">
                        <div class="col-lg-6 position-lg-absolute right-half h-100">
                           <div class="triangle-bottomright-dark"></div>
                           <div class="image" data-bgimage="url({{ asset('assets/frontend/images/rooms/5.jpg') }}) center"></div>
                        </div>
                        <div class="col-lg-6">
                           <div class="me-lg-3 py-5">
                              <div class="col-lg-8">
                                 <h3 class="m-0">Family Suite</h3>
                                 <span class="d-stars id-color d-block mb-4">
                                 <i class="icofont-star"></i><i class="icofont-star"></i>
                                 <i class="icofont-star"></i><i class="icofont-star"></i>
                                 <i class="icofont-star"></i>
                                 </span>
                                 <p class="pb-0">
                                    Spacious and family-friendly suite offering comfort and convenience for a relaxing stay together.
                                 </p>
                                 <div class="fs-15">
                                    <div class="d-flex align-items-center mb-1">
                                       <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">4 guests
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                       <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">42 ft
                                    </div>
                                 </div>
                                 <div class="spacer-single"></div>
                                 <div class="d-flex">
                                    <div class="d-flex align-items-center me-5">
                                       <div class="fs-24 fw-bold mb-0 me-1">$199</div>
                                       <span>/night</span>
                                    </div>
                                    <a href="{{ route('property.single', ['id' => 5]) }}" class="btn-main fx-slide hover-white"><span>Select Room</span></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <!-- Luxury Suite -->
            <section class="p-0">
               <div class="container-fluid position-relative half-fluid">
                  <div class="container">
                     <div class="row">
                        <div class="col-lg-6 position-lg-absolute left-half h-100">
                           <div class="triangle-bottomright-dark"></div>
                           <div class="image" data-bgimage="url({{ asset('assets/frontend/images/rooms/6.jpg') }}) center"></div>
                        </div>
                        <div class="col-lg-6 offset-lg-7">
                           <div class="me-lg-3 py-5">
                              <div class="col-lg-8">
                                 <h3 class="m-0">Luxury Suite</h3>
                                 <span class="d-stars id-color d-block mb-4">
                                 <i class="icofont-star"></i><i class="icofont-star"></i>
                                 <i class="icofont-star"></i><i class="icofont-star"></i>
                                 <i class="icofont-star"></i>
                                 </span>
                                 <p class="pb-0">
                                    An exclusive suite offering expansive space, premium amenities, and a truly refined atmosphere.
                                 </p>
                                 <div class="fs-15">
                                    <div class="d-flex align-items-center mb-1">
                                       <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">2 guests
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                       <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">50 ft
                                    </div>
                                 </div>
                                 <div class="spacer-single"></div>
                                 <div class="d-flex">
                                    <div class="d-flex align-items-center me-5">
                                       <div class="fs-24 fw-bold mb-0 me-1">$249</div>
                                       <span>/night</span>
                                    </div>
                                    <a href="{{ route('property.single', ['id' => 6]) }}" class="btn-main fx-slide hover-white"><span>Select Room</span></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
        </div>

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