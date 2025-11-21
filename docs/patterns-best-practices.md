# Development Patterns & Best Practices

## Overview

This section outlines the development patterns and best practices implemented in the system to ensure consistency, maintainability, and scalability across the application.

## Code Organization

### Controller Organization

Controllers are organized by domain to ensure clear separation of concerns:

- `app/Http/Controllers/Admin/` - Administrative functionality
- `app/Http/Controllers/Auth/` - Authentication functionality
- `app/Http/Controllers/Guest/` - Public functionality
- `app/Http/Controllers/Sys/` - System functionality (monitoring, backups, logs, etc.)

### MVC Pattern Implementation

The system follows the Model-View-Controller (MVC) pattern for clear separation of concerns:

#### Model Responsibilities
- Database interaction and relationships
- Business logic implementation
- Validation rules definition
- Accessor and mutator methods
- Scope methods for common queries

#### View Responsibilities
- Presenting data to users
- User interface elements
- Form rendering and validation display
- Template structure and layout

#### Controller Responsibilities
- Handling HTTP requests
- Coordinating between models and views
- Managing user input validation
- Implementing business logic (when it involves multiple models)

### Resource Controllers

The system extensively uses Laravel's resource controllers for standard CRUD operations:

```php
Route::resource('users', UserController::class);
```

This creates the standard routes:
- `GET /users` → `index()` - List all users
- `GET /users/create` → `create()` - Show form for creating user
- `POST /users` → `store()` - Store new user
- `GET /users/{user}` → `show()` - Show single user
- `GET /users/{user}/edit` → `edit()` - Show form for editing user
- `PUT/PATCH /users/{user}` → `update()` - Update user
- `DELETE /users/{user}` → `destroy()` - Delete user

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

### Specific Field Loading

Load only the fields actually needed to reduce database load:

```php
// Load specific fields only
$users = User::with(['profile:id,user_id,name'])->select('id', 'name', 'email')->get();
```

### Advanced Eager Loading

Use nested relationships and constraint loading when needed:

```php
// Load nested relationships
$users = User::with(['posts', 'posts.comments'])->get();

// Constrain loaded relationships
$users = User::with(['posts' => function($query) {
    $query->where('published', true);
}])->get();

// Load specific fields of relationships
$users = User::with(['posts:id,title,user_id,published_at'])->get();
```

## Database Operations

### Transaction Management

Always use database transactions for important operations:

```php
use Illuminate\Support\Facades\DB;

DB::beginTransaction();

try {
    $user = User::create($userData);
    $user->profile()->create($profileData);
    
    DB::commit();
    return response()->json(['success' => true]);
} catch (Exception $e) {
    DB::rollback();
    logError($e);
    return response()->json(['success' => false, 'message' => 'Operation failed']);
}
```

### Soft Deletes

Use soft deletes instead of permanent deletion to maintain data integrity:

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;
    
    // ... model implementation
}

// To permanently delete when necessary
$user->forceDelete();
```

### Query Scopes

Implement query scopes for commonly used query filters:

```php
class User extends Model
{
    // Local scope
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    // Scope with parameters
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
    
    // Global scope (applies to all queries)
    protected static function booted()
    {
        static::addGlobalScope('published', function (Builder $builder) {
            $builder->where('published', true);
        });
    }
}
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
    public function authorize()
    {
        return auth()->user()->can('create-users');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
```

### Authorization Checks

Implement proper authorization checks at both route and controller levels:

```php
// In routes/admin.php
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

Encrypt sensitive IDs in URLs to prevent enumeration attacks:

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

### SQL Injection Prevention

Laravel's Eloquent ORM and Query Builder automatically protect against SQL injection when used properly:

```php
// Safe - using parameter binding
$users = User::where('status', $status)->get();

// Safe - using Eloquent methods
$users = User::whereStatus($status)->get();

// Avoid raw queries unless absolutely necessary
// If needed, use proper parameter binding
$users = DB::select('select * from users where status = ?', [$status]);
```

## Performance Optimization

### Caching Strategies

#### Query Caching

Cache expensive database queries:

```php
use Illuminate\Support\Facades\Cache;

public function getStats()
{
    return Cache::remember('user_stats', 3600, function () {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
        ];
    });
}
```

#### Configuration Caching

Cache configuration values in production:

```bash
# Clear configuration cache
php artisan config:clear

# Cache configuration
php artisan config:cache
```

#### Route Caching

Cache route definitions for improved performance:

```bash
# Clear route cache
php artisan route:clear

# Cache routes
php artisan route:cache
```

### Asset Optimization

#### CSS and JavaScript Optimization

- Use Laravel Mix for asset compilation and minification
- Combine and minify CSS/JS files
- Use CDN or local copies of external libraries
- Implement lazy loading for non-critical assets

#### Image Optimization

- Use appropriate image formats (WebP when possible)
- Compress images during upload
- Implement responsive image loading
- Use image caching headers

### Database Optimization

#### Indexing

Add appropriate database indexes:

```php
// In migration
Schema::table('users', function (Blueprint $table) {
    $table->index('email');
    $table->index(['status', 'created_at']);
    $table->unique('email');
});
```

#### Query Optimization

Optimize complex queries:

```php
// Use select only needed fields
$users = User::select('id', 'name', 'email')->get();

// Use chunk for large datasets
User::chunk(200, function ($users) {
    foreach ($users as $user) {
        // Process user
    }
});

// Use whereIn for multiple conditions
$ids = [1, 2, 3, 4, 5];
$users = User::whereIn('id', $ids)->get();
```

## Code Quality & Standards

### Naming Conventions

#### Variables and Functions

- Use descriptive names: `$userList` instead of `$data`
- Use boolean prefixes: `$isExpired` instead of `$status`
- Use camelCase for functions: `calculateTotalRevenue()`, `uploadProfileImage()`

#### Classes and Files

- Use PascalCase for class names: `UserManagementController`, `ProductRequest`
- Use domain-based organization: `Admin\UserController`, `Guest\HomeController`
- Follow Laravel naming conventions for standard components

### Code Documentation

#### PHPDoc Comments

Document all complex functions and classes:

```php
/**
 * Process a new user registration
 * 
 * This method handles the complete user registration process including
 * validation, user creation, profile setup, and welcome notification.
 * 
 * @param array $userData The user registration data
 * @param bool $sendWelcomeEmail Whether to send welcome email
 * @return User The newly created user instance
 * @throws ValidationException If validation fails
 * @throws Exception If registration fails
 */
public function registerUser(array $userData, bool $sendWelcomeEmail = true): User
{
    // Implementation
}
```

#### Inline Comments

Use inline comments to explain complex logic:

```php
// Calculate prorated amount based on days remaining in billing cycle
$daysRemaining = now()->diffInDays($billingPeriodEnd);
$totalDays = $billingPeriodStart->diffInDays($billingPeriodEnd);
$proratedAmount = ($daysRemaining / $totalDays) * $monthlyRate;
```

### Testing Approaches

#### Unit Testing

Write unit tests for critical business logic:

```php
// tests/Unit/BillingCalculatorTest.php
public function test_it_calculates_prorated_amount_correctly()
{
    $calculator = new BillingCalculator();
    $result = $calculator->calculateProratedAmount(100, '2023-01-15', '2023-01-31');
    
    $this->assertEquals(50, $result); // Half month = 50% of monthly rate
}
```

#### Feature Testing

Test user flows and integration points:

```php
// tests/Feature/UserRegistrationTest.php
public function test_user_can_register_with_valid_data()
{
    $response = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);
    
    $response->assertRedirect('/dashboard');
    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com'
    ]);
}
```

## Error Handling and Logging

### Exception Handling

The system implements comprehensive error logging in `bootstrap/app.php`:

```php
// In bootstrap/app.php
use App\Exceptions\Handler;

// All exceptions are automatically captured and logged
// to the SysErrorLog model with full context information
```

### Graceful Error Handling

Handle errors gracefully in controllers:

```php
public function update(Request $request, $encryptedId)
{
    try {
        $id = decryptId($encryptedId);
        $user = User::findOrFail($id);
        
        $user->update($request->validated());
        
        activity()->performedOn($user)
                 ->causedBy(auth()->user())
                 ->log('Updated user ' . $user->name);
        
        return redirect()->route('admin.users.index')
                        ->with('success', 'User updated successfully.');
    } catch (ModelNotFoundException $e) {
        logError($e, $request);
        return redirect()->route('admin.users.index')
                        ->with('error', 'User not found.');
    } catch (Exception $e) {
        logError($e, $request);
        return redirect()->route('admin.users.index')
                        ->with('error', 'Error updating user.');
    }
}
```

### Validation Error Handling

Handle validation errors consistently:

```php
public function store(UserRequest $request)
{
    try {
        $user = User::create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'user' => $user
        ]);
    } catch (Exception $e) {
        logError($e, $request);
        
        return response()->json([
            'success' => false,
            'message' => 'Error creating user'
        ], 500);
    }
}
```

## Frontend Best Practices

### Blade Template Optimization

#### Component Reusability

Create reusable Blade components:

```blade
{{-- resources/views/components/form/input.blade.php --}}
@props(['name', 'label', 'type' => 'text', 'value' => ''])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input 
        type="{{ $type }}" 
        class="form-control @error($name) is-invalid @enderror" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        value="{{ old($name, $value) }}"
        {{ $attributes }}
    >
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
```

#### Efficient Template Structure

Use layout inheritance and sections:

```blade
{{-- resources/views/pages/admin/users/create.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Create User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Create New User</h5>
                
                @include('components.forms.user-form', ['action' => route('admin.users.store')])
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets-admin/js/user-form.js') }}"></script>
@endpush
```

### JavaScript Best Practices

#### Modular Architecture

Organize JavaScript code in modules:

```javascript
// public/assets-admin/js/modules/datatable.js
const DataTableModule = (function() {
    function initializeDataTable(tableId, url, options = {}) {
        return $(tableId).DataTable({
            processing: true,
            serverSide: true,
            ajax: url,
            ...options
        });
    }
    
    function formatRupiah(amount) {
        return 'Rp ' + parseFloat(amount).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
    }
    
    return {
        initializeDataTable,
        formatRupiah
    };
})();
```

#### Performance Optimization

Use event delegation for dynamically added elements:

```javascript
// Use event delegation instead of direct binding
$(document).on('click', '.delete-btn', function() {
    const id = $(this).data('id');
    
    if (confirm('Are you sure you want to delete this item?')) {
        // Perform delete operation
    }
});
```

## Deployment Best Practices

### Environment Configuration

Maintain separate environment configurations:

```bash
# Production .env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://production-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prod_database
DB_USERNAME=prod_user
DB_PASSWORD=secure_password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Security Measures

#### Production Security

- Set `APP_DEBUG=false` in production
- Regenerate application key during deployment: `php artisan key:generate`
- Keep `.env` file out of version control
- Use HTTPS in production
- Implement proper access controls

```bash
# Regenerate key during deployment
php artisan key:generate --force
```

### Performance Optimization

#### Production Optimization

```bash
# Cache configuration, routes, and views for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize Composer autoloader
composer dump-autoload --optimize
```

## Version Control Best Practices

### Git Workflow

Follow a structured Git workflow:

1. Create feature branches from `develop`
2. Use descriptive commit messages
3. Submit pull requests for code review
4. Merge to `develop` after approval
5. Tag releases appropriately

### Commit Message Standards

Use clear, descriptive commit messages:

```
feat: Add user impersonation functionality

- Implement Lab404 impersonation package
- Add switch back functionality
- Update UI with impersonation indicators

Fixes #123
```

## Documentation Standards

Maintain updated documentation for all features:

- Document new features in the appropriate section
- Update API documentation as needed
- Keep README files up to date
- Document breaking changes in release notes

Following these patterns and best practices helps maintain a consistent, scalable, and maintainable codebase across the entire application.