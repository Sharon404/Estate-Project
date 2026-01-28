@extends('frontend.layouts.app')
@section('title', 'Room Detail - GrandStay')
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
                        <h2 class="fs-60 fs-xs-8vw wow fadeInUp" data-wow-delay=".4s">Tausi Holiday Home - Nanyuki</h2>
                    </div>
                </div>
            </div>
            <div class="crumb-wrapper">
                <ul class="crumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('properties') }}">Rooms</a></li>
                    <li class="active">Tausi Holiday Home - Nanyuki</li>
                </ul>
            </div>
            <div class="sw-overlay op-8"></div>
        </section>

        <section>
            <div class="container">
                <div class="row g-4 gx-5">

                    <div class="col-lg-12">

                        <div class="p-4 fs-18 rounded-1 bg-color-op-1 d-lg-flex d-sm-block flex-wrap align-items-center justify-content-between gap-4 mb-4 fw-500">
                            <div class="me-4 d-lg-block py-2 d-sm-inline-block relative lh-1-3 ps-30"><img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="abs w-20px start-0" alt="">4-6 Guests</div>
                            <div class="me-4 d-lg-block py-2 d-sm-inline-block relative lh-1-3 ps-30"><img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="abs w-20px start-0" alt="">2 Bedroom</div>
                            <div class="me-4 d-lg-block py-2 d-sm-inline-block relative lh-1-3 ps-30"><img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="abs w-20px start-0" alt=""> King Bed</div>
                            <div class="me-4 d-lg-block py-2 d-sm-inline-block relative lh-1-3 ps-30"><img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="abs w-20px start-0" alt=""> City View</div>
                            <div class="d-lg-block d-sm-inline-block"><h2 class="fs-40 m-0 lh-1">KES 25,000 <span class="fs-20">/ night</span></h2></div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="owl-custom-nav menu-float" data-target="#room-carousel">
                            <a class="btn-next"></a>
                            <a class="btn-prev"></a>                                

                            <div id="room-carousel" class="owl-single-static owl-carousel owl-theme">
                                <!-- item begin -->
                                <div class="item">
                                    <div class="relative">
                                        <div class="overflow-hidden rounded-1">
                                            <img src="{{ asset('assets/frontend/images/room-single/1.webp') }}" class="w-100" alt="">
                                        </div>
                                    </div>
                                </div>
                                <!-- item end -->
                                                        
                                <!-- item begin -->
                                <div class="item">
                                    <div class="relative">
                                        <div class="overflow-hidden rounded-1">
                                            <img src="{{ asset('assets/frontend/images/room-single/2.webp') }}" class="w-100" alt="">
                                        </div>
                                    </div>
                                </div>
                                <!-- item end -->
                                
                                <!-- item begin -->
                                <div class="item">
                                    <div class="relative">
                                        <div class="overflow-hidden rounded-1">
                                            <img src="{{ asset('assets/frontend/images/room-single/3.webp') }}" class="w-100" alt="">
                                        </div>
                                    </div>
                                </div>
                                <!-- item end -->

                                <!-- item begin -->
                                <div class="item">
                                    <div class="relative">
                                        <div class="overflow-hidden rounded-1">
                                            <img src="{{ asset('assets/frontend/images/room-single/4.webp') }}" class="w-100" alt="">
                                        </div>
                                    </div>
                                </div>
                                <!-- item end -->

                            </div>
                        </div>

                        <div class="spacer-single"></div>
                        <p>
                            Tausi Holiday Home offers a fully furnished private house ideal for families, couples, and small groups seeking a peaceful escape. Each home provides privacy, comfort, and a calm environment — with breakfast included and a simple flat nightly rate. Perfect for families and groups looking for complete independence and comfort.
                        </p>
                        <h3 class="mt-4 mb-3">Room Amenities</h3>
                        <ul class="ul-check">
                            <li>Free Wi-Fi</li>
                            <li>Smart TV</li>
                            <li>Minibar</li>
                            <li>Coffee Machine</li>
                            <li>Safe Deposit Box</li>
                            <li>Rain Shower</li>
                        </ul>
                    </div>

                    <div class="col-lg-4" id="booking">
                        <div class="p-40 bg-white rounded-1">
                            <form name="contactForm" id="contact_form" method="get" action="javascript:void(0)" onsubmit="handleAvailabilityCheck(); return false;">
                                <div class="row g-4 align-items-end">
                                    
                                    <div class="col-lg-12">
                                        <div class="fs-18 text-dark fw-500 mb-10">Check In</div>
                                        <input type="text" id="checkin" class="form-control" required>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="fs-18 text-dark fw-500 mb-10">Check Out</div>
                                        <input type="text" id="checkout" class="form-control" required>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="fs-18 text-dark fw-500 mb-10">Rooms</div>
                                        <select name="rooms" id="rooms" class="form-control">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="fs-18 text-dark fw-500 mb-10">Guests</div>
                                        <select name="guests" id="guests" class="form-control">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>


                                    <div class="col-lg-12">
                                        <div id='submit'>
                                            <input type='submit' id='send_message' value='Check Availability' class="btn-main w-100">
                                        </div>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                    
                </div>

                

                <div class="mt-5">
                    <h2 class="mb-4">Other Rooms</h2>
                    <div class="row g-4">
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
                                                <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">4 guests
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
                                        <h3 class="mb-2">2 Bedroom Villa - Nanyuki</h3>
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
                                        <h3 class="mb-2">3 Bedroom Villa - Nanyuki</h3>
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
                                                <img src="{{ asset('assets/frontend/images/ui/user.webp') }}" class="w-15px me-2" alt="">4-6 guests
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('assets/frontend/images/ui/floorplan.webp') }}" class="w-15px me-2" alt=""> Entire Home
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="fs-20 fw-bold">KES 25,000</div><span>/night</span>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <h3 class="mb-2">4 Bedroom Villa - Nanyuki</h3>
                                    </div>
                                </div>
                            </a>
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

    <!-- form -->
    <script src="{{ asset('assets/frontend/js/moment.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/custom-datepicker.js') }}"></script>

    <script>
        function handleAvailabilityCheck() {
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            const rooms = document.getElementById('rooms').value;
            const guests = document.getElementById('guests').value;

            if (!checkin || !checkout) {
                alert('Please select check-in and check-out dates');
                return false;
            }

            // Redirect to booking page with parameters
            const params = new URLSearchParams({
                checkin: checkin,
                checkout: checkout,
                rooms: rooms,
                guests: guests
            });

            window.location.href = '/reservation?' + params.toString();
            return false;
        }
    </script>
    @endpush

@endsection