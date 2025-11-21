# Helper Functions & Utilities

## Overview

The system includes two main categories of helper functions: Sys helpers for core system functions and Global helpers for general-purpose utilities. These functions are designed to streamline development and maintain consistency across the application.

## Sys Helper Functions

Sys helpers (located in `app/Helpers/Sys.php`) contain core system functions that should not be modified without team discussion and review. These functions handle critical system operations.

## Global Helper Functions

Global helpers (located in `app/Helpers/Global.php`) contain general-purpose functions that are safe to modify and extend as needed.

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
6. **Security**: Sanitize and validate inputs to prevent security vulnerabilities
7. **Consistency**: Maintain consistent return formats and error handling across helpers
