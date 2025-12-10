# System Monitoring & Backup

## Overview

The system includes comprehensive monitoring and backup capabilities to ensure data integrity, system health, and operational continuity. These features are managed through the system configuration interface and backend services.

## System Dashboard

The system dashboard provides comprehensive monitoring of key metrics and system health indicators.

### Dashboard Components

- **User Statistics**: Total users, active users, role distribution
- **Activity Logs**: Recent system activities and user actions
- **Error Logs**: Latest system errors and exceptions
- **Server Monitoring**: Real-time disk space, database size, and project breakdown
- **Backup Status**: Information on latest backups and storage usage

### Accessing the Dashboard

The system dashboard is accessible through the "System Dashboard" menu in the admin panel.

## Server Monitoring with Spatie Server Monitor

The system implements server monitoring capabilities using the Spatie Server Monitor package.

### Monitoring Capabilities

- **Disk Space**: Monitors available disk space on the server
- **Database Size**: Tracks the size of the database
- **Project Size Breakdown**: Detailed breakdown of project components:
  - Applications directory size
  - Uploads directory size
  - Backup directory size
  - Logs directory size

### Configuration

The monitoring service is configured in `config/server-monitor.php` and can be customized with:
- Monitoring intervals
- Threshold values for alerts
- Notification settings for monitoring failures

### Monitoring Implementation

```php
use Spatie\ServerMonitor\Models\Check;

// Example of adding a custom check
Check::create([
    'model' => YourModel::class,
    'type' => 'disk_space',
    'host' => 'localhost',
    'port' => 22,
    'user' => 'server_user',
]);
```

## Backup System

The system includes a robust backup solution with both database and file backup capabilities.

### Database Backup

#### Features
- Creates SQL dump of the entire database
- Uses `mysqldump` for efficient database backups
- Stores backups with timestamp in filename format: `database_backup_YYYY-MM-DD_HH-MM-SS.sql`
- Configurable path to `mysqldump` utility in application configuration

#### Implementation

```php
use Illuminate\Support\Facades\Storage;

public function createDatabaseBackup()
{
    $filename = 'database_backup_' . date('Y-m-d_H-i-s') . '.sql';
    $path = storage_path('app/private/backup/' . $filename);
    
    $command = sprintf(
        '%s --user=%s --password=%s --host=%s %s > %s',
        config('backup.mysqldump_path'),
        config('database.connections.mysql.username'),
        config('database.connections.mysql.password'),
        config('database.connections.mysql.host'),
        config('database.connections.mysql.database'),
        $path
    );
    
    exec($command);
    
    return $filename;
}
```

### Web File Backup

#### Features
- Creates ZIP archive of application files
- Includes important directories while excluding unnecessary ones:
  - Excludes `vendor/`, `node_modules/`, `.git/`, and `storage/` to reduce size
  - Includes application code, assets, and configurations
- Stores backups with timestamp in filename format: `web_backup_YYYY-MM-DD_HH-MM-SS.zip`

#### Implementation

```php
use ZipArchive;
use Illuminate\Support\Facades\Storage;

public function createWebFileBackup()
{
    $filename = 'web_backup_' . date('Y-m-d_H-i-s') . '.zip';
    $path = storage_path('app/private/backup/' . $filename);
    
    $zip = new ZipArchive();
    if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(base_path())
        );
        
        foreach ($files as $name => $file) {
            // Skip excluded directories
            if (strpos($name, 'vendor/') !== false || 
                strpos($name, 'node_modules/') !== false || 
                strpos($name, '.git/') !== false ||
                strpos($name, 'storage/') !== false) {
                continue;
            }
            
            if (!$file->isDir()) {
                $relativePath = substr($name, strlen(base_path()) + 1);
                $zip->addFile($name, $relativePath);
            }
        }
        
        $zip->close();
        return $filename;
    }
    
    return false;
}
```

### Backup Management Interface

Access the backup management through the "System Config" menu in the admin panel:

#### Features
- **View Existing Backups**: List all available backups with size and creation date
- **Download Backups**: Download specific backup files for storage or restoration
- **Delete Backups**: Remove old backups to manage storage space
- **Create New Backups**: Initiate new database or web file backups on demand

### Backup Security

- Backup files are stored in `storage/app/private/backup/` directory
- This directory is not accessible via web requests for security
- Backup filenames include timestamps to prevent conflicts
- Access to backup functions is restricted to authorized administrators only

## Error Logging System

The system implements comprehensive error logging that captures all application and database errors.

### Features

- **System-Wide Error Capture**: Captures all types of errors including:
  - Application exceptions
  - Database errors
  - AJAX request errors
  - HTTP errors (404, 500, etc.)
- **Detailed Context Information**: Stores comprehensive information with each error:
  - URL and HTTP method
  - IP address of the request
  - User agent string
  - User ID (if authenticated)
  - Full stack trace
  - Request parameters
- **Environment-Agnostic**: Works in all environments (local, staging, production)

### Implementation

The error logging is configured in `bootstrap/app.php` for optimal reliability:

```php
// In bootstrap/app.php
use App\Exceptions\Handler;

// The global exception handler now captures all exceptions
// and stores them in the SysErrorLog model
```

### Error Log Storage

Error logs are stored in the `sys_error_log` table with the following fields:
- `id`: Unique identifier
- `url`: URL where error occurred
- `method`: HTTP method
- `message`: Error message
- `exception`: Full exception details
- `line`: Line number where error occurred
- `file`: File where error occurred
- `trace`: Full stack trace
- `ip_address`: IP address of the request
- `user_agent`: User agent string
- `user_id`: ID of authenticated user (if applicable)
- `created_at`: Timestamp of error occurrence

### Error Log Interface

Access error logs through the "System Dashboard" or "Error Logs" menu in the admin panel:
- Filter logs by date range, error type, or user
- View detailed error information
- Export logs for analysis
- Clear old error logs to manage database size

## Configuration Management

The system includes a form-based interface for managing various configuration settings.

### App Configuration Features

#### Application Settings
- **Application Name**: Dynamically change the application name as it appears throughout the system
- **Environment Settings**: Configure between local, staging, and production environments
- **Debug Mode**: Toggle debugging functionality on or off
- **URL Configuration**: Set the application's base URL

#### Mail Configuration
- SMTP settings for sending email notifications
- Email credentials and encryption settings
- Test email functionality

#### Google OAuth Configuration
- Client ID and secret for Google authentication
- Redirect URL configuration

#### mysqldump Path Configuration
- Path to the mysqldump utility for database backups

### Cache Management

The system includes cache management functionality:
- **Clear Configuration Cache**: Clear cached configuration values
- **Clear View Cache**: Clear cached Blade templates
- **Clear Route Cache**: Clear cached route definitions
- **Clear Application Cache**: Clear all application cache

### Application Optimization

Performance optimization features:
- **Cache Configuration**: Cache configuration values for improved performance
- **Cache Routes**: Cache route definitions for faster routing
- **Cache Views**: Cache compiled Blade templates

### How to Use Configuration Management

1. **Access System Config Panel**: Navigate to the "System Config" menu in the admin panel
2. **Modify Application Settings**: Change application name, environment, debug mode, or URL
3. **Manage Mail Settings**: Configure SMTP settings for email notifications
4. **Configure Google OAuth**: Enter Google OAuth credentials
5. **Set mysqldump Path**: Configure the path to the mysqldump utility
6. **Manage Cache**: Use cache management features to clear or optimize
7. **Optimize Performance**: Use application optimization features for better performance

### Security Considerations

- Configuration changes are validated to prevent harmful settings
- Direct file system access to sensitive files is protected
- System configuration management is restricted to authorized administrators only
- Changes to .env file are properly validated before saving

## Monitoring and Backup Scheduling

The system can implement scheduled monitoring and backup tasks using Laravel's task scheduling:

### Example Scheduled Tasks

```php
// In app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Create daily database backup at 2:00 AM
    $schedule->command('backup:db')->daily()->at('02:00');
    
    // Create weekly file backup on Sundays at 3:00 AM
    $schedule->command('backup:files')->weeklyOn(0, '03:00');
    
    // Clean up old backups older than 30 days
    $schedule->command('backup:cleanup')->daily()->at('04:00');
    
    // Monitor disk space and send alerts if below threshold
    $schedule->command('monitor:disk-space')->everyFiveMinutes();
}
```

## Testing Monitoring and Backup Features

The system includes test functions accessible through the System Dashboard:
- **Test Email**: Send a test email to verify mail configuration
- **Test Notification**: Send a test notification to the current user
- **Test PDF Export**: Generate a sample PDF report for testing
- **Database Connection Test**: Verify database connectivity
- **File Backup Test**: Create a small test backup to verify functionality

These tests help ensure that all monitoring and backup features are functioning correctly.