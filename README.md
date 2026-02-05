# Estate Project - Property Rental Management System

A comprehensive, enterprise-grade property rental management system with M-PESA payment integration, role-based access control (RBAC), and audit logging built with Laravel 12.

**Status**: ⚠️ In Development (Core Features Working | Booking Flow in Progress)

---

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Quick Start](#quick-start)
- [Project Structure](#project-structure)
- [Database Schema](#database-schema)
- [API Endpoints](#api-endpoints)
- [User Flows](#user-flows)
- [Configuration](#configuration)
- [Development](#development)
- [Deployment](#deployment)
- [Troubleshooting](#troubleshooting)

---

## ✨ Features

### ✅ Implemented & Working
- **Property Management**: Full CRUD for properties with multi-image support
- **Image Management**: Upload multiple images per property, set primary image, responsive carousel display
- **Admin Dashboard**: Complete property listing with pagination and filtering
- **Role-Based Access Control (RBAC)**: Admin and Staff roles with 26 granular permissions
- **Audit Logging**: Track all user actions with IP address, user agent, and metadata
- **Payment Infrastructure**: M-PESA integration with STK push and manual verification
- **Receipt Generation**: PDF receipts with booking details and payment information
- **Frontend Property Display**: Dynamic property listings with real-time database queries
- **Responsive Design**: Built on Velzon dashboard template with Tausi custom branding
- **Email System**: Queue-based email notifications for contact forms and receipts

### ⚠️ Partially Implemented
- **Booking System**: Database tables and models exist; 3-step form in progress (Step 2 confirmation incomplete)
- **Payment Processing**: M-PESA callbacks configured; booking-to-payment linking needs work
- **Admin User Management**: Views exist but authentication flows incomplete
- **Staff Dashboard**: Framework in place but no content

### ❌ Not Yet Implemented
- **Guest Registration/Login**: No user account system for guests
- **Search & Filter**: No date range or amenity filtering on properties page
- **Booking History**: No "My Bookings" feature for guests
- **Reviews/Ratings**: No guest review system
- **Wishlist/Favorites**: No property favoriting feature
- **Blog/Gallery**: Static pages; not database-driven
- **Testimonials**: Hardcoded content; not editable via admin
- **Cancellation Policy**: No booking cancellation logic
- **Availability Calendar**: No visual calendar display

---

## 🛠️ Tech Stack

| Component | Technology |
|-----------|------------|
| **Framework** | Laravel 12.48.1 |
| **Language** | PHP 8.4.17 |
| **Database** | MySQL 8.0.44 |
| **Frontend** | Bootstrap 5 + Vite |
| **Templating** | Blade |
| **Docker** | Docker Compose (Nginx, PHP-FPM, MySQL) |
| **Payment Gateway** | M-PESA (Daraja API) |
| **Queue System** | Database driver |
| **Authentication** | Laravel Auth + Custom RBAC |
| **Audit Trail** | Custom AuditLog middleware |

---

## 🚀 Quick Start

### Prerequisites
- Docker & Docker Compose installed
- Git
- At least 2GB RAM available

### Installation

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd Estate\ Project
   ```

2. **Set Up Environment**
   ```bash
   cp .env.example .env
   ```

3. **Configure .env**
   ```env
   APP_NAME="Tausi Rental"
   APP_DEBUG=true
   APP_URL=http://localhost

   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=estate_project
   DB_USERNAME=root
   DB_PASSWORD=root

   QUEUE_CONNECTION=database
   MAIL_DRIVER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   ```

4. **Start Docker Containers**
   ```bash
   docker-compose up -d
   ```

5. **Run Migrations**
   ```bash
   docker-compose exec app php artisan migrate
   ```

6. **Generate Application Key**
   ```bash
   docker-compose exec app php artisan key:generate
   ```

7. **Create Storage Symlink**
   ```bash
   docker-compose exec app php artisan storage:link
   ```

8. **Seed Demo Data (Optional)**
   ```bash
   docker-compose exec app php artisan db:seed
   ```

### Access the Application

- **Frontend**: http://localhost:8000
- **Admin Dashboard**: http://localhost:8000/login (credentials: see seed or create via tinker)
- **Database Admin (Adminer)**: http://localhost:8081

---

## 📁 Project Structure

```
app/
├── Http/Controllers/
│   ├── FrontendController.php              # Public-facing pages
│   ├── Auth/LoginController.php            # Authentication
│   ├── Admin/
│   │   ├── PropertyController.php          # Property CRUD + image management
│   │   ├── BookingsController.php          # Booking list/detail
│   │   ├── AnalyticsController.php         # Dashboard analytics
│   │   ├── AuditLogsController.php         # Audit trail viewing
│   │   ├── UsersController.php             # User management
│   │   ├── MpesaVerificationController.php # Payment verification
│   │   ├── RefundsController.php           # Refund management
│   │   └── PayoutsController.php           # Payout processing
│   ├── Booking/BookingController.php       # 3-step reservation flow
│   ├── Payment/
│   │   ├── PaymentController.php           # Payment intents & receipts
│   │   ├── MpesaController.php             # M-PESA STK & callbacks
│   │   └── AdminPaymentController.php      # Admin verification
│   └── Staff/
│       ├── StaffDashboardController.php    # Staff view
│       ├── StaffBookingsController.php     # Staff booking view
│       └── StaffVerificationController.php # Staff verification tasks
│
├── Models/
│   ├── User.php, Property.php, PropertyImage.php
│   ├── Booking.php, Guest.php, PaymentIntent.php
│   ├── BookingTransaction.php, Receipt.php
│   ├── AuditLog.php, SupportTicket.php
│   ├── Permission.php, Role.php
│   └── [M-PESA Models]
│
├── Services/
│   ├── BookingService.php
│   ├── PaymentService.php, MpesaStkService.php
│   ├── ReceiptService.php, EmailService.php
│   ├── AuditService.php
│   └── [Additional Services]
│
└── Mail/
    ├── ContactFormSubmitted.php
    └── ReceiptNotificationMail.php

database/
├── migrations/                      # 36 migrations
└── seeders/

resources/views/
├── frontend/                        # Public pages
├── admin/                           # Admin panels
├── booking/                         # Booking flow (3-step)
├── payment/                         # Payment interface
└── layouts/

routes/web.php                       # 77 routes

config/
├── app.php, database.php, mail.php
└── mpesa.php

storage/app/public/properties/       # Uploaded images
```

---

## 🗄️ Database Schema (35 Tables)

### Core Tables

| Table | Key Fields |
|-------|-----------|
| **properties** | id, name, nightly_rate, currency, description (TEXT), amenities (JSON), status (APPROVED\|PENDING\|REJECTED) |
| **property_images** | id, property_id, file_path, is_primary (boolean) |
| **bookings** | id, booking_ref, property_id, guest_id, check_in, check_out, status (DRAFT\|PENDING_PAYMENT\|PAID\|CANCELLED), total_amount |
| **guests** | id, full_name, email, phone_e164 |
| **payment_intents** | id, booking_id, method (MPESA_STK\|MPESA_MANUAL), amount, status |
| **booking_transactions** | id, booking_id, type (CREDIT\|DEBIT), amount, reference (immutable ledger) |
| **receipts** | id, booking_id, receipt_number (RCP-{booking_ref}), amount_paid |

### RBAC Tables
- **users**: With role field (admin\|staff\|guest)
- **permissions**: Granular permission names
- **roles**: Role definitions
- **role_permissions**: Role-permission mapping

### Audit & Payment Tables
- **audit_logs**: action, description, ip_address, user_agent, meta (JSON)
- **mpesa_stk_requests, mpesa_stk_callbacks, mpesa_c2b_transactions**: M-PESA integration

---

## 🔗 API Endpoints

### Frontend Routes (Public)
```
GET  /                              # Home page
GET  /properties                    # Properties listing
GET  /property/{id}                 # Property detail
GET  /contact, POST /contact        # Contact form
GET  /facilities, /gallery, /blog   # Static pages
```

### Booking Routes
```
GET  /reservation                   # Step 1: Form
GET  /reservation/confirm           # Step 2: Confirmation (incomplete)
POST /booking/store                 # Step 3: Create booking
GET  /bookings/{booking}/summary    # Summary
```

### Payment Routes
```
GET  /payment/booking/{booking}     # Payment page
POST /payment/intents               # Create intent
POST /payment/mpesa/stk             # STK push
POST /payment/mpesa/callback        # M-PESA callback
POST /payment/manual-entry          # Manual submission
GET  /payment/receipts/{number}     # Receipt
```

### Admin Routes (Protected)
```
GET  /admin/dashboard               # Dashboard
GET  /admin/properties              # Property list
GET  /admin/properties/create       # Create form
POST /admin/properties              # Store property
GET  /admin/properties/{id}/edit    # Edit form
PUT  /admin/properties/{id}         # Update property
DELETE /admin/properties/{id}       # Delete property
DELETE /admin/properties/{id}/photos/{image} # Delete image
POST /admin/properties/{id}/photos/{image}/primary # Set primary

GET  /admin/bookings                # Booking list
GET  /admin/analytics               # Analytics
GET  /admin/audit-logs              # Audit trail
GET  /admin/users                   # User management
GET  /admin/mpesa-verification      # Payment verification
GET  /admin/refunds                 # Refund requests
GET  /admin/tickets                 # Support tickets
```

### Staff Routes (Protected)
```
GET  /staff/dashboard               # Staff dashboard
GET  /staff/bookings                # View bookings
GET  /staff/verification            # Verification tasks
```

---

## 👥 User Flows

### Guest Booking Flow (⚠️ Incomplete)
```
1. Guest visits /reservation
2. Fills form: check-in, check-out, rooms, guests
3. Form posts to /booking/store
   ✅ Creates Guest & DRAFT Booking
   ❌ NO property selection (always uses first active property)
4. Redirects to /reservation/confirm
   ❌ ISSUE: Confirmation form is empty, no data passed
5. Guest should confirm and proceed to payment
6. Payment at /payment/booking/{booking}
   ✅ STK push initiated or manual fallback
7. Callback marks booking PAID
8. Receipt generated and emailed
```

### Admin Property Management Flow (✅ Working)
```
1. Admin logs in → /admin/properties
2. Creates: /admin/properties/create
   ✅ Fills all fields, uploads multiple images
3. Edits: /admin/properties/{id}/edit
   ✅ Modifies fields, manages images
4. Deletes: /admin/properties/{id}
   ✅ Cascades delete all images
5. Manages images:
   ✅ Delete specific images
   ✅ Set primary image
```

---

## ⚙️ Configuration

### M-PESA Integration (.env)
```env
MPESA_CONSUMER_KEY=your_daraja_consumer_key
MPESA_CONSUMER_SECRET=your_daraja_consumer_secret
MPESA_BUSINESS_SHORTCODE=your_shortcode
MPESA_PASSKEY=your_passkey
MPESA_CALLBACK_URL=https://yourdomain.com/payment/mpesa/callback
```

### Email Configuration (.env)
```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@tausirental.com
ADMIN_EMAIL=admin@tausirental.com
```

---

## 🔧 Development

### Database
```bash
docker-compose exec app php artisan migrate           # Run migrations
docker-compose exec app php artisan migrate:rollback # Rollback
docker-compose exec app php artisan db:seed          # Seed data
docker-compose exec app php artisan tinker           # Tinker shell
```

### Frontend Assets
```bash
npm run dev      # Development mode
npm run build    # Production build
```

### Optimization
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### Logs
```bash
docker-compose exec app tail -f storage/logs/laravel.log
docker-compose logs -f app
```

---

## 🚀 Deployment

### Pre-Deployment Checklist
- [ ] Set `APP_DEBUG=false`
- [ ] Configure M-PESA credentials
- [ ] Set up email service
- [ ] Create admin user
- [ ] Run migrations
- [ ] Test M-PESA sandbox
- [ ] Enable HTTPS
- [ ] Set up M-PESA IP whitelist
- [ ] Configure backups
- [ ] End-to-end testing

### Deployment Steps
```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan cache:clear
docker-compose restart app
```

---

## 🐛 Troubleshooting

### Images Not Displaying
```bash
docker-compose exec app php artisan storage:link
docker-compose exec app chmod -R 755 storage/app/public
```

### Payment Callbacks Not Received
- Verify callback URL is publicly accessible
- Confirm M-PESA IP addresses are whitelisted
- Check `laravel.log` for callback errors
- Verify `mpesa_stk_callbacks` table for payloads

### Database Connection Issues
```bash
docker-compose ps
docker-compose logs db
docker-compose restart db
```

### Out of Memory
```bash
# Increase in docker/php/php.ini
memory_limit = 256M
```

---

## 📊 Key Statistics

- **35 Database Tables**: Comprehensive data model
- **77 API Routes**: Full REST coverage
- **26 Admin/Staff Permissions**: Granular RBAC
- **27 Blade Templates**: Complete admin interface
- **12 Service Classes**: Modular business logic
- **100% Test Pass Rate**: Admin views validated

---

## 🔒 Security Best Practices

1. Never commit `.env` file
2. Validate all inputs with Laravel rules
3. CSRF protection enabled on all forms
4. SQL injection prevention via query builder
5. Rate limiting on payment/auth endpoints
6. Complete audit logging of all actions
7. Bcrypt password hashing
8. HTTPS required in production
9. M-PESA IP whitelist for callbacks
10. Regular database backups

---

## 📝 License

Proprietary software. All rights reserved.

---

## 📞 Support

For issues, bugs, or feature requests, open an issue in the repository or contact the development team.

---

**Last Updated**: February 5, 2026  
**Version**: 1.0.0 (In Development)  
**Current Focus**: Completing booking confirmation flow and implementing search functionality
