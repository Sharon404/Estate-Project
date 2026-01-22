# Laravel Authentication Implementation

## Overview

The Estate Project now has complete authentication implemented with role-based access control. Users can log in with their credentials, and access is restricted based on their assigned role (ADMIN or STAFF).

## Features Implemented

### ✅ User Roles
- **ADMIN**: Full administrative access to the dashboard and all features
- **STAFF**: Staff member access (can be restricted to specific features)

### ✅ Authentication System
- Email/password based login
- Session management
- Remember me functionality
- Automatic logout
- Protected routes with middleware

### ✅ Middleware
- `auth` - Built-in Laravel authentication middleware (requires login)
- `role` - Custom role-based middleware (`EnsureUserHasRole`)
- `guest` - Built-in Laravel guest middleware (prevents logged-in users from accessing login page)

### ✅ Login Interface
- Professional Velzon-styled login page
- Error messages and validation feedback
- Loading state on submit
- Session flash messages

### ✅ User Dropdown
- Displays authenticated user name and role
- Logout button in user menu
- Profile and settings links (placeholder)

## File Structure

### Controllers
```
app/Http/Controllers/
├── Auth/
│   └── LoginController.php      (Authentication logic)
└── DashboardController.php      (Dashboard controller)
```

### Middleware
```
app/Http/Middleware/
├── EnsureUserHasRole.php        (Role-based access control)
└── EnsureUserIsAuthenticated.php (General auth check)
```

### Views
```
resources/views/
├── auth/
│   └── login.blade.php          (Velzon-styled login)
└── dashboard/
    └── index.blade.php          (Dashboard)
```

### Database
```
database/
├── migrations/
│   └── 0001_01_01_000000_create_users_table.php (Users table with role column)
└── seeders/
    └── DatabaseSeeder.php       (Test users)
```

## Routes

```php
// Public Routes
GET  /                           → Welcome page
GET  /login                      → Login form (guest only)
POST /login                      → Login submission (guest only)

// Protected Routes
POST /logout                     → Logout (auth required)
GET  /dashboard                  → Dashboard (auth required)
```

## Test Users

Two test users are seeded by default:

### Admin User
- **Email**: `admin@example.com`
- **Password**: `password`
- **Role**: ADMIN

### Staff User
- **Email**: `staff@example.com`
- **Password**: `password`
- **Role**: STAFF

## How Authentication Works

### 1. Login Flow
```
User visits /login
    ↓
Enters email and password
    ↓
Submits form to POST /login
    ↓
LoginController validates credentials
    ↓
If valid: Create session, redirect to /dashboard
If invalid: Return error message
```

### 2. Protected Routes Flow
```
User requests protected route (e.g., /dashboard)
    ↓
Middleware checks if user is authenticated
    ↓
If authenticated: Allow access
If not: Redirect to /login
```

### 3. Logout Flow
```
User clicks logout button
    ↓
Form submits to POST /logout
    ↓
Session is destroyed
    ↓
Redirect to home page with success message
```

## Using Role-Based Middleware

### In Routes
```php
// Protect route for ADMIN role only
Route::get('/admin-only', function() {
    // Only accessible by users with ADMIN role
})->middleware('auth', 'role:ADMIN');

// Protect route for ADMIN or STAFF
Route::get('/staff-area', function() {
    // Accessible by ADMIN or STAFF users
})->middleware('auth', 'role:ADMIN,STAFF');
```

### In Controllers
```php
class SomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ADMIN']);
    }
}
```

### In Blade Templates
```blade
@if(auth()->check())
    <p>Logged in as {{ auth()->user()->name }}</p>
@endif

@if(auth()->user()->role === 'ADMIN')
    <!-- Admin-only content -->
@endif
```

## Accessing Authentication Data

### In Controllers
```php
// Get current user
$user = auth()->user();

// Get user name
$name = auth()->user()->name;

// Get user role
$role = auth()->user()->role;

// Check if authenticated
if (auth()->check()) {
    // User is logged in
}

// Check if guest
if (auth()->guest()) {
    // User is not logged in
}
```

### In Blade Templates
```blade
<!-- User Name -->
{{ auth()->user()->name }}

<!-- User Role -->
{{ auth()->user()->role }}

<!-- Check Authentication -->
@auth
    <p>Welcome, {{ auth()->user()->name }}!</p>
@else
    <p>Please log in</p>
@endauth

<!-- Check Role -->
@if(auth()->user()->role === 'ADMIN')
    <p>Admin Panel</p>
@endif
```

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255),
    role ENUM('ADMIN', 'STAFF') DEFAULT 'STAFF',
    remember_token VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## Creating New Users

### Via Tinker
```bash
php artisan tinker

App\Models\User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password123'),
    'role' => 'STAFF'
]);
```

### Via Migration/Seed
```php
User::create([
    'name' => 'Jane Smith',
    'email' => 'jane@example.com',
    'password' => bcrypt('password123'),
    'role' => 'ADMIN'
]);
```

## Login Page Customization

The login page is located at `resources/views/auth/login.blade.php`

You can customize:
- Logo image: Update `assets/velzon/images/logo-dark.png`
- Colors: Edit CSS in the `<style>` section
- Welcome text: Modify the header section
- Form fields: Add additional inputs as needed

## Security Notes

### Password Security
- Passwords are hashed using bcrypt (Laravel default)
- Never store plain text passwords
- Always use `bcrypt()` when setting passwords

### Session Security
- Sessions are secured using CSRF tokens
- Token regeneration on login/logout
- Cookies are HttpOnly by default

### Remember Me
- Uses secure "remember" token
- Tokens are hashed in database
- Can be disabled by removing from form

## Troubleshooting

### User Can't Log In
1. Check email and password combination
2. Verify user exists in database: `User::where('email', 'test@example.com')->first()`
3. Check password hash: User password was hashed with `bcrypt()`
4. Clear session cache: `php artisan cache:clear`

### Middleware Not Working
1. Check middleware is registered in `bootstrap/app.php`
2. Verify route has correct middleware: `->middleware('auth', 'role:ADMIN')`
3. Restart container: `docker-compose restart laravel_app`

### Session Not Persisting
1. Check `SESSION_DRIVER=database` in `.env`
2. Verify sessions table exists: `php artisan migrate`
3. Clear cookies in browser
4. Check database connection

## Next Steps

1. **Customize User Model**: Add additional user attributes as needed
2. **Create Admin Panel**: Build admin-only pages using role middleware
3. **Add User Management**: Create CRUD for user administration
4. **Implement Password Reset**: Add forgot password functionality
5. **Add Two-Factor Authentication**: For enhanced security
6. **Create Activity Logging**: Track user actions for audit trails
7. **Add Permission System**: Fine-grained permissions beyond roles

## Resources

- [Laravel Authentication](https://laravel.com/docs/12/authentication)
- [Laravel Authorization](https://laravel.com/docs/12/authorization)
- [Middleware](https://laravel.com/docs/12/middleware)
- [Hashing](https://laravel.com/docs/12/hashing)

---

**Status**: ✅ Authentication fully implemented and ready to use

**Login URL**: http://localhost:8001/login  
**Dashboard URL**: http://localhost:8001/dashboard  
**Test Credentials**: admin@example.com / password
