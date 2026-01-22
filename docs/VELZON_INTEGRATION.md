# Velzon Admin Template Integration Guide

This project integrates the Velzon HTML admin template into Laravel Blade for a professional admin dashboard.

## Project Structure

### Base Layout
- **Location**: `resources/views/layouts/velzon/app.blade.php`
- **Features**:
  - Responsive header with navigation
  - Sidebar menu with expandable items
  - Main content area with page header
  - Footer
  - CSS and JavaScript yield sections
  - CSRF token integration
  - Authentication-ready structure

### Assets
- **Location**: `public/assets/velzon/`
- **Folders**:
  - `css/` - Stylesheet files
  - `js/` - JavaScript files
  - `images/` - Images and logos

### Views
- **Dashboard**: `resources/views/dashboard/index.blade.php`
- Uses the Velzon layout with statistics cards, charts, and recent activity

## Yield Sections

The base layout provides the following customizable sections:

### `@yield('title')`
Page title displayed in browser tab and page header.
```blade
@section('title', 'Dashboard')
```

### `@yield('page-title')`
Main page heading displayed in the page header.
```blade
@section('page-title', 'Dashboard')
```

### `@yield('page-title-right')`
Right-aligned content in the page header (breadcrumbs, buttons, etc).
```blade
@section('page-title-right')
    <a href="#" class="btn btn-primary btn-sm">Add New</a>
@endsection
```

### `@yield('content')`
Main page content area.
```blade
@section('content')
    <div class="card">
        <!-- Your content here -->
    </div>
@endsection
```

### `@yield('styles')`
Additional CSS files for page-specific styling.
```blade
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection
```

### `@stack('styles')`
Multiple CSS files can be pushed.
```blade
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/plugin.css') }}">
@endpush
```

### `@yield('scripts')`
Additional JavaScript at the end of the page.
```blade
@section('scripts')
    <script src="{{ asset('js/custom.js') }}"></script>
@endsection
```

### `@stack('scripts')`
Multiple JavaScript files can be pushed.
```blade
@push('scripts')
    <script>
        // Inline JavaScript
    </script>
@endpush
```

## Creating New Pages

### Basic Page Template
```blade
@extends('layouts.velzon.app')

@section('title', 'Page Name')
@section('page-title', 'Page Name')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Card Title</h5>
        </div>
        <div class="card-body">
            <!-- Your content here -->
        </div>
    </div>
@endsection
```

### With Additional Styles
```blade
@extends('layouts.velzon.app')

@section('title', 'Page Name')
@section('page-title', 'Page Name')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/page-specific.css') }}">
@endpush

@section('content')
    <!-- Content -->
@endsection

@push('scripts')
    <script src="{{ asset('js/page-specific.js') }}"></script>
@endpush
```

## Navigation Menu

Edit the sidebar menu in `resources/views/layouts/velzon/app.blade.php`:

```blade
<ul class="metismenu list-unstyled" id="side-menu">
    <li class="menu-title">Menu</li>
    
    <li>
        <a href="{{ route('dashboard') }}">
            <i class="ri-dashboard-2-line"></i> <span>Dashboard</span>
        </a>
    </li>
    
    <li>
        <a href="javascript: void(0);" class="has-arrow">
            <i class="ri-mail-send-line"></i> <span>Email</span>
        </a>
        <ul class="sub-menu">
            <li><a href="javascript: void(0);">Inbox</a></li>
            <li><a href="javascript: void(0);">Compose</a></li>
        </ul>
    </li>
</ul>
```

## Authentication Integration (Ready)

The layout includes placeholders for user authentication. To add authentication:

### 1. Update User Profile Section
In the header, update this section:
```blade
<span class="text-start ms-xl-2">
    <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
        {{ auth()->user()->name ?? 'Admin' }}
    </span>
    <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">
        {{ auth()->user()->role ?? 'Administrator' }}
    </span>
</span>
```

### 2. Update User Dropdown
Replace placeholder links with authenticated routes:
```blade
<a class="dropdown-item" href="{{ route('profile.edit') }}">
    <i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i>
    <span class="align-middle">Profile</span>
</a>

<a class="dropdown-item" href="{{ route('logout') }}">
    <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
    <span class="align-middle">Logout</span>
</a>
```

### 3. Protect Routes
Use middleware to protect pages:
```php
Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware('auth')->name('dashboard');
```

## Asset Files

All Velzon assets are located in `public/assets/velzon/`:

### CSS Files
- `bootstrap.min.css` - Bootstrap framework
- `icons.min.css` - Remix Icon library
- `app.min.css` - Velzon custom styles

### JavaScript Files
- `jquery-3.6.0.min.js` - jQuery library
- `bootstrap.bundle.min.js` - Bootstrap components
- `simplebar.min.js` - Custom scrollbar
- `metisMenu.min.js` - Menu animation
- `app.min.js` - Velzon app initialization

### Images
- `logo-sm.png` - Small logo
- `logo-dark.png` - Dark theme logo
- `logo-light.png` - Light theme logo
- `avatar-1.jpg` - User avatar

## Customization

### Changing Colors
Edit CSS variables in `public/assets/velzon/css/app.min.css`:
```css
:root {
    --bs-primary: #4680ff;
    --bs-secondary: #6c757d;
    --bs-success: #34c38f;
    --bs-danger: #f46a6a;
    --bs-warning: #f0ad4e;
    --bs-info: #50a5f1;
}
```

### Adding Custom CSS
Create `public/assets/velzon/css/custom.css` and include it:
```blade
<link rel="stylesheet" href="{{ asset('assets/velzon/css/custom.css') }}">
```

### Adding Custom JavaScript
Create `public/assets/velzon/js/custom.js` and include it:
```blade
<script src="{{ asset('assets/velzon/js/custom.js') }}"></script>
```

## Routes

### Current Routes
- `GET /` - Welcome page
- `GET /dashboard` - Dashboard (requires route definition)

### Example Routes to Add
```php
// Pages
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
```

## External Resources

### CDN Links (Optional)
If you prefer to use CDN instead of local files:

```blade
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Remix Icon -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.0.0/fonts/remixicon.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```

### Documentation Links
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)
- [Remix Icon Library](https://remixicon.com/)
- [Laravel Blade Templates](https://laravel.com/docs/12/blade)

## Next Steps

1. **Replace Placeholder Assets**: Download and replace CSS/JS placeholder files with actual Velzon template files
2. **Upload Images**: Add actual logo and avatar images
3. **Create Pages**: Build application pages extending the Velzon layout
4. **Configure Authentication**: Integrate Laravel authentication system
5. **Add Business Logic**: Implement model relationships and controllers

## Notes

- The layout is fully responsive and works on all devices
- All assets are served locally from `public/assets/velzon/`
- No secrets are hardcoded in views
- CSRF token is automatically included for form submissions
- The layout is authentication-ready but no auth logic is implemented yet
