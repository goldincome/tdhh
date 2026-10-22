# Production Environment Configuration

This document specifies the required `.env` variables for production deployment.

## Critical Security Settings

```env
# Must be set to production
APP_ENV=production

# Must be false in production
APP_DEBUG=false

# Production domain
APP_URL=https://tdhh.co.uk
APP_NAME="TD Holiday Homes"
```

## Telemetry (Disabled by Default)

```env
# Disable Laravel Debugbar in production
DEBUGBAR_ENABLED=false

# Disable Laravel Telescope in production
TELESCOPE_ENABLED=false

# Telescope: Empty list denies all access (fail-closed)
# Leave empty or unset to block all users
TELESCOPE_ALLOWED_EMAILS=

# IDE Helper disabled in production
IDE_HELPER_ENABLED=false
```

## Database

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=tdhh
DB_USERNAME=root
DB_PASSWORD=your_secure_password_here
```

## Cache & Session

```env
CACHE_DRIVER=redis
SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync
```

## Email (for contact form)

```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=info@tdhh.co.uk
MAIL_FROM_NAME="TD Holiday Homes"
```

## Application Keys

```env
APP_KEY=base64:your_key_here
JWT_SECRET=your_jwt_secret_here
```

## Currency & Localization

```env
DEFAULT_CURRENCY=GBP
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

## AWS S3 (if using for media storage)

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=eu-west-2
AWS_BUCKET=tdhh-media
AWS_URL=https://tdhh-media.s3.eu-west-2.amazonaws.com
```

## Deployment Steps

1. Create `.env` file from `.env.example`
2. Update all values above with production credentials
3. Run: `php artisan config:cache`
4. Verify: `php artisan config:get app.debug` should output `false`
5. Verify: `php artisan seo:diagnose` should show all FLAGS as passing

## Verification Checklist

After setting `.env`:

```bash
# Verify debug is OFF
php artisan config:get app.debug              # Should be: false

# Verify telemetry is OFF
php artisan config:get debugbar.enabled       # Should be: false
php artisan config:get telescope.enabled      # Should be: false

# Verify database connection
php artisan tinker --execute "echo DB::connection()->getPdo() ? 'OK' : 'FAIL';"

# Verify routes load (no /telescope or /_debugbar in production mode)
php artisan route:list | grep -i telescope    # Should return: (nothing)
```

## Security Notes

- Never commit `.env` to git
- Never share `.env` in logs or error messages
- Rotate `APP_KEY` and `JWT_SECRET` periodically
- Use environment-specific credentials (never reuse dev passwords)
- Store sensitive values in secure vaults, not in `.env` directly (use AWS Secrets Manager, etc.)
- After any `.env` change, run: `php artisan config:cache && php artisan route:cache`
