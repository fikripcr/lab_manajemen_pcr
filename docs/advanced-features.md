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

### Activity Log Features

- **Automatic Logging**: Models with `LogsActivity` trait automatically log changes
- **Custom Logging**: Manual logging for specific actions with context
- **Property Tracking**: Track old and new values for updates
- **User Attribution**: Link activities to the user who caused them
- **Model Association**: Link activities to the affected model
- **Log Categories**: Organize logs by category (user, role, etc.)

### Activity Log Interface

Access activity logs through the "Activity Log" menu in the admin panel:
- Filter logs by user, date range, or log type
- View detailed information about each activity
- Export logs for analysis
- Clear old logs to manage database size

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

#### Notification Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'read_at',
        'data'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

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

### Advanced Search Features

#### Auto-Focus
- Search input automatically gains focus when modal opens
- Improved user experience for quick searching

#### Quick Navigation
- Direct links to search results for rapid access
- Keyboard navigation support with up/down arrows and Enter key

#### Grouped Results
- Results are organized by content type for easier scanning
- Visual separation between different result types

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

### Implementation

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

#### Available Report Types

- **Summary Report**: Overall user statistics and system information
- **Detailed Report**: Complete user details with all available information
- **Role-specific Report**: Users assigned to specific roles
- **Custom Reports**: Reports based on specific filtering criteria

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

## Configuration Management

The application includes comprehensive system configuration management accessible from the "System Config" menu in the admin panel.

### App Configuration Features

#### Application Name Management
- Dynamically change the application name as it appears throughout the system
- Changes are reflected immediately across all pages

#### Environment Settings
- Configure between local, staging, and production environments
- Environment-specific settings are properly validated

#### Debug Mode Toggle
- Toggle debugging functionality on or off
- Includes proper validation to prevent accidental production debugging

#### URL Configuration
- Set the application's base URL
- Used for generating proper links and media URLs

### Cache Management

#### Configuration Cache
- Clear cached configuration values with `php artisan config:clear`
- Cache configuration values for performance with `php artisan config:cache`

#### View Cache
- Clear cached Blade templates with `php artisan view:clear`
- Cache compiled templates with `php artisan view:cache`

#### Route Cache
- Clear cached route definitions with `php artisan route:clear`
- Cache routes for improved performance with `php artisan route:cache`

#### Application Cache
- Clear all application cache with `php artisan cache:clear`

### Application Optimization

#### Performance Features
- Cache configuration, routes, and views for improved performance
- Proper cache invalidation when configurations change

#### Implementation

```php
// In configuration controller
public function updateAppConfig(Request $request)
{
    $request->validate([
        'app_name' => 'required|string|max:255',
        'app_env' => 'required|in:local,production,staging',
        'app_debug' => 'required|boolean',
        'app_url' => 'required|url',
    ]);
    
    // Update .env file with new values
    $this->updateEnvFile([
        'APP_NAME' => $request->app_name,
        'APP_ENV' => $request->app_env,
        'APP_DEBUG' => $request->app_debug ? 'true' : 'false',
        'APP_URL' => rtrim($request->app_url, '/')
    ]);
    
    // Clear and re-cache configurations
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    
    // Log the configuration update
    activity()->log('Updated application configuration');
    
    return redirect()->back()->with('success', 'Configuration updated successfully.');
}

private function updateEnvFile($values)
{
    $envPath = base_path('.env');
    $content = file_get_contents($envPath);
    
    foreach ($values as $key => $value) {
        if (strpos($content, $key) !== false) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            $content .= "\n{$key}={$value}";
        }
    }
    
    file_put_contents($envPath, $content);
}
```

## DataTable State Persistence

The system includes a robust state persistence system for DataTable components that preserves user preferences between page loads.

### Features

#### Search Persistence
- Search terms are automatically saved and restored using DataTable's built-in state saving

#### Filter Persistence
- Custom applied filters are preserved between page loads using custom localStorage

#### Page Length Persistence
- Selected page length (10, 25, 50, 100, All) is automatically maintained by DataTable

#### Pagination Persistence
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

#### UI Synchronization

All UI components are updated to match the restored DataTable state:

```javascript
// When DataTable state loads
$('#my-datatable').on('init.dt', function() {
    // Restore custom filter values
    restoreCustomFilters();
    
    // Update UI components to match DataTable state
    updatePageLengthSelector();
    updateSearchBox();
});
```

### Components Affected

- All pages using the `x-datatable.datatable` component
- All pages using the `x-datatable.search-filter` component
- All pages using the x-datatable.page-length` component