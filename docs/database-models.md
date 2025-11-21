# Database & Models

## Overview

This section covers the database structure, Eloquent models, relationships, migrations, and seeding strategies used in the system. The system follows Laravel conventions while implementing specific requirements for the laboratory management system.

## Database Schema

### System Tables

The system includes several categories of tables:

#### Base System Tables
- Prefixed with `sys_` (reserved for system functionality)
- Should not be modified without proper discussion
- Include error logs, notifications, activity logs, and monitoring data

#### Core Application Tables
- `users` - User accounts and authentication
- `roles` - User roles (via spatie/laravel-permission)
- `permissions` - System permissions (via spatie/laravel-permission)
- `model_has_permissions`, `model_has_roles`, `role_has_permissions` - Permission relationships

#### Application-Specific Tables
- Laboratory-related tables for the PCR lab system
- Any custom modules added to the system

### System Table Definitions

#### sys_error_log
Stores comprehensive error information across the application:

```sql
CREATE TABLE `sys_error_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `line` int NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trace` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_error_log_user_id_foreign` (`user_id`),
  CONSTRAINT `sys_error_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
```

#### sys_notifications
Manages system notifications:

```sql
CREATE TABLE `sys_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sys_notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `sys_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
```

## Eloquent Models

### Base Model Structure

All models extend Laravel's base Model class and implement common patterns:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use HasFactory, SoftDeletes;
    
    // Common configurations for all models
    protected $guarded = ['id'];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
```

### User Model

The User model extends Laravel's Authenticatable class and implements additional functionality:

```php
<?php

namespace App\Models;

// ... imports

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use HasRoles; // From spatie/laravel-permission
    use LogsActivity; // From spatie/laravel-activitylog
    use InteractsWithMedia; // From spatie/laravel-media-library

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'status', 
        'expires_at',
        'google_id',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Activity logging options
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

    // Media collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->useFallbackUrl('/assets-admin/img/avatars/default.png')
            ->useFallbackPath(public_path('/assets-admin/img/avatars/default.png'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_CROP, 600, 600)
            ->nonQueued();
    }

    // Accessors for encrypted IDs
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }

    // Scope for active users
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }
}
```

### Relationship Patterns

#### One-to-Many Relationships

```php
// In User model
public function posts()
{
    return $this->hasMany(Post::class);
}

// In Post model
public function user()
{
    return $this->belongsTo(User::class);
}
```

#### Many-to-Many Relationships

```php
// Using Spatie's permission system
class User extends Authenticatable
{
    use HasRoles;
    // This provides roles() and permissions() relationships
}

class Role extends Model
{
    use HasFactory;
    
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'model_has_roles');
    }
}
```

#### Polymorphic Relationships

```php
// For activity logging
class Activity extends Model
{
    public function subject()
    {
        return $this->morphTo();
    }
    
    public function causer()
    {
        return $this->morphTo();
    }
}

// In any model that has activity log
class User extends Authenticatable
{
    use LogsActivity;
    
    protected static $recordEvents = ['created', 'updated', 'deleted'];
}
```

### Model Accessors and Mutators

#### Accessors

```php
class User extends Authenticatable
{
    // Encrypt ID for URL safety
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }
    
    // Format registration date
    public function getFormattedCreatedAtAttribute()
    {
        return formatTanggalIndo($this->created_at);
    }
    
    // Get full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
```

#### Mutators

```php
class User extends Authenticatable
{
    // Hash password automatically
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    
    // Format phone number
    public function setPhoneNumberAttribute($value)
    {
        $this->attributes['phone_number'] = preg_replace('/[^0-9]/', '', $value);
    }
}
```

### Query Scopes

#### Local Scopes

```php
class User extends Authenticatable
{
    // Scope for active users
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    // Scope with parameters
    public function scopeByRole($query, $roleName)
    {
        return $query->whereHas('roles', function($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }
    
    // Scope with multiple conditions
    public function scopeActiveWithValidEmail($query)
    {
        return $query->where('status', 'active')
                    ->whereNotNull('email_verified_at');
    }
}
```

#### Global Scopes

```php
class User extends Authenticatable
{
    protected static function booted()
    {
        // Only show non-deleted users by default
        static::addGlobalScope('notDeleted', function (Builder $builder) {
            $builder->whereNull('deleted_at');
        });
    }
}
```

## Migrations

### Creating Migrations

```bash
# Create a new migration
php artisan make:migration create_products_table

# Create migration with model
php artisan make:model Product -m
```

### Migration Structure

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
            $table->softDeletes(); // Important: for soft deletes
            
            // Indexes for performance
            $table->index('category_id');
            $table->index('price');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
```

### Migration Best Practices

#### Always Use Proper Data Types

- Use `unsignedBigInteger()` for foreign keys
- Use `decimal($precision, $scale)` for monetary values
- Use appropriate string lengths: `string('email', 255)`

#### Index Strategic Columns

```php
public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->decimal('total_amount', 10, 2);
        $table->enum('status', ['pending', 'processing', 'completed', 'cancelled']);
        $table->timestamps();
        
        // Index foreign keys
        $table->index('user_id');
        // Index frequently queried columns
        $table->index('status');
        $table->index('created_at');
        // Composite index for complex queries
        $table->index(['status', 'created_at']);
    });
}
```

#### Use Transactions for Complex Migrations

```php
public function up()
{
    DB::transaction(function () {
        // Multiple related operations in a single transaction
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable();
        });
        
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->unique();
        });
    });
}
```

## Seeds

### Seed Structure

The application uses a structured seed hierarchy:

- `DatabaseSeeder` - Main seeder that orchestrates all others
- `UserSeeder` - Creates default users
- `PermissionSeeder` - Sets up all permissions
- `RoleSeeder` - Creates default roles
- `RolePermissionSeeder` - Assigns permissions to roles
- `SysSeeder` - System-level permissions and configurations
- `SysSuperAdminSeeder` - Super admin user and role

### Example Seed Implementation

#### Permission Seeder

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // User management permissions
        $userPermissions = [
            ['name' => 'view-users', 'category' => 'user-management', 'sub_category' => 'users'],
            ['name' => 'create-users', 'category' => 'user-management', 'sub_category' => 'users'],
            ['name' => 'edit-users', 'category' => 'user-management', 'sub_category' => 'users'],
            ['name' => 'delete-users', 'category' => 'user-management', 'sub_category' => 'users'],
        ];
        
        foreach ($userPermissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
        
        // Product management permissions
        $productPermissions = [
            ['name' => 'view-products', 'category' => 'product-management', 'sub_category' => 'products'],
            ['name' => 'create-products', 'category' => 'product-management', 'sub_category' => 'products'],
            ['name' => 'edit-products', 'category' => 'product-management', 'sub_category' => 'products'],
            ['name' => 'delete-products', 'category' => 'product-management', 'sub_category' => 'products'],
        ];
        
        foreach ($productPermissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
        
        // System permissions
        $systemPermissions = [
            ['name' => 'view-activity-log', 'category' => 'system', 'sub_category' => 'logs'],
            ['name' => 'view-error-log', 'category' => 'system', 'sub_category' => 'logs'],
            ['name' => 'manage-backups', 'category' => 'system', 'sub_category' => 'backup'],
        ];
        
        foreach ($systemPermissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
```

#### User Seeder

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create super admin user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        // Create regular admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        // Create regular user
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        // Assign roles (these will be assigned in RolePermissionSeeder)
    }
}
```

### Running Seeds

```bash
# Run all seeds
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder

# Refresh database and run seeds
php artisan migrate:refresh --seed

# Run seeds without seeder model check (if needed)
php artisan db:seed --force
```

## Query Optimization

### Efficient Query Patterns

#### Eager Loading

```php
// N+1 problem example
$users = User::all();
foreach ($users as $user) {
    echo $user->profile->bio; // Triggers N additional queries
}

// Solution: Eager loading
$users = User::with('profile')->get();
foreach ($users as $user) {
    echo $user->profile->bio; // No additional queries needed
}
```

#### Specific Field Selection

```php
// Inefficient: loads all fields
$users = User::get();

// Efficient: loads only needed fields
$users = User::select('id', 'name', 'email')->get();

// Efficient: loads needed fields with relationships
$users = User::with(['profile:id,user_id,bio'])->select('id', 'name', 'email')->get();
```

#### Chunking Large Datasets

```php
// Process large datasets efficiently
User::chunk(200, function ($users) {
    foreach ($users as $user) {
        // Process each user
        if ($user->needsNotification()) {
            $user->notify(new SpecialOfferNotification());
        }
    }
});
```

## Indexing Strategy

### When to Add Indexes

1. **Foreign Keys**: Always index foreign key columns used in JOINs
2. **Frequently Queried Columns**: Columns used in WHERE clauses
3. **Sorting Columns**: Columns used in ORDER BY clauses
4. **Unique Constraints**: Columns that need to be unique

### Index Examples

```php
// In migration
Schema::table('user_orders', function (Blueprint $table) {
    // Individual indexes
    $table->index('user_id');
    $table->index('status');
    $table->index('created_at');
    
    // Composite index for multi-column queries
    $table->index(['status', 'created_at']);
    
    // Unique index
    $table->unique(['user_id', 'order_number']);
    
    // Fulltext index (for MySQL with InnoDB)
    $table->fullText('description');
});
```

## Database Security

### Protected Data Access

Implement proper access controls and data protection:

```php
// In model - restrict sensitive data access
class User extends Authenticatable
{
    // Hide sensitive attributes from JSON
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'google_2fa_secret',
    ];
    
    // Cast sensitive data properly
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_recovery_codes' => 'json',
    ];
}
```

### Environment-Specific Configurations

Ensure proper database configuration in different environments:

```bash
# Production .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=production_db
DB_USERNAME=prod_user
DB_PASSWORD=secure_password
```

Following these database and model practices ensures a robust, scalable, and maintainable application architecture that follows Laravel conventions while implementing specific system requirements.