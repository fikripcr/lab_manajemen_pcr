# Frontend Guide

UI patterns, assets, dan JavaScript libraries.

## Vite Asset Bundling

### Entry Points

```javascript
// resources/js/admin.js - Admin context
import './global.js';
import './components/FormFeatures.js';

// resources/js/sys.js - Sys context  
import './sys/vendor/helpers.min.js';
import './sys/config.js';
import './global.js';
import './components/Notification.js';

// resources/js/global.js - Shared dependencies
import 'jquery';
import 'bootstrap';
import 'datatables.net-bs5';
import axios from 'axios';
import Swal from 'sweetalert2';
```

### CSS Entry Points

```css
/* resources/css/admin.css */
@import 'admin/vendor/core.min.css';
@import 'admin/vendor/theme-default.css';

/* resources/css/sys.css */
@import 'sys/vendor/core.min.css';
@import 'sys/vendor/theme-default.css';
```

### Build Commands

```bash
npm run dev    # Development (watch mode)
npm run build  # Production (minified)
```

## Layout Structure

### Multi-Context Layouts

```
resources/views/layouts/
├── admin/app.blade.php    # Admin area
├── sys/app.blade.php      # System management
├── auth/app.blade.php     # Authentication
└── guest/app.blade.php    # Public pages
```

### Using Layouts

```blade
<x-layouts.sys.app>
    <x-slot name="title">Page Title</x-slot>
    
    <!-- Page content here -->
</x-layouts.sys.app>
```

### Page-Specific Assets

```blade
@push('css')
    <link href="{{ asset('custom.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('custom.js') }}"></script>
    <script>
        // Inline JavaScript
    </script>
@endpush
```

## JavaScript Libraries

### Core Libraries (Global)

**jQuery** - DOM manipulation
```javascript
$('#element').on('click', function() { });
```

**Axios** - HTTP requests
```javascript
axios.get('/api/users')
    .then(response => console.log(response.data))
    .catch(error => console.error(error));
```

**SweetAlert2** - Alerts & confirmations
```javascript
Swal.fire({
    title: 'Are you sure?',
    icon: 'warning',
    showCancelButton: true
}).then((result) => {
    if (result.isConfirmed) {
        // Do something
    }
});
```

**DataTables** - Server-side tables
```javascript
$('#table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/api/users/paginate'
});
```

### Form Libraries (Lazy Loaded)

**Flatpickr** - Date picker
```javascript
await window.loadFormFeatures();
flatpickr('#date', { dateFormat: 'Y-m-d' });
```

**Choices.js** - Enhanced select
```javascript
await window.loadFormFeatures();
new Choices('#select', { searchEnabled: true });
```

**FilePond** - File uploads
```javascript
await window.loadFormFeatures();
FilePond.create(document.querySelector('#file'));
```

### Lazy Loading Pattern

```javascript
// In page script
document.addEventListener('DOMContentLoaded', async function() {
    // Load form features only when needed
    await window.loadFormFeatures();
    
    // Now initialize
    flatpickr('#date', { /* config */ });
});
```

## Blade Components

### DataTable Component

```blade
<x-datatable.datatable 
    :columns="[
        ['data' => 'name', 'title' => 'Name'],
        ['data' => 'email', 'title' => 'Email'],
        ['data' => 'action', 'title' => 'Action', 'orderable' => false]
    ]"
    :url="route('admin.users.paginate')"
/>
```

### Common Components

```blade
<!-- Search & Filter -->
<x-datatable.search-filter />
<x-datatable.page-length />

<!-- Modals -->
<x-modal id="myModal" title="Modal Title">
    Modal content
</x-modal>

<!-- Alerts -->
<x-alert type="success">Success message</x-alert>
```

## Common Patterns

### AJAX Form Submission

```javascript
$('#form').on('submit', function(e) {
    e.preventDefault();
    
    axios.post($(this).attr('action'), new FormData(this))
        .then(response => {
            Swal.fire('Success!', response.data.message, 'success');
            $('#table').DataTable().ajax.reload();
        })
        .catch(error => {
            Swal.fire('Error!', error.response.data.message, 'error');
        });
});
```

### Delete Confirmation

```javascript
$('.delete-btn').on('click', function(e) {
    e.preventDefault();
    const url = $(this).data('url');
    
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(url)
                .then(() => {
                    Swal.fire('Deleted!', 'Item deleted', 'success');
                    $('#table').DataTable().ajax.reload();
                });
        }
    });
});
```

### Loading State

```javascript
Swal.fire({
    title: 'Processing...',
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading()
});

// After operation completes
Swal.fire('Done!', 'Operation completed', 'success');
```

## Responsive Design

### Bootstrap Grid

```blade
<div class="row">
    <div class="col-12 col-md-6 col-lg-4">
        <!-- Full width mobile, half tablet, third desktop -->
    </div>
</div>
```

### Breakpoints
- `xs`: <576px
- `sm`: ≥576px  
- `md`: ≥768px
- `lg`: ≥992px
- `xl`: ≥1200px

## Asset Organization

```
public/
├── build/              # Vite compiled assets (auto-generated)
└── images/             # Static images

resources/
├── js/
│   ├── admin.js       # Admin entry
│   ├── sys.js         # Sys entry
│   ├── global.js      # Shared
│   ├── api.js         # API helpers
│   └── components/    # Reusable components
│       ├── Notification.js
│       ├── FormFeatures.js
│       └── CustomDataTables.js
└── css/
    ├── admin.css      # Admin styles
    └── sys.css        # Sys styles
```

## Quick Reference

**Add new library:**
1. `npm install library-name`
2. Import in appropriate entry point (admin.js/sys.js/global.js)
3. `npm run build`

**Page-specific JS:**
```blade
@push('scripts')
    <script>
        // Your code
    </script>
@endpush
```

**Common Issues:**
- Assets not loading → `npm run build` + hard refresh
- Library undefined → Check if imported in entry point
- Vite error → Clear cache: `rm -rf node_modules/.vite`
