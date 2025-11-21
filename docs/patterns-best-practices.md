# Development Patterns & Best Practices

## Overview

This section outlines the key development patterns and best practices for the laboratory management system.

## Code Organization

### Controller Organization

Controllers are organized by domain:

- `app/Http/Controllers/Admin/` - Administrative functionality
- `app/Http/Controllers/Auth/` - Authentication functionality
- `app/Http/Controllers/Guest/` - Public functionality
- `app/Http/Controllers/Sys/` - System functionality (monitoring, backups, logs, etc.)

### Resource Controllers

The system uses Laravel's resource controllers for standard CRUD operations:

```php
Route::resource('users', UserController::class);
```

## Security Considerations

### Input Validation

Always validate user input using FormRequest classes:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ];
    }
}
```

### Authorization Checks

Use proper authorization:

```php
// In routes
Route::resource('users', UserController::class)->middleware('can:manage-users');

// In controller methods
public function store(CreateUserRequest $request)
{
    if (!$request->user()->can('create-users')) {
        abort(403, 'Unauthorized action.');
    }

    // ... rest of method
}
```

### ID Encryption

Encrypt sensitive IDs in URLs:

```php
// In model
public function getEncryptedIdAttribute()
{
    return encryptId($this->id);
}

// In controller
public function show($encryptedId)
{
    $id = decryptId($encryptedId);
    $user = User::findOrFail($id);

    return view('pages.admin.users.show', compact('user'));
}
```

## Eloquent Query Optimization

### N+1 Query Prevention

Always use eager loading to prevent N+1 query problems, which can significantly impact application performance by generating many unnecessary database queries:

```php
// Instead of this (causes N+1 queries):
$users = User::all();
foreach($users as $user) {
    echo $user->profile->name; // Triggers additional query for each user
    echo $user->posts->count(); // Triggers additional query for each user
    echo $user->roles->first()->name; // Triggers additional query for each user
}

// Use this (eager loading):
$users = User::with(['profile', 'posts', 'roles'])->get();
foreach($users as $user) {
    echo $user->profile->name; // No additional query needed
    echo $user->posts->count(); // No additional query needed
    echo $user->roles->first()->name; // No additional query needed
}
```

#### Advanced Eager Loading Examples

You can also apply constraints to your eager loaded relationships:

```php
// Load users with their posts, but only published posts
$users = User::with(['posts' => function ($query) {
    $query->where('status', 'published');
}])->get();

// Load users with their most recent post only
$users = User::with(['posts' => function ($query) {
    $query->latest()->limit(1);
}])->get();

// Load users with their profile and posts, with additional constraints
$users = User::with([
    'profile' => function ($query) {
        $query->select('user_id', 'bio', 'avatar');
    },
    'posts' => function ($query) {
        $query->select('user_id', 'title', 'published_at')
              ->where('published_at', '>', now()->subDays(30))
              ->orderBy('published_at', 'desc');
    }
])->get();
```

#### Nested Eager Loading

You can also eager load nested relationships (relationships of relationships):

```php
// Load users with their posts and the author of each post's comments
$users = User::with('posts.comments.author')->get();

// More complex nested example
$users = User::with([
    'posts' => function ($query) {
        $query->with(['comments' => function ($commentQuery) {
            $commentQuery->with('author');
        }]);
    }
])->get();
```

Using eager loading correctly is critical for maintaining good application performance, especially when displaying lists or tables with related data.

## Performance Optimization

### Caching Strategies

#### Configuration Caching

```bash
# Cache configuration for better performance
php artisan config:cache

# Cache routes for better performance
php artisan route:cache

# Cache views for better performance
php artisan view:cache
```

## Code Quality Standards

### Naming Conventions

- Use descriptive names: `$userList` instead of `$data`
- Use boolean prefixes: `$isExpired` instead of `$status`
- Use camelCase for functions: `calculateTotalRevenue()`, `uploadProfileImage()`

### Documentation

Document complex functions with PHPDoc:

```php
/**
 * Process a new user registration
 *
 * @param array $userData The user registration data
 * @return User The newly created user instance
 */
public function registerUser(array $userData): User
{
    // Implementation
}
```

## Helper Functions Classification

The system implements a classification system for helper functions to maintain code quality and prevent accidental modifications to critical system functions.

### Sys Helper Functions

Sys helpers (located in `app/Helpers/Sys.php`) contain core system functions that handle critical operations. **These functions should not be modified without team discussion and review** as they affect fundamental system functionality.

### Global Helper Functions

Global helpers (located in `app/Helpers/Global.php`) contain general-purpose functions that are safe to modify and extend as needed. These functions are designed to be customized for specific business requirements.

### Usage Guidelines

1. **For Sys Helpers**: Only use existing functions; do not modify or add new functions without explicit team approval
2. **For Global Helpers**: Safe to extend and modify for additional functionality
3. **Creating New Helpers**: Follow the classification guidelines when adding new functions

Example of helper usage in controllers:

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        // Format dates using helper function (Global helper)
        $users->transform(function ($user) {
            $user->formatted_created_at = formatTanggalIndo($user->created_at);
            $user->formatted_updated_at = formatTanggalIndo($user->updated_at);
            return $user;
        });

        return view('pages.admin.users.index', compact('users'));
    }

    public function show($encryptedId)
    {
        // Decrypt the ID using helper (Sys helper)
        $id = decryptId($encryptedId);
        $user = User::findOrFail($id);

        // Format data using helpers
        $user->formatted_created_at = formatTanggalIndo($user->created_at);
        $user->formatted_updated_at = formatTanggalWaktuIndo($user->updated_at);

        return view('pages.admin.users.show', compact('user'));
    }
}
```
