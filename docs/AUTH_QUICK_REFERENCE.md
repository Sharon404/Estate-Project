# Authentication Quick Reference

## Accessing the Application

### Login
- **URL**: `http://localhost:8001/login`
- **Admin Account**:
  - Email: `admin@example.com`
  - Password: `password`
  - Role: ADMIN
- **Staff Account**:
  - Email: `staff@example.com`
  - Password: `password`
  - Role: STAFF

### Dashboard
- **URL**: `http://localhost:8001/dashboard`
- **Requires**: Authentication (redirects to login if not authenticated)

### Logout
- Click user profile dropdown in top-right
- Select "Logout"
- Redirects to home page

## Code Examples

### Protect Routes by Role
```php
// Only ADMIN can access
Route::get('/admin-panel', function () {
    return view('admin.panel');
})->middleware('auth', 'role:ADMIN');

// ADMIN or STAFF can access
Route::get('/reports', function () {
    return view('reports');
})->middleware('auth', 'role:ADMIN,STAFF');
```

### Check Authentication in Views
```blade
@if(auth()->check())
    <p>Welcome, {{ auth()->user()->name }}</p>
    <p>Role: {{ auth()->user()->role }}</p>
@else
    <a href="{{ route('login') }}">Login</a>
@endif
```

### Check Role in Views
```blade
@if(auth()->user()->role === 'ADMIN')
    <!-- Admin-only content -->
    <a href="/admin">Admin Panel</a>
@endif
```

### Create New User
```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name' => 'New User',
    'email' => 'user@example.com',
    'password' => bcrypt('password'),
    'role' => 'STAFF'
]);
```

## Key Files

| File | Purpose |
|------|---------|
| `app/Http/Controllers/Auth/LoginController.php` | Handle login/logout |
| `app/Http/Controllers/DashboardController.php` | Dashboard logic |
| `app/Http/Middleware/EnsureUserHasRole.php` | Role checking |
| `resources/views/auth/login.blade.php` | Login page (Velzon styled) |
| `resources/views/layouts/velzon/app.blade.php` | Admin layout (user dropdown) |
| `routes/web.php` | All authentication routes |
| `database/migrations/0001_01_01_000000_create_users_table.php` | Users table schema |
| `database/seeders/DatabaseSeeder.php` | Test user seeding |

## Database

### Users Table
- `id` - Primary key
- `name` - User name
- `email` - Unique email
- `password` - Hashed password
- `role` - ENUM: ADMIN or STAFF
- `email_verified_at` - Email verification timestamp
- `remember_token` - Remember me token

### Check Users in Database
```bash
docker exec laravel_app php artisan db:show
```

Or via tinker:
```bash
docker exec laravel_app php artisan tinker
App\Models\User::all();
```

## Common Tasks

### Reset Migrations and Reseed
```bash
docker exec laravel_app php artisan migrate:fresh --seed
```

### Change a User's Role
```bash
docker exec laravel_app php artisan tinker
```

```php
$user = App\Models\User::find(1);
$user->role = 'ADMIN';
$user->save();
```

### Delete a User
```bash
docker exec laravel_app php artisan tinker
```

```php
App\Models\User::find(1)->delete();
```

### Change a User's Password
```bash
docker exec laravel_app php artisan tinker
```

```php
$user = App\Models\User::find(1);
$user->password = bcrypt('newpassword');
$user->save();
```

## Middleware Reference

### Built-in Middleware
- `auth` - Require authentication
- `guest` - Require no authentication (login/register pages)

### Custom Middleware
- `role:ADMIN` - Require ADMIN role
- `role:STAFF` - Require STAFF role
- `role:ADMIN,STAFF` - Require ADMIN or STAFF role

## Routes

```
GET  /login                     Show login form
POST /login                     Process login
POST /logout                    Process logout
GET  /dashboard                 Dashboard (auth required)
GET  /                          Home page
```

## Sessions & Cookies

All session data is stored in the database (`sessions` table):
- Session ID
- User ID
- IP Address
- User Agent
- Payload
- Last Activity timestamp

## Next Steps

1. Create additional pages with role-based access
2. Add user management interface
3. Implement password reset
4. Add activity logging
5. Create permission system

---

For detailed documentation, see [AUTHENTICATION.md](AUTHENTICATION.md)
