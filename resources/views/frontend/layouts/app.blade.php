<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tausirental - Holiday & Getaway Homes')</title>
    <meta name="description" content="@yield('description', 'An entire house just for you. Breakfast included.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/frontend/images/favicon.ico') }}">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/frontend/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/fontawesome/all.min.css') }}">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/swiper.min.css') }}">

    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/daterangepicker.css') }}">

    <!-- Custom Styles -->
    <link href="{{ asset('assets/frontend/css/style.css') }}" rel="stylesheet">

    @stack('css')
</head>
<body>

    <!-- Navigation Header -->
    <header class="header">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light">
                <!-- Logo -->
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('assets/frontend/images/logo-black.webp') }}" alt="Tausirental" class="logo">
                </a>

                <!-- Navbar Toggler for Mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Menu -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="roomsDropdown" role="button" data-bs-toggle="dropdown">
                                Homes
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('properties') }}">All Homes</a></li>
                                <li><a class="dropdown-item" href="{{ route('properties') }}">Entire Home</a></li>
                                <li><a class="dropdown-item" href="{{ route('properties') }}">Family Stay</a></li>
                                <li><a class="dropdown-item" href="{{ route('properties') }}">Group Stay</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-bs-toggle="dropdown">
                                Pages
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('about') }}">About Us</a></li>
                                <li><a class="dropdown-item" href="{{ route('facilities') }}">Facilities</a></li>
                                <li><a class="dropdown-item" href="{{ route('offers') }}">Special Offers</a></li>
                                <li><a class="dropdown-item" href="{{ route('gallery') }}">Gallery</a></li>
                                <li><a class="dropdown-item" href="{{ route('testimonials') }}">Testimonials</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('blog') }}">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                        </li>
                    </ul>

                    <!-- Reservation Button -->
                    <a href="{{ route('reservation') }}" class="btn btn-primary ms-lg-3 mt-3 mt-lg-0">
                        Check Availability
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
                        <img src="{{ asset('assets/frontend/images/logo-white.webp') }}" alt="Tausirental" style="max-width: 150px;">
                    </a>
                    <p class="text-muted">Privacy, comfort, and warm hosting. Every stay includes freshly prepared breakfast so you start your day relaxed and refreshed.</p>
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
                        <li><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a></li>
                        <li><a href="{{ route('about') }}" class="text-muted text-decoration-none">About Us</a></li>
                        <li><a href="{{ route('properties') }}" class="text-muted text-decoration-none">Homes</a></li>
                        <li><a href="{{ route('offers') }}" class="text-muted text-decoration-none">Special Offers</a></li>
                        <li><a href="{{ route('contact') }}" class="text-muted text-decoration-none">Contact</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Contact Info</h5>
                    <p class="text-muted mb-2">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Nanyuki, Kenya
                    </p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-phone me-2"></i>
                        +254 718 756 254
                    </p>
                    <p class="text-muted">
                        <i class="fas fa-envelope me-2"></i>
                        bookings@tausivacations.com
                    </p>
                </div>

                <!-- Newsletter -->
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Newsletter</h5>
                    <p class="text-muted text-sm">Subscribe to receive special offers and updates.</p>
                    <form class="d-flex gap-2">
                        <input type="email" class="form-control form-control-sm" placeholder="Your email" required>
                        <button type="submit" class="btn btn-primary btn-sm">Subscribe</button>
                    </form>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="row mt-4 pt-4 border-top border-secondary">
                <div class="col-md-6 text-muted text-sm">
                    <p>&copy; {{ date('Y') }} Tausi Holiday & Getaway Homes. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-muted text-decoration-none me-3">Terms & Conditions</a>
                    <a href="#" class="text-muted text-decoration-none">Sitemap</a>
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
