<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Estate Project

A Laravel 12 LTS web application with pre-configured MySQL database, database-driven queues, and SMTP email support.

## Features

- **Laravel 12 LTS** - Latest long-term support version
- **MySQL Database** - Configured and ready for use
- **Queue System** - Database driver for job processing
- **SMTP Email** - Environment-based configuration (no secrets committed)
- **Documentation** - Setup guides and changelog

## Prerequisites

- PHP 8.3+
- Composer
- MySQL 5.7+
- Node.js and npm (for frontend assets)

## Quick Start

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Configure Environment**
   ```bash
   cp .env.example .env
   ```
   
   Update the following in `.env`:
   ```env
   DB_USERNAME=your_mysql_username
   DB_PASSWORD=your_mysql_password
   MAIL_USERNAME=your_smtp_username
   MAIL_PASSWORD=your_smtp_password
   ```

3. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

4. **Run Migrations**
   ```bash
   php artisan migrate
   ```

5. **Start Development Server**
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

## Configuration

### Database
- **Driver**: MySQL
- **Connection**: Configured in `.env` (localhost, port 3306)
- **Database Name**: estate_project

### Queues
- **Driver**: Database
- **Job Table**: `jobs` (created during migration)
- **Processing**: Run `php artisan queue:work`

### Email
- **Mailer**: SMTP
- **Default Provider**: Mailtrap (for development)
- **Configuration**: Environment-based (`.env` file)

## Project Structure

```
docs/
├── CHANGELOG.md          # Project changelog
└── SETUP.md             # Detailed setup documentation
```

For additional configuration details, see [SETUP.md](docs/SETUP.md).

## Development

### Build Frontend Assets
```bash
npm run dev
```

### Run Tests
```bash
php artisan test
```

### Process Queue Jobs
```bash
php artisan queue:work
```

### Tinker Shell
```bash
php artisan tinker
```

## Documentation

- [Setup Guide](docs/SETUP.md) - Detailed configuration instructions
- [Changelog](docs/CHANGELOG.md) - Project version history
- [Laravel Documentation](https://laravel.com/docs/12) - Official Laravel docs

## Security Notes

- Never commit `.env` file to version control
- Keep database credentials and SMTP passwords in `.env` only
- Review and update security headers in production
- Run `php artisan config:cache` in production

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
# Estate-Project
Real estate project
>>>>>>> 7617695f773c84b1d1c571e69dc77cc2ef93756e
