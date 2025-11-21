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

Always use eager loading to prevent N+1 query problems:

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
```

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