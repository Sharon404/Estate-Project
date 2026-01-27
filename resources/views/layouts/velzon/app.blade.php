<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Estate Project') - Velzon Admin</title>

    <!-- Velzon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/velzon/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/velzon/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/velzon/css/app.min.css') }}">

    <!-- Additional Styles -->
    @stack('styles')
    @yield('styles')
</head>
<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- Header Start -->
        <header class="app-header">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- Logo -->
                    <div class="navbar-brand-box">
                        <a href="{{ route('dashboard') }}" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{ asset('assets/velzon/images/logo-sm.png') }}" alt="logo-sm" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('assets/velzon/images/logo-dark.png') }}" alt="logo-dark" height="17">
                            </span>
                        </a>

                        <a href="{{ route('dashboard') }}" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{ asset('assets/velzon/images/logo-sm.png') }}" alt="logo-sm" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('assets/velzon/images/logo-light.png') }}" alt="logo-light" height="17">
                            </span>
                        </a>
                    </div>

                    <!-- Sidebar Toggle -->
                    <button type="button" class="btn btn-sm px-3 fs-16 header-item" id="vertical-menu-btn">
                        <i class="ri-menu-line align-middle"></i>
                    </button>
                </div>

                <div class="d-flex align-items-center">
                    <!-- Search -->
                    <div class="header-search ms-3">
                        <form class="app-search" action="#">
                            <div>
                                <input type="text" class="form-control" placeholder="Search..." autocomplete="off" id="search-options" value="">
                                <button formaction="#" type="submit" class="btn btn-primary" style="display:none"></button>
                            </div>
                        </form>
                    </div>

                    <!-- Topbar Right -->
                    <div class="ms-1 header-item d-none d-sm-flex">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                            <i class='bx bx-fullscreen fs-22'></i>
                        </button>
                    </div>

                    <!-- Light/Dark Mode -->
                    <div class="ms-1 header-item d-none d-sm-flex">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                            <i class='bx bx-moon fs-22'></i>
                        </button>
                    </div>

                    <!-- Notifications -->
                    <div class="dropdown ms-sm-3 header-item topbar-user">
                        <button type="button" class="btn" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class='bx bx-bell fs-22'></i>
                            <span class="position-absolute topbar-badge position-top-3 position-end-0 translate-middle p-2 bg-danger rounded-pill d-flex align-items-center justify-content-center" style="height:10px;width:10px;"></span>
                        </button>
                    </div>

                    <!-- User Profile -->
                    <div class="dropdown ms-sm-3 header-item topbar-user">
                        <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="d-flex align-items-center">
                                <img class="rounded-circle header-profile-user" src="{{ asset('assets/velzon/images/avatar-1.jpg') }}" alt="Header Avatar">
                                <span class="text-start ms-xl-2">
                                    <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ auth()->user()->name ?? 'Admin' }}</span>
                                    <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">{{ auth()->user()->role ?? 'Administrator' }}</span>
                                </span>
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Welcome, {{ auth()->user()->name ?? 'User' }}!</h6>
                            <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
                            <a class="dropdown-item" href="#"><i class="mdi mdi-cog text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Settings</span></a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item" style="background: none; border: none; cursor: pointer; text-align: left; width: 100%; padding: 0.5rem 1rem;"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Logout</span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Header End -->

        <!-- Left Sidebar Start -->
        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li class="menu-title" data-key="t-menu">Menu</li>

                        @if (auth()->check())
                            @if (auth()->user()->role === 'admin' || strtolower(auth()->user()->role) === 'admin')
                                <!-- Admin Menu -->
                                <li>
                                    <a href="{{ route('admin.dashboard') }}" class="has-arrow">
                                        <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                                    </a>
                                </li>

                                <li class="menu-title" data-key="t-admin">Admin</li>

                                <li>
                                    <a href="{{ route('admin.bookings') }}">
                                        <i class="ri-book-mark-line"></i> <span>Bookings</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('admin.analytics') }}">
                                        <i class="ri-bar-chart-line"></i> <span>Analytics</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('admin.audit-logs') }}">
                                        <i class="ri-file-list-line"></i> <span>Audit Logs</span>
                                    </a>
                                </li>

                            @elseif (auth()->user()->role === 'staff' || strtolower(auth()->user()->role) === 'staff')
                                <!-- Staff Menu -->
                                <li>
                                    <a href="{{ route('staff.dashboard') }}">
                                        <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                                    </a>
                                </li>

                            @else
                                <!-- Default Dashboard -->
                                <li>
                                    <a href="{{ route('dashboard') }}">
                                        <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                                    </a>
                                </li>
                            @endif
                        @else
                            <li>
                                <a href="{{ route('dashboard') }}" class="has-arrow">
                                    <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <!-- Left Sidebar End -->

        <!-- Page Content Start -->
        <div class="main-content">
            <!-- Page Header -->
            <div class="page-content">
                <!-- Container -->
                <div class="container-fluid">
                    <!-- Page Title -->
                    @if (View::hasSection('page-title'))
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">@yield('page-title')</h4>
                            <div class="page-title-right">
                                @yield('page-title-right')
                            </div>
                        </div>
                    @endif

                    <!-- Main Content -->
                    <div class="row">
                        <div class="col-12">
                            @yield('content')
                        </div>
                    </div>
                </div>
                <!-- Container End -->
            </div>
            <!-- Page Header End -->

            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script>© Estate Project
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by <a href="javascript: void(0);" class="text-reset">Your Company</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- Page Content End -->
    </div>
    <!-- End page -->

    <!-- Velzon JS -->
    <script src="{{ asset('assets/velzon/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/velzon/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/velzon/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/velzon/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/velzon/js/app.min.js') }}"></script>

    <!-- Additional Scripts -->
    @stack('scripts')
    @yield('scripts')
</body>
</html>
