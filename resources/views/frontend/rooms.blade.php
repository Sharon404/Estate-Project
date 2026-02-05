@extends('frontend.layouts.app')
@section('title', 'Homes - Tausirental')
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
                        <h2 class="subtitle id-color wow fadeInUp mb-2">Simple Pricing</h2>
                        <div class="clearfix"></div>
                        <h2 class="fs-60 fs-xs-8vw wow fadeInUp" data-wow-delay=".4s">Available Homes</h2>
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

                    @forelse($properties as $property)
                        @if($loop->first)
                        <div class="col-lg-8 offset-lg-2">
                            <a href="{{ route('property.single', ['id' => $property->id]) }}" class="d-block h-100 hover relative">
                                <div class="rounded-1 overflow-hidden">
                                    @if($property->photos && $property->photos->count() > 0)
                                        <img src="{{ $property->photos->first()->url ?? asset('assets/frontend/images/rooms/1.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                                    @else
                                        <img src="{{ asset('assets/frontend/images/rooms/1.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                                    @endif
                                </div>
                                <div class="pt-4">
                                    <div class="d-flex mb-2 fs-15 justify-content-between">
                                        <div class="d-flex">    
                                            <div class="d-flex align-items-center me-3">
                                                <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">{{ $property->max_guests }} guests
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">Entire Home
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="fs-20 fw-bold">KES {{ number_format($property->nightly_rate) }}</div><span>/night</span>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <h3 class="mb-2">{{ $property->name }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @else
                        <div class="col-md-6">
                            <a href="{{ route('property.single', ['id' => $property->id]) }}" class="d-block h-100 hover relative">
                                <div class="rounded-1 overflow-hidden">
                                    @if($property->photos && $property->photos->count() > 0)
                                        <img src="{{ $property->photos->first()->url ?? asset('assets/frontend/images/rooms/2.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                                    @else
                                        <img src="{{ asset('assets/frontend/images/rooms/' . (($loop->index % 5) + 2) . '.jpg') }}" class="w-100 hover-scale-1-2" alt="">
                                    @endif
                                </div>
                                <div class="pt-4">
                                    <div class="d-flex mb-2 fs-15 justify-content-between">
                                        <div class="d-flex">    
                                            <div class="d-flex align-items-center me-3">
                                                <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">{{ $property->max_guests }} guests
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt="">Entire Home
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="fs-20 fw-bold">KES {{ number_format($property->nightly_rate) }}</div><span>/night</span>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <h3 class="mb-2">{{ $property->name }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endif
                    @empty
                        <div class="col-12 text-center">
                            <p class="text-muted fs-18">No homes available at the moment.</p>
                        </div>
                    @endforelse       

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