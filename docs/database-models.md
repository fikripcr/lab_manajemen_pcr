# Database & Models

## System Tables

### Core System Tables
Tables with the `sys_` prefix are core system tables that are essential for application functionality. **These tables should not be modified without further discussion.**

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

Query scopes are methods that allow you to define common query constraints that can be reused in Eloquent models. This makes code cleaner and more reusable.

Example of query scopes usage:

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

Then you can use them in a controller or elsewhere as follows:

```php
// Using scope to get active users
$activeUsers = User::active()->get();

// Using scope with parameter
$adminUsers = User::byRole('admin')->get();

// Combining multiple scopes
$activeAdminUsers = User::active()->byRole('admin')->get();
```

## Migrations

### Migration System

The `database/migrations/sys` folder contains core system migrations and **should not be modified** as it contains the essential table structures for application functionality.

It's important to ensure migration structures match the actual database tables for smooth deployment processes. If the migration structure doesn't match the actual tables, issues may occur during deployment.

Column structure changes should not be made directly to tables; they must go through the migration process to ensure changes are recorded and can be applied to other environments.

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

### Dummy Data with Faker

To create dummy data in seeders, use Faker as a data source. Ensure you use the `id_ID` locale so that the generated data is in Indonesian to match the local context.

Example usage with id_ID locale:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID'); // Using Indonesian locale

        DB::table('users')->insert([
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Or using factory with id_ID locale
        \App\Models\User::factory()
            ->count(50)
            ->create();
    }
}
```

Note that we use `$faker = Faker::create('id_ID')` to ensure the dummy data is generated in Indonesian, including names, addresses, and other data that matches the local context.
