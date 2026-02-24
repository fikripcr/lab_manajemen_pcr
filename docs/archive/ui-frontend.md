# User Interface & Frontend Features

## Overview

The system implements a comprehensive frontend architecture using Bootstrap 5, custom Blade components, and modern JavaScript practices. The design is responsive and follows consistent patterns across all modules.

## Layout Structure

### Main Layout Files

The system uses a multi-layout approach:

- `resources/views/layouts/admin/app.blade.php` - Admin area layout
- `resources/views/layouts/auth/app.blade.php` - Authentication area layout
- `resources/views/layouts/sys/app.blade.php` - System Management area layout
- `resources/views/layouts/guest/app.blade.php` - Public area layout

Each layout includes separate CSS and JS components to maintain modularity:

- Admin layout: Includes `layouts/admin/css.blade.php` and `layouts/admin/js.blade.php`
- Auth layout: Includes `layouts/auth/css.blade.php` and `layouts/auth/js.blade.php`
- Sys layout: Includes `layouts/sys/css.blade.php` and `layouts/sys/js.blade.php`
- Guest layout: Includes `layouts/guest/css.blade.php` and `layouts/guest/js.blade.php`

### Layout Components

#### Admin Layout Structure

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets-admin/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-admin/css/theme-default.css') }}">
    
    <!-- Page-specific CSS -->
    @stack('css')
</head>
<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('partials.admin.sidebar')
            <!-- / Menu -->

            <!-- Layout page -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('partials.admin.navbar')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-fluid flex-grow-1">
                        {{ $slot }}
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('partials.admin.footer')
                    <!-- / Footer -->
                </div>
                <!-- / Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets-admin/js/core.js') }}"></script>
    <script src="{{ asset('assets-admin/js/template.js') }}"></script>
    
    <!-- Page-specific JavaScript -->
    @stack('scripts')
</body>
</html>
```

## Blade Components

The system uses reusable Blade components for consistency and maintainability.

### DataTable Component

The `x-datatable.datatable` component provides a standardized way to create data tables with server-side processing:

```blade
<x-datatable.datatable :columns="[
    ['data' => 'name', 'title' => 'Name'],
    ['data' => 'email', 'title' => 'Email'],
    ['data' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
]">
    {{ route('admin.users.paginate') }}
</x-datatable.datatable>
```

### Search and Filter Components

- `x-datatable.search-filter` - Provides search functionality
- `x-datatable.page-length` - Controls page length for DataTables

### Form Components

Commonly used form components:
- `x-input` - Standard input fields
- `x-textarea` - Text areas
- `x-select` - Select dropdowns
- `x-checkbox` - Checkbox inputs
- `x-radio` - Radio button inputs

### Modal Components

Modal components for consistent user interactions:
- `x-modal` - Basic modal structure
- `x-form-modal` - Modal with form capabilities

## Asset Management

### Local Libraries

All external libraries are stored locally in the `public/` directory rather than using CDNs for better performance and offline support:

- `public/assets-admin/` - Administrative UI assets
- `public/assets-auth/` - Authentication UI assets
- `public/assets-sys/` - System Management UI assets
- `public/assets-guest/` - Public UI assets
- `public/assets-global/` - Shared assets used across all templates
- `public/images/` - Images and media assets

> **Note:** Each of these asset folders must exist in the public directory, even if empty, to maintain the proper separation of concerns between different sections of the application.

### Asset Structure

```
public/
├── assets-admin/
│   ├── css/          # Admin-specific styles
│   ├── js/           # Admin-specific JavaScript
│   ├── libs/         # Admin-specific libraries
│   ├── templates/    # Admin-specific HTML templates
│   ├── vendor/       # Admin-specific vendor assets
│   └── img/          # Admin-specific images
├── assets-auth/
│   ├── css/          # Auth-specific styles
│   ├── js/           # Auth-specific JavaScript
│   ├── libs/         # Auth-specific libraries
│   ├── templates/    # Auth-specific HTML templates
│   ├── vendor/       # Auth-specific vendor assets
│   └── img/          # Auth-specific images
├── assets-sys/
│   ├── css/          # System-specific styles
│   ├── js/           # System-specific JavaScript
│   ├── libs/         # System-specific libraries
│   ├── templates/    # System-specific HTML templates
│   ├── vendor/       # System-specific vendor assets
│   └── img/          # System-specific images
├── assets-guest/
│   └── (similar structure for guest assets)
└── assets-global/
    ├── css/          # Global styles
    ├── js/           # Global JavaScript
    ├── libs/         # Shared libraries (DataTables, SweetAlert2, etc.)
    ├── img/          # Shared images
    └── (other global assets)
```

### Global Assets (assets-global/)

The global assets folder contains resources shared across multiple templates to reduce duplication:

- `css/` - Global stylesheets
- `js/` - Global JavaScript files (including `js/custom/` for custom shared scripts)
- `libs/` - Shared third-party libraries like DataTables and SweetAlert2
- `img/` - Common images used across different layouts including:
  - `no-avatar.png` - Default avatar image for users without uploaded avatars
  - `no-image.jpg` - Default image for content without uploaded images
  - `logo-apps.png` - Main logo image used across all layouts
  - Common element images (1.jpg, 2.jpg, etc.) - Standard images used in UI components
  - Avatar placeholder images (1.png, 5.png, etc.) - Default avatar options

### Asset Folder Organization

#### Purpose of Each Asset Folder

1. **assets-admin/** - Contains assets specifically for the main administrative interface
   - Full template with sidebar, navigation, and admin functionality
   - All necessary JavaScript for admin features
   - Admin-specific styling

2. **assets-auth/** - Contains assets specifically for authentication pages (login, register, etc.)
   - Minimal assets required for authentication flows
   - Page-specific styles for auth forms
   - Essential JavaScript only (jQuery and Bootstrap)

3. **assets-sys/** - Contains assets specifically for system management pages
   - Full template with system-focused navigation
   - System-specific JavaScript functionality
   - System-focused styling

4. **assets-guest/** - Contains assets for public/guest facing pages
   - Public-facing website template
   - Guest-specific styling and functionality

### TinyMCE Editor

The system includes TinyMCE as a rich text editor for content management fields:

- Located at `public/assets-admin/js/tinymce/`
- Configured with Indonesian language support
- Includes custom image upload functionality

## Responsive Design

The system follows Bootstrap 5's responsive grid system and utility classes.

### Breakpoints

- `xs`: <576px
- `sm`: ≥576px
- `md`: ≥768px
- `lg`: ≥992px
- `xl`: ≥1200px
- `xxl`: ≥1400px

### Responsive Utilities

Common responsive utility classes:
- `.d-none .d-sm-block` - Hide on extra small screens, show on small and larger
- `.col-12 .col-md-6` - Full width on mobile, half width on medium screens and larger

## JavaScript Features

### Global Search

The system includes a powerful global search functionality accessible from the search icon in the header.

#### Features
- Multi-model search across multiple data types
- Real-time results without page refresh
- Smart filtering and relevance ranking
- Modal interface for improved UX

#### Implementation

```javascript
// In public/assets-admin/js/modules/global-search.js
function initGlobalSearch() {
    const searchInput = document.getElementById('global-search-input');
    const searchResults = document.getElementById('global-search-results');
    
    searchInput.addEventListener('input', debounce(function() {
        const query = this.value.trim();
        
        if (query.length >= 2) {
            fetch('/admin/search?q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data.results);
                });
        } else {
            clearSearchResults();
        }
    }, 300));
}
```

### DataTable State Persistence

The system includes state persistence for DataTables that preserves user preferences between page loads:

#### Features
- Search term preservation
- Filter state maintenance
- Page length preservation
- Pagination state maintenance
- Unique storage keys for each table

#### Implementation

```javascript
// In public/assets-admin/js/modules/datatable.js
const initializeDataTable = (tableId, url, options = {}) => {
    const table = $(tableId).DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,  // Enable state saving for standard features
        ajax: url,
        ...options
    });

    // Additional functionality for custom filter preservation
    // handled through localStorage functions
};
```

### SweetAlert Integration

The system uses SweetAlert for enhanced user experience with beautiful pop-up confirmations and notifications:

#### Features
- Confirmation dialogs for critical operations
- Loading indicators during operations
- Success/error feedback messages
- Automatic transitions from loading to success/error states

#### Usage Examples

```javascript
// Confirmation dialog
Swal.fire({
    title: 'Are you sure?',
    text: "This action cannot be undone!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, proceed!'
}).then((result) => {
    if (result.isConfirmed) {
        // Perform the action
    }
});

// Loading indicator
Swal.fire({
    title: 'Processing...',
    text: 'Please wait while we process your request',
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    didOpen: () => {
        Swal.showLoading();
    }
});

// Success feedback
Swal.fire({
    icon: 'success',
    title: 'Operation Completed',
    text: 'Your request was processed successfully!',
    timer: 2000,
    timerProgressBar: true
});
```

## Page-specific CSS and JavaScript

### Using @stack and @push

Laravel provides a powerful system for including CSS and JavaScript files that are specific to individual pages using `@stack` and `@push` directives.

#### In Main Layout

```blade
<!-- In the <head> section for CSS -->
@stack('css')

<!-- Before closing </body> tag for JavaScript -->
@stack('scripts')
```

#### In Page Views

```blade
@push('css')
    <link rel="stylesheet" href="{{ asset('assets-admin/css/specific-page.css') }}">
    <style>
        /* Inline CSS for this page only */
        .specific-page-element {
            background-color: #f0f0f0;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets-admin/js/specific-page.js') }}"></script>
    <script>
        // JavaScript code specific to this page
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize page-specific functionality
            initializeSpecificPageFeature();
        });
    </script>
@endpush
```

### Common Use Cases

- **Rich Text Editors**: TinyMCE or other editor-specific CSS and JS
- **Charts**: Chart-specific libraries and initialization code
- **Date Pickers**: Date picker CSS and JavaScript
- **File Uploads**: File upload component CSS and JS
- **Custom Components**: Page-specific interactive elements

## UI Best Practices

### Consistent Design System

1. **Color Palette**: Use Bootstrap's predefined color system consistently
2. **Typography**: Maintain consistent font sizes and weights
3. **Spacing**: Use Bootstrap's spacing utilities for consistent padding/margin
4. **Icons**: Use consistent icon set (BoxIcons in this implementation)

### Form Design

1. **Label-Input Pairing**: Always pair labels with form inputs
2. **Validation Feedback**: Show validation errors clearly below the input
3. **Button Positioning**: Consistent placement of action buttons
4. **Responsive Forms**: Ensure forms work well on all device sizes

## Error Pages

The system includes custom error page layouts that maintain consistency with the admin template design using the SNEAT framework.

### Error Layout Structure

The error pages use the SNEAT template structure with admin assets:

```blade
<!DOCTYPE html>

<html
  lang="en"
  class="light-style"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets-admin') }}/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Error - @yield('title', 'Page Not Found')</title>

    <meta name="description" content="@yield('description', 'An error occurred on our server')" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets-admin/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets-admin/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/css/pages/page-misc.css') }}" />
    <!-- Helpers -->
    <script src="{{ asset('assets-admin/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets-admin/js/config.js') }}"></script>
  </head>

  <body>
    <!-- Content -->

    <!-- Error -->
    <div class="container-xxl container-p-y">
      <div class="misc-wrapper text-center">
        <div class="error mx-auto" data-text="@yield('error-code', '404')">
          <p class="m-b-10" style="font-size: 8rem; font-weight: bold; color: #636363;">@yield('error-code', '404')</p>
        </div>
        <h2 class="mb-2 mx-2">@yield('title', 'Error')</h2>
        <p class="mb-4 mx-2">@yield('message', 'An unexpected error occurred.')</p>
        <div class="mt-4">
          <a href="javascript:history.back()" class="btn btn-primary">← Go Back</a>
          <a href="{{ route('home') ?? url('/') }}" class="btn btn-secondary ms-2">Home</a>
        </div>
      </div>
    </div>
    <!-- /Error -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets-admin/vendor/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets-admin/vendor/libs/popper/popper.min.js') }}"></script>
    <script src="{{ asset('assets-admin/vendor/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets-admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    <script src="{{ asset('assets-admin/vendor/js/menu.min.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('assets-admin/js/main.js') }}"></script>

    <!-- Page JS -->
  </body>
</html>
```

### Error Page Features

1. **Consistent Design** - Uses the same SNEAT template as the admin area
2. **Full Template Integration** - Maintains visual consistency with the application
3. **Responsive Layout** - Works well on all device sizes
4. **Navigation Options** - Provides both "Go Back" and "Home" options
5. **Template Integration** - Uses Laravel's yield sections for dynamic content

### Customization

Individual error pages can extend the layout and customize the content:

```blade
@extends('errors.error-layout')

@section('title', 'Page Not Found')
@section('error-code', '404')
@section('message', 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.')
```

## HTTP Requests with Axios

### Migration from AJAX/fetch API to Axios

As of November 2025, all AJAX and fetch API implementations have been migrated to use Axios for better HTTP request handling. This change standardizes how HTTP requests are made across the application.

#### Key Benefits of Axios
- **Request Interception**: Ability to intercept requests and responses
- **Automatic JSON parsing**: No need for manual `response.json()` calls
- **Error handling**: Better error response handling
- **Request/response transformation**: Automatic transformation of request/response data
- **Promise-based**: Consistent with modern JavaScript practices

#### Migration Examples

**Before (fetch API):**
```javascript
fetch('/api/some-endpoint', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(data)
})
.then(response => response.json())
.then(data => {
    // Handle success
})
.catch(error => {
    // Handle error
});
```

**After (Axios):**
```javascript
axios.post('/api/some-endpoint', data)
    .then(function(response) {
        // Handle success (no need for response.json())
    })
    .catch(function(error) {
        // Handle error
    });
```

#### Configuration

Axios is configured in `resources/js/sys.js` with default settings:
```javascript
import axios from 'axios';
window.axios = axios;
window.axios.defaults.withCredentials = true; // for Sanctum/session
window.axios.defaults.withXSRFToken = true;
```

#### Best Practices

1. **Always use Axios for HTTP requests** - Do not revert to fetch API or XMLHttpRequest
2. **Handle errors consistently** - Check `error.response` for server errors
3. **Use appropriate HTTP methods** - Use `axios.get`, `axios.post`, etc. methods appropriately
4. **Include CSRF token** - For POST/PUT/PATCH/DELETE requests, ensure CSRF token is included (already configured in defaults)

#### Exceptions

DataTable functionality using `ajax` option should remain unchanged as these are handled by the DataTables library directly and not part of this migration.

### Custom Components

#### DataTable Component with Search and Filters

```blade
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Users List</h5>
    </div>
    <div class="card-datatable table-responsive">
        <div class="dataTables_wrapper dt-bootstrap5 no-footer">
            <div class="dataTables_toolbar mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <x-datatable.search-filter />
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <x-datatable.page-length />
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary ms-2">Add User</a>
                    </div>
                </div>
            </div>

            <x-datatable.datatable :columns="[
                ['data' => 'name', 'title' => 'Name'],
                ['data' => 'email', 'title' => 'Email'],
                ['data' => 'role', 'title' => 'Role'],
                ['data' => 'created_at', 'title' => 'Created'],
                ['data' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
            ]">
                {{ route('admin.users.paginate') }}
            </x-datatable.datatable>
        </div>
    </div>
</div>
```
