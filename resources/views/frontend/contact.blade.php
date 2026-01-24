@extends('frontend.layouts.app')

@section('title', 'Contact Us')

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
                        <div class="subtitle id-color wow fadeInUp mb-2">Get in Touch</div>
                        <div class="clearfix"></div>
                        <h2 class="fs-60 fs-xs-8vw wow fadeInUp" data-wow-delay=".4s">Booking & Enquiries</h2>
                    </div>
                </div>
            </div>
            <div class="crumb-wrapper">
                <ul class="crumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Contact</li>
                </ul>
            </div>
            <div class="sw-overlay op-8"></div>
        </section>

        <section class="relative">
            <div class="container">
              <div class="row align-items-center justify-content-center">
                <div class="col-lg-6">
                    <div class="subtitle">Write a Message</div>
                    <h2 class="wow fadeInUp">Get In Touch</h2>

                    <p class="col-lg-8">Have a question about availability, pricing, or house options? Reach out and we'll be happy to assist you with your booking.</p>

                    <div class="spacer-single"></div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <i class="abs fs-28 p-3 bg-color text-light rounded-1 icofont-location-pin"></i>
                            <div class="ms-80px">
                                <h3 class="fs-20 mb-0">Location</h3>
                                Nanyuki, Kenya
                            </div>
                        </div>

                        <div class="col-md-6">
                            <i class="abs fs-28 p-3 bg-color text-light rounded-1 icofont-envelope"></i>
                            <div class="ms-80px">
                                <h3 class="fs-20 mb-0">Email</h3>
                                bookings@tausivacations.com
                            </div>
                        </div>

                        <div class="col-md-6">
                            <i class="abs fs-28 p-3 bg-color text-light rounded-1 icofont-phone"></i>
                            <div class="ms-80px">
                                <h3 class="fs-20 mb-0">Call or WhatsApp</h3>
                                +254 718 756 254
                            </div>
                        </div>

                        <div class="col-md-6">
                            <i class="abs fs-28 p-3 bg-color text-light rounded-1 icofont-phone"></i>
                            <div class="ms-80px">
                                <h3 class="fs-20 mb-0">Contact</h3>
                                Message & Reply Guaranteed
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="bg-color-op-1 rounded-1 p-40 relative">
                        <form name="contactForm" id="contact_form" method="post" action="{{ route('contact.store') }}">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h3 class="fs-18">Name</h3>
                                <input type="text" name="name" id="name" class="bg-white form-control" placeholder="Your Name" required>
                            </div>

                            <div class="col-md-6">
                                <h3 class="fs-18">Email</h3>
                                <input type="text" name="email" id="email" class="bg-white form-control" placeholder="Your Email" required>
                            </div>

                            <div class="col-md-12">
                                <h3 class="fs-18">Phone</h3>
                                <input type="text" name="phone" id="phone" class="bg-white form-control" placeholder="Your Phone" required>
                            </div>

                            <div class="col-md-12">
                                <h3 class="fs-18">Message</h3>
                                <textarea name="message" id="message" class="bg-white form-control h-100px" placeholder="Your Message" required></textarea>
                            </div>

                            <div class="col-md-12">
                                <div id='submit'>
                                    <input type='submit' id='send_message' value='Send Message' class="btn-main">
                                </div>

                                <div id="success_message" class='success'>
                                    Your message has been sent successfully. Refresh this page if you want to send more messages.
                                </div>
                                <div id="error_message" class='error'>
                                    Sorry there was an error sending your form.
                                </div>
                            </div>
                        </div>
                            
                        
                        
                    </form>
                    </div>
                </div>
              </div>
            </div>
        </section>
        
    </main>

    <footer class="text-light section-dark m-2 mt-0 rounded-1">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-12">
                    <div class="d-lg-flex align-items-center justify-content-between text-center">
                        <div>
                            <h3 class="fs-20">Address</h3>
                            742 Evergreen Terrace<br>
                            Brooklyn, NY 11201
                        </div>
                        <div>
                            <img src="{{ asset('assets/frontend/images/logo-white.webp') }}" class="w-150px" alt=""><br>
                            <div class="social-icons mb-sm-30 mt-4">
                                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                <a href="#"><i class="fa-brands fa-youtube"></i></a>
                            </div>

                        </div>
                        <div>
                            <h3 class="fs-20">Contact Us</h3>
                            T. +929 333 9296<br>
                            M. contact@rivora.com
                        </div>
                    </div>
                </div>                    
            </div>
        </div>
        <div class="subfooter">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        Copyright 2026 - Rivora by Designesia
                    </div>
                </div>
            </div>
        </div>
    </footer>

@push('scripts')
    <script src="{{ asset('assets/frontend/js/vendors.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/designesia.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/custom-swiper-1.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/validation-contact.js') }}"></script>
@endpush

@endsection