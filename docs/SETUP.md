# Project Setup Documentation

## Overview
This is a Laravel 12 LTS project configured with MySQL database, database-driven queues, and SMTP email support.

## Environment Configuration

### Database Configuration
- **Driver**: MySQL
- **Host**: 127.0.0.1 (localhost)
- **Port**: 3306
- **Database**: estate_project
- **Connection**: Set in `.env` file (`DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)

**Note**: Update `DB_USERNAME` and `DB_PASSWORD` in `.env` with your MySQL credentials.

### Queue Configuration
- **Driver**: Database
- **Table**: jobs (automatically created during migration)
- **Connection**: Set via `QUEUE_CONNECTION=database` in `.env`

The queue system uses the MySQL database to store and process jobs. Jobs table will be created with the initial migrations.

### Mail Configuration
- **Mailer**: SMTP
- **Scheme**: TLS
- **Default Provider**: Mailtrap (for development)
- **Credentials**: Set in `.env` file (`MAIL_USERNAME`, `MAIL_PASSWORD`)

**Note**: No secrets are committed to version control. Update `MAIL_USERNAME` and `MAIL_PASSWORD` in `.env` with your SMTP credentials.

## Environment Variables

The following environment variables must be configured in `.env` file:

```env
# Database
DB_USERNAME=root           # MySQL username
DB_PASSWORD=               # MySQL password

# Mail (SMTP credentials)
MAIL_USERNAME=             # SMTP username/email
MAIL_PASSWORD=             # SMTP password
```

### Example Mailtrap Configuration:
```env
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
```

## Initial Setup Steps

1. **Install Dependencies**
   ```bash
   composer install
   ```

2. **Configure Environment**
   - Copy `.env.example` to `.env` if not already done
   - Update database credentials in `.env`
   - Update mail credentials in `.env`
   - Run `php artisan key:generate` (already done)

3. **Run Migrations**
   ```bash
   php artisan migrate
   ```
   This will create database tables including the `jobs` table for queues.

4. **Start Queue Worker** (optional, for processing queued jobs)
   ```bash
   php artisan queue:work
   ```

5. **Start Development Server**
   ```bash
   php artisan serve
   ```
   The application will be available at `http://localhost:8000`

## Project Structure

```
estate-project/
├── app/                    # Application code
├── config/                 # Configuration files
├── database/
│   ├── migrations/        # Database migrations
│   └── database.sqlite    # SQLite database (for testing)
├── docs/                  # Documentation
│   └── CHANGELOG.md       # Project changelog
├── public/                # Public assets
├── resources/             # Views, language, CSS
├── routes/                # Application routes
├── storage/               # Logs, cache, sessions
├── tests/                 # Test files
├── .env                   # Environment configuration (not in version control)
├── .env.example          # Example environment configuration
├── composer.json         # PHP dependencies
└── artisan               # Artisan CLI tool
```

## Important Notes

- **Security**: Never commit `.env` file to version control. Only commit `.env.example`.
- **Database Credentials**: Update database credentials before running migrations.
- **SMTP Credentials**: Configure SMTP credentials before sending emails.
- **No Business Logic**: This is a base setup with no application logic implemented.

## Queue Processing

To process queued jobs in development:
```bash
php artisan queue:work
```

To process jobs synchronously (useful for testing):
Set `QUEUE_CONNECTION=sync` in `.env`

## Testing Mail

To test email configuration without actually sending:
```bash
php artisan tinker
Mail::raw('Test message', function($message) { $message->to('test@example.com'); });
```

Or temporarily set `MAIL_MAILER=log` in `.env` to log email output to `storage/logs/laravel.log`.

## Troubleshooting

- **Migration errors**: Ensure MySQL is running and credentials are correct
- **Mail errors**: Verify SMTP credentials and host configuration
- **Queue issues**: Check database connection and ensure jobs table exists

For more information, visit the [Laravel documentation](https://laravel.com/docs/12).
