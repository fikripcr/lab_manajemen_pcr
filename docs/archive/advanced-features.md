# Advanced Features

## Overview

This section covers the advanced features of the system including activity logging, notifications, global search, impersonation, PDF generation, and configuration management.

## Activity Logging

The system implements comprehensive activity logging using the Spatie Laravel Activity Log package.

### Implementation

#### Model Configuration

In models requiring activity logging (e.g., `User.php`):

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasFactory, LogsActivity;

    protected static $logName = 'user';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
```

#### Manual Logging in Controllers

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

## Notifications

The system includes a comprehensive notification management system with both database and email delivery options.

### Core Features

#### Database Notifications
- Notifications stored in `sys_notifications` table with title, body, and timestamp
- Support for rich text content with HTML formatting
- Automatic read/unread status tracking
- User-specific notification filtering

#### Email Notifications
- Support for sending notifications via email
- Configurable email templates
- Queue-based delivery for performance

### Implementation


#### Sending Notifications

```php
// In controllers
use App\Models\SysNotification;

// Send to current user
SysNotification::create([
    'user_id' => auth()->id(),
    'title' => 'Notification Title',
    'body' => 'Notification body content',
    'data' => [
        'link' => route('admin.dashboard'),
        'type' => 'info'
    ]
]);

// Send to specific user
SysNotification::create([
    'user_id' => $targetUserId,
    'title' => 'Special Announcement',
    'body' => 'This is an important announcement',
    'data' => [
        'link' => route('admin.announcements.show', $announcementId),
        'type' => 'alert'
    ]
]);
```

#### Notification Interface

In the UI, notifications are accessible through:
- Header dropdown with quick access
- Dedicated notifications page
- Mark as read individually, in bulk, or all at once

### Testing Capabilities

#### Test Notification Functionality
- Send test notifications to current user from notifications page
- Send notifications to specific users from user management page
- Test email notifications functionality
- Loading indicators during notification operations

## Global Search Feature

The application includes a powerful global search functionality accessible from the search icon in the header.

### Search Capabilities

#### Multi-model Search
- Search across multiple data types simultaneously
- Configurable models and fields to include in search
- Relevance ranking based on search terms

#### Real-time Results
- See results as you type without page refresh
- Debounced search requests to reduce server load
- Loading indicators during search operations

### Implementation

#### Search Controller

```php
<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role; 
// Other models to search

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }
        
        $results = [];
        
        // Search users
        $users = User::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->limit(5)
                    ->get();
        
        if ($users->count() > 0) {
            $results[] = [
                'type' => 'Users',
                'items' => $users->map(function ($user) {
                    return [
                        'id' => $user->encrypted_id,
                        'title' => $user->name,
                        'description' => $user->email,
                        'url' => route('admin.users.edit', $user->encrypted_id),
                        'icon' => 'bx bx-user'
                    ];
                })->toArray()
            ];
        }
        
        // Add other models to search...
        
        return response()->json(['results' => $results]);
    }
}
```

#### Frontend Implementation

```javascript
// In public/assets-admin/js/modules/global-search.js
function initGlobalSearch() {
    const searchInput = document.getElementById('global-search-input');
    const searchResults = document.getElementById('global-search-results');
    const searchModal = document.getElementById('global-search-modal');
    
    searchInput.addEventListener('input', debounce(function() {
        const query = this.value.trim();
        
        if (query.length >= 2) {
            showLoadingState();
            
            fetch('/admin/search?q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    hideLoadingState();
                    displaySearchResults(data.results);
                })
                .catch(error => {
                    hideLoadingState();
                    console.error('Search error:', error);
                });
        } else {
            clearSearchResults();
        }
    }, 300));
    
    // Auto-focus search input when modal opens
    searchModal.addEventListener('shown.bs.modal', function () {
        searchInput.focus();
    });
}
```

### Customizable Search

Developers can easily extend the search functionality to include additional models by modifying the `GlobalSearchController`:

1. Add new models to the search logic
2. Customize which fields to search in each model
3. Adjust result presentation formatting

## User Impersonation

The system supports administrator impersonation using the `lab404/laravel-impersonate` package.

### Implementation Details

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

#### Usage in Controllers

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

## PDF Generation

The system includes PDF generation capabilities using Laravel DomPDF.

#### PDF Export Service

```php
<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfExportService
{
    public function generateUserReport($userId = null)
    {
        $users = $userId ? User::where('id', $userId)->get() : User::all();
        
        $pdf = Pdf::loadView('pdf.user-report', [
            'users' => $users,
            'reportDate' => now()->format('d F Y H:i:s')
        ]);
        
        return $pdf->download('user-report-' . date('Y-m-d') . '.pdf');
    }
    
    public function generateSummaryReport()
    {
        // Get summary data
        $summaryData = [
            'totalUsers' => User::count(),
            'activeUsers' => User::whereNull('deleted_at')->count(),
            'trashedUsers' => User::onlyTrashed()->count(),
            // ... other summary data
        ];
        
        $pdf = Pdf::loadView('pdf.summary-report', [
            'summaryData' => $summaryData,
            'reportDate' => now()->format('d F Y H:i:s')
        ]);
        
        return $pdf->download('summary-report-' . date('Y-m-d') . '.pdf');
    }
}
```

#### PDF Templates

Create custom PDF templates in `resources/views/pdf/`:

```blade
{{-- resources/views/pdf/user-report.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>User Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>User Report</h1>
    <p>Generated on: {{ $reportDate }}</p>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ formatTanggalIndo($user->created_at) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
```

### Optimization for PDF Reports

To prevent page overflow, PDF reports include only essential information:

```php
// In controller
public function exportPdf()
{
    // Get only essential data for the PDF
    $users = User::select('id', 'name', 'email', 'created_at', 'updated_at')
                 ->get();
    
    // Format data appropriately for PDF display
    $users->transform(function ($user) {
        $user->formatted_created_at = formatTanggalIndo($user->created_at);
        $user->formatted_updated_at = formatTanggalIndo($user->updated_at);
        return $user;
    });
    
    // Generate PDF
    $pdf = Pdf::loadView('pdf.users', compact('users'));
    return $pdf->download('users-report.pdf');
}
```

## DataTable State Persistence

The system includes a robust state persistence system for DataTable components that preserves user preferences between page loads.

### Features

- Search terms are automatically saved and restored using DataTable's built-in state saving
- Custom applied filters are preserved between page loads using custom localStorage
- Selected page length (10, 25, 50, 100, All) is automatically maintained by DataTable
- Current page number and state are automatically preserved by DataTable

#### Unique Storage Keys
- DataTable automatically creates unique keys based on table ID and URL path

### Implementation Details

#### DataTable Component

Uses native DataTables `stateSave: true` for standard features:
- Search
- Pagination
- Page length
- Column ordering
- Column visibility

#### Custom Filter Handling

Custom filters are handled separately with custom localStorage functionality:

```javascript
// Save custom filter state
function saveCustomFilterState(filterId, value) {
    const key = `datatable_filter_${getTableId()}_${filterId}`;
    localStorage.setItem(key, value);
}

// Load custom filter state
function loadCustomFilterState(filterId) {
    const key = `datatable_filter_${getTableId()}_${filterId}`;
    return localStorage.getItem(key) || '';
}
```

### Components Affected

- All pages using the `x-datatable.datatable` component
- All pages using the `x-datatable.search-filter` component
- All pages using the x-datatable.page-length` component

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

### Example of Helper Usage

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
