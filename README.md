# Laravel Base Template - Development Guidelines

[Table of Contents](#table-of-contents)

## Table of Contents
- [Installation](#installation)
- [Features](#features)
- [Project Structure](#project-structure)
- [Development Patterns](#development-patterns)
- [Authentication & Authorization](#authentication--authorization)
- [Database Operations](#database-operations)
- [Eloquent Query Optimization](#eloquent-query-optimization)
- [Media Handling with Spatie Laravel Media Library](#media-handling-with-spatie-laravel-media-library)
- [Asset Management (Local Libraries and Fonts)](#asset-management-local-libraries-and-fonts)
- [PDF Generation with Laravel DomPDF](#pdf-generation-with-laravel-dompdf)
- [Activity Logging with Spatie Laravel Activity Log](#activity-logging-with-spatie-laravel-activity-log)
- [Validation](#validation)
- [Export/Import](#exportimport)
- [Notifications](#notifications)
- [Laravel Impersonate](#laravel-impersonate)
- [Helper Functions](#helper-functions)
- [TinyMCE Editor](#tinymce-editor)
- [SweetAlert](#sweetalert)
- [Custom Error Pages](#custom-error-pages)
- [Helper Functions for Data Preparation](#helper-functions-for-data-preparation)
- [Base System Tables](#base-system-tables)
- [Security](#security)
- [System Configuration Management](#system-configuration-management)

This repository serves as a comprehensive Laravel base template that implements essential features for efficient web application development. It provides a solid foundation with authentication, authorization, CRUD operations, and various utility features that can be reused across multiple projects. Bismillah

## 📋 Features Overview

This template includes preconfigured implementations for:

- **Authentication System** with Laravel Breeze & Google OAuth
- **Role-Based Access Control** using spatie/laravel-permission
- **User Management** with profiles and impersonation
- **CRUD Operations** with Resource Controllers
- **Form Validation** with Custom Request Classes
- **Frontend Architecture** with Blade Components & Layouts
- **Data Export/Import** with Maatwebsite/Excel
- **Dynamic Tables** with Yajra DataTables
- **ID Encryption** with vinkla/hashids
- **Media Management** with Spatie Media Library
- **Activity Logging** with spatie/activity-log
- **Notifications** with Database Channel
- **Performance Optimization** with local libraries and caching
- **Custom Error Pages** for better UX
- **Soft Delete** for data integrity
- **Debugging Tools** with Laravel Debugbar
- **Security** with environment files and encryption

## 📁 Project Structure

### Controller Organization
- `app/Http/Controllers/Admin/` - Administrative functionality
- `app/Http/Controllers/Auth/` - Authentication functionality
- `app/Http/Controllers/Guest/` - Public functionality

### Route Structure
- `routes/admin.php` - Administrative routes
- `routes/auth.php` - Authentication routes
- `routes/guest.php` - Public routes
- `routes/web.php` - Main route configuration

### Frontend Assets
- `public/assets-admin/` - Administrative UI assets
- `public/assets-guest/` - Public UI assets

### View Organization
- `resources/views/components/` - Reusable Blade components
- `resources/views/layouts/` - Layout files (admin/auth/guest)
- `resources/views/pages/` - Page-specific views (admin/auth/guest)

### Request Validation
- `app/Http/Requests/` - Custom request validation classes

## 🛠️ Development Patterns

### Creating New Modules
Follow these steps to create new functionality:

1. **Generate Model with Migration and Resource Controller:**
   ```bash
   php artisan make:model Product -mcr
   ```

2. **Add Resource Route:**
   ```php
   // In routes/admin.php
   Route::resource('products', ProductController::class);
   ```

3. **Create Custom Request Validation:**
   ```bash
   php artisan make:request ProductRequest
   ```

4. **Use in Controller:**
   ```php
   public function store(ProductRequest $request) {
       // Validation handled automatically by ProductRequest
       // Implement your logic here
   }
   ```

5. **Implement DataTables (for index view):**
   ```php
   public function paginate(Request $request) {
       $products = Product::whereNull('deleted_at');
       // Return DataTables response
   }
   ```

6. **Create Views in appropriate directories:**
   - `resources/views/pages/admin/products/index.blade.php`
   - `resources/views/pages/admin/products/create.blade.php`
   - `resources/views/pages/admin/products/edit.blade.php`
   - `resources/views/pages/admin/products/show.blade.php`

7. **Prepare Data in Controller:**
   - Format dates using `formatTanggalIndo()` helper function:
     ```php
     $formattedDate = formatTanggalIndo($model->created_at);
     ```
   - Use accessor in models for encrypted IDs (IMPORTANT: Handle encryption in model using accessor):
     ```php
     // In model - create accessor for encrypted ID based on the primary key
     // For example, for User model with 'id' as primary key:
     public function getEncryptedIdAttribute()
     {
         return encryptId($this->id);
     }

     // For Lab model with 'lab_id' as primary key:
     public function getEncryptedLabIdAttribute()
     {
         return encryptId($this->lab_id);
     }

     // For foreign keys that reference other entities:
     public function getEncryptedLabIdAttribute()
     {
         return encryptId($this->lab_id);
     }

     public function getEncryptedUserIdAttribute()
     {
         return encryptId($this->user_id);
     }

     // In view - use encrypted ID via accessor
     <form action="{{ route('route.name', $user->encrypted_id) }}">  // For user
     <form action="{{ route('route.name', $lab->encrypted_lab_id) }}">  // For lab
     <form action="{{ route('route.name', $model->encrypted_lab_id) }}">  // For model with lab_id
     ```
   - Prepare formatted data in controller, not in views

### Authentication & Authorization
- Use `auth` middleware for protected routes
- Use `can:` middleware for permission checks
- Use `check.expired` middleware to verify account expiration status
- Use `$user->can('permission')` in controllers/views
- Permissions and roles managed with Spatie Laravel Permission package
- Cache permissions for performance using `php artisan permission:cache-reset`
- Use `auth()->user()->getRoleNames()->first()` to get roles from cache efficiently
- For deletion, use soft deletes instead of permanent deletion (use `forceDelete()` for permanent deletion)

### Account Expiration Middleware
- **CheckAccountExpiration Middleware** - Automatically checks if user accounts have expired on every authenticated request
- When applied together with `auth` middleware as `['auth', 'check.expired']`, it verifies that authenticated users haven't exceeded their account validity period
- Automatically logs out users with expired accounts
- Shows appropriate error messages when account is expired
- Supports both web and API requests (returns JSON error for API requests)
- Implemented as `check.expired` middleware alias in bootstrap/app.php

**Example implementation in User model (found in `app/Models/User.php`):**
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}
```

**Create permissions and assign to roles in seeders (found in `database/seeders/PermissionSeeder.php` or `database/seeders/RolePermissionSeeder.php`):**
```php
// Create permissions
Permission::create(['name' => 'manage-users']);
Permission::create(['name' => 'view-users']);

// Create role and assign permissions
$role = Role::create(['name' => 'admin']);
$role->givePermissionTo(['manage-users', 'view-users']);
```

**Cache permissions for performance:**
```bash
php artisan permission:cache-reset
```

**Example implementation:**
```php
// In controllers - checking permissions
if ($request->user()->can('manage-users')) {
    // User can manage users
}

// In controllers - getting roles efficiently (cached)
$roleName = auth()->user()->getRoleNames()->first();

// In views - checking permissions
@if(auth()->user()->can('manage-users'))
    <button>Manage Users</button>
@endif

// In routes - using permission middleware
Route::middleware(['can:manage-users'])->group(function () {
    // Protected routes
});

// For hard deletion when needed
$model->forceDelete(); // Permanent deletion bypassing soft delete
```

### Database Operations
- Always use database transactions for important operations (`DB::beginTransaction()`/`commit()`/`rollback()`)
- Use soft deletes instead of permanent deletion
- Encrypt sensitive IDs in URLs using `encryptId()`/`decryptId()` helpers

### Helper Functions for Data Preparation
- Use `formatTanggalIndo()` for Indonesian date formatting (handles both date and datetime)
- Use `encryptId()`/`decryptId()` for ID security
- Use `getVerifiedMediaUrl()` for safe media access
- Prepare all formatted data in controller before sending to view

### Media Handling with Spatie Laravel Media Library
- Implement `HasMedia` interface in models requiring file uploads
- Use `getFirstMediaUrl()` or `getVerifiedMediaUrl()` helper for safe media access
- Define collections and conversions in model's `registerMediaCollections()` and `registerMediaConversions()`

**Example implementation in Product model (using product_images and product_attachments collections):**
```php
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images')
            ->singleFile()
            ->useFallbackUrl('/assets-admin/img/default-product-image.jpg')
            ->useFallbackPath(public_path('/assets-admin/img/default-product-image.jpg'));

        $this->addMediaCollection('product_attachments')
            ->useFallbackUrl('/assets-admin/img/default-attachment.jpg')
            ->useFallbackPath(public_path('/assets-admin/img/default-attachment.jpg'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(\Spatie\Image\Manipulations::FIT_CROP, 150, 150)
            ->nonQueued();

        $this->addMediaConversion('preview')
            ->fit(\Spatie\Image\Manipulations::FIT_CROP, 400, 400)
            ->nonQueued();
    }
}
```

**Upload file in controller:**
```php
// Store product images
if ($request->hasFile('product_images')) {
    foreach ($request->file('product_images') as $image) {
        if ($image->isValid()) {
            $product->addMedia($image)
               ->withCustomProperties(['uploaded_by' => auth()->id()])
               ->toMediaCollection('product_images');
        }
    }
}

// Store product attachments
if ($request->hasFile('product_attachments')) {
    foreach ($request->file('product_attachments') as $attachment) {
        if ($attachment->isValid()) {
            $product->addMedia($attachment)
               ->withCustomProperties(['uploaded_by' => auth()->id()])
               ->toMediaCollection('product_attachments');
        }
    }
}
```

**Get media in views:**
```php
// Get media with conversion
$productImageUrl = $product->getFirstMediaUrl('product_images', 'thumb');
$productAttachments = $product->getMedia('product_attachments');

// Check if product has images
@if($product->hasMedia('product_images'))
    @foreach($product->getMedia('product_images') as $media)
        <img src="{{ $media->getUrl() }}" alt="Product Image">
    @endforeach
@endif
```

### Eloquent Query Optimization
- Use `with()` method to eager load related models and prevent N+1 query problems
- Load only the relationships that are actually needed in the view
- Use `select()` to limit the fields retrieved from the database

**Example implementation:**
```php
// Instead of this (causes N+1 queries):
$users = User::all();
foreach($users as $user) {
    echo $user->profile->name; // Triggers additional query for each user
}

// Use this (eager loading):
$users = User::with('profile')->get();
foreach($users as $user) {
    echo $user->profile->name; // No additional query needed
}

// Load multiple relationships:
$users = User::with(['profile', 'posts', 'comments'])->get();

// Load specific fields only:
$users = User::with(['profile:id,user_id,name'])->select('id', 'name', 'email')->get();
```

### Asset Management (Local Libraries and Fonts)
- All external libraries (CDNs) have been downloaded and stored locally
- Located in `public/assets-admin/` and `public/assets-guest/` directories
- CSS, JS, fonts, and other assets are served locally for better performance and offline support
- Third-party libraries are stored in appropriate asset directories instead of using CDNs

**Asset locations:**
- Admin assets: `public/assets-admin/`
- Guest assets: `public/assets-guest/`
- TinyMCE Editor: `public/assets-admin/js/tinymce/`
- Images: `public/images/` and respective asset folders

### PDF Generation with Laravel DomPDF
- Use `barryvdh/laravel-dompdf` for generating PDF reports
- Include user summary, detailed user reports, and role-specific reports
- Available in user management section with dropdown option

**Available report types:**
- Summary Report: Overall user statistics
- Detailed Report: Complete user details
- Role-specific Report: Users assigned to specific roles

### Activity Logging with Spatie Laravel Activity Log
- Use `spatie/laravel-activitylog` traits in models for automatic logging
- Customize log options in model's `getActivitylogOptions()` method

**Example implementation in User model (found in `app/Models/User.php`):**
```php
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use LogsActivity;

    protected static $logName = 'user';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
```

**Manual logging in controllers:**
```php
// Log user activities in controllers
activity()
    ->performedOn($user)
    ->causedBy(auth()->user())
    ->log('Membuat pengguna ' . $user->name);

// With properties
activity()
    ->performedOn($user)
    ->causedBy(auth()->user())
    ->withProperties([
        'old' => $oldAttributes,
        'attributes' => $user->getAttributes(),
    ])
    ->log('Memperbarui pengguna ' . $user->name);
```

### Validation
- Separate validation logic into FormRequest classes
- Use custom error messages when needed in FormRequest classes

### Export/Import
- Use `Maatwebsite\Excel` for data import/export functionality
- Create dedicated Export/Import classes extending appropriate base classes
- Include filtering capabilities in export functionality
- Import/Export files are located in `storage/app/exports` and `storage/app/imports` directories

### Activity Logging with Spatie Laravel Activity Log
- Use `spatie/laravel-activitylog` traits in models for automatic logging
- Customize log options in model's `getActivitylogOptions()` method

**Example implementation in User model (found in `app/Models/User.php`):**
```php
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use LogsActivity;

    protected static $logName = 'user';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
```

**Manual logging in controllers:**
```php
// Log user activities in controllers
activity()
    ->performedOn($user)
    ->causedBy(auth()->user())
    ->log('Membuat pengguna ' . $user->name);

// With properties
activity()
    ->performedOn($user)
    ->causedBy(auth()->user())
    ->withProperties([
        'old' => $oldAttributes,
        'attributes' => $user->getAttributes(),
    ])
    ->log('Memperbarui pengguna ' . $user->name);
```

### Roles & Permissions with Spatie Laravel Permission
- Use `spatie/laravel-permission` package for role and permission management
- Manage permissions and assign them to roles in seeders
- Use permissions in controllers and views
- Use `php artisan permission:cache-reset` to optimize performance by caching permissions

**Example implementation in User model (found in `app/Models/User.php`):**
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}
```

**Create permissions and assign to roles in seeders (found in `database/seeders/PermissionSeeder.php` or `database/seeders/RolePermissionSeeder.php`):**
```php
// Create permissions
Permission::create(['name' => 'manage-users']);
Permission::create(['name' => 'view-users']);

// Create role and assign permissions
$role = Role::create(['name' => 'admin']);
$role->givePermissionTo(['manage-users', 'view-users']);
```

**Cache permissions for performance:**
```bash
php artisan permission:cache-reset
```

**Use in controllers (cache-efficient way):**
```php
// Check permissions using cache
if ($request->user()->can('manage-users')) {
    // Allow action
}

// Or use middleware
Route::middleware(['can:manage-users'])->group(function () {
    // Protected routes
});

// Get user roles efficiently (from cache)
$roleName = auth()->user()->getRoleNames()->first();
```

**Use in views:**
```blade
@if(auth()->user()->can('manage-users'))
    <button>Manage Users</button>
@endif
```
### Laravel Impersonate
- Use `lab404/laravel-impersonate` package for admin to login as other users
- Provides secure way to test functionality from other user perspectives
- Includes switch back functionality to return to original account
- Switch back button is available in user profile dropdown (top right corner)

**Installation:**
```bash
composer require lab404/laravel-impersonate
php artisan vendor:publish --provider="Lab404\Impersonate\ImpersonateServiceProvider"
```

**Configuration:**
```php
// In bootstrap/app.php
'middleware' => function (Middleware $middleware) {
    $middleware->alias([
        // ... other aliases
        'impersonate' => \Lab404\Impersonate\Middleware\ImpersonateMiddleware::class,
    ]);
};

// In routes/admin.php
Route::impersonate();
```

**Usage in controllers:**
```php
// Take user impersonation
app('impersonate')->take($current_user, $target_user);

// Leave impersonation
app('impersonate')->leave();

// Check if impersonating
if (app('impersonate')->isImpersonating()) {
    // User is impersonating another user
}
```

**Available routes:**
- `GET /impersonate/take/{id}` - Impersonate user
- `GET /impersonate/leave` - Leave impersonation

**In views - checking if impersonating:**
```blade
@if(app('impersonate')->isImpersonating())
    <li>
        <a class="dropdown-item" href="{{ route('admin.switch-back') }}">
            <i class="bx bx-log-out me-2"></i>
            <span class="align-middle">Switch Back to Original Account</span>
        </a>
    </li>
@endif
```

### Notifications
- Use database channel for notifications
- Create notification classes extending `Illuminate\Notifications\Notification`
- Use `notify()` method to send notifications

## 🔐 Security Features

- ID encryption prevents enumeration attacks
- FormRequest validation prevents common vulnerabilities
- Authorization checks ensure proper access control
- Google OAuth integration for secure authentication
- Soft deletes maintain data integrity
- **Account Expiration Middleware** - Automatically checks if user accounts have expired on every authenticated request with the `check.expired` middleware

## 📚 Additional Features

### TinyMCE Editor
Rich text editor for content management fields.

### SweetAlert
Enhanced user experience with beautiful pop-up confirmations.

### Custom Error Pages
Professional error pages published to `resources/views/errors/`.

### Base System Tables
- `sys_` prefixed tables are reserved for system functionality
- Do not modify these tables without proper discussion

### Security
- Never commit `.env` files to Git - Always add `.env` to `.gitignore` to protect sensitive information
- Set `APP_DEBUG=false` in production to prevent exposing sensitive configuration details
- Always regenerate application key using `php artisan key:generate` during deployment

**Environment security best practices:**
```bash
# Ensure .env is in .gitignore
echo ".env" >> .gitignore

# Set APP_DEBUG to false in production
APP_DEBUG=false

# Always regenerate key during deployment
php artisan key:generate
```

### Environment Configuration
Ensure `APP_URL` is correctly set for media links to work properly.

## System Configuration Management

The application includes comprehensive system configuration management accessible from the "System Config" menu in the admin panel. This combines both application configuration and backup management in a single location.

## Global Search Feature

The application includes a powerful global search functionality accessible from the search icon in the header. This feature allows users to quickly find any content across the application. Key features include:

### Search Capabilities
- **Multi-model Search**: Search across multiple data types simultaneously
- **Real-time Results**: See results as you type without page refresh
- **Smart Filtering**: Automatic filtering and relevance ranking
- **Modal Interface**: Dedicated search interface with improved UX

### How to Use
1. **Click the Search Icon**: Located in the header navigation
2. **Type Your Query**: Enter text to search across all relevant content
3. **View Results**: Results appear in real-time in a dedicated modal
4. **Navigate**: Click on any result to go directly to that content's page

### Advanced Search
- **Auto-Focus**: Search input automatically gains focus when modal opens
- **Quick Navigation**: Direct links to search results for rapid access
- **Grouped Results**: Results are organized by content type for easier scanning
- **Responsive Interface**: Works seamlessly on all device sizes

### Customizable Search
Developers can easily extend the search functionality to include additional models by modifying the GlobalSearchController:
- Add new models to the search logic
- Customize which fields to search in each model
- Adjust result presentation formatting

### App Configuration Features

- **Application Name**: Dynamically change the application name as it appears throughout the system
- **Environment Settings**: Configure between local, staging, and production environments
- **Debug Mode**: Toggle debugging functionality on or off
- **URL Configuration**: Set the application's base URL
- **Cache Management**: Clear application cache (config, view, route)
- **Application Optimization**: Cache configuration, routes, and views for performance

### Backup Management Features

- **Database Backup**: Creates SQL dump of the entire database
- **Web File Backup**: Creates ZIP archive of application files (excluding unnecessary directories)
- **Custom Backup Implementation**: Uses custom backup logic instead of third-party packages
- **Backup Management**: View, download, and delete existing backups
- **Secure Storage**: Backups stored in `storage/app/private/backup/` directory

### How to Use

1. **Access System Config Panel**: Navigate to the "System Config" menu in the admin panel
2. **Configure Application**: Modify application settings as needed
3. **Manage Backups**: Create database or web file backups as needed
4. **Optimize Performance**: Use cache management and optimization features
5. **View Existing Backups**: See list of existing backups with size and creation date
6. **Download/Manage Backups**: Download or remove old backups to manage storage space

### Configuration Process

**App Configuration**:
- Settings are saved directly to the `.env` file
- Changes take effect immediately after configuration cache is cleared
- Includes validation for all fields to ensure valid configurations

**Backup Process**:
- **Database Backup**: Uses `mysqldump` to create SQL backup of the database; stored with timestamp in filename (e.g., `database_backup_2025-11-18_12-00-00.sql`)
- **Web File Backup**: Creates ZIP archive of application code and assets; includes important directories while excluding vendor/, node_modules/, .git/, and storage/ to reduce size; stored with timestamp in filename (e.g., `web_backup_2025-11-18_12-00-00.zip`)

### Security Considerations

- Configuration changes are validated to prevent harmful settings
- Backup files are stored in private directory (`storage/app/private/`)
- Proper path validation prevents directory traversal attacks
- System configuration management is restricted to authorized administrators only
- Direct file system access to sensitive files is protected

---

## 🏗️ Template Usage

When starting a new project using this template:

1. Clone the repository
2. Install dependencies (`composer install`, `npm install`)
3. Configure environment settings
4. Run migrations (`php artisan migrate --seed`)
5. Build assets (`npm run build`)

The template is designed to handle common requirements like authentication, user management, roles/permissions, and CRUD operations out-of-the-box. Add your specific business logic by following the established patterns.

---

This template provides a strong foundation to build Laravel applications efficiently while following best practices for maintainability and scalability.
