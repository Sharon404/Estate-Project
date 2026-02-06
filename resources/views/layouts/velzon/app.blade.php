<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Tausi</title>

    <!-- Velzon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/velzon/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/velzon/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/velzon/css/app.min.css') }}">
    
    <!-- Tausi Dashboard Branding -->
    <link rel="stylesheet" href="{{ asset('assets/tausi/tausi-dashboard.css') }}">

    <!-- Additional Styles -->
    @stack('styles')
    @yield('styles')
</head>
<body data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-layout-mode="light">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- Header Start -->
        <header class="app-header" style="background: linear-gradient(135deg, #652482 0%, #bc92cb 100%); display: flex !important; visibility: visible !important; opacity: 1 !important; height: auto; min-height: 75px; align-items: center; padding: 0.85rem 1.5rem; position: sticky; top: 0; z-index: 1030; border-bottom: 2px solid #decfbc; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; gap: 1rem;">
                <!-- Logo Section -->
                <div style="display: flex; align-items: center; flex-shrink: 0;">
                    <!-- Menu Toggle -->
                    <button type="button" id="vertical-menu-btn" style="background: transparent; border: none; color: #ffffff; margin-left: 1rem; font-size: 1.5rem; padding: 0.5rem 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; visibility: visible; opacity: 1;">
                        <span style="font-size: 1.5rem;">☰</span>
                    </button>
                    <!-- Logo -->
                    <a href="{{ route('dashboard') }}" style="display: flex; align-items: center; text-decoration: none; padding: 0; margin: 0; height: 50px;">
                        <img src="{{ asset('assets/velzon/images/logo-v5-black.png') }}" alt="Tausi" style="height: 45px; width: auto; display: block !important; visibility: visible !important; opacity: 1 !important; object-fit: contain; max-width: 150px;">
                    </a>
                </div>

                <!-- Right Section - Buttons & User -->
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-left: auto;">
                    <!-- Fullscreen Button -->
                    <button type="button" style="background: transparent; border: none; color: #ffffff; font-size: 1.3rem; padding: 0.5rem 0.8rem; cursor: pointer; display: flex; align-items: center; justify-content: center; visibility: visible; opacity: 1;" data-toggle="fullscreen" title="Fullscreen">
                        <span style="font-size: 1.2rem;">⛶</span>
                    </button>

                    <!-- Dark/Light Mode -->
                    <button type="button" class="light-dark-mode" style="background: transparent; border: none; color: #ffffff; font-size: 1.3rem; padding: 0.5rem 0.8rem; cursor: pointer; display: flex; align-items: center; justify-content: center; visibility: visible; opacity: 1;" title="Dark/Light Mode">
                        <span style="font-size: 1.2rem;">🌙</span>
                    </button>

                    <!-- Notifications -->
                    <div style="position: relative; display: flex; align-items: center;">
                        <button type="button" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: transparent; border: none; color: #ffffff; font-size: 1.3rem; padding: 0.5rem 0.8rem; cursor: pointer; display: flex; align-items: center; justify-content: center; visibility: visible; opacity: 1; position: relative;" title="Notifications">
                            <span style="font-size: 1.2rem;">🔔</span>
                            <span style="position: absolute; top: 3px; right: 0; translate: 50% -50%; padding: 2px; background: #dc3545; border-radius: 50%; height: 10px; width: 10px; display: flex; align-items: center; justify-content: center;"></span>
                        </button>
                    </div>

                    <!-- User Profile -->
                    <div style="position: relative; display: flex; align-items: center;">
                        <button type="button" id="page-header-user-dropdown" onclick="window.toggleDropdown(event)" style="background: transparent; border: none; color: #ffffff; display: flex; align-items: center; gap: 0.5rem; visibility: visible; opacity: 1; cursor: pointer; padding: 0; position: relative; z-index: 1001;">
                            <div style="width: 42px; height: 42px; border-radius: 50%; border: 2px solid #decfbc; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15); background-color: #652482; display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: bold; font-size: 1rem;">
                                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                            </div>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" id="user-dropdown-menu" style="position: absolute; top: 100%; right: 0; background: #ffffff; border: 1px solid #decfbc; border-radius: 6px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); min-width: 250px; display: none; z-index: 1001; margin-top: 5px;">
                            <h6 style="background: transparent; color: #652482; font-size: 0.95rem; font-weight: 600; padding: 0.75rem 1rem; margin: 0;">Welcome, {{ auth()->user()->name ?? 'User' }}!</h6>
                            <a href="#" onclick="event.preventDefault()" style="color: #222222; font-size: 0.95rem; padding: 0.7rem 1rem; display: block; text-decoration: none; transition: all 0.2s;"><span style="font-size: 0.9rem; margin-right: 0.5rem;">👤</span> <span>Profile</span></a>
                            <a href="#" onclick="event.preventDefault()" style="color: #222222; font-size: 0.95rem; padding: 0.7rem 1rem; display: block; text-decoration: none; transition: all 0.2s;"><span style="font-size: 0.9rem; margin-right: 0.5rem;">⚙️</span> <span>Settings</span></a>
                            <div style="border-top: 1px solid #e9ecef; margin: 0.5rem 0;"></div>
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <input type="hidden" name="redirect_to" value="{{ route('login') }}">
                                <button type="submit" style="background: none; border: none; cursor: pointer; text-align: left; width: 100%; padding: 0.7rem 1rem; color: #222222; font-size: 0.95rem; display: block; text-decoration: none;"><span style="font-size: 0.9rem; margin-right: 0.5rem;">🚪</span> <span>Logout</span></button>
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

                                <li class="menu-title" data-key="t-admin-management">Management</li>

                                <li>
                                    <a href="{{ route('admin.users.index') }}">
                                        <i class="ri-user-3-line"></i> <span>Users</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('admin.properties.index') }}">
                                        <i class="ri-home-4-line"></i> <span>Properties</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('admin.bookings') }}">
                                        <i class="ri-book-mark-line"></i> <span>Bookings</span>
                                    </a>
                                </li>

                                <li class="menu-title" data-key="t-admin-payments">Payments</li>

                                <li>
                                    <a href="{{ route('admin.refunds.index') }}">
                                        <i class="ri-refund-2-line"></i> <span>Refunds</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('admin.payouts.index') }}">
                                        <i class="ri-exchange-dollar-line"></i> <span>Payouts</span>
                                    </a>
                                </li>

                                <li class="menu-title" data-key="t-admin-support">Support</li>

                                <li>
                                    <a href="{{ route('admin.tickets.index') }}">
                                        <i class="ri-customer-service-2-line"></i> <span>Tickets</span>
                                    </a>
                                </li>

                                <li class="menu-title" data-key="t-admin-ops">Operations</li>

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

                                <li class="menu-title" data-key="t-admin-reports">Reports</li>

                                <li>
                                    <a href="{{ route('admin.reports.index') }}">
                                        <i class="ri-bar-chart-line"></i> <span>Reports</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('admin.reconciliation.index') }}">
                                        <i class="ri-shield-check-line"></i> <span>Reconciliation</span>
                                    </a>
                                </li>

                            @elseif (auth()->user()->role === 'staff' || strtolower(auth()->user()->role) === 'staff')
                                <!-- Staff Menu -->
                                <li>
                                    <a href="{{ route('staff.dashboard') }}">
                                        <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
                                    </a>
                                </li>

                                <li class="menu-title" data-key="t-staff-operations">Operations</li>

                                <li>
                                    <a href="{{ route('staff.bookings.index') }}">
                                        <i class="ri-book-mark-line"></i> <span>Bookings</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('staff.verification.index') }}">
                                        <i class="ri-shield-user-line"></i> <span>Verification</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('staff.tickets.index') }}">
                                        <i class="ri-customer-service-2-line"></i> <span>Tickets</span>
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
                            <script>document.write(new Date().getFullYear())</script> © Tausi Holiday & Getaway Homes
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Nanyuki, Kenya
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
    
    <!-- Disable Bootstrap dropdown to prevent interference -->
    <script>
        if (typeof $.fn.dropdown !== 'undefined') {
            $.fn.dropdown.Constructor.VERSION = '999'; // Disable version checks
        }
    </script>
    
    <script src="{{ asset('assets/velzon/js/app.min.js') }}"></script>

    <!-- Custom Menu Toggle & Dropdown Script -->
    <script>
        // Global dropdown toggle function - uses vanilla JavaScript only
        window.toggleDropdown = function(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            const menu = document.getElementById('user-dropdown-menu');
            if (!menu) {
                console.error('Dropdown menu not found');
                return;
            }
            
            const isVisible = menu.style.display === 'block';
            menu.style.display = isVisible ? 'none' : 'block';
        };
        
        // Close dropdown when clicking outside - runs immediately
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('user-dropdown-menu');
            const btn = document.getElementById('page-header-user-dropdown');
            
            if (menu && btn) {
                // Don't close if clicking the button or menu
                if (!btn.contains(e.target) && !menu.contains(e.target)) {
                    if (menu.style.display === 'block') {
                        menu.style.display = 'none';
                    }
                }
            }
        }, true);

        // Menu toggle functionality - runs after page loads
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.getElementById('vertical-menu-btn');
            const sidebar = document.querySelector('.vertical-menu');
            const body = document.body;
            
            if (menuBtn && sidebar) {
                menuBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const screenWidth = window.innerWidth;
                    
                    // Always toggle sidebar-enable for mobile
                    body.classList.toggle('sidebar-enable');
                    
                    // For desktop (≥992px), toggle vertical-collapsed
                    if (screenWidth >= 992) {
                        body.classList.toggle('vertical-collapsed');
                    }
                    
                    // Force layout recalculation
                    void sidebar.offsetHeight; // Trigger reflow
                });
            }

            // Fullscreen toggle
            const fullscreenBtn = document.querySelector('[data-toggle="fullscreen"]');
            if (fullscreenBtn) {
                fullscreenBtn.addEventListener('click', function() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(err => {
                            // Fullscreen error
                        });
                    } else {
                        document.exitFullscreen();
                    }
                });
            }

            // Light/Dark mode toggle
            const themeBtn = document.querySelector('.light-dark-mode');
            if (themeBtn) {
                themeBtn.addEventListener('click', function() {
                    document.body.classList.toggle('dark-mode');
                    const icon = themeBtn.querySelector('span');
                    if (icon) {
                        icon.textContent = document.body.classList.contains('dark-mode') ? '☀️' : '🌙';
                    }
                });
            }
        });
    </script>

    <!-- Additional Scripts -->
    @stack('scripts')
    @yield('scripts')
</body>
</html>
