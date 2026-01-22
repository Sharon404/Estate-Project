@extends('frontend.layouts.app')
@section('title', 'Facilities - GrandStay')
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
                        <div class="subtitle id-color wow fadeInUp mb-2">Enjoy Your Stay</div>
                        <div class="clearfix"></div>
                        <h2 class="fs-60 fs-xs-8vw wow fadeInUp" data-wow-delay=".4s">Facilities</h2>
                    </div>
                </div>
            </div>
            <div class="crumb-wrapper">
                <ul class="crumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Facilities</li>
                </ul>
            </div>
            <div class="sw-overlay op-8"></div>
        </section>

        <section class="bg-light">
            <div class="container">

                <div class="row g-0">
                    <div class="col-lg-6">
                        <div>
                            <div class="row g-0 align-items-end">
                                <div class="col-md-6">
                                    <div class="relative overflow-hidden">
                                        <img src="{{ asset('assets/frontend/images/misc/s4.webp') }}" class="w-100 hover-scale-1-2 wow fadeIn fadeInRightBig" data-wow-delay=".0s" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-40 wow fadeInRight" data-wow-delay=".2s">
                                        <h3>Cafe and Restaurant</h3>
                                        <p class="mb-0">Esse aute incididunt mollit quis et in veniam id officia ad nostrud eiusmod laborum.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div>
                            <div class="row g-0 align-items-end">
                                <div class="col-md-6">
                                    <div class="relative overflow-hidden">
                                        <img src="{{ asset('assets/frontend/images/misc/s5.webp') }}" class="w-100 hover-scale-1-2 wow fadeIn fadeInRightBig" data-wow-delay=".2s" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-40 wow fadeInRight" data-wow-delay=".4s">
                                        <h3>Swimming Pool</h3>
                                        <p class="mb-0">Esse aute incididunt mollit quis et in veniam id officia ad nostrud eiusmod laborum.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div>
                            <div class="row g-0 align-items-start">
                                <div class="col-sm-6">
                                    <div class="p-40 wow fadeInRight" data-wow-delay=".6s">
                                        <h3>Spa & Massage</h3>
                                        <p class="mb-0">Esse aute incididunt mollit quis et in veniam id officia ad nostrud eiusmod laborum.</p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="relative overflow-hidden">
                                        <img src="{{ asset('assets/frontend/images/misc/s6.webp') }}" class="w-100 hover-scale-1-2 wow fadeIn fadeInRightBig" data-wow-delay=".4s" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div>
                            <div class="row g-0 align-items-start">
                                <div class="col-sm-6">
                                    <div class="p-40 wow fadeInRight" data-wow-delay=".8s">
                                        <h3>Fitness Center</h3>
                                        <p class="mb-0">Esse aute incididunt mollit quis et in veniam id officia ad nostrud eiusmod laborum.</p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="relative overflow-hidden">
                                        <img src="{{ asset('assets/frontend/images/misc/s7.webp') }}" class="w-100 hover-scale-1-2 wow fadeIn fadeInRightBig" data-wow-delay=".6s" alt="">
                                    </div>
                                </div>
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
    @endpush

@endsection