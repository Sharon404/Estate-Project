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
              <div class="row">
                <div class="col-lg-8">
                    <div class="subtitle">Write a Message</div>
                    <h2 class="wow fadeInUp">Get In Touch</h2>

                    <p>Have a question about availability, pricing, or house options? Reach out and we'll be happy to assist you with your booking.</p>

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

                    <div class="spacer-single"></div>

                    <div class="bg-color-op-1 rounded-1 p-40 relative">
                        @if(session('success'))
                            <div class="alert alert-success mb-4" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger mb-4" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form name="contactForm" id="contact_form" method="post" action="{{ route('contact.store') }}">
                        @csrf
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

        <!-- Google Maps Location Section -->
        <section class="relative">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="subtitle">Our Location</div>
                        <h2 class="wow fadeInUp mb-3">Find Us on Google Maps</h2>
                        <p class="mb-4">Visit us at Tausi Holiday & Getaway Homes in Nanyuki, Kenya. Use the map below for directions or <a href="https://www.google.com/maps/place/Nanyuki,+Kenya/@-0.0176197,36.9449778,12z" target="_blank" rel="noopener noreferrer" class="text-decoration-underline" style="color: #652482; font-weight: 600;">open in Google Maps</a> for navigation.</p>
                        
                        <div class="rounded-1 overflow-hidden shadow-sm" style="height: 400px; width: 100%;">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127499.14527869476!2d36.9449778!3d-0.0176197!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x17887b1e2cffffff%3A0x7bce2f1e2bf0c0!2sNanyuki%2C%20Kenya!5e0!3m2!1sen!2s!4v1738088400000!5m2!1sen!2s" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Tausi Holiday & Getaway Homes Location - Nanyuki, Kenya">
                            </iframe>
                        </div>

                        <style>
                            @media (max-width: 768px) {
                                .rounded-1.overflow-hidden.shadow-sm {
                                    height: 300px !important;
                                }
                            }
                        </style>
                    </div>
                </div>
            </div>
        </section>
        
    </main>

@push('scripts')
    <script src="{{ asset('assets/frontend/js/vendors.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/designesia.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/custom-swiper-1.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/validation-contact.js') }}"></script>
@endpush

@endsection