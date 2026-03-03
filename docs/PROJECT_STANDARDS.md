# Project Standardization Guide (Single Source of Truth)

**Last Updated:** Februari 2026  
**Laravel Version:** 12.x  
**PHP Version:** 8.4+

Dokumen ini adalah referensi teknis mendalam (*Single Source of Truth*) untuk seluruh arsitektur, standar koding, dan keamanan proyek ini. Seluruh pengembang wajib mematuhi pedoman ini tanpa pengecualian.

---

## Table of Contents

1. [Arsitektur Backend](#1-arsitektur-backend)
   - [A. Service-Oriented Architecture](#a-service-oriented-architecture)
   - [A.2. Service Delegation Pattern](#a2-service-delegation-pattern-cross-service-usage)
   - [A.3. Constructor Injection Pattern](#a3-constructor-injection-pattern)
   - [A.4. Blameable Trait Implementation](#a4-blameable-trait-implementation)
   - [B. Route Model Binding & Security](#c-route-model-binding--security-encrypted-id)
   - [C. Validation (Form Requests)](#d-validation-form-requests)
   - [D. Model Traits & Best Practices](#e-model-traits--best-practices)
   - [E. Responses & Global Error Handling](#f-responses-global-helpers--global-error-handling)
   - [F. Centralized Venezuelan Validation (Bahasa Indonesia)](#g-centralized-indonesian-validation)

2. [Frontend & UI Standardization](#2-frontend--ui-standardization)
   - [A. Layout Structure](#a-layout-structure-multi-context)
   - [B. Vite Asset Bundling](#b-vite-asset-bundling)
   - [C. Blade Components](#c-blade-components-x-tabler)
   - [D. JavaScript Libraries](#d-javascript-libraries)
   - [E. AJAX Handlers](#e-ajax-handlers)
   - [F. Unified Views Pattern](#f-unified-views-pattern)
   - [G. JavaScript Re-initialization](#g-javascript-re-initialization-pattern)
   - [H. Global Search Implementation](#h-global-search-implementation)

3. [Database & System Helpers](#3-database--system-helpers)
   - [A. Sys Helpers](#a-sys-helpers-apphelperssyshelperphp)
   - [B. Global Helpers](#b-global-helpers-apphelpersglobalhelperphp)
   - [C. Activity Logging](#c-activity-logging)
   - [D. Error Logging](#d-error-logging)
   - [E. Approval System Pattern](#e-approval-system-pattern)

4. [Struktur Folder & Naming](#4-struktur-folder--naming)

5. [Authentication & Authorization](#5-authentication--authorization)

6. [Features & Packages](#6-features--packages)
   - [A. Core Packages](#a-core-packages)
   - [B. Key Features](#b-key-features)
   - [C. Frontend Libraries](#c-frontend-libraries)
   - [D. Theme Toggle Pattern](#d-theme-toggle-pattern-server-first-approach)
   - [E. DataTables State Persistence](#e-datatables-state-persistence)
   - [F. Custom Event System](#f-custom-event-system-ajax-forms)

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

---

### A.2. Service Delegation Pattern (Cross-Service Usage)

> **Prinsip Inti**: Setiap model memiliki satu Service yang bertanggung jawab atas domain-nya. Controller dan Service lain **WAJIB** memanggil service yang tepat — bukan query model langsung.

#### ❌ Anti-Pattern: Direct Model Query di Luar Domain-nya

```php
// ❌ SALAH — AmiController query PeriodeSpmi langsung
class AmiController extends Controller {
    public function index() {
        $periodes = PeriodeSpmi::orderBy('tahun', 'desc')->get(); // DILARANG!
    }
}

// ❌ SALAH — TimMutuController query PeriodeSpmi langsung
class TimMutuController extends Controller {
    public function index() {
        $periodes = PeriodeSpmi::orderByDesc('periode')->get(); // DILARANG!
    }
}
```

#### ✅ Correct Pattern: Delegate ke Service yang Tepat

```php
// ✅ BENAR — Inject PeriodeSpmiService, gunakan method-nya
class AmiController extends Controller {
    public function __construct(
        protected AmiService $AmiService,
        protected PeriodeSpmiService $PeriodeSpmiService, // inject service domain lain
    ) {}

    public function index() {
        $periodes = $this->PeriodeSpmiService->getPeriodes(); // delegate ✅
    }
}

// ✅ BENAR — TimMutuController gunakan PeriodeSpmiService
class TimMutuController extends Controller {
    public function __construct(
        protected TimMutuService $timMutuService,
        protected PeriodeSpmiService $PeriodeSpmiService,
    ) {}

    public function index() {
        $periodes = $this->PeriodeSpmiService->getAll(); // delegate ✅
    }
}
```

#### Standard Query Methods di Setiap Service

Setiap Service **WAJIB menyediakan** method standar berikut jika modelnya diakses dari luar:

| Method | Return Type | Kegunaan |
|--------|-------------|----------|
| `getAll()` | `Collection` | List lengkap untuk dropdown/select |
| `getPeriodes(int $perPage)` | `LengthAwarePaginator` | Paginated list untuk halaman index |
| `getBaseQuery()` | `Builder` | Raw query untuk DataTables |
| `getPeriodeAktif()` / `getAktif()` | `Model\|null` | Ambil record aktif saat ini |

#### Scope Domain Setiap Service

| Service | Domain Model | Siapa yang Boleh Inject |
|---------|-------------|------------------------|
| `PeriodeSpmiService` | `PeriodeSpmi` | Semua controller/service yang butuh periode SPMI |
| `PeriodeService` | `PeriodeKpi` | Semua controller/service yang butuh periode KPI |
| `IndikatorService` | `Indikator` | Controller yang manage indikator |
| `TimMutuService` | `TimMutu` | Controller tim mutu |
| `AmiService` | `IndikatorOrgUnit` (AMI context) | `AmiController` |

> **Pengecualian**: `DashboardController` boleh query banyak model langsung karena fungsinya adalah **agregasi statistik lintas domain** — bukan CRUD dari satu domain.

---

### A.3. Constructor Injection Pattern

Gunakan constructor injection dengan nama properti mengikuti **PascalCase** (mengikuti nama class service):

```php
// ✅ Good - PascalCase mengikuti nama class
public function __construct(
    protected AmiService $AmiService,
    protected PeriodeSpmiService $PeriodeSpmiService,
    protected UserService $UserService,
) {}

// ❌ Bad - snake_case (tidak konsisten)
public function __construct(
    protected AmiService $ami_service,
    protected PeriodeSpmiService $periode_spmi_service,
) {}

// ❌ Bad - camelCase lowercase (tidak konsisten dengan nama class)
public function __construct(
    protected AmiService $amiService,
    protected PeriodeSpmiService $periodeSpmiService,
) {}
```

**Rationale:** Nama properti PascalCase memudahkan tracking saat search & replace, dan konsisten dengan naming convention class.

---

### A.4. Blameable Trait Implementation

Trait `Blameable` otomatis mengisi `created_by`, `updated_by`, dan `deleted_by` dari user yang terautentikasi.

**⚠️ IMPORTANT:** Implementasi saat ini menggunakan **string (nama user)**, bukan integer (ID user).

```php
// app/Traits/Blameable.php
trait Blameable
{
    public static function bootBlameable()
    {
        static::creating(function ($model) {
            if (Auth::check() && $model->isFillable('created_by')) {
                $model->created_by = Auth::user()->name; // STRING, bukan ID!
            }
        });

        static::updating(function ($model) {
            if (Auth::check() && $model->isFillable('updated_by')) {
                $model->updated_by = Auth::user()->name; // STRING, bukan ID!
            }
        });

        static::deleting(function ($model) {
            if (Auth::check() && $model->isFillable('deleted_by')) {
                if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                    $model->deleted_by = Auth::user()->name; // STRING, bukan ID!
                    $model->saveQuietly();
                }
            }
        });
    }
}
```

**Database Schema:**
```php
// Migration
$table->string('created_by')->nullable();
$table->string('updated_by')->nullable();
$table->string('deleted_by')->nullable();
```

**Usage di Model:**
```php
// app/Models/User.php
use App\Traits\Blameable;

class User extends Model
{
    use Blameable;

    protected $fillable = ['name', 'email', 'created_by', 'updated_by', 'deleted_by'];
}
```

**Query berdasarkan Blameable:**
```php
// Get users created by specific user (by name, not ID!)
$users = User::where('created_by', 'John Doe')->get();

// Eager load tidak diperlukan karena ini kolom biasa, bukan relasi
```

---


Gunakan constructor injection. Nama properti service **WAJIB PascalCase** (mengikuti nama class):

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

    public function attributes(): array
    {
        return [
            'name'     => 'Nama Pengguna',
            'email'    => 'Alamat Email',
            'password' => 'Kata Sandi',
        ];
    }
}
```

#### ✅ BaseRequest & Attributes Usage
Gunakan `BaseRequest` sebagai parent class untuk semua `FormRequest` agar otomatis menggunakan pesan validasi Bahasa Indonesia tersentralisasi. Selain itu, **WAJIB** menyertakan method `attributes()` untuk menamai ulang atribut agar ramah pengguna (*human-readable*).

```php
// app/Http/Requests/BaseRequest.php
abstract class BaseRequest extends FormRequest {
    public function messages(): array {
        // ... (mengembalikan seluruh pesan generic terpusat)
    }
}

// app/Http/Requests/Sys/UserRequest.php
class UserRequest extends BaseRequest {
    public function rules(): array { 
        return [
            'name' => 'required|string',
        ];
    }
    
    public function attributes(): array {
        return [
            'name' => 'Nama Lengkap',
        ];
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

### F. Responses (Global Helpers & Global Error Handling)

Seluruh penanganan error di aplikasi telah **disentralisasi** melalui `bootstrap/app.php`. 

> [!IMPORTANT]
> **HINDARI** penggunaan blok `try-catch` manual di Controller hanya untuk `logError` atau `jsonError` standar. Exception yang tidak ditangkap akan otomatis dihandle secara global.

#### Centralized Error Logic (`bootstrap/app.php`)
Handler global otomatis melakukan:
1. **Logging**: Memanggil `logError()` (ke tabel `sys_error_log`) untuk setiap exception.
2. **AJAX/JSON Response**: Mengembalikan `jsonError()` jika request mengharapkan JSON/AJAX.
3. **HTTP Redirect**: Melakukan `back()->withInput()->with('error', ...)` untuk request web biasa.
4. **Activity Log**: Tetap mencatat aktivitas jika diperlukan.

#### Standard Response Usage
```php
// ✅ BENAR - Controller lean, biarkan handler tangkap exception
public function store(UserRequest $request)
{
    $this->userService->createUser($request->validated());
    return jsonSuccess('User created', route('users.index'));
}

// ❌ SALAH - Jangan pakai try-catch manual jika hanya untuk logging standar
public function store(UserRequest $request)
{
    try {
        $this->userService->createUser($request->validated());
        return jsonSuccess('User created', route('users.index'));
    } catch (\Exception $e) {
        logError($e);
        return jsonError($e->getMessage());
    }
}
```

#### JSON Response Helpers
```php
// Success response
jsonSuccess('Operation successful', route('users.index'));

// Success with data
jsonSuccess([
    'data' => $user,
    'redirect' => route('users.show', $user),
]);

// Error response (Manual error, bukan Exception)
jsonError('Kredensial tidak valid', 401);
```

### G. Centralized Indonesian Validation

Untuk memastikan seluruh pesan validasi menggunakan Bahasa Indonesia yang konsisten, proyek ini mempusatkan *error messages* di dalam `BaseRequest`.

**Aturan & Cara Penggunaan:**
1. Semua class Request **WAJIB** extend `App\Http\Requests\BaseRequest`. `BaseRequest` menangani seluruh format pesan validasi bawaan tipe data, *rules* standar, size, dll.
2. **WAJIB** menambahkan method `attributes()` di setiap class Request untuk mendefinisikan alias atribut yang ramah pengguna. *Hindari mendefinisikan array `messages` manual jika fungsi `attributes()` sudah cukup mengubah pesan error default*.
3. Hanya override method `messages()` jika membutuhkan **pesan kesalahan yang sangat spesifik / unik** untuk field tertentu. Jika melakukannya, wajib menggunakan `array_merge()` dengan `parent::messages()`.

```php
public function messages(): array
{
    // Cukup tambahkan khusus untuk rule spesifik ini:
    return array_merge(parent::messages(), [
        'email.unique' => 'Alamat email ini dipastikan sudah terdaftar di sistem kami.',
    ]);
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

Sistem ini menggunakan **Blade Components** sebagai lapisan abstraksi UI. Arsitektur ini bertujuan untuk menjamin konsistensi visual di seluruh modul, mengurangi redundansi kode HTML, dan mengotomatisasi integrasi library pihak ketiga (Select2, Flatpickr, FilePond).

**🔴 ATURAN EMAS - WAJIB DIPATUHI:**
> **DILARANG KERAS** menggunakan tag HTML manual seperti `<button>`, `<input>`, `<select>`, atau manual bootstrap classes (`class="btn btn-primary"`) jika komponen `<x-tabler.*>` tersedia. 
> 
> Jika Anda menemukan keterbatasan pada komponen, **perbarui komponen tersebut** di `resources/views/components/tabler/` daripada menulis HTML ad-hoc.

---

#### 1. Page Header (`<x-tabler.page-header>`)
Digunakan secara wajib di bagian atas setiap halaman konten. Mengatur hierarki judul dan menstandarkan peletakan tombol aksi.

**Usage Patterns:**
```blade
<x-tabler.page-header 
    title="Daftar Pegawai" 
    pretitle="Kepegawaian"
>
    <x-slot:actions>
        <x-tabler.button type="create" :href="route('pegawai.create')" />
        <x-tabler.button type="export" :href="route('pegawai.export')" />
    </x-slot:actions>
</x-tabler.page-header>
```

**Penting Diketahui:**
- **Pretitle**: Gunakan untuk nama modul atau kategori besar.
- **Actions Slot**: Semua tombol di dalam slot ini akan otomatis dibungkus dengan `.btn-list` untuk jarak yang rapi.

---

#### 2. Buttons (`<x-tabler.button>`)
Komponen pintar yang mengelola icon, warna, dan perilaku standar berdasarkan atribut `type`.

**Konfigurasi Tipe Standar:**
| Type | Color | Icon | Default Text | Behavior |
|------|-------|------|--------------|----------|
| `create` | `primary` | `plus` | Tambah | Render `<a>` jika ada `href` |
| `back` | `outline-secondary` | `arrow-left` | Kembali | Auto `history.back()` jika tanpa `href` |
| `submit` | `primary` | `device-floppy`| Simpan | `type="submit"` |
| `cancel` | `outline-secondary` | `x` | Batal | Auto `history.back()` / close modal |
| `delete` | `danger` | `trash` | Hapus | Terintegrasi dengan `ajax-delete` |
| `edit` | `primary` | `edit` | Ubah | |

**🔴 Anti-pattern vs ✅ Best Practice:**
```blade
{{-- ❌ SALAH: Menentukan icon dan class manual --}}
<x-tabler.button type="back" class="btn-secondary" icon="ti ti-chevron-left" text="Balik" />

{{-- ✅ BENAR: Percayakan pada default komponen --}}
<x-tabler.button type="back" />
```

**Props Utama:**
- `iconOnly`: Set `true` untuk tombol tanpa teks (`btn-icon`).
- `modalUrl` & `modalTitle`: Otomatis mengaktifkan `ajax-modal-btn`.

---

#### 3. DataTables (`<x-tabler.datatable>`)
Standardisasi penampilan data dalam jumlah besar dengan fitur server-side rendering.

**Usage Patterns:**
```blade
<x-tabler.datatable
    id="userTable"
    :url="route('admin.users.paginate')"
    :columns="[
        ['data' => 'name', 'title' => 'Nama'],
        ['data' => 'email', 'title' => 'Email'],
        ['data' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
    ]"
    :checkbox="true"
/>
```

**Integrasi Controller (`datatables-actions`):**
Untuk kolom aksi, **WAJIB** menggunakan component view di dalam Service/Controller.
```php
->addColumn('action', function ($row) {
    return view('components.tabler.datatables-actions', [
        'editUrl'   => route('users.edit', $row->encrypted_id),
        'editModal' => true,
        'deleteUrl' => route('users.destroy', $row->encrypted_id),
        'extraActions' => [
             ['text' => 'Reset Password', 'url' => '#', 'icon' => 'ti-key', 'class' => 'reset-btn']
        ]
    ])->render();
})
```

---

#### 4. Form Modal (`<x-tabler.form-modal>`)
Wrapper untuk modal yang mendukung pengiriman form via AJAX secara otomatis.

**Usage Patterns:**
```blade
<x-tabler.form-modal 
    id="modalPegawai" 
    title="Input Data Pegawai" 
    :route="route('pegawai.store')"
    size="lg"
>
    <x-tabler.form-input name="name" label="Nama" required="true" />
    <x-tabler.form-select name="jabatan" label="Jabatan" :options="$jabatanOptions" />
</x-tabler.form-modal>
```

**Fitur Canggih:**
- **Automatic AJAX**: Jika `:route` disediakan, modal akan otomatis menggunakan `class="ajax-form"`.
- **Slot Kustom**: Gunakan `<x-slot:titleSlot>` untuk mengubah seluruh area header modal.
- **Redirect Logic**: Atur `data-redirect="true"` untuk merefresh halaman setelah sukses simpan.

---

#### 5. Form Fields (`x-tabler.form-*`)

**A. Form Input & Flatpickr**
Mengelola label, validasi error, dan integrasi datepicker.
- `type="date/datetime/range"`: Otomatis memicu Flatpickr.
- `type="password"`: Menyertakan toggle "eye" (visibility) secara otomatis.
- `type="file"`: Terintegrasi dengan FilePond (jika diaktifkan).

**B. Form Select & Select2**
Gunakan `type="select2"` untuk pencarian dropdown yang canggih.
```blade
<x-tabler.form-select 
    name="unit_id" 
    label="Unit Kerja" 
    :options="$units" 
    type="select2"
/>
```

**C. Form Textarea & HugeRTE**
Dua mode penggunaan:
- **Normal**: Untuk input teks panjang biasa.
- **Editor**: Gunakan `type="editor"` untuk mengaktifkan Rich Text Editor (HugeRTE).

**D. Checkbox & Radio**
Mendukung array naming dan state `checked` berbasis `old()` input.
- `<x-tabler.form-checkbox switch="true" />`: Mengubah tampilan menjadi toggle switch.

---

#### 6. Flash Message & Empty State
Komponen pemberi feedback kepada pengguna.

- **Flash Message**: **WAJIB** diletakkan di setiap halaman index/create/edit (biasanya sudah ada di `app.blade.php`). Menangani session `success`, `error`, dan `$errors`.
- **Empty State**: **WAJIB** ditampilkan jika data tabel/list kosong untuk menjaga estetika UI.

```blade
@if($items->isEmpty())
    <x-tabler.empty-state 
        title="Belum Ada Data" 
        subtitle="Klik tombol Tambah untuk memulai." 
    />
@endif
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

### G. JavaScript Re-initialization Pattern

**PENTING:** Saat menambahkan elemen form via AJAX/Modal, komponen **TIDAK** otomatis terinisialisasi. Anda **WAJIB** memanggil fungsi re-init secara manual.

#### Komponen yang Perlu Re-init

| Komponen | Class/Trigger | Fungsi Re-init |
|----------|---------------|----------------|
| Flatpickr (Date Picker) | `.flatpickr-input` | `window.initFlatpickr()` |
| Select2 (Dropdown) | `.select2-offline` | `window.initOfflineSelect2()` |
| FilePond (File Upload) | `.filepond-input` | `window.initFilePond()` |
| HugeRTE (Rich Text Editor) | `textarea` dengan `type="editor"` | `window.loadHugeRTE('#id')` |

#### Kapan Re-init Diperlukan?

1. **Setelah Modal AJAX Dibuka** - `FormHandlerAjax.js` sudah handle otomatis
2. **Setelah AJAX Load Content** - Manual call diperlukan
3. **Setelah Dynamic Content Insert** - Manual call diperlukan

#### Example: Manual Re-init Setelah AJAX

```javascript
// Load content via AJAX
axios.get('/api/form-content')
    .then(function(response) {
        $('#formContainer').html(response.data);
        
        // MANUAL RE-INIT - WAJIB!
        if (typeof window.initFlatpickr === 'function') {
            window.initFlatpickr();
        }
        if (typeof window.initOfflineSelect2 === 'function') {
            window.initOfflineSelect2();
        }
        if (typeof window.initFilePond === 'function') {
            window.initFilePond();
        }
    });
```

#### Example: Re-init HugeRTE di Modal

```javascript
// After modal content loaded
$('#formModal').on('shown.bs.modal', function() {
    // Re-init HugeRTE if present
    if (typeof window.loadHugeRTE === 'function') {
        $(this).find('textarea.form-control').each(function() {
            const id = $(this).attr('id');
            if (id && $(this).closest('.tinymce-container').length > 0) {
                window.loadHugeRTE('#' + id, {
                    height: 200,
                    menubar: false,
                    statusbar: false,
                    setup: function(editor) {
                        editor.on('change', function() {
                            editor.save();
                        });
                    }
                });
            }
        });
    }
});
```

#### Example: Cleanup HugeRTE Saat Modal Tutup

```javascript
// Cleanup to prevent initialization issues
$('#formModal').on('hidden.bs.modal', function() {
    const $modal = $(this);
    const $form = $modal.find('.ajax-form');
    
    // Clean up HugeRTE instances
    if (typeof window.hugerte !== 'undefined') {
        $modal.find('textarea.form-control').each(function() {
            const id = $(this).attr('id');
            if (id && window.hugerte.get(id)) {
                window.hugerte.remove('#' + id);
            }
        });
    }
    
    // Reset form
    if ($form.length) {
        $form[0].reset();
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').remove();
    }
});
```

#### Auto Re-init di FormHandlerAjax.js

`FormHandlerAjax.js` **sudah otomatis** melakukan re-init setelah AJAX modal dibuka:

```javascript
// Di FormHandlerAjax.js - AJAX Modal Button
.then(function (response) {
    $modalContent.html(response.data);
    
    // Auto re-init components
    if (typeof window.initOfflineSelect2 === 'function') {
        window.initOfflineSelect2();
    }
    if (typeof window.initFlatpickr === 'function') {
        window.initFlatpickr();
    }
    if (typeof window.initFilePond === 'function') {
        window.initFilePond();
    }
    
    // Re-init HugeRTE if present
    if (typeof window.loadHugeRTE === 'function') {
        // ... auto init code
    }
});
```

**CATATAN:** Untuk custom AJAX calls di luar `FormHandlerAjax.js`, Anda **WAJIB** manual re-init.

---

### H. Global Search Implementation

Fitur global search memungkinkan pencarian lintas model dengan real-time results.

#### Architecture

```
User Input → GlobalSearch.js → AJAX /global-search → GlobalSearchController
                                    ↓
                            Spatie Searchable
                                    ↓
                            Multi-model results → JSON Response → Modal Display
```

#### Component Usage

```blade
<!-- Search Icon di Header -->
<button class="btn btn-icon" data-bs-toggle="modal" data-bs-target="#globalSearchModal">
    <i class="ti ti-search"></i>
</button>

<!-- Modal Component -->
<x-tabler.modal-global-search />
```

#### Controller Implementation

```php
// app/Http/Controllers/Lab/GlobalSearchController.php
use Spatie\Searchable\Search;
use Spatie\Searchable\Searchable;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $searchResults = (new Search())
            ->registerModel(\App\Models\User::class, ['name', 'email'])
            ->registerModel(\App\Models\Lab\Lab::class, ['name', 'kode'])
            ->registerModel(\App\Models\Hr\Pegawai::class, ['name', 'nip'])
            ->search($query);
        
        return response()->json([
            'success' => true,
            'results' => $searchResults->toArray(),
        ]);
    }
}
```

#### Model Setup (Spatie Searchable)

```php
// app/Models/User.php
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class User extends Model implements Searchable
{
    public function getSearchResult(): SearchResult
    {
        return new SearchResult($this, route('admin.users.show', $this->encrypted_id));
    }
}
```

#### JavaScript (GlobalSearch.js)

```javascript
// resources/assets/tabler/js/GlobalSearch.js
export class GlobalSearch {
    constructor() {
        this.input = document.querySelector('#global-search-input');
        this.resultsContainer = document.querySelector('#global-search-results');
        this.bindEvents();
    }
    
    bindEvents() {
        this.input.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            if (query.length >= 3) {
                this.search(query);
            }
        });
    }
    
    async search(query) {
        const response = await axios.get(`/global-search?q=${query}`);
        this.renderResults(response.data.results);
    }
    
    renderResults(results) {
        // Render results to modal
    }
}
```

#### Route Setup

```php
// routes/web.php
Route::get('/global-search', [GlobalSearchController::class, 'search'])
    ->middleware(['auth', 'check.expired']);
```

#### Best Practices

1. **Minimum 3 Characters** - Trigger search setelah 3 karakter untuk performa
2. **Debounce Input** - Gunakan debounce 300ms untuk mengurangi request
3. **Limit Results** - Max 10 results per model
4. **Encrypted IDs** - Selalu gunakan encrypted ID di result URLs
5. **Eager Loading** - Gunakan `with()` untuk prevent N+1

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

### E. Approval System Pattern

Sistem approval digunakan untuk workflow yang memerlukan persetujuan berjenjang (HR, Dokumen, dll).

#### Architecture

```
Request → ApprovalController → ApprovalService → Update Model + Log Approval
                                    ↓
                            Notification ke Approver
                                    ↓
                            Approver Approve/Reject → Update Status
```

#### Database Schema

```php
// Table: hr_approvals (atau {module}_approvals)
Schema::create('hr_approvals', function (Blueprint $table) {
    $table->id();
    $table->morphs('approvable'); // approvable_type, approvable_id
    $table->foreignId('approver_id')->constrained('users');
    $table->enum('status', ['pending', 'approved', 'rejected']);
    $table->text('notes')->nullable();
    $table->timestamp('approved_at')->nullable();
    $table->timestamps();
});
```

#### Helper Functions (ApprovalHelpers.php)

```php
// app/Helpers/ApprovalHelpers.php

/**
 * Get pending approvals for current user
 */
function getPendingApprovals($user = null)
{
    $user = $user ?? auth()->user();
    
    return \App\Models\Hr\Approval::where('approver_id', $user->id)
        ->where('status', 'pending')
        ->with('approvable')
        ->get();
}

/**
 * Check if user can approve specific model
 */
function canApprove($model, $user = null)
{
    $user = $user ?? auth()->user();
    
    return \App\Models\Hr\Approval::where('approvable_type', get_class($model))
        ->where('approvable_id', $model->id)
        ->where('approver_id', $user->id)
        ->where('status', 'pending')
        ->exists();
}

/**
 * Approve model
 */
function approveModel($model, $notes = null)
{
    $approval = \App\Models\Hr\Approval::where('approvable_type', get_class($model))
        ->where('approvable_id', $model->id)
        ->where('status', 'pending')
        ->where('approver_id', auth()->id())
        ->first();
    
    if ($approval) {
        $approval->update([
            'status' => 'approved',
            'notes' => $notes,
            'approved_at' => now(),
        ]);
        
        logActivity('approval', "Approved {$model->getMorphClass()} #{$model->id}", $model);
        
        // Notify requester
        $model->requester->notify(new ApprovalNotification($model, 'approved'));
    }
    
    return $approval;
}

/**
 * Reject model
 */
function rejectModel($model, $notes = null)
{
    $approval = \App\Models\Hr\Approval::where('approvable_type', get_class($model))
        ->where('approvable_id', $model->id)
        ->where('status', 'pending')
        ->where('approver_id', auth()->id())
        ->first();
    
    if ($approval) {
        $approval->update([
            'status' => 'rejected',
            'notes' => $notes,
            'approved_at' => now(),
        ]);
        
        logActivity('approval', "Rejected {$model->getMorphClass()} #{$model->id}", $model);
        
        // Notify requester
        $model->requester->notify(new ApprovalNotification($model, 'rejected'));
    }
    
    return $approval;
}
```

#### Blade Component (Pending Approval Widget)

```blade
<!-- resources/views/components/hr/pending-approval-widget.blade.php -->
@php
    $pendingApprovals = getPendingApprovals();
@endphp

@if($pendingApprovals->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pending Approvals</h3>
            <span class="badge bg-red">{{ $pendingApprovals->count() }}</span>
        </div>
        <div class="card-body">
            @foreach($pendingApprovals as $approval)
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="fw-bold">{{ $approval->approvable->getShortDescription() }}</div>
                        <div class="text-muted small">{{ formatTanggalIndo($approval->created_at) }}</div>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-success btn-sm" onclick="approveItem({{ $approval->id }})">
                            <i class="ti ti-check"></i> Approve
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="rejectItem({{ $approval->id }})">
                            <i class="ti ti-x"></i> Reject
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
```

#### Approval History Component

```blade
<!-- resources/views/components/tabler/approval-history.blade.php -->
@props(['model'])

@php
    $approvals = $model->approvals()->with('approver')->orderBy('created_at')->get();
@endphp

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Approval History</h3>
    </div>
    <div class="card-body">
        @foreach($approvals as $approval)
            <div class="d-flex align-items-center mb-3">
                <div class="me-3">
                    @if($approval->status === 'approved')
                        <span class="badge bg-green-lt">Approved</span>
                    @elseif($approval->status === 'rejected')
                        <span class="badge bg-red-lt">Rejected</span>
                    @else
                        <span class="badge bg-yellow-lt">Pending</span>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold">{{ $approval->approver->name }}</div>
                    <div class="text-muted small">{{ $approval->notes }}</div>
                </div>
                <div class="text-muted small">
                    {{ formatTanggalIndo($approval->created_at) }}
                </div>
            </div>
        @endforeach
    </div>
</div>
```

#### Usage di Controller

```php
// app/Http/Controllers/Hr/PerizinanController.php

class PerizinanController extends Controller
{
    public function store(PerizinanRequest $request)
    {
        $data = $request->validated();
        $perizinan = Perizinan::create($data);
        
        // Create approval request
        $this->createApprovalRequest($perizinan);
        
        return jsonSuccess('Perizinan created. Waiting for approval.', route('hr.perizinan.index'));
    }
    
    public function approve(Perizinan $perizinan, Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);
        
        approveModel($perizinan, $request->notes);
        
        return jsonSuccess('Perizinan approved.');
    }
    
    public function reject(Perizinan $perizinan, Request $request)
    {
        $request->validate([
            'notes' => 'required|string|max:500',
        ]);
        
        rejectModel($perizinan, $request->notes);
        
        return jsonSuccess('Perizinan rejected.');
    }
    
    private function createApprovalRequest($perizinan)
    {
        // Get approver based on hierarchy
        $approver = $this->determineApprover($perizinan);
        
        \App\Models\Hr\Approval::create([
            'approvable_type' => get_class($perizinan),
            'approvable_id' => $perizinan->id,
            'approver_id' => $approver->id,
            'status' => 'pending',
        ]);
        
        // Notify approver
        $approver->notify(new ApprovalRequestNotification($perizinan));
    }
}
```

#### Best Practices

1. **Morphs Relationship** - Gunakan polymorphic relation untuk reusable approval system
2. **Approval Chain** - Support multi-level approval (sequential atau parallel)
3. **Notifications** - Selalu notify requester dan approver
4. **Audit Trail** - Log semua approval actions
5. **Timeout** - Auto-approve/reject setelah timeout tertentu (optional)

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

### D. Theme Toggle Pattern (Server-First Approach)

Theme toggle menggunakan **server-first approach** dengan optimistic UI update dan rollback mechanism.

#### Architecture

```
User Click → Update UI (Optimistic) → POST /theme/save → Server Save
                                              ↓
                                      Success: Save to localStorage
                                      Fail: Rollback UI + Notify
```

#### JavaScript Implementation (tabler.js)

```javascript
/**
 * Toggle theme with server-first approach
 * Sends request to server first, then updates UI only on success
 * @param {string} mode - 'light' or 'dark'
 */
window.toggleTheme = function (mode) {
    const previousMode = document.documentElement.getAttribute('data-bs-theme');

    // Optimistic UI update (for better UX, but we'll rollback if server fails)
    document.documentElement.setAttribute('data-bs-theme', mode);

    // Sync with ThemeTabler if available
    if (window.themeTabler) {
        window.themeTabler.refresh();
    }

    // Persist to Server FIRST (server is source of truth)
    if (window.axios) {
        axios.post('/theme/save', {
            mode: 'tabler',
            theme: mode
        })
        .then(() => {
            // Server confirmed - save to localStorage
            localStorage.setItem('tabler-theme', mode);
        })
        .catch((error) => {
            console.error('Failed to save theme preference, rolling back...', error);
            // Rollback: Restore previous theme
            document.documentElement.setAttribute('data-bs-theme', previousMode);
            localStorage.setItem('tabler-theme', previousMode);

            if (window.themeTabler) {
                window.themeTabler.refresh();
            }

            // Notify user of the failure
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Theme Sync Failed',
                    text: 'Unable to save theme preference. Your local view has been restored.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    } else {
        // No axios available - just update locally
        localStorage.setItem('tabler-theme', mode);
        console.warn('Axios not available, theme preference not saved to server');
    }
};
```

#### Server-Side (Theme Controller)

```php
// app/Http/Controllers/Sys/ThemeController.php

class ThemeController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:tabler',
            'theme' => 'required|in:light,dark',
        ]);
        
        // Save to user settings or session
        auth()->user()->update([
            'theme_preference' => $request->theme,
        ]);
        
        return response()->json(['success' => true]);
    }
}
```

#### Dark Mode Detection (HugeRTE Example)

```javascript
// Dynamic dark mode detection for editors
const isDarkMode = () => {
    // Prioritize HTML element attribute (SSR source of truth)
    const htmlTheme = document.documentElement.getAttribute('data-bs-theme');
    if (htmlTheme === 'dark') return true;
    if (htmlTheme === 'light') return false;

    // Fallback to LocalStorage
    const theme = localStorage.getItem("tabler-theme");
    if (theme === 'dark') return true;
    if (theme === 'light') return false;
    
    // Auto mode - check system preference
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
};

// Load appropriate skin based on current theme
const skinImport = currentDarkMode
    ? import('hugerte/skins/ui/oxide-dark/skin.min.css')
    : import('hugerte/skins/ui/oxide/skin.min.css');
```

#### Best Practices

1. **Server is Source of Truth** - Selalu sync ke server dulu
2. **Optimistic UI** - Update UI langsung untuk UX yang lebih baik
3. **Rollback Mechanism** - Restore tema sebelumnya jika server fail
4. **LocalStorage Cache** - Cache preferensi user di localStorage
5. **User Notification** - Notify user jika sync gagal
6. **System Preference Fallback** - Gunakan system preference jika tidak ada setting

---

### E. DataTables State Persistence

DataTables menggunakan localStorage untuk persist state (page length, search, filters, sorting).

#### Implementation (CustomDataTables.js)

```javascript
// resources/assets/tabler/js/CustomDataTables.js

export default class CustomDataTables {
    constructor(tableId, options = {}) {
        this.tableId = tableId;
        this.stateName = 'DataTables_' + this.tableId + '_' + window.location.pathname;
        // ... other options
    }

    init() {
        this.table = $(SELECTOR.table).DataTable({
            processing: true,
            serverSide: true,
            stateSave: true, // Enable state saving
            // ... other options
            stateLoadCallback: (settings, callback) => this.loadState(callback, SELECTOR),
            stateSaveCallback: (settings, data) => this.saveState(data, SELECTOR.filterForm),
        });
    }

    loadState(callback, SELECTOR) {
        const stored = localStorage.getItem(this.stateName);
        if (stored) {
            const state = JSON.parse(stored);
            
            // Set the page length from the stored state before loading
            if (state.length !== undefined) {
                this.options.pageLengthValue = state.length;
            }

            callback(state);

            setTimeout(() => {
                // Sync UI
                const pageLengthEl = $(`#${this.tableId}-pagelength`);
                if (pageLengthEl.length) pageLengthEl.val(state.length);

                if (this.options.search && state.search?.search) {
                    $(`#${this.tableId}-search`).val(state.search.search);
                }

                const form = document.getElementById(`${this.tableId}-filter`);
                if (form && state.customFilter) {
                    for (const [key, value] of Object.entries(state.customFilter)) {
                        const el = form.querySelector(`[name="${key}"]`);
                        if (el) el.value = value;
                    }
                }
            }, 0);
        } else {
            callback(null);
        }
    }

    saveState(data, filterFormSelector) {
        const filterForm = document.querySelector(filterFormSelector);
        if (filterForm && filterForm instanceof HTMLFormElement) {
            const formData = new FormData(filterForm);
            data.customFilter = Object.fromEntries(formData.entries());
        }

        // Ensure page length is preserved in state
        data.length = this.table.page.len();

        localStorage.setItem(this.stateName, JSON.stringify(data));
    }
}
```

#### State yang Disimpan

| State | Description |
|-------|-------------|
| `length` | Page length (10, 25, 50, All) |
| `start` | Current page/start index |
| `order` | Current sorting |
| `search` | Search query |
| `customFilter` | Custom filter form values |
| `columns` | Column visibility and search |

#### Clear State (Jika Diperlukan)

```javascript
// Clear specific table state
localStorage.removeItem('DataTables_myTable_/admin/users');

// Clear all DataTables states
Object.keys(localStorage).forEach(key => {
    if (key.startsWith('DataTables_')) {
        localStorage.removeItem(key);
    }
});
```

#### Best Practices

1. **Unique State Name** - Gunakan table ID + path untuk unique key
2. **Preserve Page Length** - Selalu save page length di state
3. **Sync Filter Form** - Save custom filter values
4. **Clear on Schema Change** - Clear state jika kolom berubah
5. **Path-Based State** - State berbeda per URL path

---

### F. Custom Event System (AJAX Forms)

Sistem event custom untuk AJAX forms memungkinkan loose coupling antara form handlers dan custom logic.

#### Event Types

| Event | Trigger | Detail |
|-------|---------|--------|
| `ajax-form:success` | Form success | `{ response, form }` |
| `ajax-form:error` | Form error | `{ error, form }` |
| `ajax-delete:success` | Delete success | `{ response, button }` |
| `ajax-delete:error` | Delete error | `{ error, button }` |

#### Event Listener Example

```javascript
// Listen for AJAX form success
document.addEventListener('ajax-form:success', function(e) {
    console.log('Form submitted successfully:', e.detail.response);
    console.log('Form element:', e.detail.form);
    
    // Custom logic here
    if (e.detail.response.data.redirect) {
        // Do something before redirect
    }
});

// jQuery style (for delegated listeners)
$(document).on('ajax-form:success', '.ajax-form', function(e, response, form) {
    console.log('Success:', response);
    console.log('Form:', form);
});
```

#### Custom Event Dispatch (FormHandlerAjax.js)

```javascript
// resources/assets/tabler/js/FormHandlerAjax.js

.then(function (response) {
    // ... success handling

    // Fire custom event
    const successEvent = new CustomEvent('ajax-form:success', {
        detail: { response: response.data, form: $form[0] },
        bubbles: true,
        cancelable: true
    });
    $form[0].dispatchEvent(successEvent);

    // Fire jQuery event (for delegated listeners)
    $form.trigger('ajax-form:success', [response.data, $form[0]]);

    // Redirect immediately if specified
    if (response.data.redirect) {
        window.location.href = response.data.redirect;
    }
});
```

#### Use Cases

1. **Analytics Tracking** - Track form submissions
2. **Conditional Redirect** - Override default redirect
3. **Additional Logging** - Log to external services
4. **UI Updates** - Update other parts of the page
5. **Integration** - Trigger third-party actions

#### Example: Analytics Tracking

```javascript
// Track form submissions with Google Analytics
document.addEventListener('ajax-form:success', function(e) {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'form_submission', {
            event_category: 'Forms',
            event_label: e.target.action,
            value: 1
        });
    }
});
```

#### Example: Conditional Redirect Override

```javascript
// Override redirect for specific forms
document.addEventListener('ajax-form:success', function(e) {
    if (e.target.dataset.noRedirect === 'true') {
        e.preventDefault(); // Cancel default redirect
        // Custom handling
        showSuccessMessage('Data saved without redirect!');
    }
});
```

#### Best Practices

1. **Event Bubbling** - Set `bubbles: true` untuk parent listeners
2. **Cancelable** - Set `cancelable: true` jika perlu prevent default
3. **Detail Object** - Selalu include `response` dan `form` di detail
4. **jQuery Fallback** - Support both CustomEvent dan jQuery events
5. **Documentation** - Document custom events di README module

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

### A. View Development Checklist (WAJIB SEBELUM MEMBUAT VIEW)

**Sebelum membuat atau mengedit view file, WAJIB checklist ini:**

#### 1. Cek Komponen x-tabler yang Tersedia ✅

**DILARANG** membuat HTML manual jika komponen x-tabler sudah ada!

```blade
{{-- ❌ SALAH - HTML Manual (DILARANG!) --}}
<input type="text" class="form-control" name="name">
<button class="btn btn-primary">Simpan</button>
<select class="form-select"><option>...</option></select>
<table class="table">...</table>

{{-- ✅ BENAR - Gunakan x-tabler --}}
<x-tabler.form-input name="name" />
<x-tabler.button type="submit" text="Simpan" />
<x-tabler.form-select name="role_id">...</x-tabler.form-select>
<x-tabler.datatable :columns="[...]" :url="route('...')" />
```

#### 2. Komponen WAJIB yang Harus Dicek

| Element | Gunakan | JANGAN Gunakan |
|---------|---------|----------------|
| Page Header | `<x-tabler.page-header>` | `<h1>`, manual breadcrumb |
| Button | `<x-tabler.button>` | `<button class="btn">` |
| Input Text | `<x-tabler.form-input>` | `<input class="form-control">` |
| Date Picker | `<x-tabler.form-input type="date">` | `<input type="date">` |
| Select | `<x-tabler.form-select>` | `<select class="form-select">` |
| Textarea | `<x-tabler.form-textarea>` | `<textarea>` |
| Checkbox | `<x-tabler.form-checkbox>` | `<input type="checkbox">` |
| Modal | `<x-tabler.form-modal>` | `<div class="modal">` |
| DataTable | `<x-tabler.datatable>` | `<table class="table">` |
| Flash Message | `<x-tabler.flash-message>` | Manual alert div |
| Empty State | `<x-tabler.empty-state>` | Manual empty div |

#### 3. Template Checklist

```markdown
## View Development Checklist

- [ ] Apakah saya menggunakan `<x-tabler.page-header>` untuk header?
- [ ] Apakah saya menggunakan `<x-tabler.button>` untuk semua tombol?
- [ ] Apakah saya menggunakan `<x-tabler.form-input>` untuk semua input?
- [ ] Apakah saya menggunakan `<x-tabler.form-select>` untuk semua select?
- [ ] Apakah saya menggunakan `<x-tabler.datatable>` untuk tabel data?
- [ ] Apakah saya menggunakan `<x-tabler.flash-message>` untuk notifikasi?
- [ ] Apakah saya menggunakan `<x-tabler.form-modal>` untuk modal?
- [ ] Apakah saya TIDAK menggunakan HTML manual (`<button>`, `<input>`, `<table>`)?
- [ ] Apakah semua class menggunakan komponen x-tabler?
- [ ] Apakah fitur otomatis (Flatpickr, Select2, FilePond) sudah terintegrasi?
```

### B. Documentation

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

### C. Error Handling

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

#### Code Examples
- **Controllers:** `app/Http/Controllers/Sys/RoleController.php`
- **Services:** `app/Services/Sys/RoleService.php`
- **Requests:** `app/Http/Requests/Sys/RoleRequest.php`
- **Models:** `app/Models/Sys/Role.php`
- **Views:** `resources/views/pages/sys/roles/`
- **Traits:** `app/Traits/HashidBinding.php`, `app/Traits/Blameable.php`
- **Helpers:** `app/Helpers/SysHelper.php`, `app/Helpers/GlobalHelper.php`

#### x-tabler Components (WAJIB GUNAKAN!)
**Location:** `resources/views/components/tabler/`

| Component File | Usage | Example View |
|----------------|-------|--------------|
| `button.blade.php` | Semua tombol | `sys/roles/index.blade.php` |
| `form-input.blade.php` | Input, date, file | `sys/users/create-edit-ajax.blade.php` |
| `form-select.blade.php` | Select dropdown | `sys/roles/create-edit-ajax.blade.php` |
| `form-textarea.blade.php` | Textarea, editor | `sys/documentation/create-edit.blade.php` |
| `form-modal.blade.php` | Modal form | `**/create-edit-ajax.blade.php` |
| `datatable.blade.php` | Data tables | `sys/roles/index.blade.php` |
| `datatables-actions.blade.php` | Action buttons | `sys/roles/index.blade.php` |
| `page-header.blade.php` | Page header | `SEMUA halaman` |
| `flash-message.blade.php` | Notifications | `SEMUA halaman` |
| `empty-state.blade.php` | Empty data | `sys/backup/index.blade.php` |

**🔴 REMINDER:** Dilarang membuat HTML manual jika komponen x-tabler sudah tersedia!

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

### D. Project Management Module Standards

The Project Management module serves as a **reference implementation** for new modules.

#### Module Structure Example:
```
app/
├── Models/Project/
│   ├── Project.php
│   ├── ProjectTask.php
│   └── ...
├── Services/Project/
│   ├── ProjectService.php
│   └── ProjectTaskService.php
├── Http/
│   ├── Controllers/Project/
│   │   ├── ProjectController.php
│   │   └── ProjectTaskController.php
│   └── Requests/Project/
│       ├── ProjectRequest.php
│       └── ProjectTaskRequest.php
routes/
└── project.php
resources/
├── views/pages/projects/
│   ├── index.blade.php
│   ├── show.blade.php
│   ├── create-edit.blade.php
│   └── kanban.blade.php
└── components/project/
    ├── project-name.blade.php
    └── ...
```

#### Key Features:
- ✅ Custom primary keys (`project_id`, `project_task_id`)
- ✅ Encrypted IDs (HashidBinding trait)
- ✅ Service Pattern architecture
- ✅ Bottom-up costing system
- ✅ Kanban board with drag & drop
- ✅ DataTables integration
- ✅ AJAX CRUD operations
- ✅ Activity logging

#### Naming Standards:
```php
// Tables: pr_{module}_{entity}
pr_projects
pr_project_tasks
pr_project_costs

// Models: PascalCase in Project namespace
App\Models\Project\Project
App\Models\Project\ProjectTask

// Controllers: {Entity}Controller
ProjectController
ProjectTaskController

// Services: {Entity}Service
ProjectService
ProjectTaskService

// Requests: {Entity}Request
ProjectRequest
ProjectTaskRequest
```

---

**Dokumen ini bersifat definitif dan diperbarui secara berkala.**
Untuk pertanyaan atau clarifications, refer ke dokumen ini atau cek existing implementations di codebase.

**Last Updated:** Februari 2026
**Version:** 3.0 (Major Update - Added Service Delegation, JS Re-init, Global Search, Approval System, Theme Toggle, State Persistence, Custom Events)

---

## Changelog

### Version 3.1 - Februari 2026 (x-tabler Components Update)

#### Added
- **Section C (Frontend)**: "ATURAN EMAS" - Explicit rule untuk x-tabler components
- **Section 8.A**: View Development Checklist (WAJIB SEBELUM MEMBUAT VIEW)
- **Quick Reference**: x-tabler Components table dengan contoh file
- **Component List**: Daftar lengkap komponen WAJIB vs OPSIONAL
- **Examples**: BENAR vs SALAH untuk setiap komponen (form-input, button, datatable, modal)

#### Changed
- **Section C**: Expanded dari 12 komponen menjadi 14 komponen (12 wajib + 2 opsional)
- **Examples**: Lebih detail dengan perbandingan ✅ BENAR vs ❌ SALAH
- **Checklist**: Template checklist untuk view development

#### Emphasized
- **DILARANG KERAS** membuat HTML manual jika x-tabler component tersedia
- **WAJIB GUNAKAN** x-tabler components untuk konsistensi UI
- **JANGAN PERNAH** gunakan `<button class="btn">`, `<input class="form-control">`, `<table class="table">`

### Version 3.0 - Februari 2026 (Major Update)

#### Added
- **Section A.3**: Constructor Injection Pattern (PascalCase convention)
- **Section A.4**: Blameable Trait Implementation (string-based user name)
- **Section G**: JavaScript Re-initialization Pattern
- **Section H**: Global Search Implementation
- **Section E (Database)**: Approval System Pattern
- **Section D (Features)**: Theme Toggle Pattern (Server-First Approach)
- **Section E (Features)**: DataTables State Persistence
- **Section F (Features)**: Custom Event System (AJAX Forms)

#### Changed
- Updated Table of Contents dengan section baru
- Clarified service property naming convention (PascalCase)
- Added detailed implementation examples untuk semua pattern baru

#### Fixed
- Inconsistency antara dokumentasi dan implementasi Blameable trait
- Missing documentation untuk HugeRTE (replacing TinyMCE references)

### Version 2.1 - Previous
- Added Project Management Module Standards

### Version 2.0 - Initial
- Initial comprehensive documentation

---
