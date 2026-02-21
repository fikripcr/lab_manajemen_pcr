# Project Theme Customization Guide (Tabler UI)

**Last Updated:** Februari 2026
**Tabler Version:** 1.4.0
**Bootstrap Version:** 5.3.8

Dokumen ini menjelaskan arsitektur, komponen, dan cara kerja kustomisasi tema Tabler UI yang digunakan sebagai base frontend pada proyek ini.

---

## Table of Contents

1. [Arsitektur Frontend](#1-arsitektur-frontend)
2. [Theme Customization System](#2-theme-customization-system)
3. [Blade Components](#3-blade-components)
4. [AJAX Handlers](#4-ajax-handlers)
5. [JavaScript Libraries](#5-javascript-libraries)
6. [Global Search](#6-global-search)
7. [Notification System](#7-notification-system)
8. [Layout Structure](#8-layout-structure)
9. [Dark Mode](#9-dark-mode)
10. [Panduan Pengembangan](#10-panduan-pengembangan)

---

## 1. Arsitektur Frontend

### A. Entry Point & Bundle Structure

```
resources/js/tabler.js  → Main entry point
├── Core Libraries
│   ├── jQuery 3.7.1 (DataTables dependency)
│   ├── Axios (HTTP requests)
│   ├── Bootstrap 5.3.8 (UI framework)
│   ├── SortableJS 1.15.6 (Drag & drop)
│   └── SweetAlert2 11.26.3 (Alerts)
│
├── Auto-loaded Modules
│   ├── ThemeTabler.js (Theme management)
│   ├── CustomSweetAlerts.js (Alert utilities)
│   ├── Notification.js (Notification system)
│   └── FormHandlerAjax.js (AJAX handlers)
│
└── Lazy-loaded Modules
    ├── CustomDataTables.js (Server-side tables)
    ├── GlobalSearch.js (Global search)
    ├── Flatpickr (Date picker)
    ├── Select2 (Enhanced select)
    ├── FilePond (File upload)
    └── HugeRTE (Rich text editor)
```

### B. Build System

**Vite Configuration:**
```javascript
// vite.config.js
export default {
    plugins: [
        laravel({
            input: [
                'resources/js/tabler.js',
                'resources/css/tabler.css'
            ],
            refresh: true,
        }),
    ],
};
```

**Commands:**
```bash
npm run dev   # Development dengan HMR (Hot Module Replacement)
npm run build # Production build (minified)
```

### C. File Structure

```
resources/
├── js/
│   ├── tabler.js                    # Main entry point
│   └── assets/
│       └── tabler/
│           ├── js/
│           │   ├── ThemeTabler.js        # Theme management
│           │   ├── FormHandlerAjax.js    # AJAX form handlers
│           │   ├── CustomDataTables.js   # DataTables wrapper
│           │   ├── CustomSweetAlerts.js  # Alert utilities
│           │   ├── Notification.js       # Notification system
│           │   └── GlobalSearch.js       # Global search
│           └── css/
├── css/
│   └── tabler.css                   # Main stylesheet
└── views/
    ├── layouts/
    │   └── tabler/
    │       ├── app.blade.php        # Main layout
    │       ├── header.blade.php     # Top navigation
    │       ├── sidebar.blade.php    # Sidebar menu
    │       ├── footer.blade.php     # Footer
    │       └── empty.blade.php      # Blank layout
    └── components/
        └── tabler/                  # Blade components
            ├── button.blade.php
            ├── form-input.blade.php
            ├── form-modal.blade.php
            ├── datatable.blade.php
            └── ...
```

---

## 2. Theme Customization System

### A. Overview

ThemeTabler menggunakan pattern **"Unified State"** dengan server sebagai **Single Source of Truth**. Tidak ada dependency pada localStorage untuk state utama.

### B. Class Structure

```javascript
// resources/assets/tabler/js/ThemeTabler.js
class ThemeTabler {
    constructor(mode = 'tabler')  // 'tabler' atau 'auth'
    
    // Public Methods
    initSettingsPanel()           // Initialize settings canvas
    refresh()                     // Sync UI dengan server state
    handleApply()                 // Save ke server via AJAX
    
    // Private Methods
    _updateStructure(state)       // Update structural classes
    _updateVisibility(state)      // Show/hide UI elements
    _updateStyles(state)          // Update CSS variables
}
```

### C. Theme Settings Panel

**Location:** `resources/views/components/tabler/theme-settings.blade.php`

**Settings yang Dapat Dikustomisasi:**

| Setting | Input Type | Options | CSS Variable / Attribute |
|---------|------------|---------|--------------------------|
| **Layout** | Radio | `vertical`, `horizontal`, `condensed`, `boxed` | `body.layout-{type}` |
| **Container Width** | Radio | `fluid`, `standard`, `narrow`, `ultra-wide`, `boxed` | `data-container-width` |
| **Theme Mode** | Toggle | `light`, `dark` | `data-bs-theme` |
| **Font Family** | Select | `Inter`, `Roboto`, `Poppins`, `Public Sans`, `Nunito` | `--tblr-font-sans-serif` |
| **Theme Base** | Select | `auto`, `light`, `dark` | `data-bs-theme-base` |
| **Header Sticky** | Radio | `true`, `false`, `hidden` | `sticky-top` class |
| **Body Background** | Color Picker | Any hex/rgba | `--tblr-body-bg` |
| **Sidebar Background** | Color Picker | Any hex/rgba | `--tblr-sidebar-bg` |
| **Header Top Background** | Color Picker | Any hex/rgba | `--tblr-header-top-bg` |
| **Header Overlap Background** | Color Picker | Any hex/rgba | `--tblr-header-overlap-bg` |
| **Boxed Background** | Color Picker | Any hex/rgba | `--tblr-boxed-bg` |
| **Primary Color** | Color Picker | Any hex/rgba | `--tblr-primary` |
| **Border Radius** | Select | `0.25`, `0.5`, `1`, `1.5` (rem) | `--tblr-border-radius` |
| **Card Style** | Select | `default`, `shadow`, `outline` | `data-bs-card-style` |

### D. Live Preview Mechanism

```javascript
// Setiap perubahan input memicu refresh()
refresh() {
    // 1. Read state dari DOM inputs
    const state = {
        layout: getVal('layout'),
        width: getVal('container-width'),
        theme: document.documentElement.getAttribute('data-bs-theme'),
        font: getVal('theme-font'),
        sticky: getVal('theme-header-sticky')
    };
    
    // 2. Update structural classes
    this._updateStructure(state);
    
    // 3. Update visibility (show/hide presets)
    this._updateVisibility(state);
    
    // 4. Update CSS variables & attributes
    this._updateStyles(state);
}
```

### E. CSS Variables Manipulation

```css
/* Body & Layout */
--tblr-body-bg              /* Background color body */
--tblr-boxed-bg             /* Background untuk boxed layout */

/* Navigation */
--tblr-sidebar-bg           /* Sidebar background */
--tblr-sidebar-text         /* Sidebar text color (auto-contrast) */
--tblr-sidebar-text-muted   /* Sidebar muted text (auto-contrast) */
--tblr-header-top-bg        /* Header top background */
--tblr-header-top-text      /* Header text (auto-contrast) */
--tblr-header-overlap-bg    /* Condensed header overlap background */

/* Theme */
--tblr-primary              /* Primary brand color */
--tblr-border-radius        /* Base border radius */
--tblr-border-radius-sm     /* Small radius (0.75x) */
--tblr-border-radius-lg     /* Large radius (1.25x) */
--tblr-border-radius-pill   /* Pill radius (100rem) */

/* Typography */
--tblr-font-sans-serif      /* Font family stack */
```

### F. Auto-Contrast System

ThemeTabler otomatis menghitung kontras teks berdasarkan warna background:

```javascript
_updateContrast(root, color, textVar, mutedVar) {
    const isDark = this._getLuminance(color) < 0.6;
    root.style.setProperty(textVar, isDark ? '#ffffff' : '#1e293b');
    if (mutedVar) {
        root.style.setProperty(mutedVar, isDark ? 'rgba(255,255,255,0.7)' : '#6c757d');
    }
}
```

### G. Server Integration

**Endpoint:** `POST /theme/save`

**Controller:** `ThemeTablerController`

**Request Format:**
```json
{
    "_mode": "tabler",
    "layout": "vertical",
    "container-width": "standard",
    "theme": "dark",
    "theme-font": "inter",
    "theme-bg": "#f4f6fa",
    "theme-sidebar-bg": "#1e2937",
    "theme-primary": "#206bc4",
    "theme-radius": "0.5"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Theme settings saved successfully"
}
```

---

## 3. Blade Components

### A. Form Components

#### `<x-tabler.form-input>`

**File:** `resources/views/components/tabler/form-input.blade.php`

**Usage:**
```blade
<!-- Text Input -->
<x-tabler.form-input 
    name="name" 
    label="Nama Lengkap" 
    required="true" 
    placeholder="Masukkan nama"
/>

<!-- Date Picker (Flatpickr) -->
<x-tabler.form-input 
    type="date" 
    name="tanggal" 
    label="Pilih Tanggal"
    help="Format: DD MMMM YYYY"
/>

<!-- Time Picker -->
<x-tabler.form-input 
    type="time" 
    name="jam" 
    label="Pilih Waktu"
/>

<!-- DateTime Picker -->
<x-tabler.form-input 
    type="datetime" 
    name="created_at" 
    label="Tanggal & Waktu"
/>

<!-- Date Range -->
<x-tabler.form-input 
    type="range" 
    name="periode" 
    label="Periode"
/>

<!-- Multiple Dates -->
<x-tabler.form-input 
    type="multiple" 
    name="tanggal_libur" 
    label="Tanggal Libur"
/>

<!-- Password dengan Eye Toggle -->
<x-tabler.form-input 
    type="password" 
    name="password" 
    label="Kata Sandi"
/>

<!-- File Upload dengan Preview (FilePond) -->
<x-tabler.form-input 
    type="file" 
    name="avatar" 
    label="Foto Profil"
    accept="image/*"
/>

<!-- Number Input -->
<x-tabler.form-input 
    type="number" 
    name="quantity" 
    label="Jumlah"
    min="0"
    max="100"
/>
```

**Auto-initialization Classes:**
- `.flatpickr-input` → Date/time picker
- `.filepond-input` → File upload dengan preview
- `.select2-offline` → Select2 dropdown

**Attributes:**
| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `name` | string | required | Input name |
| `label` | string | null | Input label |
| `type` | string | `text` | Input type (text, date, password, file, dll) |
| `required` | bool | false | Required validation |
| `value` | mixed | null | Default value |
| `help` | string | null | Help text below input |
| `placeholder` | string | null | Placeholder text |
| `readonly` | bool | false | Readonly state |
| `disabled` | bool | false | Disabled state |
| `multiple` | bool | false | Multiple selection (file/dates) |

---

#### `<x-tabler.form-select>`

**Usage:**
```blade
<!-- Offline Select (Select2) -->
<x-tabler.form-select 
    name="role_id" 
    label="Pilih Role"
    :options="$roles"
    option-key="id"
    option-value="name"
    placeholder="Pilih role..."
/>

<!-- Manual Options -->
<select class="form-select select2-offline" name="status">
    <option value="">Pilih Status</option>
    <option value="active">Active</option>
    <option value="inactive">Inactive</option>
</select>
```

---

#### `<x-tabler.form-textarea>`

**Usage:**
```blade
<!-- Standard Textarea -->
<x-tabler.form-textarea 
    name="description" 
    label="Deskripsi"
    rows="5"
/>

<!-- Rich Text Editor (HugeRTE) -->
<x-tabler.form-textarea 
    type="editor" 
    name="content" 
    label="Konten"
    :config="['height' => 400]"
/>
```

---

#### `<x-tabler.form-checkbox>` & `<x-tabler.form-radio>`

**Usage:**
```blade
<!-- Single Checkbox -->
<x-tabler.form-checkbox 
    name="is_active" 
    label="Active"
    :checked="true"
/>

<!-- Checkbox Group -->
<div class="mb-3">
    <label class="form-label">Permissions</label>
    <x-tabler.form-checkbox 
        name="permissions[]" 
        value="view" 
        label="View"
    />
    <x-tabler.form-checkbox 
        name="permissions[]" 
        value="edit" 
        label="Edit"
    />
</div>

<!-- Radio Group -->
<div class="mb-3">
    <label class="form-label">Status</label>
    <x-tabler.form-radio 
        name="status" 
        value="active" 
        label="Active"
    />
    <x-tabler.form-radio 
        name="status" 
        value="inactive" 
        label="Inactive"
    />
</div>
```

---

### B. Modal Components

#### `<x-tabler.form-modal>`

**File:** `resources/views/components/tabler/form-modal.blade.php`

**Usage:**
```blade
<!-- Basic Modal -->
<x-tabler.form-modal
    id="formModal"
    title="Tambah User"
    route="{{ route('users.store') }}"
    size="modal-lg"
>
    <x-tabler.form-input name="name" label="Nama" required="true" />
    <x-tabler.form-input name="email" label="Email" type="email" required="true" />
</x-tabler.form-modal>

<!-- Edit Modal dengan Method PUT -->
<x-tabler.form-modal
    id="editModal"
    title="Edit User"
    route="{{ route('users.update', $user->encrypted_id) }}"
    method="PUT"
>
    <x-tabler.form-input name="name" label="Nama" :value="$user->name" required="true" />
</x-tabler.form-modal>

<!-- Modal dengan Custom Submit -->
<x-tabler.form-modal
    id="importModal"
    title="Import Data"
    route="{{ route('users.import') }}"
    submit-text="Import"
    submit-icon="ti-file-import"
>
    <x-tabler.form-input type="file" name="file" accept=".xlsx,.csv" required="true" />
</x-tabler.form-modal>
```

**Attributes:**
| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `id` | string | required | Modal ID |
| `title` | string | `Modal Title` | Modal title |
| `route` | string | `#` | Form action URL |
| `method` | string | `POST` | HTTP method |
| `size` | string | null | Modal size (`modal-sm`, `modal-lg`, `modal-xl`) |
| `submit-text` | string | `Simpan` | Submit button text |
| `submit-icon` | string | `ti-device-floppy` | Submit button icon |

---

### C. Button Components

#### `<x-tabler.button>`

**File:** `resources/views/components/tabler/button.blade.php`

**Usage:**
```blade
<!-- Submit Button -->
<x-tabler.button type="submit" />

<!-- Create Button (with AJAX modal) -->
<x-tabler.button 
    type="create" 
    modal-url="{{ route('users.create') }}"
    modal-title="Tambah User"
/>

<!-- Back Button (auto history.back) -->
<x-tabler.button type="back" />

<!-- Delete Button -->
<x-tabler.button type="delete" />

<!-- Export Button -->
<x-tabler.button type="export" href="{{ route('users.export') }}" />

<!-- Icon Only Button -->
<x-tabler.button type="edit" :icon-only="true" href="{{ route('users.edit', $user->id) }}" />

<!-- Custom Text -->
<x-tabler.button type="submit" text="Save Changes" />

<!-- With Slot Content -->
<x-tabler.button type="submit">
    <span class="d-none d-sm-inline">Save & Continue</span>
</x-tabler.button>
```

**Button Types:**
| Type | Color | Icon | Default Text |
|------|-------|------|--------------|
| `create` | Primary | `ti-plus` | Tambah |
| `submit` | Primary | `ti-device-floppy` | Simpan |
| `back` | Outline Secondary | `ti-arrow-left` | Kembali |
| `cancel` | Outline Secondary | `ti-x` | Batal |
| `delete` | Danger | `ti-trash` | Hapus |
| `edit` | Primary | `ti-edit` | Ubah |
| `import` | Success | `ti-file-import` | Impor |
| `export` | Success | `ti-file-export` | Ekspor |
| `reset` | Secondary | `ti-refresh` | Reset |
| `warning` | Warning | `ti-alert-triangle` | Peringatan |
| `success` | Success | `ti-check` | Berhasil |

---

### D. DataTable Components

#### `<x-tabler.datatable>`

**File:** `resources/views/components/tabler/datatable.blade.php`

**Usage:**
```blade
<!-- Basic DataTable -->
<x-tabler.datatable
    id="usersTable"
    :route="route('users.paginate')"
    :columns="[
        ['data' => 'id', 'title' => 'ID'],
        ['data' => 'name', 'title' => 'Nama'],
        ['data' => 'email', 'title' => 'Email'],
        ['data' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
    ]"
/>

<!-- DataTable dengan Checkbox -->
<x-tabler.datatable
    id="usersTable"
    :route="route('users.paginate')"
    :checkbox="true"
    :checkbox-key="'encrypted_id'"
    :columns="[
        ['data' => 'checkbox', 'title' => ''],
        ['data' => 'name', 'title' => 'Nama'],
        ['data' => 'action', 'title' => 'Aksi', 'orderable' => false]
    ]"
/>

<!-- DataTable dengan Filter -->
<x-tabler.datatable
    id="usersTable"
    :route="route('users.paginate')"
    :search="true"
    :page-length="true"
    :columns="[...]"
>
    <x-slot name="filter">
        <form id="usersTable-filter" class="mb-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </form>
    </x-slot>
</x-tabler.datatable>
```

**Attributes:**
| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `id` | string | required | Table ID |
| `route` | string | required | Server-side pagination route |
| `columns` | array | required | Column definitions |
| `search` | bool | `true` | Enable search input |
| `pageLength` | bool | `true` | Enable page length selector |
| `checkbox` | bool | `false` | Enable row checkbox |
| `checkboxKey` | string | `id` | Checkbox value key |

---

#### `<x-tabler.datatables-actions>`

**File:** `resources/views/components/tabler/datatables-actions.blade.php`

**Usage:**
```blade
<!-- Standard Actions -->
<x-tabler.datatables-actions
    :edit-url="route('users.edit', $user->encrypted_id)"
    :edit-modal="true"
    :delete-url="route('users.destroy', $user->encrypted_id)"
/>

<!-- Custom Actions -->
<x-tabler.datatables-actions
    :edit-url="route('users.edit', $user->encrypted_id)"
    :delete-url="route('users.destroy', $user->encrypted_id)"
>
    <x-slot name="extra">
        <a href="{{ route('users.show', $user->encrypted_id) }}" class="btn btn-sm btn-info">
            <i class="ti ti-eye"></i> Detail
        </a>
    </x-slot>
</x-tabler.datatables-actions>
```

---

### E. Other Components

#### `<x-tabler.flash-message>`

**Usage:**
```blade
<!-- Auto-display session flash messages -->
<x-tabler.flash-message />
```

**Supports:**
- `success` → Green alert
- `error` → Red alert
- `warning` → Yellow alert
- `info` → Blue alert

---

#### `<x-tabler.empty-state>`

**Usage:**
```blade
<x-tabler.empty-state
    title="No Data Found"
    description="There are no items to display"
    icon="ti ti-database-off"
    button-text="Add New Item"
    button-url="{{ route('users.create') }}"
/>
```

---

#### `<x-tabler.page-header>`

**Usage:**
```blade
<x-tabler.page-header
    title="User Management"
    subtitle="Manage system users"
    :breadcrumbs="[
        ['label' => 'Home', 'url' => route('dashboard')],
        ['label' => 'Users', 'url' => route('users.index')],
        ['label' => 'Management', 'active' => true]
    ]"
>
    <x-slot name="actions">
        <x-tabler.button type="create" modal-url="{{ route('users.create') }}" />
    </x-slot>
</x-tabler.page-header>
```

---

## 4. AJAX Handlers

### A. AJAX Form (`.ajax-form`)

**File:** `resources/assets/tabler/js/FormHandlerAjax.js`

**Usage:**
```blade
<form class="ajax-form" action="{{ route('users.store') }}" method="POST">
    @csrf
    <x-tabler.form-input name="name" label="Nama" required="true" />
    <x-tabler.form-input name="email" label="Email" type="email" required="true" />
    <button type="submit">Simpan</button>
</form>
```

**Auto-handling:**
1. ✅ Disable submit button + loading spinner
2. ✅ Show loading SweetAlert
3. ✅ Submit via Axios dengan FormData
4. ✅ Close modal otomatis (jika ada)
5. ✅ Reload DataTable (`.dataTable` class)
6. ✅ Reset form & clear validation
7. ✅ Show success/error toast
8. ✅ Redirect jika response mengandung `redirect`
9. ✅ Fire custom event `ajax-form:success`

**Custom Event Listener:**
```javascript
document.addEventListener('ajax-form:success', function(e) {
    console.log('Form submitted successfully', e.detail.response);
    console.log('Form element', e.detail.form);
});
```

---

### B. AJAX Delete (`.ajax-delete`)

**Usage:**
```blade
<button 
    class="ajax-delete"
    data-url="{{ route('users.destroy', $user->encrypted_id) }}"
    data-title="Hapus User?"
    data-text="User akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan."
>
    <i class="ti ti-trash"></i> Hapus
</button>
```

**Auto-handling:**
1. ✅ SweetAlert2 confirmation dialog
2. ✅ DELETE request via Axios
3. ✅ Show loading while deleting
4. ✅ Reload DataTable
5. ✅ Show success/error toast
6. ✅ Redirect jika response mengandung `redirect`

**Custom Event Listener:**
```javascript
document.addEventListener('ajax-delete:success', function(e) {
    console.log('Item deleted', e.detail.response);
});
```

---

### C. AJAX Modal Button (`.ajax-modal-btn`)

**Usage:**
```blade
<!-- Open Modal via AJAX -->
<button 
    class="ajax-modal-btn"
    data-url="{{ route('users.create') }}"
    data-modal-target="#formModal"
    data-modal-title="Tambah User"
    data-modal-size="modal-lg"
>
    <i class="ti ti-plus"></i> Tambah User
</button>

<!-- Link Version -->
<a 
    href="{{ route('users.create') }}"
    class="ajax-modal-btn"
    data-modal-target="#formModal"
>
    Edit
</a>
```

**Auto-handling:**
1. ✅ Open Bootstrap modal
2. ✅ Show loading spinner
3. ✅ Fetch content via AJAX GET
4. ✅ Render HTML response in modal body
5. ✅ Re-initialize components:
   - Select2 (`.select2-offline`)
   - Flatpickr (`.flatpickr-input`)
   - FilePond (`.filepond-input`)
   - HugeRTE (textarea editor)
6. ✅ Move modal to body (z-index fix)
7. ✅ Error handling dengan error message

---

### D. Custom Event Hooks

**Available Events:**
```javascript
// AJAX Form Success
document.addEventListener('ajax-form:success', (e) => {
    const { response, form } = e.detail;
    // Custom logic here
});

// AJAX Form Error
document.addEventListener('ajax-form:error', (e) => {
    const { error, form } = e.detail;
    // Custom error handling
});

// AJAX Delete Success
document.addEventListener('ajax-delete:success', (e) => {
    const { response } = e.detail;
    // Custom logic here
});

// Modal Content Loaded
document.addEventListener('ajax-modal:loaded', (e) => {
    const { modal, content } = e.detail;
    // Custom initialization after modal load
});
```

---

## 5. JavaScript Libraries

### A. Core Libraries (Auto-loaded)

| Library | Version | Purpose | Global Variable |
|---------|---------|---------|-----------------|
| **jQuery** | 3.7.1 | DOM manipulation, DataTables | `$`, `jQuery` |
| **Axios** | Latest | HTTP requests | `axios` |
| **Bootstrap 5** | 5.3.8 | UI framework, modals, tooltips | `bootstrap` |
| **SortableJS** | 1.15.6 | Drag & drop sorting | `Sortable` |
| **SweetAlert2** | 11.26.3 | Alerts & confirmations | `Swal` |

---

### B. Lazy-loaded Libraries

#### Flatpickr (Date/Time Picker)

**Trigger:** `.flatpickr-input` class

**Usage:**
```blade
<!-- Basic Date -->
<input type="text" class="form-control flatpickr-input" name="tanggal" />

<!-- With data attributes -->
<input 
    type="text" 
    class="form-control flatpickr-input" 
    name="datetime"
    data-flatpickr-type="datetime"
    data-flatpickr-config='{"enableTime": true, "dateFormat": "Y-m-d H:i"}'
/>
```

**Data Attributes:**
| Attribute | Value | Description |
|-----------|-------|-------------|
| `data-flatpickr-type` | `date`, `time`, `datetime`, `range`, `multiple` | Picker type |
| `data-flatpickr-enable-time` | `true` | Enable time picker |
| `data-flatpickr-mode` | `range`, `multiple` | Selection mode |

---

#### Select2 (Enhanced Select)

**Trigger:** `.select2-offline` class

**Usage:**
```blade
<!-- Blade Component -->
<x-tabler.form-select 
    name="role_id" 
    :options="$roles"
    placeholder="Pilih role..."
/>

<!-- Manual -->
<select class="form-select select2-offline" name="role_id" data-placeholder="Select role">
    <option value="">Pilih Role</option>
    @foreach($roles as $role)
        <option value="{{ $role->id }}">{{ $role->name }}</option>
    @endforeach
</select>
```

**Configuration:**
```javascript
$('.select2-offline').select2({
    theme: 'bootstrap-5',
    width: '100%',
    placeholder: 'Select option',
    allowClear: true
});
```

---

#### FilePond (File Upload)

**Trigger:** `.filepond-input` class

**Usage:**
```blade
<!-- Single File -->
<x-tabler.form-input 
    type="file" 
    name="avatar" 
    accept="image/*"
/>

<!-- Multiple Files -->
<input 
    type="file" 
    class="form-control filepond-input" 
    name="photos[]"
    multiple
    data-allow-multiple="true"
    data-label-idle="Drag & Drop photos or Browse"
/>
```

**Data Attributes:**
| Attribute | Description |
|-----------|-------------|
| `data-accepted-file-types` | Comma-separated MIME types |
| `data-allow-multiple` | Enable multiple file selection |
| `data-label-idle` | Custom placeholder text |

---

#### HugeRTE (Rich Text Editor)

**Trigger:** Manual via `window.loadHugeRTE()`

**Usage:**
```blade
<!-- Auto-initialized via form-textarea -->
<x-tabler.form-textarea 
    type="editor" 
    name="content" 
    :config="['height' => 400]"
/>

<!-- Manual Initialization -->
<textarea id="editor" name="content"></textarea>

@push('scripts')
<script>
    window.loadHugeRTE('#editor', {
        height: 400,
        menubar: false,
        plugins: ['lists', 'link', 'image', 'table']
    });
</script>
@endpush
```

---

#### DataTables (Server-side Tables)

**Trigger:** `<x-tabler.datatable>` component

**Usage:**
```blade
<x-tabler.datatable
    id="usersTable"
    :route="route('users.paginate')"
    :columns="[
        ['data' => 'id', 'title' => 'ID'],
        ['data' => 'name', 'title' => 'Nama'],
        ['data' => 'action', 'title' => 'Aksi', 'orderable' => false]
    ]"
/>
```

**Features:**
- ✅ Server-side pagination & sorting
- ✅ State persistence (localStorage)
- ✅ Advanced filtering
- ✅ Bulk selection checkbox
- ✅ Search dengan debounce 300ms
- ✅ Page length selector
- ✅ Active filters badge display

---

#### ApexCharts (Charts)

**Trigger:** Manual via `window.loadApexCharts()`

**Usage:**
```blade
<div id="chart"></div>

@push('scripts')
<script>
    window.loadApexCharts().then((ApexCharts) => {
        new ApexCharts(document.querySelector('#chart'), {
            chart: { type: 'bar' },
            series: [{ name: 'Sales', data: [10, 20, 30] }],
            xaxis: { categories: ['Jan', 'Feb', 'Mar'] }
        }).render();
    });
</script>
@endpush
```

---

## 6. Global Search

### A. Component

**File:** `resources/views/components/tabler/modal-global-search.blade.php`

**Usage:**
```blade
<!-- Trigger Button -->
<button onclick="openGlobalSearchModal()" class="btn btn-icon">
    <i class="ti ti-search"></i>
</button>

<!-- With Custom Endpoint -->
<button onclick="openGlobalSearchModal('/custom-search')" class="btn btn-icon">
    <i class="ti ti-search"></i>
</button>
```

### B. JavaScript Class

**File:** `resources/assets/tabler/js/GlobalSearch.js`

```javascript
// resources/assets/tabler/js/GlobalSearch.js
export class GlobalSearch {
    constructor() {
        this.endpoint = '/global-search';
        this.timer = null;
        this.currentSearchTerm = '';
    }
    
    openModal()           // Open search modal
    closeModal()          // Close search modal
    performGlobalSearch(term)  // AJAX search
    displaySearchResults(results)  // Render results
}
```

### C. Search Flow

```
1. User klik search icon → openModal()
2. User mengetik → debounce 500ms
3. AJAX request ke endpoint
4. Display results grouped by category
5. Click result → navigate ke URL
```

### D. API Response Format

```json
{
    "users": [
        {
            "name": "John Doe",
            "email": "john@example.com",
            "url": "/users/123",
            "avatar": "/avatars/john.jpg"
        }
    ],
    "roles": [
        {
            "name": "Admin",
            "description": "System Administrator",
            "url": "/roles/1"
        }
    ],
    "permissions": [
        {
            "name": "edit-users",
            "description": "Can edit users",
            "url": "/permissions/5"
        }
    ]
}
```

### E. Custom Search Configuration

```javascript
// Add custom search category
window.globalSearch.addSearchConfig('products', {
    icon: 'bx bx-box',
    label: 'Products',
    itemTemplate: (item) => `
        <a href="${item.url}" class="list-group-item">
            <h6 class="mb-0">${item.name}</h6>
            <small class="text-muted">${item.description}</small>
        </a>
    `
});
```

---

## 7. Notification System

### A. Component

**Location:** Header notification dropdown in `layouts/tabler/header.blade.php`

### B. JavaScript Class

**File:** `resources/assets/tabler/js/Notification.js`

```javascript
// resources/assets/tabler/js/Notification.js
export class NotificationManager {
    constructor() {
        this.dom = {
            count: document.querySelectorAll('.notification-count'),
            list: document.getElementById('notifications-list'),
            markReadBtn: document.getElementById('markAllAsReadBtn')
        };
    }
    
    loadNotifications()   // Load notification list
    handleMarkAllAsRead() // Mark all as read
    updateAllCounters(count) // Update badge count
}
```

### C. API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/notifications/unread-count` | Get unread count |
| `GET` | `/api/notifications/dropdown` | Get notification list |
| `POST` | `/api/notifications/mark-all-read` | Mark all as read |

### D. Notification Data Format

```json
{
    "data": [
        {
            "id": 1,
            "title": "New User Registered",
            "body": "John Doe has registered as a new user",
            "is_unread": true,
            "action_url": "/users/123",
            "created_at": "2026-02-22 10:30:00"
        }
    ]
}
```

---

## 8. Layout Structure

### A. Layout Files

```
resources/views/layouts/
├── tabler/
│   ├── app.blade.php        # Main admin layout
│   ├── empty.blade.php      # Blank layout (no header/sidebar)
│   ├── header.blade.php     # Top navigation bar
│   ├── sidebar.blade.php    # Sidebar menu
│   └── footer.blade.php     # Footer
├── auth/
│   └── app.blade.php        # Authentication pages (login, register)
├── guest/
│   └── app.blade.php        # Public pages
├── public/
│   └── app.blade.php        # Public website
└── exam/
    └── app.blade.php        # Exam interface
```

### B. Layout Usage

```blade
<!-- Main Admin Layout -->
<x-layouts.tabler.app>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="subtitle">Overview</x-slot>
    
    <div class="page-header">
        <h2 class="page-title">Dashboard</h2>
    </div>
    
    <div class="page-body">
        @yield('content')
    </div>
</x-layouts.tabler.app>

<!-- Empty Layout (No Header/Sidebar) -->
<x-layouts.tabler.empty>
    <x-slot name="title">Full Page</x-slot>
    
    <div class="page-wrapper">
        @yield('content')
    </div>
</x-layouts.tabler.empty>
```

### C. Layout Sections

| Section | Description | Required |
|---------|-------------|----------|
| `title` | Page title | ✅ |
| `subtitle` | Page subtitle | ❌ |
| `pretitle` | Small title above main title | ❌ |
| `header` | Custom page header | ❌ |
| `actions` | Page action buttons | ❌ |
| `css` | Page-specific CSS | ❌ |
| `scripts` | Page-specific JavaScript | ❌ |

### D. Theme Integration

**Controller:** `ThemeTablerController`

**Usage in Layout:**
```blade
@php
    use App\Http\Controllers\Sys\ThemeTablerController;
    $themeController = app(ThemeTablerController::class);
    $themeData = $themeController->getThemeData('tabler');
    $layoutData = $themeController->getLayoutData('tabler');
@endphp

<!DOCTYPE html>
<html lang="en" {!! $themeController->getHtmlAttributes('tabler') !!}>
<head>
    {!! $themeController->getStyleBlock('tabler') !!}
</head>
<body class="{{ $layoutData['bodyClass'] }}">
    <div class="page-wrapper {{ $layoutData['containerClass'] }}">
        <!-- Content -->
    </div>
</body>
</html>
```

---

## 9. Dark Mode

### A. Implementation

**Dual Storage Strategy:**
1. **localStorage** - Client-side instant toggle
2. **Server** - Persistent via `/theme/save` endpoint

### B. Toggle Function

```javascript
// Global function in tabler.js
window.toggleTheme(mode) {
    // 1. Set HTML attribute
    document.documentElement.setAttribute('data-bs-theme', mode);
    
    // 2. Save to localStorage
    localStorage.setItem('tabler-theme', mode);
    
    // 3. Sync with ThemeTabler
    if (window.themeTabler) {
        window.themeTabler.refresh();
    }
    
    // 4. Persist to server
    axios.post('/theme/save', {
        mode: 'tabler',
        theme: mode
    });
}
```

### C. Dark Mode Behavior

| Aspect | Light Mode | Dark Mode |
|--------|------------|-----------|
| Background presets | Visible | Hidden |
| Text contrast | Auto-calculated | Auto-calculated |
| HugeRTE skin | `oxide` | `oxide-dark` |
| Content CSS | `default` | `dark` |
| CSS variables | Standard values | Adjusted values |

### D. Dark Mode Detection

```javascript
// In ThemeTabler.js
const isDarkMode = () => {
    // 1. Check HTML attribute (SSR source of truth)
    const htmlTheme = document.documentElement.getAttribute('data-bs-theme');
    if (htmlTheme === 'dark') return true;
    if (htmlTheme === 'light') return false;
    
    // 2. Fallback to localStorage
    const theme = localStorage.getItem('tabler-theme');
    if (theme === 'dark') return true;
    if (theme === 'light') return false;
    
    // 3. Auto mode - check system preference
    return window.matchMedia('(prefers-color-scheme: dark)').matches;
};
```

---

## 10. Panduan Pengembangan

### A. Membuat Blade Component Baru

**1. Create Component File:**
```bash
php artisan make:component Tabler.Alert --view=components.tabler.alert
```

**2. Component Template:**
```blade
@props([
    'type' => 'info', // info, success, warning, danger
    'title' => null,
    'dismissible' => true
])

@php
    $colors = [
        'info' => 'blue',
        'success' => 'green',
        'warning' => 'yellow',
        'danger' => 'red'
    ];
    $color = $colors[$type] ?? 'blue';
@endphp

<div class="alert alert-{{ $color }}" role="alert" {{ $attributes }}>
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    @endif
    
    @if($title)
        <h4 class="alert-title">{{ $title }}</h4>
    @endif
    
    <div class="text-secondary">{{ $slot }}</div>
</div>
```

**3. Usage:**
```blade
<x-tabler.alert type="success" title="Berhasil!">
    Data berhasil disimpan.
</x-tabler.alert>
```

---

### B. Menambah Library JavaScript Baru

**1. Install via npm:**
```bash
npm install library-name --save
```

**2. Add to tabler.js:**
```javascript
// Lazy load pattern
window.loadLibraryName = async function() {
    if (window.LibraryName) return window.LibraryName;
    
    const LibraryName = (await import('library-name')).default;
    window.LibraryName = LibraryName;
    return LibraryName;
};

// Auto-init pattern
document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('.library-trigger');
    if (elements.length > 0) {
        window.loadLibraryName().then(() => {
            elements.forEach(el => {
                // Initialize library
            });
        });
    }
});
```

---

### C. Menambah Search Category

**1. Extend GlobalSearch Config:**
```javascript
// In your custom JS file
document.addEventListener('DOMContentLoaded', () => {
    if (window.globalSearch) {
        window.globalSearch.addSearchConfig('products', {
            icon: 'bx bx-box',
            label: 'Products',
            itemTemplate: (item) => `
                <a href="${item.url}" class="list-group-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-xs bg-label-primary rounded-circle">
                                <i class="bx bx-box"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">${item.name}</h6>
                            <small class="text-muted">${item.description}</small>
                        </div>
                    </div>
                </a>
            `
        });
    }
});
```

**2. Update Backend Endpoint:**
```php
// In GlobalSearchController
public function search(Request $request)
{
    $term = $request->q;
    
    return response()->json([
        'users' => User::search($term)->get(),
        'roles' => Role::search($term)->get(),
        'products' => Product::search($term)->get(), // New category
    ]);
}
```

---

### D. Custom Theme Settings

**1. Add New Setting to ThemeTabler:**
```javascript
// In ThemeTabler.js
this.themeMap = {
    // ... existing settings
    'theme-custom-setting': { 
        var: '--tblr-custom-var', 
        attr: 'data-bs-custom' 
    },
};
```

**2. Add Input to Settings Panel:**
```blade
<!-- In theme-settings.blade.php -->
<div class="mb-4" id="custom-setting-control">
    <label class="form-label">Custom Setting</label>
    <input 
        type="text" 
        name="theme-custom-setting" 
        class="form-control"
        value="{{ $layoutData['theme-custom-setting'] ?? '' }}"
    />
</div>
```

**3. Add CSS Variable:**
```css
/* In tabler.css */
:root {
    --tblr-custom-var: initial;
}
```

---

### E. Best Practices

#### ✅ DO:
- Gunakan Blade components untuk UI consistency
- Gunakan `ajax-form` dan `ajax-delete` untuk form handling
- Lazy-load libraries untuk performance
- Gunakan `encrypted_id` untuk semua URL
- Simpan state ke server, bukan localStorage
- Gunakan `@push('scripts')` untuk page-specific JS

#### ❌ DON'T:
- Jangan membuat inline JavaScript yang kompleks
- Jangan gunakan localStorage untuk state penting
- Jangan hardcode ID di URL (gunakan `encrypted_id`)
- Jangan skip validation di server-side
- Jangan gunakan `!important` di CSS kecuali critical

---

### F. Troubleshooting

#### Issue: Modal tidak muncul
**Solution:**
```blade
<!-- Pastikan z-index cukup -->
<style>
    .modal.modal-blur {
        z-index: 99999 !important;
    }
</style>
```

#### Issue: Select2 tidak initialize
**Solution:**
```javascript
// Re-init setelah AJAX load
$(document).on('ajax-modal:loaded', function() {
    window.initOfflineSelect2();
});
```

#### Issue: Flatpickr tidak muncul
**Solution:**
```blade
<!-- Pastikan class benar -->
<input class="form-control flatpickr-input" />

<!-- Re-init setelah AJAX -->
<script>
    window.initFlatpickr();
</script>
```

#### Issue: Theme tidak persist setelah reload
**Solution:**
```javascript
// Pastikan server endpoint bekerja
axios.post('/theme/save', {
    mode: 'tabler',
    theme: 'dark'
}).then(response => {
    console.log('Theme saved:', response.data);
});
```

---

## Appendix: Quick Reference

### Common Component Usage

```blade
<!-- Form Input -->
<x-tabler.form-input name="name" label="Nama" required="true" />

<!-- Form Select -->
<x-tabler.form-select name="role_id" :options="$roles" />

<!-- Form Textarea -->
<x-tabler.form-textarea name="description" rows="5" />

<!-- Form Modal -->
<x-tabler.form-modal id="modal" title="Title" route="{{ route('store') }}">
    <!-- Form inputs -->
</x-tabler.form-modal>

<!-- Button -->
<x-tabler.button type="create" modal-url="{{ route('create') }}" />

<!-- DataTable -->
<x-tabler.datatable
    id="table"
    :route="route('paginate')"
    :columns="[['data' => 'name', 'title' => 'Nama']]"
/>

<!-- Flash Message -->
<x-tabler.flash-message />

<!-- Empty State -->
<x-tabler.empty-state title="No Data" description="No items found" />
```

### JavaScript Global Functions

```javascript
// Theme
window.toggleTheme('dark')

// DataTables
window.loadDataTables()
window.DT_tableId  // Access DataTable instance

// Form Components
window.initOfflineSelect2()
window.initFlatpickr()
window.initFilePond()
window.loadHugeRTE('#editor')

// Charts
window.loadApexCharts()

// Search
window.openGlobalSearchModal()

// SweetAlert
window.Swal.fire()
window.showSuccessMessage('Success!')
window.showErrorMessage('Error!')
window.showLoadingMessage('Processing...')
window.showDeleteConfirmation('Delete?', 'Are you sure?')
```

### AJAX Classes

```blade
<!-- AJAX Form -->
<form class="ajax-form" action="{{ route('store') }}" method="POST">
    @csrf
    <!-- Inputs -->
    <button type="submit">Save</button>
</form>

<!-- AJAX Delete -->
<button class="ajax-delete" data-url="{{ route('destroy', $id) }}">
    Delete
</button>

<!-- AJAX Modal -->
<button class="ajax-modal-btn" data-url="{{ route('create') }}">
    Open Modal
</button>
```

---

**Dokumentasi ini adalah pelengkap dari `PROJECT_STANDARDS.md` dan harus digunakan bersama sebagai referensi tunggal untuk development.**
