# Project Standardization Guide (Single Source of Truth)

**Last Updated:** Februari 2026  
**Laravel Version:** 12.x  
**PHP Version:** 8.4+

Dokumen ini adalah referensi teknis mendalam (*Single Source of Truth*) untuk seluruh arsitektur, standar koding, dan keamanan proyek ini. Seluruh pengembang wajib mematuhi pedoman ini tanpa pengecualian.

---

## Table of Contents

1. [Arsitektur Backend](#1-arsitektur-backend)
2. [Frontend & UI Standardization](#2-frontend--ui-standardization)
3. [Database & System Helpers](#3-database--system-helpers)
4. [Struktur Folder & Naming](#4-struktur-folder--naming)
5. [Authentication & Authorization](#5-authentication--authorization)
6. [Features & Packages](#6-features--packages)
7. [Development Workflow](#7-development-workflow)
8. [Code Quality & Best Practices](#8-code-quality--best-practices)

---

## 1. Arsitektur Backend

### A. Service-Oriented Architecture

Proyek ini menggunakan **Service-Oriented Architecture** untuk memisahkan logika bisnis dari HTTP respons.

#### Pattern Flow
```
Request → Controller → Service → Model → Database
              ↓
          Response (View/JSON)
```

#### Controller (Thin)
Controller **hanya** bertugas:
- Handle HTTP request/response
- Validation (via Form Requests)
- Call service methods
- Return response (View atau JSON)

```php
// app/Http/Controllers/Sys/RoleController.php
public function store(RoleRequest $request)
{
    try {
        $data = $request->validated();
        $data['permissions'] = $request->input('permissions', []);

        $this->roleService->createRole($data);

        return redirect()->route('sys.roles.index')
            ->with('success', 'Role created');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', $e->getMessage());
    }
}
```

#### Service (Thick)
Semua business logic ada di Service:
- Database transactions
- Business rules validation
- External API calls
- File operations
- Activity logging

```php
// app/Services/Sys/RoleService.php
public function createRole(array $data): Role
{
    return DB::transaction(function() use ($data) {
        $role = Role::create(['name' => $data['name']]);

        if (!empty($data['permissions'])) {
            $role->givePermissionTo($data['permissions']);
        }

        logActivity('role_management', "Created role: {$role->name}");

        return $role;
    });
}
```

#### Why Service Pattern?
- ✅ **Reusable**: Service bisa dipanggil dari Controller, API, CLI, Export, Import
- ✅ **Testable**: Easy to unit test tanpa HTTP layer
- ✅ **Maintainable**: Business logic centralized di satu tempat
- ✅ **Separation of Concerns**: Controller fokus di HTTP, Service fokus di business logic

### B. Dependency Injection

Gunakan constructor injection. Nama properti service **WAJIB camelCase**:

```php
// ✅ Good
public function __construct(
    protected UserService $userService,
    protected RoleService $roleService
) {}

// ❌ Bad
public function __construct(
    protected UserService $user_service,  // Don't use snake_case
) {}
```

### C. Route Model Binding & Security (Encrypted ID)

Keamanan ID adalah prioritas utama (**Encrypted ID Everywhere**).

#### Trait HashidBinding
Wajib ditambahkan pada setiap model yang diekspos ke URL:

```php
// app/Models/User.php
use App\Traits\HashidBinding;

class User extends Model
{
    use HashidBinding;
}
```

Trait ini otomatis:
- Mengenkripsi ID di route (`getRouteKey()`)
- Men-dekripsi saat resolve binding (`resolveRouteBinding()`)
- Menyediakan accessor `hashid` attribute

#### Controller Usage
```php
// ✅ Good - Route Model Binding
public function show(User $user)
{
    return view('users.show', compact('user'));
}

// ❌ Bad - Manual decryption
public function show($encryptedId)
{
    $id = decryptId($encryptedId);
    $user = User::findOrFail($id);
}
```

#### Helper Encryption
```php
encryptId($id)                    // Encrypt ID for URLs
decryptId($hash, $throwException = true)  // Decrypt hashid
decryptIdIfEncrypted($id)         // Decrypt if hashid, return as-is if numeric
```

### D. Validation (Form Requests)

Validasi harus dipisahkan dari controller menggunakan class Request khusus.

#### Naming Convention
- `{Entity}Request.php` - General validation
- `{Entity}{Action}Request.php` - Specific action validation

```php
// app/Http/Requests/Sys/CreateUserRequest.php
class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create users');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return validation_messages_id(); // Use Indonesian messages
    }
}
```

#### Controller Integration
```php
public function store(CreateUserRequest $request)
{
    $validated = $request->validated();
    $this->userService->createUser($validated);
    return redirect()->route('users.index');
}
```

### E. Model Traits & Best Practices

#### Standard Traits

| Trait | Purpose | Usage |
|-------|---------|-------|
| `HashidBinding` | Encrypted ID handling | All public-facing models |
| `Blameable` | Auto audit trail (`created_by`, `updated_by`, `deleted_by`) | All transactional models |
| `SoftDeletes` | Logical deletion | Most models (except logs) |

```php
// app/Models/User.php
use App\Traits\HashidBinding;
use App\Traits\Blameable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HashidBinding, Blameable, SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'created_by', 'updated_by', 'deleted_by'];
}
```

#### Eager Loading (N+1 Prevention)
**WAJIB** gunakan `with()` untuk mencegah N+1 query:

```php
// ❌ Bad - N+1 queries
$users = User::all();
foreach($users as $user) {
    echo $user->profile->name;  // Extra query per user!
}

// ✅ Good - Eager loading
$users = User::with('profile', 'roles')->get();
foreach($users as $user) {
    echo $user->profile->name;  // No extra queries
}
```

#### Database Transactions
```php
DB::transaction(function() {
    $user = User::create($data);
    $user->profile()->create($profileData);
    $user->assignRole('admin');
    logActivity('user_management', 'Created user: ' . $user->name);
});
```

### F. Responses (Global Helpers & Error Handling)

Seluruh aksi di Controller **WAJIB** dibungkus dalam blok `try-catch`.

#### JSON Response Helpers
```php
// Success response
jsonSuccess('Operation successful', route('users.index'));

// Success with data
jsonSuccess([
    'data' => $user,
    'redirect' => route('users.show', $user),
]);

// Error response
jsonError('User not found', 404);

// Manual response
jsonResponse(true, 'Success', ['user' => $user], 200, route('users.show'));
```

#### Error Logging
```php
try {
    $this->userService->createUser($data);
    return jsonSuccess('User created', route('users.index'));
} catch (\Exception $e) {
    logError($e); // Log to ErrorLog model
    return jsonError($e->getMessage(), 500);
}
```

---

## 2. Frontend & UI Standardization

### A. Layout Structure (Multi-Context)

```
resources/views/layouts/
├── admin/app.blade.php    # Admin area
├── sys/app.blade.php      # System management
├── auth/app.blade.php     # Authentication pages
└── guest/app.blade.php    # Public pages
```

#### Using Layouts
```blade
<x-layouts.sys.app>
    <x-slot name="title">Page Title</x-slot>
    <x-slot name="subtitle">Page subtitle</x-slot>

    <!-- Page content here -->
</x-layouts.sys.app>
```

### B. Vite Asset Bundling

#### Entry Points
```javascript
// resources/js/tabler.js - Main entry point
import './global.js';
import './components/FormFeatures.js';
```

#### Build Commands
```bash
npm run dev    # Development (watch mode with HMR)
npm run build  # Production (minified)
```

#### Page-Specific Assets
```blade
@push('css')
    <link href="{{ asset('custom.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('custom.js') }}"></script>
@endpush
```

### C. Blade Components (x-tabler)

Hampir seluruh elemen UI dibungkus dalam komponen Blade untuk konsistensi.

| Komponen | Kegunaan |
|----------|----------|
| `<x-tabler.page-header>` | Judul halaman dan breadcrumb |
| `<x-tabler.button>` | Tombol standar (type: submit, back, delete, modal) |
| `<x-tabler.form-input>` | Input teks, date (Flatpickr), password (eye toggle), file (FilePond) |
| `<x-tabler.form-select>` | Select dropdown, otomatis mendukung Select2 (offline) |
| `<x-tabler.form-textarea>` | Textarea teks atau Rich Text Editor (`type="editor"`) |
| `<x-tabler.form-checkbox>` | Checkbox input |
| `<x-tabler.form-radio>` | Radio input |
| `<x-tabler.form-modal>` | Modal wrapper untuk form |
| `<x-tabler.empty-state>` | UI placeholder jika data tabel/list kosong |
| `<x-tabler.flash-message>` | Menampilkan notifikasi error/sukses dari session flash |
| `<x-tabler.datatable>` | Server-side DataTables component |
| `<x-tabler.datatables-actions>` | Standard action buttons untuk DataTable |

**CATATAN**: Dilarang membuat elemen UI ad-hoc jika komponen `<x-tabler.*>` sudah tersedia.

#### Example: Form Input
```blade
<x-tabler.form-input 
    type="date" 
    name="tanggal" 
    label="Pilih Tanggal" 
    required="true" 
    help="Format: DD MMMM YYYY"
/>

<x-tabler.form-input 
    type="password" 
    name="password" 
    label="Kata Sandi"
    placeholder="Minimal 8 karakter"
/>
```

#### Example: DataTable
```blade
<x-tabler.datatable
    :columns="[
        ['data' => 'name', 'title' => 'Nama'],
        ['data' => 'email', 'title' => 'Email'],
        ['data' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
    ]"
    :url="route('admin.users.paginate')"
/>
```

#### Example: DataTable Actions
```php
// In Controller
->addColumn('action', function ($row) {
    return view('components.tabler.datatables-actions', [
        'editUrl'   => route('module.sub.edit', $row->encrypted_id),
        'editModal' => true, // Use true if edit uses AJAX modal
        'deleteUrl' => route('module.sub.destroy', $row->encrypted_id),
    ])->render();
})
```

### D. JavaScript Libraries

#### Core Libraries (Auto-loaded via tabler.js)

| Library | Purpose | Usage |
|---------|---------|-------|
| **jQuery** | DOM manipulation | `$('#element').on('click', fn)` |
| **Axios** | HTTP requests | `axios.get('/api/users')` |
| **Bootstrap 5** | UI framework | Modals, tooltips, alerts |
| **SweetAlert2** | Alerts & confirmations | `Swal.fire({...})` |
| **DataTables** | Server-side tables | `$('#table').DataTable({...})` |
| **SortableJS** | Drag & drop sorting | `Sortable.create(el)` |

#### Form Libraries (Lazy-loaded)

| Library | Trigger | Usage |
|---------|---------|-------|
| **Flatpickr** | `.flatpickr-input` class | Date/time picker |
| **Select2** | `.select2-offline` class | Enhanced select dropdown |
| **FilePond** | `.filepond-input` class | File upload with preview |
| **HugeRTE** | `window.loadHugeRTE()` | Rich text editor |

#### Automatic Initialization Pattern

The project uses global initialization in `tabler.js`:

1. `window.initFlatpickr()` - Runs on `DOMContentLoaded`
2. `window.initSelect2()` - Runs on `DOMContentLoaded`
3. `window.initFilePond()` - Runs on `DOMContentLoaded`
4. `FormHandlerAjax.js` automatically re-runs initialization when modals are shown

**Simply use the component classes and they will be auto-initialized:**

```blade
<!-- Auto-initialized as date picker -->
<x-tabler.form-input type="date" name="tanggal" />

<!-- Auto-initialized as Select2 -->
<select class="form-select select2-offline" name="role_id">
    <option value="">Pilih Role</option>
    @foreach($roles as $role)
        <option value="{{ $role->id }}">{{ $role->name }}</option>
    @endforeach
</select>

<!-- Auto-initialized as FilePond -->
<x-tabler.form-input type="file" name="avatar" accept="image/*" />
```

### E. AJAX Handlers

Logic utama berada di `FormHandlerAjax.js`:

#### AJAX Form (`class="ajax-form"`)
Form akan dikirim via Axios dengan automatic handling:
- Loading spinner pada tombol submit
- Menutup modal secara otomatis
- Reload DataTable (`.dataTable`) secara otomatis
- Notifikasi sukses/error (SweetAlert2)

```blade
<form action="{{ route('users.store') }}" method="POST" class="ajax-form">
    @csrf
    <x-tabler.form-input name="name" label="Nama" required="true" />
    <x-tabler.button type="submit" text="Simpan" />
</form>
```

#### AJAX Delete (`class="ajax-delete"`)
Tombol hapus dengan konfirmasi otomatis:

```blade
<button 
    class="btn btn-danger ajax-delete"
    data-url="{{ route('users.destroy', $user->encrypted_id) }}"
    data-title="Hapus User?"
    data-text="User akan dihapus secara permanen"
>
    Hapus
</button>
```

#### AJAX Modal Button (`class="ajax-modal-btn"`)
Membuka modal dan memuat konten secara dinamis via AJAX:

```blade
<button 
    class="btn btn-primary ajax-modal-btn"
    data-url="{{ route('users.create') }}"
    data-modal="formModal"
>
    Tambah User
</button>
```

### F. Unified Views Pattern

Untuk mengurangi duplikasi file, gunakan pola satu file untuk tambah dan edit:

**Filename:** `create-edit-ajax.blade.php`

```blade
<x-tabler.form-modal 
    id="formModal" 
    title="{{ $user->exists ? 'Edit User' : 'Tambah User' }}"
    data-redirect="{{ $user->exists ? 'false' : 'true' }}"
>
    <form action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}" 
          method="POST" 
          class="ajax-form"
    >
        @csrf
        @if($user->exists) @method('PUT') @endif

        <x-tabler.form-input name="name" label="Nama" :value="$user->name" required="true" />
        <x-tabler.form-input name="email" label="Email" type="email" :value="$user->email" required="true" />

        <div class="modal-footer">
            <x-tabler.button type="back" text="Batal" />
            <x-tabler.button type="submit" text="{{ $user->exists ? 'Update' : 'Simpan' }}" />
        </div>
    </form>
</x-tabler.form-modal>
```

---

## 3. Database & System Helpers

### A. Sys Helpers (`app/Helpers/SysHelper.php`)

**⚠️ DO NOT MODIFY** - Core system functions

| Function | Purpose |
|----------|---------|
| `encryptId($id)` | Encrypt ID for URLs |
| `decryptId($hash, $throwException = true)` | Decrypt hashid |
| `decryptIdIfEncrypted($id)` | Decrypt if hashid, return as-is if numeric |
| `logActivity($logName, $description, $subject = null, $properties = [])` | Log user activity |
| `logError($exception, $level = 'error', $context = [])` | Log error to ErrorLog model |
| `normalizePath($path)` | Clean path to prevent directory traversal |
| `formatBytes($size, $precision = 2)` | Format bytes to human readable |
| `validation_messages_id()` | Indonesian validation messages |

### B. Global Helpers (`app/Helpers/GlobalHelper.php`)

**✅ Safe to modify** - Custom business logic

| Function | Purpose |
|----------|---------|
| `formatTanggalIndo($tanggal)` | Format date to Indonesian (dddd, D MMMM YYYY HH:mm) |
| `formatWaktuSaja($waktu)` | Format time only (HH:ii) |
| `generateKodeInventaris($labId, $inventarisId)` | Generate unique inventory code |
| `jsonSuccess($arg1, $arg2, $arg3, $arg4)` | Standardized success JSON response |
| `jsonError($message, $code, $data, $redirect)` | Standardized error JSON response |
| `jsonResponse($success, $message, $data, $code, $redirect)` | Base JSON response helper |
| `setActiveRole($roleName)` | Set active role session |
| `getActiveRole()` | Get active role session |
| `getAllUserRoles()` | Get all user roles |
| `generateQrCodeImage($text, $filename, $directory)` | Generate QR code image |
| `generateQrCodeBase64($text)` | Generate QR code as base64 |

### C. Activity Logging

```php
// Basic usage
logActivity('user_management', 'Created user: ' . $user->name);

// With subject
logActivity('role_management', 'Assigned role', $user, ['role_id' => $roleId]);

// Auto-captures: IP address, user agent, URL, method, user_id
```

### D. Error Logging

```php
// Automatic in exception handler
// Manual logging
logError($exception, 'error', ['custom_context' => 'value']);
```

Error disimpan ke `sys_error_log` table dengan informasi:
- Exception class, message, file, line
- Full stack trace
- Request context (URL, method, IP, user agent, user_id)
- Additional custom context

---

## 4. Struktur Folder & Naming

### Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin features
│   │   ├── Auth/           # Authentication
│   │   ├── Guest/          # Public pages
│   │   ├── Sys/            # System management
│   │   ├── Lab/            # Lab management
│   │   ├── Hr/             # HR management
│   │   ├── Pemutu/         # Penjaminan Mutu
│   │   ├── Eoffice/        # E-office features
│   │   ├── Pmb/            # PMB features
│   │   ├── Cbt/            # Computer Based Test
│   │   ├── Survei/         # Survey features
│   │   └── Api/            # API endpoints
│   ├── Middleware/
│   │   └── CheckAccountExpiration.php
│   └── Requests/
│       └── {Module}/       # Form validation
├── Services/
│   └── {Module}/           # Business logic (Service Pattern)
├── Models/
│   ├── Sys/                # System models
│   ├── Lab/                # Lab models
│   ├── Hr/                 # HR models
│   └── ...                 # Other modules
├── Traits/
│   ├── HashidBinding.php   # Encrypted ID handling
│   └── Blameable.php       # Auto audit trail
├── Helpers/
│   ├── SysHelper.php       # Core system helpers (DO NOT MODIFY)
│   ├── GlobalHelper.php    # Custom business helpers
│   ├── ApprovalHelpers.php # Approval-specific helpers
│   └── EofficeHelper.php   # E-office helpers
├── Exports/                # Excel exports (Maatwebsite)
├── Imports/                # Excel imports (Maatwebsite)
├── Notifications/          # Laravel notifications
├── MediaLibrary/           # Spatie Media Library customizations
├── Events/                 # Laravel events
├── Listeners/              # Event listeners
└── Providers/              # Service providers

resources/
├── js/
│   ├── tabler.js          # Main entry point
│   ├── global.js          # Shared dependencies
│   └── assets/
│       └── tabler/
│           ├── js/
│           │   ├── ThemeTabler.js
│           │   ├── FormHandlerAjax.js
│           │   ├── CustomDataTables.js
│           │   ├── Notification.js
│           │   └── GlobalSearch.js
│           └── css/
├── css/
│   ├── admin.css          # Admin styles
│   └── sys.css            # System styles
└── views/
    ├── layouts/
    │   ├── admin/
    │   ├── sys/
    │   ├── auth/
    │   └── guest/
    ├── pages/
    │   ├── admin/
    │   ├── sys/
    │   ├── lab/
    │   ├── hr/
    │   └── ...
    └── components/
        ├── tabler/        # Tabler UI components
        ├── guest/
        └── hr/

routes/
├── web.php               # Main routes
├── auth.php              # Authentication routes
├── admin.php             # Admin routes
├── sys.php               # System routes
├── lab.php               # Lab routes
├── hr.php                # HR routes
├── pemutu.php            # Penjaminan Mutu routes
├── eoffice.php           # E-office routes
├── pmb.php               # PMB routes
├── cbt.php               # CBT routes
├── survei.php            # Survey routes
├── event.php             # Event routes
├── public.php            # Public routes
└── api.php               # API routes

database/
├── migrations/           # Database schema
│   ├── 2025_01_01_000000_create_sys_tables.php
│   ├── 2025_01_01_000001_create_shared_tables.php
│   ├── 2025_01_01_000002_create_lab_tables.php
│   └── ...
├── seeders/             # Sample data
│   └── PermissionSeeder.php
└── factories/           # Model factories

config/
├── auth.php              # Authentication
├── permission.php        # Spatie permissions
├── media-library.php     # File uploads
├── activitylog.php       # Activity logging
├── hashids.php           # ID encryption
├── laravel-impersonate.php # Impersonate config
├── backup.php            # Backup config
├── datatables.php        # DataTables config
└── services.php          # OAuth (Google)

storage/
├── app/
│   ├── backups/          # Backup files
│   ├── media/            # Uploaded files (Spatie)
│   └── qrcodes/          # Generated QR codes
└── logs/                 # Application logs
```

### Naming Conventions

#### Variables
```php
// ✅ Good
$userList = [];
$isExpired = false;
$hasPermission = true;

// ❌ Bad
$data = [];      // Too generic
$status = false; // Unclear meaning
```

#### Functions/Methods
```php
calculateTotalRevenue()
uploadProfileImage()
createUserWithProfile()
```

#### Classes
```php
UserController          // Controller
UserService             // Service
CreateUserRequest       // Form Request
UserExport              // Excel Export
```

#### Database
```php
// Tables
users
user_profiles
sys_error_logs

// Columns
{entity}_id             // Primary key (user_id)
created_by              // Blameable trait
updated_by              // Blameable trait
deleted_by              // Blameable trait (soft deletes)
deleted_at              // Soft deletes
encrypted_id            // Virtual attribute (via accessor)
```

---

## 5. Authentication & Authorization

### A. Laravel Breeze (Primary Auth)

- Login, Register, Password Reset
- Email verification
- **Config:** `config/auth.php`

### B. Google OAuth

- Social login via Google
- **Package:** `laravel/socialite`
- **Config:** `.env`
  ```env
  GOOGLE_CLIENT_ID=your_client_id
  GOOGLE_CLIENT_SECRET=your_client_secret
  GOOGLE_REDIRECT_URI=https://yourapp.com/auth/google/callback
  ```

### C. Spatie Permission (RBAC)

- Role-based access control
- **Package:** `spatie/laravel-permission`
- **Seeder:** `database/seeders/PermissionSeeder.php`

#### Usage
```php
// Assign role
$user->assignRole('admin');

// Remove role
$user->removeRole('admin');

// Check role
$user->hasRole('admin');

// Assign permission
$user->givePermissionTo('edit users');

// Check permission
$user->can('edit users');

// In Blade
@can('edit users')
    <a href="{{ route('users.edit', $user->encrypted_id) }}">Edit</a>
@endcan

// In Controller
public function __construct()
{
    $this->middleware('can:view users')->only(['index', 'show']);
    $this->middleware('can:create users')->only(['create', 'store']);
    $this->middleware('can:edit users')->only(['edit', 'update']);
    $this->middleware('can:delete users')->only(['destroy']);
}
```

#### Middleware
```php
// Route level
Route::resource('users', UserController::class)
    ->middleware('permission:manage users');

// Controller level (Recommended)
$this->middleware('can:view users')->only(['index', 'show']);

// In controller method
if ($user->can('edit users')) {
    // ...
}
```

### D. Multi-Role System

User dapat memiliki multiple roles. Gunakan session untuk active role:

```php
// Set active role
setActiveRole('admin');

// Get active role
$activeRole = getActiveRole();

// Get all roles
$allRoles = getAllUserRoles();
```

---

## 6. Features & Packages

### A. Core Packages

| Package | Version | Purpose |
|---------|---------|---------|
| `laravel/framework` | 12.x | Core framework |
| `spatie/laravel-permission` | 6.x | Role-based access control |
| `spatie/laravel-activitylog` | 4.x | Activity logging |
| `spatie/laravel-medialibrary` | 11.x | File uploads with thumbnails |
| `yajra/laravel-datatables-oracle` | 12.x | Server-side DataTables |
| `maatwebsite/excel` | 3.x | Excel import/export |
| `vinkla/hashids` | 13.x | ID encryption |
| `lab404/laravel-impersonate` | 1.7 | Admin impersonation |
| `laravel/socialite` | 5.x | OAuth (Google) |
| `barryvdh/laravel-dompdf` | 3.x | PDF generation |
| `spatie/laravel-searchable` | 1.x | Global search |
| `bacon/bacon-qr-code` | 3.x | QR code generation |

### B. Key Features

#### User Impersonation
- **Package:** `lab404/laravel-impersonate`
- **Routes:** `Route::impersonate()` (in web.php)
- **Middleware:** `impersonate.protect` (for protecting users)
- **Usage:**
  ```php
  // In Controller
  app('impersonate')->take(auth()->user(), $targetUser);
  
  // Leave impersonation
  app('impersonate')->leave();
  
  // Check if impersonating
  app('impersonate')->isImpersonating();
  
  // Get impersonator
  $impersonator = app('impersonate')->getImpersonator();
  ```

#### Account Expiration
- **Middleware:** `CheckAccountExpiration`
- **Field:** `users.expired_at`
- Auto-logout untuk akun yang expired

#### Soft Delete
- **Trait:** `SoftDeletes`
- **Usage:**
  ```php
  $user->delete();      // Soft delete
  $user->forceDelete(); // Permanent delete
  $user->restore();     // Restore
  User::withTrashed()->get(); // Include deleted
  ```

#### ID Encryption
- **Package:** `vinkla/hashids`
- **Config:** `config/hashids.php`
- **Helper:** `encryptId($id)`, `decryptId($encrypted)`

#### Global Search
- **Route:** `/global-search?q=query`
- **Component:** `<x-tabler.modal-global-search>`
- **Controller:** `GlobalSearchController`

#### Media Library
- **Package:** `spatie/laravel-medialibrary`
- **Usage:**
  ```php
  $user->addMedia($file)->toMediaCollection('avatar');
  $avatar = $user->getFirstMediaUrl('avatar');
  ```
- **Config:** `config/media-library.php`
- **Storage:** `storage/app/media/`

#### Backup & Restore
- **Service:** `app/Services/Sys/BackupService.php`
- **Command:** `php artisan backup:database`
- **View:** `sys/backups`
- **Storage:** `storage/app/backups/`

#### Notifications
- Database notifications
- **API:** `/api/notifications/count`, `/api/notifications/list`
- **Component:** `resources/js/components/Notification.js`

### C. Frontend Libraries

#### Core (Auto-loaded)
| Library | Purpose |
|---------|---------|
| jQuery | DOM manipulation |
| Bootstrap 5 | UI framework |
| Axios | HTTP client |
| SweetAlert2 | Alerts & confirmations |
| DataTables | Interactive tables |
| SortableJS | Drag & drop sorting |

#### Form Enhancement (Lazy-loaded)
| Library | Purpose |
|---------|---------|
| Flatpickr | Date/time picker |
| Select2 | Enhanced select dropdown |
| FilePond | File upload with preview |
| HugeRTE | Rich text editor |

#### Data Visualization
| Library | Purpose |
|---------|---------|
| ApexCharts | Charts & graphs |

---

## 7. Development Workflow

### A. Common Commands

#### Development
```bash
# Start development server
php artisan serve

# Watch assets (Vite HMR)
npm run dev

# Full dev suite (server, queue, logs, vite)
composer dev
```

#### Production
```bash
# Build assets
npm run build

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize
php artisan optimize
```

#### Database
```bash
# Run migrations
php artisan migrate

# Fresh migration with seeders
php artisan migrate:fresh --seed

# Run specific seeder
php artisan db:seed --class=PermissionSeeder
```

#### Cache Management
```bash
# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Reset permission cache
php artisan permission:cache-reset
```

#### Testing
```bash
# Run tests
composer test
php artisan test

# Run with coverage
php artisan test --coverage
```

### B. Environment Setup

#### Requirements
- PHP >= 8.4
- Composer
- Node.js & npm
- MySQL 8.0+

#### Installation Steps
```bash
# 1. Clone & install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Configure database
# Edit .env - set DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 4. Run migrations & seeders
php artisan migrate --seed

# 5. Build assets
npm run build

# 6. Create storage link
php artisan storage:link

# 7. Start server
php artisan serve
```

#### Default Login
- **Super Admin:** `superadmin@example.com` / `password`
- **Admin:** `admin@example.com` / `password`

### C. Troubleshooting

#### Assets not loading?
```bash
npm run build
php artisan config:clear
```

#### Permission errors?
```bash
php artisan permission:cache-reset
```

#### Database errors?
```bash
php artisan migrate:fresh --seed
```

#### Vite build errors?
```bash
rm -rf node_modules/.vite
npm run build
```

#### Error logging issues?
Check `sys_error_log` table or `storage/logs/laravel.log`

---

## 8. Code Quality & Best Practices

### A. Documentation

#### PHPDoc Blocks
```php
/**
 * Create a new user with profile
 *
 * @param array $data User data
 * @return \App\Models\User Created user instance
 * @throws \Exception
 */
public function createUser(array $data): User
{
    // Implementation
}
```

#### Inline Comments
```php
// Check if user has active role
if ($user->hasRole('admin')) {
    // Process admin action
}
```

### B. Error Handling

#### Try-Catch Pattern
```php
public function store(CreateUserRequest $request)
{
    try {
        $validated = $request->validated();
        $user = $this->userService->createUser($validated);
        
        return jsonSuccess('User created', route('users.show', $user));
    } catch (\Exception $e) {
        logError($e);
        return jsonError($e->getMessage(), 500);
    }
}
```

#### Exception Reporting
All exceptions are automatically logged to `sys_error_log` table via `bootstrap/app.php` exception handler, except:
- `ValidationException` (routine validation errors)
- `AuthenticationException` (routine auth failures)

### C. Performance Tips

#### Eager Loading
Always use `with()` to prevent N+1 queries:
```php
// ✅ Good
$users = User::with('profile', 'roles')->get();

// ❌ Bad
$users = User::all();
foreach ($users as $user) {
    $user->profile;  // N+1 query
    $user->roles;    // N+1 query
}
```

#### Caching
```php
// Cache expensive queries
$users = Cache::remember('users.all', 3600, function () {
    return User::with('roles')->get();
});

// Cache configuration (production)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Queue Long Tasks
```php
// Dispatch to queue
ProcessPodcast::dispatch($podcast);

// In Job
public function handle()
{
    // Long-running task
}
```

### D. Security Best Practices

#### ID Encryption
Always use encrypted IDs in URLs:
```php
// ✅ Good
Route::get('/users/{user}', [UserController::class, 'show']);
// URL: /users/abc123xyz (encrypted)

// ❌ Bad
Route::get('/users/{id}', [UserController::class, 'show']);
// URL: /users/123 (exposed)
```

#### Authorization
Always check authorization:
```php
// In Controller
$this->middleware('can:edit users')->only(['edit', 'update']);

// In Blade
@can('edit users')
    <!-- Show edit button -->
@endcan

// In Service
if (!$user->can('delete users')) {
    throw new \Exception('Unauthorized');
}
```

#### SQL Injection Prevention
Use Eloquent or parameterized queries:
```php
// ✅ Good
User::where('email', $email)->first();

// ❌ Bad
DB::select("SELECT * FROM users WHERE email = '$email'");
```

#### XSS Prevention
Escape output in Blade:
```blade
{{-- ✅ Good --}}
{{ $user->name }}

{{-- ❌ Bad --}}
{!! $user->name !!}  {{-- Only use for trusted HTML --}}
```

### E. Module Maturity Status

| Module | Status | Notes |
|--------|--------|-------|
| **SYS** | ✅ Full | All forms migrated to x-tabler components |
| **HR** | ✅ Full | Including pegawai and presensi sub-modules |
| **LAB** | ✅ Full | Including semester and inventaris management |
| **PEMUTU** | ✅ Full | Including KPI, Indikator, and Dokumen |
| **EOFFICE** | ✅ Full | Including layanan configuration and feedback |
| **PMB** | ✅ Full | Admission management |
| **CBT** | ✅ Full | Computer Based Test |
| **SURVEI** | ✅ Full | Survey management |
| **EVENT** | ✅ Full | Event management |

---

## Quick Reference

### Find Examples
- **Controllers:** `app/Http/Controllers/Sys/RoleController.php`
- **Services:** `app/Services/Sys/RoleService.php`
- **Requests:** `app/Http/Requests/Sys/RoleRequest.php`
- **Models:** `app/Models/Sys/Role.php`
- **Views:** `resources/views/pages/sys/roles/`
- **Traits:** `app/Traits/HashidBinding.php`, `app/Traits/Blameable.php`
- **Helpers:** `app/Helpers/SysHelper.php`, `app/Helpers/GlobalHelper.php`
- **Components:** `resources/views/components/tabler/`

### Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Permission denied | Check `can:permission-name` middleware or `@can` directive |
| N+1 queries | Add `with()` eager loading |
| Validation errors | Check FormRequest rules and messages |
| Assets not loading | `npm run build` + hard refresh (Ctrl+Shift+R) |
| Library undefined | Check if imported in entry point (tabler.js) |
| Encrypted ID error | Ensure model uses `HashidBinding` trait |
| Activity not logging | Check `logActivity()` call and Spatie config |

### Helper Functions Quick Access

```php
// Encryption
encryptId($id)
decryptId($hash)
decryptIdIfEncrypted($id)

// Activity & Error Logging
logActivity('module', 'Description', $subject)
logError($exception)

// Date Formatting
formatTanggalIndo($date)
formatTanggalWaktuIndo($date)
formatWaktuSaja($time)

// JSON Response
jsonSuccess('Message', $redirectUrl)
jsonError('Message', $code)
jsonResponse($success, $message, $data, $code, $redirect)

// Role Management
setActiveRole('admin')
getActiveRole()
getAllUserRoles()

// Utilities
normalizePath($path)
formatBytes($size)
generateQrCodeImage($text, $filename)
generateQrCodeBase64($text)
validation_messages_id()
```

---

## Appendix

### A. Configuration Files Reference

```php
// config/auth.php - Authentication guards, providers, passwords
// config/permission.php - Spatie permission settings
// config/activitylog.php - Activity log settings
// config/media-library.php - File upload settings
// config/hashids.php - Hashids salt and length
// config/laravel-impersonate.php - Impersonate settings
// config/backup.php - Backup settings
// config/datatables.php - DataTables settings
// config/services.php - OAuth services (Google)
```

### B. Environment Variables Reference

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lab_manajemen_pcr
DB_USERNAME=root
DB_PASSWORD=

# Google OAuth
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=

# Media Library
MEDIA_DISK=public

# Hashids
HASHIDS_SALT=
HASHIDS_LENGTH=8

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
```

### C. Artisan Commands Reference

```bash
# Backup
php artisan backup:database
php artisan backup:files

# Permissions
php artisan permission:cache-reset
php artisan permission:create-permission "permission name"
php artisan permission:create-role "role name"

# Custom
php artisan app:check-expired-accounts

# Standard Laravel
php artisan make:controller UserController --resource
php artisan make:request CreateUserRequest
php artisan make:service UserService
php artisan make:model User -m
php artisan make:seeder UserSeeder
php artisan make:export UsersExport --model=User
php artisan make:import UsersImport --model=User
```

---

**Dokumen ini bersifat definitif dan diperbarui secara berkala.**  
Untuk pertanyaan atau clarifications, refer ke dokumen ini atau cek existing implementations di codebase.

**Last Updated:** Februari 2026  
**Version:** 2.0 (Comprehensive Single Source of Truth)
