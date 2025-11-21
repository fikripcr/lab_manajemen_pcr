# User Interface & Frontend Features

## Overview

The system implements a comprehensive frontend architecture using Bootstrap 5, custom Blade components, and modern JavaScript practices. The design is responsive and follows consistent patterns across all modules.

## Layout Structure

### Main Layout Files

The system uses a multi-layout approach:

- `resources/views/layouts/admin/app.blade.php` - Admin area layout
- `resources/views/layouts/auth/app.blade.php` - Authentication area layout
- `resources/views/layouts/guest/app.blade.php` - Public area layout

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
    ['data' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]
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
- `public/assets-guest/` - Public UI assets
- `public/images/` - Images and media assets

### Asset Structure

```
public/
├── assets-admin/
│   ├── css/
│   │   ├── core.css
│   │   ├── theme-default.css
│   │   └── custom.css
│   ├── js/
│   │   ├── core.js
│   │   ├── template.js
│   │   ├── app.js
│   │   └── modules/
│   │       ├── datatable.js
│   │       ├── search.js
│   │       └── global-search.js
│   ├── libs/
│   │   └── (third-party libraries)
│   └── img/
│       └── (images and icons)
└── assets-guest/
    └── (similar structure for guest assets)
```

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

### Accessibility

1. **Semantic HTML**: Use appropriate HTML elements
2. **ARIA Attributes**: Implement ARIA attributes where needed
3. **Keyboard Navigation**: Ensure all functionality is accessible via keyboard
4. **Color Contrast**: Maintain proper contrast ratios

## Custom Components

### DataTable Component with Search and Filters

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
                ['data' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]
            ]">
                {{ route('admin.users.paginate') }}
            </x-datatable.datatable>
        </div>
    </div>
</div>
```