# Authentication & Authorization

## Overview

The system implements comprehensive authentication and authorization using Laravel Breeze and the Spatie Laravel Permission package. This includes user registration, login, Google OAuth integration, and role-based access control.

## Authentication System

### Laravel Breeze

The authentication system is built on Laravel Breeze, providing:
- Login, registration, email verification, and password reset
- Session management
- CSRF protection
- Secure logout functionality

### Google OAuth Integration

#### Configuration

- Set up Google OAuth credentials in Google Cloud Console
- Configure callback URL: `http://yoursite.com/auth/google/callback`
- Add credentials to `.env`:

```bash
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT=http://localhost/auth/google/callback
```

#### Implementation

Google OAuth is implemented in `AuthController` with the following features:
- User creation on first login
- Association of Google account with existing users
- Proper error handling for OAuth failures

## Role-Based Access Control (RBAC)

The system uses `spatie/laravel-permission` for role and permission management.

### Core Concepts

- **Roles**: Collections of permissions (e.g., admin, manager, user)
- **Permissions**: Actions users can perform (e.g., view-users, manage-posts)
- **Users**: Assigned one or more roles and inherit permissions accordingly

### Permission Structure

Permissions are organized with categories and sub-categories for better organization:
- Category: Major functional area (e.g., user management, system configuration)
- Sub-category: Specific functionality within a category (e.g., create users, edit users)

### Implementation

#### Assigning Roles & Permissions

In seeders (typically `RolePermissionSeeder`):

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Create permissions
$manageUsers = Permission::create(['name' => 'manage-users', 'category' => 'user-management', 'sub_category' => 'users']);
$viewUsers = Permission::create(['name' => 'view-users', 'category' => 'user-management', 'sub_category' => 'users']);

// Create role and assign permissions
$adminRole = Role::create(['name' => 'admin']);
$adminRole->givePermissionTo([$manageUsers, $viewUsers]);
```

#### Checking Permissions

In controllers:
```php
// Check permissions using cache
if ($request->user()->can('manage-users')) {
    // Allow action
}
```

In views:
```blade
@if(auth()->user()->can('manage-users'))
    <button>Manage Users</button>
@endif
```

Using middleware:
```php
Route::middleware(['can:manage-users'])->group(function () {
    // Protected routes
});
```

### Caching Permissions

For performance optimization:
```bash
php artisan permission:cache-reset
```

Permissions are cached automatically and cleared when roles or permissions change.

## Account Expiration

### Middleware

The system implements an account expiration check with the `CheckAccountExpiration` middleware.

#### Configuration

The middleware is registered in `bootstrap/app.php`:
```php
'middleware' => function (Middleware $middleware) {
    $middleware->alias([
        // ... other aliases
        'check.expired' => \App\Http\Middleware\CheckAccountExpiration::class,
    ]);
};
```

#### Implementation

- Automatically checks account expiration on every authenticated request
- Applied together with `auth` middleware as `['auth', 'check.expired']`
- Automatically logs out users with expired accounts
- Shows appropriate error messages when account is expired
- Supports both web and API requests (returns JSON error for API requests)

## User Impersonation

The system supports administrator impersonation using the `lab404/laravel-impersonate` package.

### Implementation

#### Middleware Configuration

```php
// In bootstrap/app.php
'middleware' => function (Middleware $middleware) {
    $middleware->alias([
        // ... other aliases
        'impersonate' => \Lab404\Impersonate\Middleware\ImpersonateMiddleware::class,
    ]);
};
```

#### Routes

```php
// In routes/admin.php
Route::impersonate();
```

#### Usage

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

### UI Integration

A "Switch Back" button is available in the user profile dropdown when impersonating:
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

## Multiple Role Switching

Users with multiple roles can switch between their active roles with a dedicated interface in the header dropdown.

### Implementation

This functionality is implemented in `SysHelper` functions:
- `setActiveRole()` - Set the active role for the current session
- `getActiveRole()` - Get the currently active role
- `getAllUserRoles()` - Get all roles assigned to the user

### UI Integration

The role switching interface appears in the header dropdown when a user has multiple roles.

## Authentication Event Logging

The system logs all authentication events:

- **Login Logging** - Records all successful user login events with user identity
- **Logout Logging** - Records all user logout events
- **Impersonation Logging** - Records when administrators impersonate other users
- **Switch Back Logging** - Records when impersonation sessions end and users return to their original accounts

This provides complete audit trails for security and compliance purposes.