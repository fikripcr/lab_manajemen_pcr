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

### Authentication Event Logging
- **Login Logging** - Records all successful user login events with user identity
- **Logout Logging** - Records all user logout events
- **Impersonation Logging** - Records when administrators impersonate other users
- **Switch Back Logging** - Records when impersonation sessions end and users return to their original accounts
- All authentication events are stored in the activity log system for audit trails

### Application Configuration Logging
- **Configuration Update Logging** - Records when application configuration is updated (app name, environment, debug mode, URL)
- **Cache Clear Logging** - Records when application cache is cleared (config, cache, views, and routes)
- **Optimization Logging** - Records when application optimization occurs (caching config, routes, and views)

### Notification Event Logging
- **Notification Sending Logging** - Records when test notifications are sent to users
- **Notification Marking Logging** - Records when users mark notifications as read (individually, all, or in bulk)

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
- Use `logError()` to log errors directly to the ErrorLog model
- Use `normalizePath()` to clean up file paths and prevent directory traversal attacks
- Use `formatBytes()` to format file sizes to human-readable format
- Use `getVerifiedMediaUrl()` for safe media access
- Use Spatie Server Monitor for system monitoring (disk space, database size, project size breakdown)
- Prepare all formatted data in controller before sending to view

### System Monitoring with Spatie Server Monitor
- Automatically monitors disk space, database size, and project size breakdown
- Provides detailed breakdown of project components (apps, storage, logs, uploads)
- Real-time monitoring available on the system dashboard
- Configurable monitoring intervals and alerts
- Integration with the ErrorLog system for monitoring failures

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
- **Enhanced Notification Management**: Implemented comprehensive notification system with both database and email delivery options
- **Test Notification Functionality**: Added test notification features with loading indicators and success feedback
- **Unified Notification Interface**: Centralized notification management available in System Configuration
- **User-Specific Notifications**: Ability to send notifications to individual users or user groups
- **Loading Indicators**: Implemented SweetAlert loading indicators during notification operations

## Notification System Features

The application includes a comprehensive notification management system with the following capabilities:

### Core Features
- **Database Notifications**: Notifications stored in `sys_notifications` table with title, body, and timestamp
- **Email Notifications**: Support for sending notifications via email
- **User Dashboard**: Notifications displayed in header dropdown and dedicated notifications page
- **Read/Unread Management**: Mark notifications as read individually, in bulk, or all at once

### Testing Capabilities
- **Test Notifications**: Send test notifications to current user from notifications page
- **User-Specific Testing**: Send notifications to specific users from user management page
- **Email Testing**: Test email notifications functionality
- **Loading Indicators**: Visual feedback during notification sending operations

### Management Interface
- **Dedicated Pages**: Separate interface for managing notifications in System Configuration
- **Bulk Operations**: Mark multiple notifications as read simultaneously
- **Status Tracking**: Clear indication of read/unread status
- **Timestamp Information**: Shows when notifications were created

### How to Use
1. **Send Test Notification**: Click "Test Notification" button on notifications page
2. **Send to Specific User**: Use dropdown menu on user management page
3. **View Notifications**: Check header dropdown or notifications page
4. **Manage Notifications**: Access full management interface in System Configuration

## 🔐 Security Features

- ID encryption prevents enumeration attacks
- FormRequest validation prevents common vulnerabilities
- Authorization checks ensure proper access control
- Google OAuth integration for secure authentication
- Soft deletes maintain data integrity
- **Account Expiration Middleware** - Automatically checks if user accounts have expired on every authenticated request with the `check.expired` middleware
- **Activity Logging** - Comprehensive logging for user authentication events (login, logout, impersonation)

## 📚 Additional Features

### TinyMCE Editor
Rich text editor for content management fields.

### SweetAlert
Enhanced user experience with beautiful pop-up confirmations.
### Enhanced User Interface with Loading Indicators
Added SweetAlert loading indicators to improve user experience during operations that may take time to complete:
- **Notification Operations**: Loading indicators during notification and email sending operations
- **Database Operations**: Visual feedback during backup and other database operations
- **User Experience**: Clear indication that operations are processing with "Processing..." message
- **Loading States**: Prevents user interaction during operations to avoid duplicate actions
- **Success Feedback**: Automatic transition from loading to success/error messages

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

## DataTable State Persistence

The application includes a robust state persistence system for DataTable components that preserves user preferences between page loads using DataTables' built-in state saving functionality. This feature enhances user experience by maintaining their search terms, page length, and pagination state.

### Features
- **Search Persistence**: Search terms are automatically saved and restored using DataTable's built-in state saving
- **Filter Persistence**: Custom applied filters are preserved between page loads using custom localStorage
- **Page Length Persistence**: Selected page length (10, 25, 50, 100, All) is automatically maintained by DataTable
- **Pagination Persistence**: Current page number and state are automatically preserved by DataTable
- **Unique Storage Keys**: DataTable automatically creates unique keys based on table ID and URL path
- **Server-side Processing Compatible**: Works properly with server-side DataTables

### Implementation Details
- **DataTable Component**: Uses native DataTables `stateSave: true` for standard features (search, pagination, page length, column ordering, etc.)
- **Custom Filters**: Custom filter controls are handled separately with custom localStorage functionality
- **UI Synchronization**: All UI components are updated to match the restored DataTable state

### How It Works
1. **State Saving**: DataTable automatically saves state when users change search, pagination, or page length
2. **Custom Filter Saving**: Custom filters are saved to localStorage when changed
3. **State Loading**: On page load, saved state is retrieved and applied to the DataTable and UI components
4. **Synchronization**: The UI components are updated to match the restored DataTable state

### Components Affected
- All pages using the `x-datatable.datatable` component
- All pages using the `x-datatable.search-filter` component
- All pages using the `x-datatable.page-length` component

This feature significantly improves user experience by maintaining context when navigating between pages or refreshing content.

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

## Page-specific CSS and JavaScript with @stack and @push

Laravel provides a powerful system for including CSS and JavaScript files that are specific to individual pages using `@stack` and `@push` directives. This enables efficient loading of only the resources needed for each page, improving performance and preventing conflicts.

### Implementation in Layout

The main layout file (`resources/views/layouts/admin/app.blade.php`) includes two stack locations for additional assets:

```blade
<!-- In the <head> section for CSS -->
@stack('css')

<!-- Before closing </body> tag for JavaScript -->
@stack('scripts')
```

### Adding Page-specific CSS

To include CSS that is only needed on a specific page:

1. **In your Blade view**, use the `@push` directive:

```blade
@push('css')
    <link rel="stylesheet" href="{{ asset('assets-admin/css/specific-page.css') }}">
    <style>
        /* Inline CSS for this page only */
        .specific-page-element {
            background-color: #f0f0f0;
        }
    </style>
@endpush
```

### Adding Page-specific JavaScript

To include JavaScript that is only needed on a specific page:

1. **In your Blade view**, use the `@push` directive:

```blade
@push('scripts')
    <script src="{{ asset('assets-admin/js/specific-page.js') }}"></script>
    <script>
        // JavaScript code specific to this page
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize page-specific functionality
            initializeSpecificPageFeature();
        });
    </script>
@endpush
```

### Examples in the Project

Several existing pages already use this system:

**Dashboard (resources/views/pages/admin/dashboard.blade.php):**
```blade
@push('css')
    <!-- Dashboard-specific CSS -->
@endpush

@push('scripts')
    <!-- Dashboard-specific JavaScript -->
@endpush
```

**Data Tables (resources/views/components/datatable/datatable.blade.php):**
```blade
@push('scripts')
    <!-- DataTable-specific JavaScript -->
@endpush
```

### Best Practices

- **Use `@push` only in main views**, not in components to avoid confusion
- **Combine related CSS/JS** into single @push blocks when possible
- **Use descriptive names** for external files to indicate their purpose
- **Always wrap inline JavaScript** in DOM ready events when needed
- **Minimize global CSS/JS** by using page-specific assets when possible

### Common Use Cases

- **Rich Text Editors**: TinyMCE or other editor-specific CSS and JS
- **Charts**: Chart-specific libraries and initialization code
- **Date Pickers**: Date picker CSS and JavaScript
- **File Uploads**: File upload component CSS and JS
- **Custom Components**: Page-specific interactive elements
### Additional Features and Updates
- **Enhanced Activity Logging**: Extended activity logs with IP address, browser information, and additional context
- **Centralized Configuration**: Added form-based management for Google OAuth, Mail settings, and mysqldump path in App Configuration
- **Unified Notification System**: Consolidated notification sending and marking functions for both email and database notifications
- **Improved Avatar Handling**: Added image conversions (small, medium, large) and automatic cleanup of original files
- **Enhanced User Interface**: Improved DataTables with consistent pagination, and better UI components
- **Email Testing**: Added email testing functionality accessible from user dropdown menu
- **Validation Messages**: Added Indonesian translation helper for validation messages
- **DataTable State Persistence**: Leveraged DataTable's built-in state saving functionality to preserve search terms, page length, and pagination state, with additional custom localStorage for filter controls
- **System Error Logging**: Implemented comprehensive error logging system that captures all application and database errors, storing them in a dedicated table with full stack traces and context information for system administrators
