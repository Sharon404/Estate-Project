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

        <section>
            <div class="container">

                <!-- Deluxe Room -->
                <div class="room-item hover p-2 rounded-1 bg-white mb-4">
                    <div class="row g-5 align-items-center">
                        <div class="col-md-4">
                            <a href="{{ route('property.single', ['id' => 1]) }}" class="d-block hover relative">
                                <div class="rounded-1 overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/rooms/1.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="p-4">
                                <div class="row justify-content-between">
                                    <div class="col-md-6">
                                        <h3 class="m-0">Deluxe Room</h3>
                                        <span class="d-stars id-color d-block">
                                            <i class="icofont-star"></i><i class="icofont-star"></i>
                                            <i class="icofont-star"></i><i class="icofont-star"></i>
                                            <i class="icofont-star"></i>
                                        </span>
                                        <p class="pb-0">
                                            Elegant and cozy room designed for comfort and relaxation, featuring tasteful interiors and a warm, inviting atmosphere.
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fs-15">
                                            <div class="d-flex align-items-center mb-1">
                                                <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">2 guests
                                            </div>
                                            <div class="d-flex align-items-center mb-1">
                                                <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">28 ft
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="fs-24 fw-bold mb-0 me-1">KES 10,900</div><span>/night</span>
                                            </div>
                                            <div class="spacer-single"></div>
                                            <a href="{{ route('property.single', ['id' => 1]) }}" class="btn-main fx-slide hover-white"><span>Select Room</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Superior Room -->
                <div class="room-item hover p-2 rounded-1 bg-white mb-4">
                    <div class="row g-5 align-items-center">
                        <div class="col-md-4">
                            <a href="{{ route('property.single', ['id' => 2]) }}" class="d-block hover relative">
                                <div class="rounded-1 overflow-hidden position-relative">
                                    <span class="abs bg-color text-white fs-14 p-2 px-3 m-3 rounded-3 top-0 start-0 z-3">
                                        Best Selling
                                    </span>
                                    <img src="{{ asset('assets/frontend/images/rooms/2.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="p-4">
                                <div class="row justify-content-between">
                                    <div class="col-md-6">
                                        <h3 class="m-0">Superior Room</h3>
                                        <span class="d-stars id-color d-block">
                                            <i class="icofont-star"></i><i class="icofont-star"></i>
                                            <i class="icofont-star"></i><i class="icofont-star"></i>
                                            <i class="icofont-star"></i>
                                        </span>
                                        <p class="pb-0">
                                            Refined space with premium amenities and modern design, offering enhanced comfort for a more enjoyable stay.
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fs-15">
                                            <div class="d-flex align-items-center mb-1">
                                                <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">2 guests
                                            </div>
                                            <div class="d-flex align-items-center mb-1">
                                                <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">28 ft
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="fs-24 fw-bold mb-0 me-1">KES 12,900</div><span>/night</span>
                                            </div>
                                            <div class="spacer-single"></div>
                                            <a href="{{ route('property.single', ['id' => 2]) }}" class="btn-main fx-slide hover-white"><span>Select Room</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Executive Room -->
                <div class="room-item hover p-2 rounded-1 bg-white mb-4">
                    <div class="row g-5 align-items-center">
                        <div class="col-md-4">
                            <a href="{{ route('property.single', ['id' => 3]) }}" class="d-block hover relative">
                                <div class="rounded-1 overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/rooms/3.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="p-4">
                                <div class="row justify-content-between">
                                    <div class="col-md-6">
                                        <h3 class="m-0">Executive Room</h3>
                                        <span class="d-stars id-color d-block">
                                            <i class="icofont-star"></i><i class="icofont-star"></i>
                                            <i class="icofont-star"></i><i class="icofont-star"></i>
                                            <i class="icofont-star"></i>
                                        </span>
                                        <p class="pb-0">
                                            Ideal for business travelers, combining comfort and functionality with a calm and well-designed interior.
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fs-15">
                                            <div class="d-flex align-items-center mb-1">
                                                <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">2 guests
                                            </div>
                                            <div class="d-flex align-items-center mb-1">
                                                <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">28 ft
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="fs-24 fw-bold mb-0 me-1">KES 14,900</div><span>/night</span>
                                            </div>
                                            <div class="spacer-single"></div>
                                            <a href="{{ route('property.single', ['id' => 3]) }}" class="btn-main fx-slide hover-white"><span>Select Room</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Premium Suite -->
                <div class="room-item hover p-2 rounded-1 bg-white mb-4">
                    <div class="row g-5 align-items-center">
                        <div class="col-md-4">
                            <a href="{{ route('property.single', ['id' => 4]) }}" class="d-block hover relative">
                                <div class="rounded-1 overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/rooms/4.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="p-4">
                                <div class="row justify-content-between">
                                    <div class="col-md-6">
                                        <h3 class="m-0">Premium Suite</h3>
                                        <span class="d-stars id-color d-block">
                                            <i class="icofont-star"></i><i class="icofont-star"></i>
                                            <i class="icofont-star"></i><i class="icofont-star"></i>
                                            <i class="icofont-star"></i>
                                        </span>
                                        <p class="pb-0">
                                            Luxury suite offering spacious living areas and refined details for a more elevated stay experience.
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fs-15">
                                            <div class="d-flex align-items-center mb-1">
                                                <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">2 guests
                                            </div>
                                            <div class="d-flex align-items-center mb-1">
                                                <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">28 ft
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="fs-24 fw-bold mb-0 me-1">KES 17,900</div><span>/night</span>
                                            </div>
                                            <div class="spacer-single"></div>
                                            <a href="{{ route('property.single', ['id' => 4]) }}" class="btn-main fx-slide hover-white"><span>Select Room</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Family Suite -->
                <div class="room-item hover p-2 rounded-1 bg-white mb-4">
                    <div class="row g-5 align-items-center">
                        <div class="col-md-4">
                            <a href="{{ route('property.single', ['id' => 5]) }}" class="d-block hover relative">
                                <div class="rounded-1 overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/rooms/5.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="p-4">
                                <div class="row justify-content-between">
                                    <div class="col-md-6">
                                        <h3 class="m-0">Family Suite</h3>
                                        <span class="d-stars id-color d-block">
                                            <i class="icofont-star"></i><i class="icofont-star"></i>
                                            <i class="icofont-star"></i><i class="icofont-star"></i>
                                            <i class="icofont-star"></i>
                                        </span>
                                        <p class="pb-0">
                                            Perfect choice for families, providing generous space and thoughtful layout for added comfort.
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fs-15">
                                            <div class="d-flex align-items-center mb-1">
                                                <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">4 guests
                                            </div>
                                            <div class="d-flex align-items-center mb-1">
                                                <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">50 ft
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="fs-24 fw-bold mb-0 me-1">KES 22,900</div><span>/night</span>
                                            </div>
                                            <div class="spacer-single"></div>
                                            <a href="{{ route('property.single', ['id' => 5]) }}" class="btn-main fx-slide hover-white"><span>Select Room</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Luxury Suite -->
                <div class="room-item hover p-2 rounded-1 bg-white">
                    <div class="row g-5 align-items-center">
                        <div class="col-md-4">
                            <a href="{{ route('property.single', ['id' => 6]) }}" class="d-block hover relative">
                                <div class="rounded-1 overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/rooms/6.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="p-4">
                                <div class="row justify-content-between">
                                    <div class="col-md-6">
                                        <h3 class="m-0">Luxury Suite</h3>
                                        <span class="d-stars id-color d-block">
                                            <i class="icofont-star"></i><i class="icofont-star"></i>
                                            <i class="icofont-star"></i><i class="icofont-star"></i>
                                            <i class="icofont-star"></i>
                                        </span>
                                        <p class="pb-0">
                                            Ultimate luxury experience featuring exclusive facilities and refined design for discerning guests.
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fs-15">
                                            <div class="d-flex align-items-center mb-1">
                                                <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">4 guests
                                            </div>
                                            <div class="d-flex align-items-center mb-1">
                                                <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">60 ft
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="fs-24 fw-bold mb-0 me-1">$309</div><span>/night</span>
                                            </div>
                                            <div class="spacer-single"></div>
                                            <a href="{{ route('property.single', ['id' => 6]) }}" class="btn-main fx-slide hover-white"><span>Select Room</span></a>
                                        </div>
                                    </div>
                                </div>
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