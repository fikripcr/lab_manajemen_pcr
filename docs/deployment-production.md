# Deployment & Production

## Overview

This section covers the deployment process, production environment configuration, security considerations, performance optimization, and maintenance tasks for the laboratory management system.

## Environment Configuration

### Production Environment Setup

#### Server Requirements

- **PHP**: Version 8.2 or higher
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Database**: MySQL 8.0+ or PostgreSQL 12+
- **Node.js**: Version 16+ for asset compilation
- **Memory**: Minimum 512MB (recommended 1GB+)
- **Disk Space**: Minimum 500MB for application files
- **Extensions**: Required PHP extensions (see Laravel 12 requirements)

#### PHP Configuration

```ini
; php.ini
memory_limit = 512M
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
max_input_vars = 3000
```

#### Web Server Configuration

##### Apache (.htaccess)

The system includes an optimized `.htaccess` file for Apache:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

##### Nginx Configuration

For Nginx servers, use this configuration:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/your/laravel/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Environment Variables (.env)

Production-specific environment configuration:

```bash
APP_NAME="Production Application Name"
APP_ENV=production
APP_KEY= # Generated during deployment
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=production_database_name
DB_USERNAME=production_db_user
DB_PASSWORD=secure_password

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

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

### Pre-Deployment Checklist

1. **Environment Configuration**
   - Update `.env` for production
   - Set `APP_DEBUG=false`
   - Configure database, caching, and queue settings

2. **Security Checks**
   - Verify `.env` is not committed to version control
   - Ensure sensitive information is properly secured
   - Check file permissions

3. **Asset Compilation**
   - Compile production assets
   - Verify all assets load correctly

4. **Database Preparation**
   - Backup current database
   - Prepare for migration (if needed)

5. **Testing**
   - Run all tests to ensure functionality
   - Test critical user flows

### Deployment Steps

#### 1. Transfer Files

Option 1: Git deployment
```bash
git pull origin production
```

Option 2: Manual file transfer
- Use SFTP or similar to transfer files
- Ensure correct file permissions

#### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies (if needed)
npm ci --production
```

#### 3. Set File Permissions

```bash
# Set directory permissions
chmod -R 755 storage bootstrap/cache

# Set specific file permissions if needed
chmod -R 775 storage/logs
chmod -R 775 storage/framework/cache
chmod -R 775 storage/framework/sessions
chmod -R 775 storage/framework/views
chmod -R 775 storage/app
```

#### 4. Environment Setup

```bash
# Generate application key if not already set
php artisan key:generate --force

# Link storage directory
php artisan storage:link
```

#### 5. Database Migrations

```bash
# Run database migrations
php artisan migrate --force

# Seed the database if necessary
php artisan db:seed --force
```

#### 6. Caching Optimization

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
```

#### 7. Asset Compilation

```bash
# Compile production assets
npm run build

# Or if using Laravel Mix
npm run production
```

#### 8. Service Restart (if needed)

```bash
# Restart PHP-FPM if applicable
sudo systemctl reload php8.2-fpm

# Restart queue workers if applicable
sudo supervisorctl restart all
```

### Automated Deployment Script

Create a deployment script for consistent deployments:

```bash
#!/bin/bash

# deployment.sh
echo "Starting deployment..."

# Update code
git pull origin production

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci --production

# Set permissions
chmod -R 755 storage bootstrap/cache

# Generate key if needed
php artisan key:generate --force

# Link storage
php artisan storage:link

# Run migrations
php artisan migrate --force

# Optimize production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Compile assets
npm run production

echo "Deployment completed successfully!"
```

## Security Considerations

### PHP Configuration Security

```ini
; Disable dangerous functions
disable_functions = exec,passthru,shell_exec,system,proc_open,popen

; Hide PHP version
expose_php = Off

; Enable security-related settings
allow_url_fopen = Off
```

### Web Server Security

#### Apache Security Headers

Add to `.htaccess`:
```apache
# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
</IfModule>
```

#### Nginx Security Headers

```nginx
add_header X-Content-Type-Options nosniff;
add_header X-Frame-Options DENY;
add_header X-XSS-Protection "1; mode=block";
add_header Referrer-Policy "strict-origin-when-cross-origin";
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload";
```

### Application Security

#### Secure Session Configuration

```php
// config/session.php for production
'secure' => env('SESSION_SECURE_COOKIE', true),
'http_only' => true,
'same_site' => 'lax',
```

#### CSRF Protection

Laravel's CSRF protection is enabled by default and works automatically.

#### Input Validation

Always validate user inputs using FormRequest classes:

```php
// Example validation rule
public function rules()
{
    return [
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|min:8|confirmed',
        'name' => 'required|string|max:255',
    ];
}
```

#### SQL Injection Prevention

Laravel's Eloquent ORM and Query Builder protect against SQL injection when used properly:

```php
// Safe - using parameter binding
$users = User::where('status', $status)->get();

// Safe - using Eloquent methods
$users = User::whereStatus($status)->get();
```

## Performance Optimization

### Caching Strategies

#### Configuration Caching

```bash
# Cache configuration
php artisan config:cache
```

#### Route Caching

```bash
# Cache routes (requires all routes to be cacheable)
php artisan route:cache
```

#### View Caching

```bash
# Cache compiled views
php artisan view:cache
```

#### Query Caching

Implement query caching for expensive queries:

```php
// In controller or service
$stats = Cache::remember('user_stats', 3600, function () {
    return [
        'total_users' => User::count(),
        'active_users' => User::where('status', 'active')->count(),
    ];
});
```

### Database Optimization

#### Database Indexing

Ensure proper indexing on frequently queried columns:

```sql
-- Example indexes
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_status ON users(status);
CREATE INDEX idx_users_created_at ON users(created_at);
CREATE INDEX idx_users_status_created ON users(status, created_at);
```

#### Database Connection Pooling

Configure database connection pooling in production:

```php
// config/database.php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    // ... other configuration
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        PDO::ATTR_PERSISTENT => true, // Enable persistent connections
    ]) : [],
],
```

### Asset Optimization

#### CSS and JavaScript Minification

Compile assets for production:

```bash
# Using Laravel Mix
npm run production
```

#### Image Optimization

- Use appropriate formats (WebP when possible)
- Implement responsive images
- Use lazy loading for non-critical images

### Server-Level Optimization

#### Redis Configuration

For production use, configure Redis properly:

```bash
# redis.conf
maxmemory 256mb
maxmemory-policy allkeys-lru
```

#### PHP-FPM Configuration

Optimize PHP-FPM for better performance:

```ini
; /etc/php/8.2/fpm/pool.d/www.conf
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
```

## SSL/HTTPS Configuration

### SSL Certificate Installation

#### Using Let's Encrypt

```bash
# Install Certbot
sudo apt-get install certbot python3-certbot-nginx

# For Nginx
sudo certbot --nginx -d your-domain.com

# For Apache
sudo certbot --apache -d your-domain.com
```

### Force HTTPS Redirect

#### Apache

```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

#### Nginx

```nginx
server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}
```

## Monitoring and Logging

### Error Logging Configuration

Configure error logging for production:

```php
// config/logging.php
'default' => env('LOG_CHANNEL', 'stack'),
'debug' => env('LOG_LEVEL', 'debug'),

'stack' => [
    'driver' => 'stack',
    'channels' => ['daily', 'slack'],
    'ignore_exceptions' => false,
],

'daily' => [
    'driver' => 'daily',
    'path' => storage_path('logs/laravel.log'),
    'level' => env('LOG_LEVEL', 'debug'),
    'days' => 14,
],
```

### Log Management

#### Log Rotation

Implement log rotation to manage disk space:

```bash
# /etc/logrotate.d/laravel
/path/to/your/laravel/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    notifempty
    create 644 www-data www-data
    postrotate
        systemctl reload php8.2-fpm > /dev/null 2>&1 || true
    endscript
}
```

## Maintenance Tasks

### Regular Maintenance

#### Database Maintenance

```bash
# Clean up soft-deleted records periodically
php artisan model:prune

# Optimize database tables (MySQL)
php artisan tinker --execute="\$tables = DB::select('SHOW TABLES'); foreach(\$tables as \$table) { \$tableName = array_values((array)\$table)[0]; echo DB::statement('OPTIMIZE TABLE ' . \$tableName) ? 'Optimized: ' . \$tableName . PHP_EOL : 'Error optimizing: ' . \$tableName . PHP_EOL; }"
```

#### File Cleanup

```bash
# Clean up old session files
find storage/framework/sessions -type f -name '*' -mtime +1 -delete

# Clean up old cache files
php artisan cache:prune-stale-tags
```

### Backup Strategy

#### Automated Backups

Create a backup script for regular automated backups:

```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/path/to/backup/directory"

# Create backup directory if it doesn't exist
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u $DB_USERNAME -p$DB_PASSWORD $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# File backup (excluding unnecessary directories)
tar --exclude='vendor' --exclude='node_modules' --exclude='.git' --exclude='storage/logs' -czf $BACKUP_DIR/file_backup_$DATE.tar.gz /path/to/your/laravel/

# Remove backups older than 30 days
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
```

### Scheduled Tasks

#### Laravel Task Scheduling

Configure Laravel's task scheduler in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Create daily database backup at 2:00 AM
    $schedule->command('backup:db')->daily()->at('02:00');
    
    // Clean up old backups older than 30 days at 4:00 AM
    $schedule->command('backup:cleanup')->daily()->at('04:00');
    
    // Clean up expired user sessions at 3:00 AM
    $schedule->command('session:gc')->daily()->at('03:00');
    
    // Clear old logs at 1:00 AM
    $schedule->call(function () {
        $logFiles = glob(storage_path('logs/*.log'));
        foreach ($logFiles as $file) {
            if (filemtime($file) < strtotime('-7 days')) {
                unlink($file);
            }
        }
    })->daily()->at('01:00');
    
    // Prune soft-deleted records at 11:00 PM
    $schedule->command('model:prune')->daily()->at('23:00');
}
```

#### Crontab Configuration

Add the Laravel scheduler to your server's crontab:

```bash
# Edit crontab
crontab -e

# Add this line to run Laravel scheduler every minute
* * * * * cd /path/to/your/laravel && php artisan schedule:run >> /dev/null 2>&1
```

## Troubleshooting Common Issues

### Application Issues

#### Clearing Caches

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# If issues persist
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Artisan Command Issues

```bash
# Check if artisan commands are working
php artisan list

# If getting "Class '...' not found" errors
composer dump-autoload --optimize
```

### Permission Issues

```bash
# Fix common permission issues
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs
chmod -R 775 storage/framework/cache
chmod -R 775 storage/framework/sessions
chmod -R 775 storage/framework/views
chmod -R 775 storage/app
```

### Database Issues

```bash
# Check if database connection is working
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Connected successfully';"

# Run migrations if needed
php artisan migrate --force
```

### Email Issues

```bash
# Test email configuration
php artisan tinker --execute="Mail::raw('Test email', function($message) { $message->to('test@example.com')->subject('Test Email'); }); echo 'Email sent';"
```

## Performance Monitoring

### Server Monitoring

Monitor server resources:
- CPU usage
- Memory usage
- Disk space
- Network traffic
- Database performance

### Application Monitoring

Use Laravel Telescope or similar tools for application-specific monitoring:
- Request timing
- Database query performance
- Queue job processing
- Exception tracking

## Rollback Plan

In case of deployment issues, have a rollback plan:

1. **Immediate Actions**:
   - Roll back code changes
   - Restore previous database state if needed
   - Restart services

2. **Communication**:
   - Notify stakeholders about the issue
   - Provide estimated resolution time
   - Communicate when service is restored

3. **Documentation**:
   - Document what went wrong
   - Document how it was fixed
   - Update procedures to prevent future occurrences