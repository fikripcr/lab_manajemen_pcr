# Helper Functions & Utilities

## Overview

The system includes two main categories of helper functions: Sys helpers for core system functions and Global helpers for general-purpose utilities. These functions are designed to streamline development and maintain consistency across the application.

## Sys Helper Functions

Sys helpers (located in `app/Helpers/Sys.php`) contain core system functions that should not be modified without team discussion and review. These functions handle critical system operations.

### Error Logging Functions

#### `logError()`
Logs errors to the system error log with comprehensive context information:

```php
use App\Models\SysErrorLog;

function logError($exception, $request = null) {
    SysErrorLog::create([
        'url' => $request ? $request->url() : url()->current(),
        'method' => $request ? $request->method() : request()->method(),
        'message' => $exception->getMessage(),
        'exception' => get_class($exception),
        'line' => $exception->getLine(),
        'file' => $exception->getFile(),
        'trace' => $exception->getTraceAsString(),
        'ip_address' => $request ? $request->ip() : request()->ip(),
        'user_agent' => $request ? $request->userAgent() : request()->userAgent(),
        'user_id' => auth()->id(),
    ]);
}
```

### Path Management Functions

#### `normalizePath()`
Normalizes file paths across different operating systems:

```php
function normalizePath($path) {
    return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
}
```

### Active Role Management Functions

#### `setActiveRole()`
Sets the active role for the current session:

```php
function setActiveRole($roleName) {
    session(['active_role' => $roleName]);
}
```

#### `getActiveRole()`
Returns the currently active role:

```php
function getActiveRole() {
    return session('active_role');
}
```

#### `getAllUserRoles()`
Returns all roles assigned to the current user:

```php
function getAllUserRoles() {
    return auth()->user()->roles->pluck('name')->toArray();
}
```

## Global Helper Functions

Global helpers (located in `app/Helpers/Global.php`) contain general-purpose functions that are safe to modify and extend as needed.

### Date and Time Formatting

#### `formatTanggalIndo()`
Formats a date string to Indonesian format (contoh: 1 Januari 2023):

```php
function formatTanggalIndo($tanggal) {
    $bulan = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];

    $date = date('d', strtotime($tanggal));
    $month = $bulan[date('m', strtotime($tanggal))];
    $year = date('Y', strtotime($tanggal));

    return $date . ' ' . $month . ' ' . $year;
}
```

#### `formatTanggalWaktuIndo()`
Formats a datetime string to Indonesian format with time (contoh: 1 Januari 2023 14:30):

```php
function formatTanggalWaktuIndo($tanggal) {
    $bulan = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];

    $date = date('d', strtotime($tanggal));
    $month = $bulan[date('m', strtotime($tanggal))];
    $year = date('Y', strtotime($tanggal));
    $time = date('H:i', strtotime($tanggal));

    return $date . ' ' . $month . ' ' . $year . ' ' . $time;
}
```

### ID Encryption and Decryption

#### `encryptId()`
Encrypts an ID using hashids for URL safety:

```php
use Vinkla\Hashids\Facades\Hashids;

function encryptId($id) {
    return Hashids::encode($id);
}
```

#### `decryptId()`
Decrypts an encrypted ID back to its original value:

```php
function decryptId($encryptedId) {
    $decoded = Hashids::decode($encryptedId);
    return $decoded ? $decoded[0] : null;
}
```

### Number Formatting

#### `formatRupiah()`
Formats a number as Indonesian Rupiah currency (contoh: Rp 1.000.000,00):

```php
function formatRupiah($angka) {
    $hasil = "Rp " . number_format($angka, 2, ',', '.');
    return $hasil;
}
```

### File and Image Utilities

#### `formatFileSize()`
Formats file size to human-readable format (contoh: 1.5 MB):

```php
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, 2) . ' ' . $units[$i];
}
```

### Validation Helpers

#### `formatValidationErrors()`
Formats validation errors for display in the UI:

```php
function formatValidationErrors($errors) {
    $formatted = [];
    
    foreach ($errors->messages() as $field => $messages) {
        $formatted[$field] = implode(', ', $messages);
    }
    
    return $formatted;
}
```

### Indonesia-Specific Helpers

#### `translateValidationMessages()`
Provides Indonesian translations for validation messages:

```php
function translateValidationMessages() {
    return [
        'required' => ':attribute wajib diisi.',
        'email' => ':attribute harus berupa email yang valid.',
        'unique' => ':attribute sudah digunakan.',
        'confirmed' => ':attribute konfirmasi tidak cocok.',
        // ... more translations
    ];
}
```

### Array and Collection Helpers

#### `arrayToSelectOptions()`
Converts an array to options for select dropdowns:

```php
function arrayToSelectOptions($array, $key = null, $value = null) {
    $options = [];
    
    foreach ($array as $k => $v) {
        $optionKey = $key ? $v[$key] : $k;
        $optionValue = $value ? $v[$value] : $v;
        
        $options[$optionKey] = $optionValue;
    }
    
    return $options;
}
```

### Text and String Helpers

#### `truncateText()`
Truncates text to a specified length:

```php
function truncateText($text, $length = 100, $append = '...') {
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length) . $append;
    }
    
    return $text;
}
```

#### `sanitizeInput()`
Sanitizes user input to prevent XSS:

```php
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
}
```

## Usage Examples

### Using Helper Functions in Controllers

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
        
        // Format dates using helper function
        $users->transform(function ($user) {
            $user->formatted_created_at = formatTanggalIndo($user->created_at);
            $user->formatted_updated_at = formatTanggalIndo($user->updated_at);
            return $user;
        });
        
        return view('pages.admin.users.index', compact('users'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
        ]);
        
        $user = User::create($request->validated());
        
        // Format as needed before redirecting
        return redirect()->route('admin.users.index')
            ->with('success', 'User ' . $user->name . ' created successfully on ' . formatTanggalIndo(now()));
    }
    
    public function show($encryptedId)
    {
        // Decrypt the ID using helper
        $id = decryptId($encryptedId);
        $user = User::findOrFail($id);
        
        // Format data using helpers
        $user->formatted_created_at = formatTanggalIndo($user->created_at);
        $user->formatted_updated_at = formatTanggalWaktuIndo($user->updated_at);
        
        return view('pages.admin.users.show', compact('user'));
    }
}
```

### Using Helper Functions in Views

```blade
{{-- In resources/views/pages/admin/users/show.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'User Detail')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">User Information</h5>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Name</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $user->formatted_created_at }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $user->formatted_updated_at }}</td>
                        </tr>
                    </table>
                </div>
                
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection
```

### Using Helper Functions in JavaScript

For client-side formatting, you can expose helper functions through a JavaScript variable:

```blade
{{-- In your layout --}}
<script>
    window.App = {
        formatRupiah: function(angka) {
            return 'Rp ' + parseFloat(angka).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
        },
        formatTanggalIndo: function(dateString) {
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }
    };
</script>
```

## Creating Custom Helper Functions

To add new helper functions, follow these guidelines:

### For Sys Helpers (Critical System Functions)

1. Add to `app/Helpers/Sys.php`
2. Discuss with the team before implementation
3. Include proper documentation

### For Global Helpers (General-Purpose Functions)

1. Add to `app/Helpers/Global.php`
2. Include docblock comments
3. Follow naming conventions

### Example of Adding a New Helper Function

```php
// In app/Helpers/Global.php
if (!function_exists('formatPhone')) {
    /**
     * Format phone number to Indonesian format
     * 
     * @param string $phone
     * @return string
     */
    function formatPhone($phone) {
        // Remove all non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Add Indonesian country code if not present
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Format the number: +62 812-3456-7890
        $formatted = substr($phone, 0, 3) . ' ' . 
                    substr($phone, 3, 3) . '-' . 
                    substr($phone, 6, 4) . '-' . 
                    substr($phone, 10, 4);
        
        return $formatted;
    }
}
```

## Best Practices

1. **Naming**: Use descriptive names that clearly indicate the function's purpose
2. **Documentation**: Include docblock comments for complex functions
3. **Validation**: Validate inputs in helper functions when appropriate
4. **Error Handling**: Implement proper error handling in helpers
5. **Performance**: Ensure helper functions are efficient and don't perform unnecessary operations
6. **Testing**: Write unit tests for utility functions to ensure reliability
7. **Security**: Sanitize and validate inputs to prevent security vulnerabilities
8. **Consistency**: Maintain consistent return formats and error handling across helpers

## Helper Function Categories

### Formatting Helpers
- Date/time formatting functions
- Currency formatting functions
- Number formatting functions
- Text formatting functions

### Security Helpers
- Input sanitization functions
- ID encryption/decryption functions
- Validation helper functions

### Data Processing Helpers
- Array manipulation functions
- String processing functions
- File processing functions

### System Helpers
- Path normalization functions
- System status functions
- Configuration helper functions