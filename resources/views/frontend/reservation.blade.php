@extends('frontend.layouts.app')
@section('title', 'Reservation - GrandStay')
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
                        <h2 class="fs-60 fs-xs-8vw wow fadeInUp" data-wow-delay=".4s">Reservation</h2>
                    </div>
                </div>
            </div>
            <div class="crumb-wrapper">
                <ul class="crumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Reservation</li>
                </ul>
            </div>
            <div class="sw-overlay op-8"></div>
        </section>

        <section id="section_form" class="relative lines-deco">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div id="success_message" class="text-center">
                            <h2>Your reservation has been sent successfully.</h2>
                            <div class="col-lg-8 offset-lg-2">
                                <p>We will contact you shortly. Refresh this page if you want to make another reservation.</p>
                            </div>
                        </div>

                        <div id="booking_form_wrap">
                            <form name="contactForm" id='booking_form' class="form-border" method="post" action="{{ route('booking.submit') }}">
                                
                                <div class="row g-4 mb-4">
                                    <div class="col-md-12">
                                        <h4>Choose Date</h4>
                                        <input type="text" id="date-picker" class="form-control" name="date" value="">
                                    </div>

                                    <div class="col-md-4">
                                        <div class="text-center border-1">
                                            <h4>Adult</h4>
                                            <div class="de-number">
                                                <span class="d-minus">-</span>
                                                <input type="text" class="no-border no-bg" value="1" name="adult">
                                                <span class="d-plus">+</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center border-1">
                                            <h4>Children</h4>
                                            <div class="de-number">
                                                <span class="d-minus">-</span>
                                                <input type="text" class="no-border no-bg" value="0" name="children">
                                                <span class="d-plus">+</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center border-1">
                                            <h4>Homes</h4>
                                            <div id="d-room-count" class="de-number">
                                                <span class="d-minus">-</span>
                                                <input id="room-count" type="text" class="no-border no-bg" value="1" name="room_count">
                                                <span class="d-plus">+</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="select-room mb-4">
                                    <h4>Select Home</h4>

                                    <select class="room-type form-control" name="room_type">
                                      <option value='Standart Room' data-src="{{ asset('assets/frontend/images/form/1.jpg') }}">
                                        Studio | KES 25,000/night | 2 Guests
                                      </option>
                                      <option value='Deluxe Room' data-src="{{ asset('assets/frontend/images/form/2.jpg') }}">
                                        Bedsitter | KES 25,000/night | 2 Guests
                                      </option>
                                      <option value='Premier Room' data-src="{{ asset('assets/frontend/images/form/3.jpg') }}">
                                        1 Bedroom House | KES 25,000/night | 4 Guests
                                      </option>
                                      <option value='Family Suite' data-src="{{ asset('assets/frontend/images/form/4.jpg') }}">
                                        2 Bedroom House | KES 25,000/night | 6 Guests
                                      </option>
                                      <option value='Luxury Suite' data-src="{{ asset('assets/frontend/images/form/5.jpg') }}">
                                        2 Bedroom House | KES 25,000/night | 6 Guests
                                      </option>
                                      <option value='Presidential Suite' data-src="{{ asset('assets/frontend/images/form/6.jpg') }}">
                                        Premium Home | KES 25,000/night | 8 Guests
                                      </option>
                                    </select>
                                </div>

                                <div class="row">
                                    <h4>Enter Your Details</h4>
                                    <div class="col-md-6">

                                        <div class="mb-4">
                                            <input type='text' name='name' id='name' class="form-control" placeholder="Your Name" required>
                                        </div>

                                        <div class="mb-4">
                                            <input type='email' name='email' id='email' class="form-control" placeholder="Your Email" required>
                                        </div>

                                        <div class="mb-4">
                                            <input type='text' name='phone' id='phone' class="form-control" placeholder="Your Phone" required>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <textarea name='message' id='message' rows="7" class="form-control" placeholder="Your Message"></textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <p id='submit'>
                                            <input type='submit' id='send_message' value='Submit Form' class="btn-main">
                                        </p>
                                    </div>
                                </div>
                            </form>
                            <div id='error_message' class='error'>Sorry, error occured this time sending your message.</div>
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
    <script src="{{ asset('assets/frontend/js/select2.js') }}"></script>
    <script>
        (function($) {
            $('.room-type').select2({
              minimumResultsForSearch: Infinity,
              templateResult: formatState,
              templateSelection: formatState,
              width: '100%'
            });
        });
    </script>

    <!-- swiper slider -->
    <script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/custom-swiper-1.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/moment.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/daterangepicker.js') }}"></script>

    <!-- form -->
    <script src="{{ asset('assets/frontend/js/custom-datepicker-in-out.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/validation-reservation.js') }}"></script>
    @endpush

@endsection