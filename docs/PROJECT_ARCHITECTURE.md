# Global Project Architecture & Standards (Single Source of Truth)

**Last Updated:** March 2026  
**Laravel Version:** 12.x (12.46.0)  
**PHP Version:** 8.4+  
**Database:** MySQL (MariaDB)  
**Frontend Build:** Vite 7.0 + TailwindCSS 3.x  
**UI Framework:** Tabler 1.4

Dokumen ini adalah **satu-satunya pusat referensi** arsitektur, alur kerja, pustaka, dan standar pemrograman proyek ini. Seluruh pengembang (manusia maupun AI agent) **WAJIB** mematuhi panduan ini tanpa pengecualian.

---

## Table of Contents

1. [Tech Stack & Packages](#1-tech-stack--packages)
2. [Arsitektur Global (Request/Response Flow)](#2-arsitektur-global-requestresponse-flow)
3. [ID Encryption System (HashidBinding)](#3-id-encryption-system-hashidbinding)
4. [Service-Oriented Architecture (Thin Controller, Fat Service)](#4-service-oriented-architecture-thin-controller-fat-service)
5. [Bounded Context (Modular Monolith)](#5-bounded-context-modular-monolith)
6. [Validation (BaseRequest & Form Requests)](#6-validation-baserequest--form-requests)
7. [Global Error Handling (Zero Try-Catch)](#7-global-error-handling-zero-try-catch)
8. [Audit Trail (Blameable + Activity Log + Error Log)](#8-audit-trail-blameable--activity-log--error-log)
9. [Frontend: Vite Entry Points & Asset Bundling](#9-frontend-vite-entry-points--asset-bundling)
10. [Frontend: Tabler UI Components & Layouts](#10-frontend-tabler-ui-components--layouts)
11. [Frontend: AJAX Form System (core-ajax.js)](#11-frontend-ajax-form-system-core-ajaxjs)
12. [Frontend: SweetAlert2 Alert System (core-alerts.js)](#12-frontend-sweetalert2-alert-system-core-alertsjs)
13. [Frontend: Select2, Flatpickr, FilePond](#13-frontend-select2-flatpickr-filepond)
14. [Frontend: HugeRTE Rich Text Editor](#14-frontend-hugerte-rich-text-editor)
15. [Frontend: Theme Toggle (Dark/Light Mode)](#15-frontend-theme-toggle-darklight-mode)
16. [Media Management (Spatie MediaLibrary + FilePond)](#16-media-management-spatie-medialibrary--filepond)
17. [DataTables (Yajra Server-Side)](#17-datatables-yajra-server-side)
18. [QR Code Generation (BaconQrCode)](#18-qr-code-generation-baconqrcode)
19. [Authentication & Authorization (RBAC)](#19-authentication--authorization-rbac)
20. [Global Helper Functions](#20-global-helper-functions)
21. [Folder Structure & Naming Convention](#21-folder-structure--naming-convention)
22. [Development Workflow](#22-development-workflow)

---

## 1. Tech Stack & Packages

### Backend (`composer.json`)

| Package | Versi | Fungsi |
|---------|-------|--------|
| `laravel/framework` | ^12.0 | Core framework |
| `laravel/sanctum` | ^4.2 | API token authentication |
| `laravel/socialite` | ^5.23 | OAuth login (Google, etc.) |
| `spatie/laravel-permission` | ^6.23 | RBAC (Roles & Permissions) |
| `spatie/laravel-medialibrary` | ^11.17 | File/media management terpusat |
| `spatie/laravel-activitylog` | ^4.10 | Activity audit trail |
| `spatie/laravel-searchable` | ^1.13 | Global search |
| `yajra/laravel-datatables-oracle` | ^12.6 | Server-side DataTables |
| `vinkla/hashids` | ^13.0 | ID encryption untuk URL |
| `maatwebsite/excel` | ^3.1 | Export/Import Excel |
| `phpoffice/phpword` | ^1.4 | Export DOCX |
| `barryvdh/laravel-dompdf` | ^3.1 | Export PDF |
| `bacon/bacon-qr-code` | 3.0 | QR Code generation |
| `lab404/laravel-impersonate` | ^1.7 | Login sebagai user lain |
| `jenssegers/date` | ^2.0 | Tanggal Bahasa Indonesia |
| `league/commonmark` | ^2.7 | Markdown parser |

### Frontend (`package.json`)

| Package | Versi | Fungsi |
|---------|-------|--------|
| `@tabler/core` | ^1.4.0 | UI Framework (Dashboard Admin) |
| `@tabler/icons-webfont` | ^3.36.1 | Icon library (ribuan ikon) |
| `tailwindcss` | ^3.1.0 | Utility-first CSS |
| `jquery` | ^3.7.1 | DOM manipulation (legacy compatibility) |
| `axios` | ^1.11.0 | HTTP client (AJAX) |
| `bootstrap` | ^5.3.8 | Modal, tooltip, etc. |
| `select2` | ^4.1.0 | Searchable dropdown |
| `flatpickr` | ^4.6.13 | Date/time picker |
| `filepond` + plugins | ^4.32.10 | Drag-drop file upload |
| `sweetalert2` | ^11.26.3 | Alert/confirmation popups |
| `apexcharts` | ^5.3.6 | Charts & graphs |
| `hugerte` | ^1.0.9 | Rich text editor (TinyMCE fork) |
| `sortablejs` | ^1.15.6 | Drag-to-reorder |
| `jkanban` | ^1.3.1 | Kanban board |
| `datatables.net-bs5` | ^2.3.6 | DataTables Bootstrap 5 styling |
| `@toast-ui/editor` | ^3.2.2 | Markdown editor |

### Dev Dependencies

| Package | Fungsi |
|---------|--------|
| `laravel/breeze` | Auth scaffolding |
| `laravel/pint` | PHP code formatter |
| `laravel/boost` | MCP server untuk AI agent |
| `barryvdh/laravel-debugbar` | Debug toolbar |
| `barryvdh/laravel-ide-helper` | IDE autocomplete |
| `phpunit/phpunit` | Testing framework |

---

## 2. Arsitektur Global (Request/Response Flow)

Proyek ini menganut pola **Modular Monolith** dengan aturan ketat **Thin Controller, Fat Service**.

```
┌─────────────────────────────────────────────────────────────────┐
│                    CLIENT REQUEST                               │
│         (URL berisi Encrypted ID, contoh: /user/Xj9a2S)        │
└──────────────────────┬──────────────────────────────────────────┘
                       ▼
┌─────────────────────────────────────────────────────────────────┐
│  1. ROUTE MODEL BINDING (HashidBinding Trait)                   │
│     - Auto-decrypt Xj9a2S → 102 (integer)                      │
│     - Model di-resolve otomatis, developer tidak perlu manual   │
└──────────────────────┬──────────────────────────────────────────┘
                       ▼
┌─────────────────────────────────────────────────────────────────┐
│  2. MIDDLEWARE (bootstrap/app.php)                               │
│     - 'role' → Spatie\Permission\Middleware\RoleMiddleware       │
│     - 'permission' → PermissionMiddleware                       │
│     - 'role_or_permission' → RoleOrPermissionMiddleware         │
│     - 'check.expired' → CheckAccountExpiration                  │
└──────────────────────┬──────────────────────────────────────────┘
                       ▼
┌─────────────────────────────────────────────────────────────────┐
│  3. FORM REQUEST VALIDATION (extends BaseRequest)               │
│     - Semua validasi wajib di FormRequest, bukan inline         │
│     - BaseRequest sudah berisi pesan error Bahasa Indonesia     │
│     - Wajib sertakan method attributes() untuk label field      │
└──────────────────────┬──────────────────────────────────────────┘
                       ▼
┌─────────────────────────────────────────────────────────────────┐
│  4. CONTROLLER (THIN — Hanya 3 tugas)                           │
│     a. Terima HTTP Request                                      │
│     b. Panggil Service via Constructor Injection                │
│     c. Return Response (view/json/redirect)                     │
│     ⛔ DILARANG: query DB, business logic, try-catch manual     │
└──────────────────────┬──────────────────────────────────────────┘
                       ▼
┌─────────────────────────────────────────────────────────────────┐
│  5. SERVICE (FAT — Semua logika bisnis di sini)                 │
│     - DB::transaction() untuk operasi tulis                     │
│     - Business rules, validasi kompleks                         │
│     - File operations, API calls                                │
│     - logActivity() untuk audit trail                           │
└──────────────────────┬──────────────────────────────────────────┘
                       ▼
┌─────────────────────────────────────────────────────────────────┐
│  6. MODEL → DATABASE                                            │
│     - Trait wajib: HashidBinding, Blameable, SoftDeletes        │
│     - Eager loading wajib (cegah N+1)                           │
│     - Gunakan Eloquent, hindari DB:: facade                     │
└──────────────────────┬──────────────────────────────────────────┘
                       ▼
┌─────────────────────────────────────────────────────────────────┐
│  7. RESPONSE                                                    │
│     - Web: return view() atau redirect()->route()               │
│     - AJAX: return jsonSuccess() atau jsonError()               │
│     - Exception: auto-handled oleh Global Exception Handler     │
└─────────────────────────────────────────────────────────────────┘
```

---

## 3. ID Encryption System (HashidBinding)

Setiap model yang ter-ekspos ke URL **WAJIB** menggunakan Trait `HashidBinding`. Trait ini menyamarkan Primary Key integer menjadi string hash agar tidak bisa ditebak.

**File:** `app/Traits/HashidBinding.php`  
**Library:** `vinkla/hashids` (^13.0)

### Cara Kerja (Otomatis, Tanpa Kode Manual)

```php
// 1. Tambahkan trait di Model
class User extends Model
{
    use HashidBinding, Blameable, SoftDeletes;
}

// 2. Di route, gunakan Route Model Binding biasa
Route::get('/users/{user}', [UserController::class, 'show']);

// 3. URL yang dihasilkan otomatis terenkripsi
// route('users.show', $user) → /users/Xj9a2S (bukan /users/102)

// 4. Di controller, model sudah ter-resolve otomatis
public function show(User $user) // $user sudah object lengkap, bukan hash
{
    return view('users.show', compact('user'));
}
```

### Helper Functions (SysHelper.php)

```php
encryptId(102);              // → "Xj9a2S"
decryptId("Xj9a2S");        // → 102
decryptIdIfEncrypted($val);  // Cerdas: jika numeric return as-is, jika hash decrypt
```

### ⛔ Anti-Pattern

```php
// ❌ DILARANG — Dekripsi manual di controller
public function show($encryptedId) {
    $id = decryptId($encryptedId);
    $user = User::findOrFail($id);
}

// ✅ BENAR — Route Model Binding otomatis
public function show(User $user) {
    return view('users.show', compact('user'));
}
```

---

## 4. Service-Oriented Architecture (Thin Controller, Fat Service)

### Controller (Thin) — Hanya Boleh:

```php
class UserController extends Controller
{
    // Constructor Injection — inject semua service yang dibutuhkan
    public function __construct(
        protected UserService $UserService,
        protected RoleService $RoleService,
    ) {}

    public function store(UserRequest $request)
    {
        // 1. Ambil data tervalidasi
        $data = $request->validated();

        // 2. Delegate ke service
        $this->UserService->createUser($data);

        // 3. Return response
        return jsonSuccess('User berhasil dibuat', route('sys.users.index'));
    }
}
```

### Service (Fat) — Semua Logika:

```php
class UserService
{
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create($data);
            $user->assignRole($data['role']);
            logActivity('user_management', "Created user: {$user->name}");
            return $user;
        });
    }
}
```

### Wajib Disediakan Setiap Service (Jika Model Diakses Lintas Domain)

| Method | Return | Kegunaan |
|--------|--------|----------|
| `getAll()` | `Collection` | Untuk dropdown/select |
| `getBaseQuery()` | `Builder` | Untuk DataTables |
| `getAktif()` | `Model\|null` | Record aktif saat ini |

### Cross-Service Delegation

```php
// ✅ BENAR — inject service domain lain
class AmiController extends Controller {
    public function __construct(
        protected AmiService $AmiService,
        protected PeriodeSpmiService $PeriodeSpmiService, // domain lain
    ) {}

    public function index() {
        $periodes = $this->PeriodeSpmiService->getAll(); // delegate ✅
    }
}

// ❌ SALAH — query model domain lain langsung
class AmiController extends Controller {
    public function index() {
        $periodes = PeriodeSpmi::orderBy('tahun', 'desc')->get(); // DILARANG!
    }
}
```

---

## 5. Bounded Context (Modular Monolith)

Proyek ini terstruktur sebagai **Modular Monolith**, di mana setiap modul memiliki model, service, controller, dan tabel database sendiri. **DILARANG** mencampur atau menyatukan service lintas modul.

### Contoh: Approval System

| Modul | Service | Tabel Database | Model |
|-------|---------|----------------|-------|
| HR | `App\Services\Hr\ApprovalService` | `hr_riwayat_approval` | `App\Models\Hr\RiwayatApproval` |
| Pemutu | `App\Services\Pemutu\DokumenApprovalService` | `pemutu_riwayat_approval` | `App\Models\Pemutu\RiwayatApproval` |
| PMB | `App\Services\Pmb\VerificationService` | `pmb_riwayat_approval` | `App\Models\Pmb\RiwayatApproval` |

> **⚠️ PENTING:** Meskipun ketiga modul memiliki konsep "Approval", mereka memiliki skema tabel yang berbeda (field berbeda), sehingga **tidak boleh disatukan** menjadi satu service global. Ini bukan duplikasi — ini adalah isolasi domain yang disengaja.

### Modul-Modul Utama

```
app/
├── Models/
│   ├── Hr/           # Human Resource (Pegawai, Perizinan, Lembur, dll.)
│   ├── Pemutu/       # Penjaminan Mutu (Dokumen, Indikator, AMI, dll.)
│   ├── Pmb/          # Penerimaan Mahasiswa Baru
│   ├── Lab/          # Manajemen Laboratorium
│   ├── Sys/          # System (Users, Roles, Settings, ErrorLog)
│   └── ...
├── Services/
│   ├── Hr/           # Satu service per domain model HR
│   ├── Pemutu/       # Satu service per domain model Pemutu
│   ├── Pmb/          # dst.
│   └── ...
├── Http/Controllers/
│   ├── Hr/
│   ├── Pemutu/
│   ├── Pmb/
│   └── ...
```

---

## 6. Validation (BaseRequest & Form Requests)

Validasi **WAJIB** dilakukan melalui Form Request class, bukan inline di controller.

```php
// Semua Request WAJIB extend BaseRequest
class UserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
        ];
    }

    // WAJIB — label field Bahasa Indonesia
    public function attributes(): array
    {
        return [
            'name' => 'Nama Lengkap',
            'email' => 'Email',
        ];
    }

    // OPSIONAL — override pesan spesifik saja
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'email.unique' => 'Alamat email ini sudah terdaftar.',
        ]);
    }
}
```

---

## 7. Global Error Handling (Zero Try-Catch)

**File:** `bootstrap/app.php`

Controller **TIDAK PERLU** menulis blok `try-catch`. Sistem menangani semua exception secara global:

1. **Report:** Setiap exception (kecuali `ValidationException` dan `AuthenticationException`) otomatis dicatat ke tabel `sys_error_log` via `logError()`.
2. **Render AJAX:** Jika request `expectsJson()`, return `jsonError('Terjadi kesalahan: ...')`.
3. **Render HTTP POST/PUT/DELETE:** Redirect `back()->withInput()->with('error', '...')`.
4. **Render HTTP GET:** Fallback ke halaman error Laravel default (500).

```php
// ✅ BENAR — biarkan exception meledak, handler yang tangkap
public function store(UserRequest $request)
{
    $this->UserService->createUser($request->validated());
    return jsonSuccess('User created', route('users.index'));
}

// ❌ SALAH — try-catch manual yang redundan
public function store(UserRequest $request)
{
    try {
        $this->UserService->createUser($request->validated());
        return jsonSuccess('User created');
    } catch (\Exception $e) {
        logError($e);                    // Sudah otomatis!
        return jsonError($e->getMessage()); // Sudah otomatis!
    }
}
```

---

## 8. Audit Trail (Blameable + Activity Log + Error Log)

### Blameable Trait (`app/Traits/Blameable.php`)

Otomatis mengisi kolom `created_by`, `updated_by`, `deleted_by` dengan **nama user (string)**, bukan ID.

```php
class Dokumen extends Model
{
    use Blameable, SoftDeletes, HashidBinding;

    protected $fillable = [
        'judul', 'kode', /* ... */
        'created_by', 'updated_by', 'deleted_by', // String columns!
    ];
}
```

**Database Migration:**
```php
$table->string('created_by')->nullable();
$table->string('updated_by')->nullable();
$table->string('deleted_by')->nullable();
```

### Activity Log (`logActivity()`)

Menggunakan **Spatie Activity Log**. Data disimpan ke tabel `activity_log`.

```php
logActivity('user_management', "Created user: {$user->name}", $user, [
    'extra_data' => 'value',
]);

// Parameter: ($logName, $description, $subject?, $extraProperties?)
// Otomatis menambahkan: ip_address, user_agent, url, method, user_id, user_name
```

### Error Log (`logError()`)

Menyimpan exception ke tabel `sys_error_log` lengkap dengan stack trace.

```php
logError($exception);              // Exception object
logError('Something went wrong');   // String message

// Otomatis menyimpan: level, message, exception_class, file, line, trace,
// url, method, ip_address, user_agent, user_id, session_id
```

---

## 9. Frontend: Vite Entry Points & Asset Bundling

**File:** `vite.config.js`

### Entry Points (6 total)

```
CSS Entry Points:
├── resources/tabler-core/css/tabler.css    → Dashboard admin
├── resources/css/auth.css                   → Halaman login/register
└── resources/css/public.css                 → Halaman publik/guest

JS Entry Points:
├── resources/tabler-core/js/tabler.js       → Dashboard admin (jQuery, Axios, Bootstrap, dll.)
├── resources/js/auth.js                      → Halaman login/register
└── resources/js/public.js                    → Halaman publik/guest
```

### Alias Path

```js
resolve: {
    alias: {
        '@tabler-core': '/resources/tabler-core',
    },
}
```

### JS Global yang Otomatis Tersedia (`tabler.js` Entry)

File `tabler.js` adalah entry point utama yang memuat semua library global:

```
tabler.js
├── jQuery ($, jQuery) → window.$, window.jQuery
├── Axios → window.axios (dengan CSRF token otomatis)
├── Bootstrap 5 Bundle → window.bootstrap
├── SortableJS → window.Sortable
├── SweetAlert2 → window.Swal
├── core-theme.js → ThemeTabler (dark/light toggle handler)
├── core-alerts.js → 11 fungsi SweetAlert2 global
├── core-ajax.js → Global AJAX form handler
└── Module Helpers:
    ├── pemutu-workspace.js
    ├── pemutu-indikator.js
    ├── projects-kanban.js
    ├── hr-pegawai.js
    └── tab-persistence.js
```

---

## 10. Frontend: Tabler UI Components & Layouts

### Layout Contexts (4 layout)

| Layout | Path | Kegunaan |
|--------|------|----------|
| **Admin** | `layouts.app` atau `layouts.admin.app` | Dashboard utama (sidebar + header) |
| **Auth** | `layouts.auth.app` | Login, register, forgot password |
| **Guest** | `layouts.guest.app` | Halaman tanpa sidebar (verifikasi QR, dll.) |
| **Public** | `layouts.public.app` | Landing page publik |
| **Assessment** | `layouts.assessment.app` | Halaman assessment/ujian |

### Blade Components Tabler (33 components)

Semua komponen berada di `resources/tabler-core/views/components/tabler/` dan dipanggil dengan prefix `<x-tabler.*>`:

**Layout & Card:**
| Component | Usage |
|-----------|-------|
| `<x-tabler.card>` | Card wrapper |
| `<x-tabler.card-header>` | Card header dengan title |
| `<x-tabler.card-body>` | Card body |
| `<x-tabler.card-footer>` | Card footer |
| `<x-tabler.page-header>` | Page title + breadcrumb |
| `<x-tabler.empty-state>` | Empty state placeholder |

**Form Components:**
| Component | Usage |
|-----------|-------|
| `<x-tabler.form-input>` | Text input (support `type="file"` untuk FilePond) |
| `<x-tabler.form-select>` | Dropdown select (otomatis Select2 jika `.select2`) |
| `<x-tabler.form-textarea>` | Textarea |
| `<x-tabler.form-checkbox>` | Checkbox |
| `<x-tabler.form-radio>` | Radio button |
| `<x-tabler.form-modal>` | Modal form wrapper |

**DataTable Components:**
| Component | Usage |
|-----------|-------|
| `<x-tabler.datatable>` | DataTable wrapper (auto-init server-side) |
| `<x-tabler.datatable-filter>` | Filter bar di atas DataTable |
| `<x-tabler.datatable-search>` | Search input |
| `<x-tabler.datatable-pagination>` | Pagination controls |
| `<x-tabler.datatable-info>` | "Showing X of Y" info |
| `<x-tabler.datatable-page-length>` | Items per page selector |
| `<x-tabler.datatables-actions>` | Edit/Delete action buttons |
| `<x-tabler.datatable-checkbox>` | Bulk select checkbox |

**Interactive:**
| Component | Usage |
|-----------|-------|
| `<x-tabler.button>` | Styled button |
| `<x-tabler.button-group>` | Button group |
| `<x-tabler.dropdown>` | Dropdown menu |
| `<x-tabler.dropdown-item>` | Dropdown item |
| `<x-tabler.dropdown-divider>` | Dropdown separator |
| `<x-tabler.flash-message>` | Flash notification |
| `<x-tabler.approval-history>` | Timeline approval history |
| `<x-tabler.theme-settings>` | Dark/light mode toggle |

---

## 11. Frontend: AJAX Form System (`core-ajax.js`)

Form yang menggunakan class `ajax-form` akan otomatis di-handle tanpa JavaScript tambahan.

### Cara Pakai

```blade
<form class="ajax-form" action="{{ route('users.store') }}" method="POST">
    @csrf
    <x-tabler.form-input name="name" label="Nama" required />
    <x-tabler.form-input type="email" name="email" label="Email" required />
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
```

### Apa yang Terjadi Otomatis

1. Form di-submit via **Axios** (bukan browser default).
2. Tombol submit **disabled** + spinner "Memproses...".
3. **SweetAlert loading** muncul.
4. Jika sukses:
   - Modal ditutup otomatis.
   - **DataTable di-reload** otomatis (`.dataTable` class).
   - Form di-reset.
   - **Toast success** muncul.
   - Custom event `ajax-form:success` di-fire.
5. Jika gagal (422 validation):
   - **Inline error** muncul di bawah field yang salah (class `.is-invalid`).
6. Jika gagal (500 server error):
   - **SweetAlert error** muncul.

### Backend Response Format

```php
// ✅ Untuk AJAX success
return jsonSuccess('Data berhasil disimpan', route('users.index'));

// ✅ Untuk AJAX error
return jsonError('Kredensial tidak valid', 401);
```

---

## 12. Frontend: SweetAlert2 Alert System (`core-alerts.js`)

Semua fungsi SweetAlert2 didaftarkan ke `window` dan bisa dipanggil dari mana saja:

| Fungsi | Kegunaan | Auto-Close |
|--------|----------|------------|
| `showSuccessMessage(title, text)` | Toast sukses (pojok kanan atas) | 3 detik |
| `showErrorMessage(title, text)` | Alert error | 3 detik |
| `showWarningMessage(title, text)` | Alert peringatan | 2.5 detik |
| `showInfoMessage(title, text)` | Alert info | 2 detik |
| `showLoadingMessage(title, text)` | Loading spinner | Manual |
| `showDeleteConfirmation(title, text)` | Konfirmasi hapus (Ya/Batal) | Manual |
| `showConfirmation(title, text)` | Konfirmasi umum (Ya/Batal) | Manual |
| `showFormErrors(errors)` | Tampilkan validation errors | 5 detik |
| `showBulkActionConfirmation(action, count)` | Konfirmasi aksi massal | Manual |
| `confirmAction(title, text, btn, callback)` | Konfirmasi + callback | Manual |
| `confirmDelete(url, tableId)` | Konfirmasi hapus + AJAX DELETE + reload tabel | Manual |

### Contoh Penggunaan `confirmDelete`

```blade
{{-- Di DataTable action column --}}
<a href="javascript:void(0)"
   onclick="confirmDelete('{{ route('users.destroy', $user) }}', 'users-table')">
    <i class="ti ti-trash text-danger"></i>
</a>
```

Ini akan: tampilkan konfirmasi → kirim DELETE request via Axios → reload DataTable `#users-table`.

---

## 13. Frontend: Select2, Flatpickr, FilePond

### Select2 (Searchable Dropdown)
Tambahkan class `select2` pada element `<select>`:

```blade
<x-tabler.form-select name="pegawai_id" label="Pegawai" class="select2">
    <option value="">-- Pilih --</option>
    @foreach($pegawais as $p)
        <option value="{{ $p->pegawai_id }}">{{ $p->nama }}</option>
    @endforeach
</x-tabler.form-select>
```

> **⚠️** Jika Select2 berada di dalam Modal Bootstrap 5, focus trap harus di-bypass. Ini sudah di-handle otomatis di `core-ajax.js`.

### Flatpickr (Date/Time Picker)
Tambahkan class `flatpickr` pada element `<input>`:

```blade
<x-tabler.form-input name="tanggal" label="Tanggal" class="flatpickr" />
```

### FilePond (File Upload)
Tambahkan class `filepond-input` pada input file:

```blade
<x-tabler.form-input type="file" name="dokumen[]" label="Lampiran" class="filepond-input" multiple />
```

FilePond akan otomatis:
- Render antarmuka drag-drop.
- Validate tipe dan ukuran file.
- Mengirim file sebagai bagian dari `FormData` saat form di-submit.

---

## 14. Frontend: HugeRTE Rich Text Editor

HugeRTE (fork TinyMCE) di-load secara **lazy** (dynamic import) untuk performa.

### Cara Pakai

```js
// Di dalam <script> blade view
window.loadHugeRTE('#my-textarea', {
    height: 400,
    // config tambahan opsional
}).then((editor) => {
    console.log('Editor ready', editor);
});
```

### Fitur Otomatis
- Deteksi dark mode dan load skin yang sesuai (oxide/oxide-dark).
- Plugin: lists, link, image, table, code, fullscreen, wordcount, searchreplace.
- Toolbar disesuaikan dengan kebutuhan proyek.

---

## 15. Frontend: Theme Toggle (Dark/Light Mode)

Proyek ini mendukung **3 mode tema**: Light, Dark, dan Auto (ikut OS).

### Mekanisme (Server-First)
1. **Preferensi tersimpan** di `localStorage` key `tabler-theme`.
2. **HTML attribute** `data-bs-theme="dark|light"` di-set pada `<html>`.
3. **CSS** menggunakan pattern `dark:` dari Tailwind atau `.dark` selector.

### Penggunaan di View
Selalu gunakan class `dark:` Tailwind atau cek `data-bs-theme`:
```blade
<div class="bg-white dark:bg-dark text-dark dark:text-white">
    Konten yang responsive terhadap tema
</div>
```

---

## 16. Media Management (Spatie MediaLibrary + FilePond)

### ⚠️ IMPORTANT: Two Upload Patterns

Project ini mendukung **2 pattern upload** tergantung business requirement:

| Pattern | Use Case | Modules |
|---------|----------|---------|
| **Pattern 1: Spatie MediaLibrary** | Simple upload tanpa workflow tracking | Pemutu, Hr, Lab, Event, Cms, Project (95% cases) |
| **Pattern 2: Custom Model + Spatie** | Upload dengan verification workflow | PMB only (complex approval) |

---

### Pattern 1: Spatie MediaLibrary (PRIMARY - Use for 95% of cases)

**When to use:** Simple file upload tanpa complex workflow tracking.

#### Backend (Model)

```php
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Dokumen extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('lampiran')
            ->useFallbackUrl('/assets/placeholder.pdf')
            ->useFallbackPath(public_path('/assets/placeholder.pdf'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(\Spatie\Image\Manipulations::FIT_CROP, 150, 150)
            ->nonQueued();
    }
}
```

#### Backend (Controller/Service)

```php
// Upload
if ($request->hasFile('dokumen')) {
    foreach ($request->file('dokumen') as $file) {
        $model->addMedia($file)
            ->withCustomProperties(['uploaded_by' => auth()->id()])
            ->toMediaCollection('lampiran');
    }
}

// Download
public function download($mediaId)
{
    $media = Media::findOrFail($mediaId);
    return downloadStorageFile($media->getPath(), $media->file_name);
}
```

#### Frontend (View)

```blade
{{-- Upload --}}
<x-tabler.form-input type="file" name="dokumen[]" label="Lampiran Dokumen" class="filepond-input" multiple />

{{-- Display --}}
@if($model->hasMedia('lampiran'))
    @foreach($model->getMedia('lampiran') as $media)
        <div class="file-item">
            <a href="{{ $media->getUrl() }}" target="_blank">
                <i class="ti ti-file"></i> {{ $media->file_name }}
            </a>
            <span class="file-size">{{ formatBytes($media->size) }}</span>
        </div>
    @endforeach
@endif
```

---

### Pattern 2: Custom Upload Model + Spatie Media (PMB Pattern Only)

**When to use:** ONLY when you need complex workflow tracking per file:
- Verification status per document
- Notes/comments per file
- Revision tracking
- Approval workflow per document

**⚠️ WARNING:** Use this pattern ONLY when absolutely necessary. For 95% of cases, Pattern 1 is sufficient.

#### Backend (Metadata Model)

```php
// Model for metadata tracking (NOT for file storage!)
class DokumenUpload extends Model
{
    protected $fillable = [
        'pendaftaran_id',
        'jenis_dokumen_id',
        'status_verifikasi',      // Pending, Valid, Revisi, Ditolak
        'catatan_verifikasi',     // Verificator notes
        'catatan_revisi',         // Revision notes
        'verifikator_id',         // Who verified
        'waktu_upload',
    ];

    // Relationship to actual file (via Spatie Media)
    public function media()
    {
        return $this->morphOne(\Spatie\MediaLibrary\MediaCollections\Models\Media::class, 'model')
            ->where('collection_name', 'dokumen_file');
    }

    // Helper to get file URL
    public function getFileUrlAttribute()
    {
        return $this->media ? $this->media->getUrl() : null;
    }
}
```

#### Backend (Parent Model)

```php
class Pendaftaran extends Model
{
    // Add Spatie MediaLibrary support
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pmb_dokumen');
    }

    // Relationship to metadata
    public function dokumenUploads()
    {
        return $this->hasMany(DokumenUpload::class, 'pendaftaran_id');
    }
}
```

#### Backend (Controller)

```php
// Upload
public function uploadDokumen(FileUploadRequest $request)
{
    return DB::transaction(function () use ($request) {
        // 1. Create metadata record
        $upload = DokumenUpload::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'jenis_dokumen_id' => $request->jenis_dokumen_id,
            'status_verifikasi' => 'Pending',
            'waktu_upload' => now(),
        ]);

        // 2. Store actual file with Spatie Media
        $pendaftaran = Pendaftaran::find($request->pendaftaran_id);
        $pendaftaran->addMedia($request->file('file'))
            ->withCustomProperties([
                'dokumen_upload_id' => $upload->id,
                'jenis_dokumen_id' => $request->jenis_dokumen_id,
            ])
            ->toMediaCollection('pmb_dokumen');

        logActivity('pmb_upload', "Upload dokumen: {$upload->id}", $upload);

        return jsonSuccess('Dokumen berhasil diupload');
    });
}

// Verify
public function verifyDocument(VerifyDocRequest $request, DokumenUpload $upload)
{
    $upload->update([
        'status_verifikasi' => $request->status,
        'catatan_verifikasi' => $request->keterangan,
        'verifikator_id' => auth()->id(),
    ]);

    logActivity('pmb_verifikasi', "Verifikasi dokumen: {$upload->id}", $upload);

    return jsonSuccess('Verifikasi berhasil');
}
```

---

### Media Handling Best Practices

1. **Always use Spatie MediaLibrary** untuk file storage
2. **Use custom properties** untuk additional metadata:
   ```php
   ->withCustomProperties(['uploaded_by' => auth()->id()])
   ```
3. **Define conversions** untuk images (thumb, preview, etc)
4. **Use `downloadStorageFile()` helper** untuk safe downloads
5. **Use fallback images** untuk collections yang might be empty
6. **Queue conversions** untuk large images:
   ```php
   ->queued() // Instead of ->nonQueued()
   ```
7. **DONT store file paths in database** - Let Spatie handle it
8. **DONT use `Storage::put()`** manual untuk file uploads

---

### Helper Functions

```php
// Download file from Spatie Media
downloadStorageFile($media->getPath(), $media->file_name);

// Get media URL
$media->getUrl();           // Original
$media->getUrl('thumb');    // Conversion

// Check if has media
$model->hasMedia('collection_name');

// Get all media
$model->getMedia('collection_name');

// Get first media
$model->getFirstMedia('collection_name');
$model->getFirstMediaUrl('collection_name', 'thumb');

// Delete media
$media->delete();
$model->clearMediaCollection('collection_name');

// Format bytes
formatBytes($media->size);  // "1.5 MB"
```

---

### File Upload Module Reference

| Module | Pattern | Collections | Notes |
|--------|---------|-------------|-------|
| **Pemutu** | Pattern 1 | `ed_attachments`, `dokumen_pendukung` | Evaluasi Diri attachments |
| **Hr** | Pattern 1 | `pegawai_photos`, `dokumen_pendukung` | Employee photos & documents |
| **Lab** | Pattern 1 | `inventaris_images`, `surat_attachments` | Lab equipment images |
| **Event** | Pattern 1 | `event_images`, `dokumen` | Event banners & documents |
| **Cms** | Pattern 1 | `article_images`, `gallery` | Article & gallery images |
| **Project** | Pattern 1 | `project_images`, `deliverables` | Project documentation |
| **PMB** | Pattern 2 | `pmb_dokumen` | With verification workflow |

---

## 17. DataTables (Yajra Server-Side)

### Controller (Return DataTable)

```php
public function index(Request $request)
{
    if ($request->ajax()) {
        $query = $this->UserService->getBaseQuery();
        return DataTables::of($query)
            ->addColumn('actions', function ($row) {
                return view('components.actions', compact('row'));
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    return view('pages.users.index');
}
```

### Blade View

```blade
<x-tabler.datatable
    id="users-table"
    :url="route('users.index')"
    :columns="[
        ['data' => 'name', 'title' => 'Nama'],
        ['data' => 'email', 'title' => 'Email'],
        ['data' => 'actions', 'title' => 'Aksi', 'orderable' => false],
    ]"
/>
```

---

## 18. QR Code Generation (BaconQrCode)

### Helper Functions (`SysHelper.php`)

```php
// Generate QR code sebagai Base64 string (untuk embed di HTML)
$base64 = generateQrCodeBase64($text);
// <img src="data:image/png;base64,{{ $base64 }}">

// Generate QR code dan simpan sebagai file
$path = generateQrCodeImage($text, 'qr_dokumen_123.png');
```

### Contoh Penggunaan: Verifikasi Dokumen

```php
// Di Service:
$verifyUrl = route('pemutu.dokumen.verify', $dokumen->encrypted_dok_id);
$renderer = new \BaconQrCode\Renderer\ImageRenderer(
    new RendererStyle(120, 1),
    new SvgImageBackEnd()
);
$writer = new \BaconQrCode\Writer($renderer);
$qrCode = $writer->writeString($verifyUrl);

// Di View:
<div>{!! $qrCode !!}</div>
```

---

## 19. Authentication & Authorization (RBAC)

### Library: Spatie Permission v6

### Role & Permission

```php
// Assign role
$user->assignRole('admin');

// Check role
if ($user->hasRole('admin')) { ... }

// Check permission
if ($user->can('edit articles')) { ... }
```

### Middleware (bootstrap/app.php)

```php
// Route protection
Route::middleware(['role:admin'])->group(function () { ... });
Route::middleware(['permission:manage users'])->group(function () { ... });
Route::middleware(['role_or_permission:admin|manage users'])->group(function () { ... });
```

### Active Role System (Session-Based)

```php
setActiveRole('Kepala Laboratorium');  // Simpan ke session
$role = getActiveRole();               // Ambil dari session
$allRoles = getAllUserRoles();          // Semua role user
```

---

## 20. Global Helper Functions

### SysHelper.php (ID, Logging, Utilities)

| Fungsi | Kegunaan | Example |
|--------|----------|---------|
| `encryptId($id)` | Encrypt integer ID ke hashid string | `encryptId(102)` → `"Xj9a2S"` |
| `decryptId($hash)` | Decrypt hashid ke integer | `decryptId("Xj9a2S")` → `102` |
| `decryptIdIfEncrypted($val)` | Smart decrypt (numeric → as-is, hash → decrypt) | `decryptIdIfEncrypted($id)` |
| `logActivity($log, $desc, $subject?, $props?)` | Catat aktivitas ke `activity_log` | `logActivity('user', 'Created user')` |
| `logError($error, $level?, $ctx?)` | Catat error ke `sys_error_log` | `logError($exception)` |
| `jsonSuccess($message, $redirect?)` | JSON response sukses | `jsonSuccess('OK', url()->previous())` ✅ |
| `jsonError($message, $code?)` | JSON response error | `jsonError('Error', 404)` |
| `setActiveRole($roleName)` | Set active role di session | `setActiveRole('admin')` |
| `getActiveRole()` | Get active role dari session | `getActiveRole()` |
| `getAllUserRoles()` | Get semua role user | `getAllUserRoles()` |
| `generateQrCodeBase64($text)` | Generate QR code base64 | `generateQrCodeBase64('text')` |
| `generateQrCodeImage($text, $file)` | Generate QR code ke file | `generateQrCodeImage('text', 'qr.png')` |
| `sysGenerateRefNumber($prefix, $model, $col)` | Generate nomor referensi sekuensial | `sysGenerateRefNumber('REG-', User::class, 'email')` |
| `downloadStorageFile($path, $name?)` | Download file dari Storage (aman) | `downloadStorageFile($path, 'file.pdf')` |
| `normalizePath($path)` | Sanitasi path (cegah directory traversal) | `normalizePath('../file.txt')` |
| `formatBytes($size)` | Format byte ke KB/MB/GB | `formatBytes(1024)` → "1 KB" |
| `sysDataTableSearchValue($val)` | Extract search value dari DataTable request | `sysDataTableSearchValue($request->search)` |
| `sysParseDateRange($str)` | Parse "date1 to date2" string | `sysParseDateRange('2026-01-01 to 2026-12-31')` |

### GlobalHelper.php (Tanggal & Format Indonesia)

| Fungsi | Kegunaan | Example Output |
|--------|----------|----------------|
| `formatTanggalIndo($tgl)` | Format tanggal | "Senin, 24 Maret 2026 14:30" |
| `formatTanggalWaktuIndo($tgl)` | Alias dari formatTanggalIndo | "Senin, 24 Maret 2026 14:30" |
| `formatWaktuSaja($waktu)` | Format waktu | "14:30" |

---

### 📋 JSON Response Best Practices

**✅ BEST PRACTICE: Gunakan `url()->previous()` untuk AJAX forms**

```php
// ✅ BENAR - Auto reload halaman sebelumnya
public function store(Request $request)
{
    $this->service->create($request->validated());
    return jsonSuccess('Data berhasil disimpan.', url()->previous());
}

// ✅ BENAR - Redirect ke route spesifik jika perlu
public function store(Request $request)
{
    $product = $this->service->create($request->validated());
    return jsonSuccess('Product berhasil dibuat.', route('products.index'));
}

// ❌ SALAH - Tidak ada redirect, halaman tidak reload
public function store(Request $request)
{
    $this->service->create($request->validated());
    return jsonSuccess('Data berhasil disimpan.'); // ❌ No redirect!
}
```

**Why `url()->previous()`?**
- ✅ Tidak perlu hardcode route
- ✅ Otomatis reload halaman yang sama
- ✅ Lebih maintainable
- ✅ Modal akan tertutup otomatis (core-ajax.js handle)

**Documentation:** [JSON_RESPONSE_BEST_PRACTICES.md](./JSON_RESPONSE_BEST_PRACTICES.md)

---

## 20a. Best Practices & Code Quality

### ✅ WAJIB - Model Traits

Setiap model yang ter-ekspos ke URL **WAJIB** menggunakan traits berikut:

```php
class MyModel extends Model
{
    use SoftDeletes;        // Soft delete (deleted_at)
    use HashidBinding;      // ID encryption untuk URL safety
    use Blameable;          // Auto-fill created_by, updated_by, deleted_by (USER NAME)
    use LogsActivity;       // Activity logging ke activity_log
}
```

**Blameable Trait - Penting!**
- Menyimpan **USER NAME (string)**, BUKAN ID
- Kolom database: `created_by`, `updated_by`, `deleted_by` (string, nullable)
- Keuntungan: Audit trail langsung terbaca tanpa join users table

**Migration Example:**
```php
$table->string('created_by')->nullable();
$table->string('updated_by')->nullable();
$table->string('deleted_by')->nullable();
```

### ✅ WAJIB - Service getBaseQuery()

Setiap Service **WAJIB** memiliki method `getBaseQuery()` untuk DataTables:

```php
class UserService
{
    public function getBaseQuery(): Builder
    {
        return User::with(['roles', 'media'])->whereNull('deleted_at');
    }
}
```

**Controller Usage:**
```php
public function data(Request $request)
{
    $query = $this->userService->getBaseQuery();
    
    return DataTables::of($query)->make(true);
}
```

### ✅ WAJIB - Gunakan Accessor encrypted_id

**JANGAN** pakai `encryptId()` manual di controller/view:

```php
// ❌ SALAH
$encryptedId = encryptId($user->id);
route('users.edit', $encryptedId);

// ✅ BENAR - Pakai accessor
route('users.edit', $user->encrypted_id);
```

**Model Setup:**
```php
class User extends Model
{
    use HashidBinding;
    
    // Accessor sudah ada di trait HashidBinding
    // public function getHashidAttribute() { return encryptId($this->id); }
}
```

### ✅ WAJIB - Consistent Return Types

Service methods harus konsisten return type-nya:

```php
// ✅ BENAR - Return model yang diupdate
public function updateUser(int $userId, array $data): User
{
    return DB::transaction(function () use ($userId, $data) {
        $user = User::findOrFail($userId);
        $user->update($data);
        return $user; // Return updated user
    });
}

// ❌ SALAH - Return bool (tidak informatif)
public function updateUser(int $userId, array $data): bool
{
    // ...
    return true;
}
```

### ✅ WAJIB - Helper Function Type Hints

Semua helper function WAJIB punya return type hint:

```php
// ✅ BENAR
function logActivity($logName, $description, $subject = null, $properties = []): \Spatie\Activitylog\Models\Activity
{
    // ...
    return $activity->withProperties($properties)->log($description);
}

// ❌ SALAH - No return type
function logActivity($logName, $description, $subject = null, $properties = [])
{
    // ...
}
```

### ✅ WAJIB - No Duplicate Helpers

**JANGAN** duplicate helper functions di multiple files:
- `formatTanggalIndo()` → Hanya di `GlobalHelper.php`
- `encryptId()` → Hanya di `SysHelper.php`

Jika ada duplicate, hapus dan gunakan yang sudah ada.

### ✅ WAJIB - Query Limits untuk Dashboard

Semua query untuk dashboard statistics WAJIB pakai limit:

```php
// ✅ BENAR
Activity::latest()->limit(10)->get();

// ❌ SALAH - Load semua data
Activity::latest()->get();
```

### ✅ WAJIB - Zero Try-Catch di Business Logic

**JANGAN** pakai try-catch di controller/service untuk business logic:

```php
// ❌ SALAH
public function store(Request $request)
{
    try {
        $this->userService->create($request->validated());
        return jsonSuccess('Success');
    } catch (\Exception $e) {
        logError($e);
        return jsonError($e->getMessage());
    }
}

// ✅ BENAR - Biarkan global handler yang handle
public function store(Request $request)
{
    $this->userService->create($request->validated());
    return jsonSuccess('Success');
}
```

**Exception:** Try-catch diperbolehkan untuk:
- File operations dengan graceful fallback
- External API calls dengan retry logic
- Operations yang memang butuh recovery

### ✅ WAJIB - Eager Loading untuk Prevent N+1

```php
// ❌ SALAH - N+1 query problem
$users = User::all();
foreach ($users as $user) {
    echo $user->roles->name; // Query tambahan per user
}

// ✅ BENAR - Eager loading
$users = User::with('roles')->get();
foreach ($users as $user) {
    echo $user->roles->name; // No additional query
}
```

### ✅ WAJIB - Helper Function untuk Complex Logic

Untuk logic yang kompleks dan reusable, buat helper function:

```php
// ✅ BENAR - Helper di GlobalHelper.php atau SysHelper.php
function generateKodeInventaris($labId, $inventarisId)
{
    // Complex logic here
    return $kodeInventaris;
}

// Usage
$kode = generateKodeInventaris($labId, $inventarisId);
```

---

## 20b. Common Issues & Fixes Reference

| Issue | Solution |
|-------|----------|
| Blameable type inconsistency | Gunakan `string` (name), bukan `bigint` (ID) |
| Missing getBaseQuery() di Service | Add method dengan eager loading |
| Manual encryptId() di controller | Pakai accessor `$model->encrypted_id` |
| Duplicate helper functions | Hapus duplicate, keep di file yang benar |
| Try-catch redundant di service | Hapus, biarkan global handler |
| Query tanpa limit di dashboard | Add `->limit($n)` |
| Inconsistent return types | Return model, bukan bool |
| Missing return type hints | Add `: ReturnType` |

---

## 21. Folder Structure & Naming Convention

```
app/
├── Config/                     # Config class (seperti PemutuDokumenConfig)
├── Exceptions/                 # Custom exceptions (DataNotFoundException)
├── Helpers/                    # Global helper functions
│   ├── GlobalHelper.php        # Tanggal, format
│   ├── SysHelper.php           # Encrypt, log, JSON response
│   ├── ApprovalHelpers.php     # Approval helpers
│   ├── PemutuHelper.php        # Pemutu-specific helpers
│   ├── HrHelper.php            # HR-specific helpers
│   ├── PmbHelper.php           # PMB-specific helpers
│   ├── LabHelper.php           # Lab-specific helpers
│   └── EofficeHelper.php       # E-Office helpers
├── Http/
│   ├── Controllers/
│   │   ├── Hr/                 # HR controllers
│   │   ├── Pemutu/             # Pemutu controllers
│   │   ├── Pmb/                # PMB controllers
│   │   ├── Sys/                # System controllers
│   │   └── ...
│   ├── Middleware/              # Custom middleware
│   └── Requests/               # Form Requests (extends BaseRequest)
├── Models/
│   ├── Hr/                     # HR models
│   ├── Pemutu/                 # Pemutu models
│   └── ...
├── Services/
│   ├── Hr/                     # HR services (satu per model)
│   ├── Pemutu/                 # Pemutu services
│   └── ...
└── Traits/
    ├── HashidBinding.php       # Encrypted ID trait
    └── Blameable.php           # Audit trait

resources/
├── tabler-core/
│   ├── css/
│   │   └── tabler.css          # CSS entry point utama
│   ├── js/
│   │   ├── tabler.js           # JS entry point utama
│   │   ├── core-ajax.js        # Global AJAX form handler
│   │   ├── core-alerts.js      # SweetAlert2 functions
│   │   └── core-theme.js       # Dark/Light mode handler
│   └── views/components/tabler/ # 33 Blade components
├── css/
│   ├── auth.css                # Auth pages CSS
│   └── public.css              # Public pages CSS
├── js/
│   ├── auth.js                 # Auth pages JS
│   ├── public.js               # Public pages JS
│   └── helpers/                # Module-specific JS helpers
└── views/
    ├── layouts/                # 4 layout contexts
    ├── pages/                  # Views per module
    └── components/             # Shared Blade components
```

### Naming Convention

| Item | Convention | Contoh |
|------|-----------|--------|
| Controller | PascalCase + Controller | `UserController.php` |
| Service | PascalCase + Service | `UserService.php` |
| Model | PascalCase singular | `User.php` |
| FormRequest | PascalCase + Request | `UserRequest.php` |
| Migration | snake_case, timestamp prefix | `2026_01_01_create_users_table.php` |
| View | kebab-case | `create-edit-ajax.blade.php` |
| Route name | dot-separated | `hr.pegawai.index` |
| Injected Service property | PascalCase (ikut nama class) | `$UserService` |
| DB table | `{module}_{entity}` | `hr_pegawai`, `pemutu_dokumen` |
| Primary Key | `{entity}_id` | `pegawai_id`, `dokumen_id` |

---

## 22. Development Workflow

### Setup Awal
```bash
composer setup   # install deps → generate key → migrate → build frontend
```

### Development Mode
```bash
composer run dev
# Menjalankan secara bersamaan:
# 1. php artisan serve (HTTP server)
# 2. php artisan queue:listen (Queue worker)
# 3. php artisan pail (Real-time log viewer)
# 4. npm run dev (Vite HMR)
```

### Wajib Sebelum Commit
```bash
vendor/bin/pint --dirty --format agent   # Fix code style
php artisan test --compact                # Run semua test
```

### Membuat File Baru (Artisan)
```bash
php artisan make:model NamaModel --no-interaction
php artisan make:controller NamaController --no-interaction
php artisan make:test NamaTest --phpunit --no-interaction
php artisan make:request NamaRequest --no-interaction
php artisan make:class NamaService --no-interaction
```

---

**Status Dokumen:** March 2026 — Fully Consolidated dari riset mendalam terhadap seluruh source code proyek.
