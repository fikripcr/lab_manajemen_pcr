# Laravel Base Template - Development Guidelines

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
- **Custom Error Pages** for better UX
- **Soft Delete** for data integrity
- **Debugging Tools** with Laravel Debugbar

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
   - Encrypt IDs before sending to view:
     ```php
     $encryptedId = encryptId($model->id);
     ```
   - Prepare formatted data in controller, not in views

### Authentication & Authorization
- Use `auth` middleware for protected routes
- Use `can:` middleware for permission checks
- Use `$user->can('permission')` in controllers/views
- Permissions and roles managed with Spatie Laravel Permission package

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

**Example implementation in User model:**
```php
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->useFallbackUrl('/images/default-avatar.png')
            ->useFallbackPath(public_path('images/default-avatar.png'))
            ->useDisk('public');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 150, 150)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->fit(Fit::Crop, 400, 400)
            ->nonQueued();
    }
}
```

**Upload file in controller:**
```php
// Store avatar
if ($request->hasFile('avatar')) {
    $user->addMedia($request->file('avatar'))->toMediaCollection('avatar');
}

// Clear existing media
$user->clearMediaCollection('avatar');
```

**Get media in views:**
```php
// Get media with conversion
$avatarUrl = getVerifiedMediaUrl($user, 'avatar', 'thumb');
// Or with helper function
$avatarUrl = $user->getFirstMediaUrl('avatar', 'thumb');
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

### Validation
- Separate validation logic into FormRequest classes
- Use custom error messages when needed in FormRequest classes

### Export/Import
- Use `Maatwebsite\Excel` for data import/export functionality
- Create dedicated Export/Import classes extending appropriate base classes
- Include filtering capabilities in export functionality

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
- Create permissions and assign them to roles
- Use permissions in controllers and views

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

**Use in controllers:**
```php
// Check permission in controller
if ($request->user()->can('manage-users')) {
    // Allow action
}

// Or use middleware
Route::middleware(['can:manage-users'])->group(function () {
    // Protected routes
});
```

**Use in views:**
```blade
@if(auth()->user()->can('manage-users'))
    <button>Manage Users</button>
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

## 📚 Additional Features

### Helper Functions
- `encryptId($id)` and `decryptId($hash)` for ID encryption/decryption
- `getVerifiedMediaUrl($model, $collection, $conversion)` for safe media URLs
- `formatTanggalIndo($tanggal)` for Indonesian date formatting (shows time if available, date only if no time)

### TinyMCE Editor
Rich text editor for content management fields.

### SweetAlert
Enhanced user experience with beautiful pop-up confirmations.

### Custom Error Pages
Professional error pages published to `resources/views/errors/`.

### Base System Tables
- `sys_` prefixed tables are reserved for system functionality
- Do not modify these tables without proper discussion

### Environment Configuration
Ensure `APP_URL` is correctly set for media links to work properly.

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
