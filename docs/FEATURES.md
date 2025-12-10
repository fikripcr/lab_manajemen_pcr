# Features Reference

Quick reference untuk fitur-fitur yang tersedia.

## Authentication & Authorization

### Laravel Breeze
- Login, Register, Password Reset
- Email verification
- **Config:** `config/auth.php`

### Google OAuth
- Social login via Google
- **Package:** `laravel/socialite`
- **Config:** `.env` - `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`

### Spatie Permission
- Role-based access control
- **Package:** `spatie/laravel-permission`
- **Usage:** `$user->assignRole('admin')`, `@can('edit users')`
- **Seeder:** `database/seeders/PermissionSeeder.php`

## Data Management

### DataTables (Yajra)
- Server-side pagination
- **Package:** `yajra/laravel-datatables`
- **Example:** `app/Http/Controllers/Sys/UserController@paginate`

### Excel Import/Export
- Import/export data to Excel
- **Package:** `maatwebsite/excel`
- **Example:** `app/Exports/UsersExport.php`

### Media Library (Spatie)
- File uploads with thumbnails
- **Package:** `spatie/laravel-medialibrary`
- **Usage:** `$user->addMedia($file)->toMediaCollection('avatar')`
- **Config:** `config/media-library.php`

## Monitoring & Logging

### Activity Log (Spatie)
- Track user actions
- **Package:** `spatie/laravel-activitylog`
- **Usage:** `logActivity('user_management', 'Created user')`
- **View:** `sys/activity-logs`

### Error Logging
- Custom error tracking
- **Model:** `App\Models\Sys\ErrorLog`
- **Config:** `bootstrap/app.php` (exception handler)
- **View:** `sys/error-logs`

### System Monitoring
- Server health checks
- Disk usage monitoring
- **Controller:** `app/Http/Controllers/Sys/MonitoringController.php`
- **View:** `sys/monitoring`

## Backup & Restore

### Database Backup
- Manual & scheduled backups
- **Service:** `app/Services/Sys/BackupService.php`
- **Command:** `php artisan backup:database`
- **View:** `sys/backups`

### File Backup
- Backup uploaded files
- **Storage:** `storage/app/backups/`

## Notifications

### Database Notifications
- In-app notifications
- **API:** `/api/notifications/count`, `/api/notifications/list`
- **Component:** `resources/js/components/Notification.js`

### Test Notifications
- Send test notifications
- **Route:** `sys/test/features`

## User Management

### User Impersonation
- Login as another user
- **Package:** `lab404/laravel-impersonate`
- **Usage:** `$admin->impersonate($user)`

### Account Expiration
- Automatic account expiration
- **Middleware:** `app/Http/Middleware/CheckAccountExpiration.php`
- **Field:** `users.expired_at`

### Soft Delete
- Recoverable user deletion
- **Trait:** `SoftDeletes`
- **Usage:** `$user->delete()`, `$user->restore()`

## Utilities

### ID Encryption
- Hide real IDs in URLs
- **Package:** `vinkla/hashids`
- **Helper:** `encryptId($id)`, `decryptId($encrypted)`
- **Config:** `config/hashids.php`

### Global Search
- Search across multiple models
- **Route:** `/global-search?q=query`
- **Component:** `resources/js/components/GlobalSearch.js`

## Frontend Libraries

### Core
- **jQuery** - DOM manipulation
- **Bootstrap 5** - UI framework
- **Axios** - HTTP client
- **SweetAlert2** - Alerts

### Form Enhancement
- **Flatpickr** - Date picker
- **Choices.js** - Enhanced selects
- **FilePond** - File uploads
- **TinyMCE** - Rich text editor

### Data Visualization
- **DataTables** - Interactive tables
- **Chart.js** - Charts (if needed)

## Configuration Files

```
config/
├── auth.php              # Authentication
├── permission.php        # Spatie permissions
├── media-library.php     # File uploads
├── activitylog.php       # Activity logging
├── hashids.php           # ID encryption
└── services.php          # OAuth (Google)
```

## Key Directories

```
app/
├── Services/Sys/         # System services
├── Models/Sys/           # System models
├── Helpers/              # Helper functions
└── Http/
    ├── Middleware/       # Custom middleware
    └── Requests/Sys/     # Form validation

database/
├── migrations/           # Database schema
└── seeders/             # Sample data

resources/
├── js/components/        # JS components
└── views/
    ├── pages/sys/       # System pages
    └── components/      # Blade components

storage/
├── app/
│   ├── backups/         # Backup files
│   └── media/           # Uploaded files
└── logs/                # Application logs
```

## API Endpoints

```
GET  /api/notifications/count        # Notification count
GET  /api/notifications/list         # Notification list
GET  /api/permissions/search?q=      # Search permissions
GET  /api/activity-logs              # Activity logs
```

## Artisan Commands

```bash
# Backup
php artisan backup:database
php artisan backup:files

# Permissions
php artisan permission:cache-reset
php artisan permission:create-permission "permission name"

# Custom
php artisan app:check-expired-accounts
```

## Environment Variables

```env
# Google OAuth
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=

# Media Library
MEDIA_DISK=public

# Hashids
HASHIDS_SALT=
HASHIDS_LENGTH=8
```

## Testing Features

**Test Page:** `sys/test/features`
- Flatpickr demo
- FilePond demo
- Choices.js demo
- TinyMCE demo
- SweetAlert demo
- Notification test

## Need More Details?

Check `docs/archive/` for comprehensive documentation on:
- `advanced-features.md` - Detailed feature explanations
- `media-management.md` - File upload details
- `monitoring-backup.md` - Monitoring & backup details
- `authentication.md` - Auth implementation details
