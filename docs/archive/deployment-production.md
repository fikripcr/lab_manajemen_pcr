# Deployment & Production

## Overview

This section covers the essential steps to deploy the laboratory management system to a production environment.

## Environment Configuration

### Server Requirements

- **PHP**: Version 8.2 or higher
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Database**: MySQL 8.0+ or PostgreSQL 12+
- **Memory**: Minimum 512MB (recommended 1GB+)

### Environment Variables (.env)

Production-specific environment configuration:

```bash
APP_NAME="Production Application Name"
APP_ENV=production
APP_KEY= # Generate during deployment
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=production_database_name
DB_USERNAME=production_db_user
DB_PASSWORD=secure_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com  # or your mail server
MAIL_PORT=587
MAIL_USERNAME=your-mail-username
MAIL_PASSWORD=your-mail-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Deployment Process

### 1. Transfer Files

Option 1: Git deployment
```bash
git pull origin production
```

Option 2: Manual file transfer using SFTP

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies (if needed)
npm ci --production
```

### 3. Set Permissions

```bash
# Set directory permissions
chmod -R 755 storage bootstrap/cache

# Set specific file permissions
chmod -R 775 storage/logs
chmod -R 775 storage/framework/cache
chmod -R 775 storage/framework/sessions
chmod -R 775 storage/framework/views
chmod -R 775 storage/app
```

### 4. Environment Setup

```bash
# Generate application key
php artisan key:generate --force

# Link storage directory
php artisan storage:link
```

### 5. Database Setup

```bash
# Run database migrations
php artisan migrate --force

# Seed the database if necessary
php artisan db:seed --force
```

### 6. Performance Optimization

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache configuration for better performance
php artisan config:cache

# Cache routes for better performance
php artisan route:cache

# Cache views for better performance
php artisan view:cache

# Compile production assets
npm run build
```

## Security Considerations

### Important Security Steps

- Set `APP_DEBUG=false` in production
- Never commit `.env` files to version control
- Use strong passwords for database and other services
- Implement proper access controls
- Use HTTPS in production
- Keep your server and packages updated