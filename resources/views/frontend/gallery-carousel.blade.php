@extends('frontend.layouts.app')
@section('title', 'Gallery - GrandStay')
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
                        <h2 class="fs-60 fs-xs-8vw wow fadeInUp" data-wow-delay=".4s">Gallery</h2>
                    </div>
                </div>
            </div>
            <div class="crumb-wrapper">
                <ul class="crumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Gallery</li>
                </ul>
            </div>
            <div class="sw-overlay op-8"></div>
        </section>

        <section id="section-gallery" class="bg-light" aria-label="section">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="owl-custom-nav menu-float" data-target="#carousel-1">
                            <a class="btn-next"></a>
                            <a class="btn-prev"></a>                                

                            <div id="carousel-1" class="owl-2-cols-center owl-carousel owl-theme">
                                <div class="item rooms">
                    <a href="{{ asset('assets/frontend/images/gallery/1.webp') }}" class="image-popup d-block hover">
                        <div class="relative overflow-hidden rounded-1">
                            <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                            <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                            <img src="{{ asset('assets/frontend/images/gallery/1.webp') }}" class="w-100 hover-scale-1-2" alt="">
                                    </a>
                                </div>

                                <div class="item dining">
                    <a href="{{ asset('assets/frontend/images/gallery/6.webp') }}" class="image-popup d-block hover">
                        <div class="relative overflow-hidden rounded-1">
                            <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                            <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                            <img src="{{ asset('assets/frontend/images/gallery/6.webp') }}" class="w-100 hover-scale-1-2" alt="">
                                    </a>
                                </div>

                                <div class="item facilities">
                    <a href="{{ asset('assets/frontend/images/gallery/9.webp') }}" class="image-popup d-block hover">
                        <div class="relative overflow-hidden rounded-1">
                            <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                            <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                            <img src="{{ asset('assets/frontend/images/gallery/9.webp') }}" class="w-100 hover-scale-1-2" alt="">
                                    </a>
                                </div>

                                <div class="item rooms">
                    <a href="{{ asset('assets/frontend/images/gallery/3.webp') }}" class="image-popup d-block hover">
                        <div class="relative overflow-hidden rounded-1">
                            <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                            <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                            <img src="{{ asset('assets/frontend/images/gallery/3.webp') }}" class="w-100 hover-scale-1-2" alt="">
                                    </a>
                                </div>

                                <div class="item dining">
                    <a href="{{ asset('assets/frontend/images/gallery/8.webp') }}" class="image-popup d-block hover">
                        <div class="relative overflow-hidden rounded-1">
                            <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                            <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                            <img src="{{ asset('assets/frontend/images/gallery/8.webp') }}" class="w-100 hover-scale-1-2" alt="">
                                    </a>
                                </div>

                                <div class="item rooms">
                    <a href="{{ asset('assets/frontend/images/gallery/5.webp') }}" class="image-popup d-block hover">
                        <div class="relative overflow-hidden rounded-1">
                            <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                            <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                            <img src="{{ asset('assets/frontend/images/gallery/5.webp') }}" class="w-100 hover-scale-1-2" alt="">
                                    </a>
                                </div>

                                <div class="item facilities">
                    <a href="{{ asset('assets/frontend/images/gallery/11.webp') }}" class="image-popup d-block hover">
                        <div class="relative overflow-hidden rounded-1">
                            <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                            <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                            <img src="{{ asset('assets/frontend/images/gallery/11.webp') }}" class="w-100 hover-scale-1-2" alt="">
                                    </a>
                                </div>

                                <div class="item rooms">
                    <a href="{{ asset('assets/frontend/images/gallery/2.webp') }}" class="image-popup d-block hover">
                        <div class="relative overflow-hidden rounded-1">
                            <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                            <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                            <img src="{{ asset('assets/frontend/images/gallery/2.webp') }}" class="w-100 hover-scale-1-2" alt="">
                                    </a>
                                </div>

                                <div class="item facilities">
                    <a href="{{ asset('assets/frontend/images/gallery/10.webp') }}" class="image-popup d-block hover">
                        <div class="relative overflow-hidden rounded-1">
                            <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                            <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                            <img src="{{ asset('assets/frontend/images/gallery/10.webp') }}" class="w-100 hover-scale-1-2" alt="">
                                    </a>
                                </div>

                                <div class="item rooms">
                    <a href="{{ asset('assets/frontend/images/gallery/4.webp') }}" class="image-popup d-block hover">
                        <div class="relative overflow-hidden rounded-1">
                            <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                            <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                            <img src="{{ asset('assets/frontend/images/gallery/4.webp') }}" class="w-100 hover-scale-1-2" alt="">
                                    </a>
                                </div>

                                <div class="item dining">
                    <a href="{{ asset('assets/frontend/images/gallery/7.webp') }}" class="image-popup d-block hover">
                        <div class="relative overflow-hidden rounded-1">
                            <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                            <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                            <img src="{{ asset('assets/frontend/images/gallery/7.webp') }}" class="w-100 hover-scale-1-2" alt="">
                                    </a>
                                </div>

                                <div class="item facilities">
                                    <a href="images/gallery/12.webp" class="image-popup d-block hover">
                                        <div class="relative overflow-hidden rounded-1">
                                            <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-3 text-center text-white">View</div>
                                            <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                                            <img src="images/gallery/12.webp" class="w-100 hover-scale-1-2" alt="">
                                        </div>
                                    </a>
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