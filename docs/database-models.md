# Database & Models

### System Tables

The system includes several categories of tables:

#### Base System Tables
- Prefixed with `sys_` (reserved for system functionality)
- Include error logs, notifications, activity logs, and monitoring data

#### Core Application Tables
- `users` - User accounts and authentication
- `roles` - User roles (via spatie/laravel-permission)
- `permissions` - System permissions (via spatie/laravel-permission)
- `model_has_permissions`, `model_has_roles`, `role_has_permissions` - Permission relationships

## Eloquent Models

### User Model

The User model includes authentication and additional functionality:

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
        'expires_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Activity logging options
    protected static $logName = 'user';

    // Media collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->useFallbackUrl('/assets-admin/img/avatars/default.png');
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
    use HasRoles; // This provides roles() and permissions() relationships
}
```

### Query Scopes

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
}
```

## Migrations

### Creating Migrations

```bash
# Create a new migration with model
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
            $table->softDeletes(); // For soft deletes

            // Indexes for performance
            $table->index('category_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
```

## Seeds

### Running Seeds

```bash
# Run all seeds
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder

# Refresh database and run seeds
php artisan migrate:refresh --seed
```
