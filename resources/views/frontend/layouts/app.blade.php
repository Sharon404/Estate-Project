<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tausi Holiday & Getaway Homes')</title>
    <meta name="description" content="@yield('description', 'AN ENTIRE HOUSE JUST FOR YOU · KES 25,000 PER NIGHT · BREAKFAST INCLUDED')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/frontend/images/favicon.ico') }}">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/frontend/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/fontawesome/all.min.css') }}">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/swiper.css') }}">

    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/daterangepicker.css') }}">

    <!-- Plugins CSS (includes Owl Carousel) -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/plugins.css') }}">

    <!-- Custom Styles -->
    <link href="{{ asset('assets/frontend/css/style.css') }}" rel="stylesheet">
    
    <!-- TAUSI BRAND OVERRIDE - Must be loaded last -->
    <link href="{{ asset('assets/tausi/tausi-brand.css') }}" rel="stylesheet">

    @stack('css')
</head>
<body>

    <!-- Navigation Header -->
    <header class="header">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light">
                <!-- Logo -->
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('assets/frontend/images/logo-v5-black.png') }}" alt="Tausi Holiday & Getaway Homes" class="logo">
                </a>

                <!-- Navbar Toggler for Mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Menu -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#home">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#about">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#services">Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#pricing">Pricing</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#testimonial">Testimonials</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#booking">Book Your Stay</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#contact">Contact</a></li>
                    </ul>

                    <!-- Reservation Button -->
                    <a href="{{ route('home') }}#booking" class="btn btn-primary ms-lg-3 mt-3 mt-lg-0">
                        Book Your Stay
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Page Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer bg-dark text-light mt-5 pt-5">
        <div class="container">
            <div class="row g-4">
                <!-- About Column -->
                <div class="col-md-3">
                    <a href="{{ route('home') }}" class="d-inline-block mb-3">
                        <img src="{{ asset('assets/frontend/images/logo-v5-black.png') }}" alt="Tausi Holiday & Getaway Homes" style="max-width: 150px;">
                    </a>
                    <p class="text-muted">Tausi Holiday & Getaway Homes offers private, fully furnished houses for peaceful stays. Flat rate of KES 25,000 per house per night, with breakfast included.</p>
                    <div class="social-icons mt-3">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}#home" class="text-muted text-decoration-none">Home</a></li>
                        <li><a href="{{ route('home') }}#about" class="text-muted text-decoration-none">About</a></li>
                        <li><a href="{{ route('home') }}#services" class="text-muted text-decoration-none">Services</a></li>
                        <li><a href="{{ route('home') }}#pricing" class="text-muted text-decoration-none">Pricing</a></li>
                        <li><a href="{{ route('home') }}#contact" class="text-muted text-decoration-none">Contact</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Booking & Enquiries</h5>
                    <p class="text-muted mb-2"><i class="fas fa-phone me-2"></i>+254 718 756 254</p>
                    <p class="text-muted mb-2"><i class="fas fa-envelope me-2"></i>bookings@tausivacations.com</p>
                    <p class="text-muted"><i class="fas fa-map-marker-alt me-2"></i>Nanyuki, Kenya</p>
                </div>

                <!-- Newsletter -->
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Booking Information</h5>
                    <p class="text-muted">Entire house rentals at a flat rate. Ideal for families, couples, and small groups looking for comfort and privacy.</p>
                    <p class="text-muted mt-3"><strong>KES 25,000</strong> per house / night<br>Breakfast included</p>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="row mt-4 pt-4 border-top border-secondary">
                <div class="col-md-6 text-muted text-sm">
                    <p>&copy; {{ date('Y') }} Tausi Holiday & Getaway Homes. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('home') }}#home" class="text-muted text-decoration-none">Back to top</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/frontend/js/bootstrap.bundle.min.js') }}"></script>

    <!-- jQuery (if needed by template) -->
    <script src="{{ asset('assets/frontend/js/jquery.min.js') }}"></script>

    @stack('scripts')

</body>
</html>
