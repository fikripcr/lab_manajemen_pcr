# Development Guide - Laravel Boilerplate

**Last Updated:** March 2026  
**Laravel Version:** 12.46.0  
**PHP Version:** 8.4.18  

Panduan development untuk membangun fitur baru di project Laravel Boilerplate ini.

---

## Table of Contents

1. [Arsitektur & Design Patterns](#arsitektur--design-patterns)
2. [Membuat Modul Baru (Step by Step)](#membuat-modul-baru-step-by-step)
3. [Controller & Service Pattern](#controller--service-pattern)
4. [Model & Database](#model--database)
5. [Validation](#validation)
6. [Frontend Development](#frontend-development)
7. [AJAX Forms](#ajax-forms)
8. [DataTables](#datatables)
9. [Media Handling](#media-handling)
10. [Activity Logging](#activity-logging)
11. [Authorization (RBAC)](#authorization-rbac)
12. [Helper Functions](#helper-functions)
13. [Testing](#testing)
14. [Code Quality](#code-quality)

---

## Arsitektur & Design Patterns

### Thin Controller, Fat Service

Project ini menggunakan pola **Service-Oriented Architecture** dengan prinsip:

- **Controller**: Hanya menangani HTTP request/response
- **Service**: Semua business logic ada di sini
- **Model**: Hanya untuk database operations dan relationships

```
┌─────────────────────────────────────────────────────────────┐
│                    REQUEST FLOW                             │
├─────────────────────────────────────────────────────────────┤
│  Route → Middleware → FormRequest → Controller → Service   │
│                                              ↓              │
│  Response ← Exception Handler ← Model ← Repository        │
└─────────────────────────────────────────────────────────────┘
```

### Bounded Context (Modular Monolith)

Setiap modul memiliki domain sendiri dengan struktur terpisah:

```
app/
├── Models/
│   ├── Hr/           # Human Resource
│   ├── Pemutu/       # Penjaminan Mutu
│   ├── Pmb/          # Penerimaan Mahasiswa Baru
│   └── ...
├── Services/
│   ├── Hr/
│   ├── Pemutu/
│   ├── Pmb/
│   └── ...
├── Http/Controllers/
│   ├── Hr/
│   ├── Pemutu/
│   ├── Pmb/
│   └── ...
```

> ⚠️ **PENTING**: DILARANG query model dari domain lain langsung. Selalu gunakan Service dari domain tersebut.

---

## Membuat Modul Baru (Step by Step)

### Contoh: Membuat Modul "Produk"

### Step 1: Generate Model dengan Migration

```bash
php artisan make:model Product -m
```

### Step 2: Generate Service

```bash
php artisan make:service ProductService
```

### Step 3: Generate Controller

```bash
php artisan make:controller ProductController --resource
```

### Step 4: Generate Form Request

```bash
php artisan make:request ProductRequest
```

### Step 5: Buat Migration

Edit file migration di `database/migrations/YYYY_MM_DD_HHMMSS_create_products_table.php`:

```php
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('code')->unique();
        $table->text('description')->nullable();
        $table->decimal('price', 12, 2)->default(0);
        $table->integer('stock')->default(0);
        
        // Blameable columns (wajib untuk audit trail)
        $table->string('created_by')->nullable();
        $table->string('updated_by')->nullable();
        $table->string('deleted_by')->nullable();
        
        $table->timestamps();
        $table->softDeletes();
    });
}
```

### Step 6: Setup Model

Edit `app/Models/Product.php`:

```php
<?php

namespace App\Models;

use App\Traits\HashidBinding;
use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use SoftDeletes, HashidBinding, Blameable, LogsActivity;

    protected $fillable = [
        'name',
        'code',
        'description',
        'price',
        'stock',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    // Activity Log Configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $logName) => "Product {$logName}");
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('stock', '>', 0);
    }
}
```

### Step 7: Setup Service

Edit `app/Services/ProductService.php`:

```php
<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(
        protected Product $product
    ) {}

    /**
     * Get all products for dropdown
     */
    public function getAll(): Collection
    {
        return $this->product
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get base query for DataTables
     */
    public function getBaseQuery(): Builder
    {
        return $this->product
            ->with(['createdByUser', 'updatedByUser'])
            ->whereNull('deleted_at');
    }

    /**
     * Get single product by ID
     */
    public function findById(int $id): ?Product
    {
        return $this->product->findOrFail($id);
    }

    /**
     * Create new product
     */
    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $product = $this->product->create($data);
            
            logActivity('product_management', "Created product: {$product->name}", $product);
            
            return $product;
        });
    }

    /**
     * Update existing product
     */
    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $product->update($data);
            
            logActivity('product_management', "Updated product: {$product->name}", $product);
            
            return $product;
        });
    }

    /**
     * Delete product (soft delete)
     */
    public function delete(Product $product): bool
    {
        return DB::transaction(function () use ($product) {
            $product->delete();
            
            logActivity('product_management', "Deleted product: {$product->name}", $product);
            
            return true;
        });
    }

    /**
     * Force delete product (permanent)
     */
    public function forceDelete(Product $product): bool
    {
        return DB::transaction(function () use ($product) {
            $product->forceDelete();
            
            logActivity('product_management', "Permanently deleted product: {$product->name}", $product);
            
            return true;
        });
    }
}
```

### Step 8: Setup Controller

Edit `app/Http/Controllers/ProductController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $ProductService
    ) {}

    /**
     * Display product list page
     */
    public function index()
    {
        return view('pages.admin.products.index');
    }

    /**
     * DataTables server-side data
     */
    public function paginate(Request $request)
    {
        $query = $this->ProductService->getBaseQuery();
        
        return datatables()->eloquent($query)
            ->addColumn('price_formatted', fn($product) => 'Rp ' . number_format($product->price, 0, ',', '.'))
            ->addColumn('stock_status', fn($product) => $product->stock > 0 
                ? '<span class="badge bg-success">Available</span>' 
                : '<span class="badge bg-danger">Out of Stock</span>'
            )
            ->addColumn('actions', fn($product) => view('pages.admin.products.partials.actions', compact('product')))
            ->rawColumns(['stock_status', 'actions'])
            ->make(true);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('pages.admin.products.create');
    }

    /**
     * Store new product
     */
    public function store(ProductRequest $request)
    {
        $this->ProductService->create($request->validated());
        
        return jsonSuccess('Product berhasil dibuat', route('products.index'));
    }

    /**
     * Show single product
     */
    public function show(Product $product)
    {
        return view('pages.admin.products.show', compact('product'));
    }

    /**
     * Show edit form
     */
    public function edit(Product $product)
    {
        return view('pages.admin.products.edit', compact('product'));
    }

    /**
     * Update product
     */
    public function update(ProductRequest $request, Product $product)
    {
        $this->ProductService->update($product, $request->validated());
        
        return jsonSuccess('Product berhasil diupdate', route('products.index'));
    }

    /**
     * Delete product
     */
    public function destroy(Product $product)
    {
        $this->ProductService->delete($product);
        
        return jsonSuccess('Product berhasil dihapus', route('products.index'));
    }
}
```

### Step 9: Setup Routes

Edit `routes/web.php` atau `routes/admin.php`:

```php
use App\Http\Controllers\ProductController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        
        // DataTables route
        Route::get('/data/paginate', [ProductController::class, 'paginate'])->name('paginate');
    });
});
```

### Step 10: Create Views

Buat folder dan file views:

```
resources/views/pages/admin/products/
├── index.blade.php
├── create.blade.php
├── edit.blade.php
├── show.blade.php
└── partials/
    └── actions.blade.php
```

**index.blade.php:**
```blade
<x-layouts.admin>
    <x-slot name="title">Product Management</x-slot>
    <x-slot name="subtitle">Daftar semua product</x-slot>

    <div class="container-fluid py-4">
        <x-tabler.card>
            <x-tabler.card-header>
                <x-slot name="title">Daftar Product</x-slot>
                <x-slot name="actions">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Tambah Product
                    </a>
                </x-slot>
            </x-tabler.card-header>

            <x-tabler.card-body>
                <x-tabler.datatable id="products-table">
                    <x-slot name="columns">
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-tabler.datatable>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("products.paginate") }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'code', name: 'code' },
                    { data: 'name', name: 'name' },
                    { data: 'price_formatted', name: 'price' },
                    { data: 'stock_status', name: 'stock' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });
        });
    </script>
    @endpush
</x-layouts.admin>
```

**partials/actions.blade.php:**
```blade
<div class="btn-group btn-group-sm">
    <a href="{{ route('products.edit', $product) }}" class="btn btn-info">
        <i class="ti ti-edit"></i> Edit
    </a>
    <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">
        <i class="ti ti-eye"></i> Detail
    </a>
    <button onclick="confirmDelete('{{ route('products.destroy', $product) }}', 'products-table')" 
            class="btn btn-danger">
        <i class="ti ti-trash"></i> Hapus
    </button>
</div>
```

---

## Controller & Service Pattern

### Controller Best Practices

✅ **DO:**
- Constructor injection untuk semua dependencies
- Type hinting untuk semua parameters
- Return response yang konsisten (`jsonSuccess()`, `jsonError()`, `view()`)
- Delegate semua logic ke Service

❌ **DON'T:**
- Query database langsung di controller
- Business logic di controller
- Try-catch manual (sudah di-handle global)
- Inline validation (gunakan FormRequest)

### Service Best Practices

✅ **DO:**
- Satu service per domain model
- DB::transaction() untuk operasi tulis
- logActivity() untuk audit trail
- Method naming yang konsisten (`getAll()`, `getBaseQuery()`, `create()`, `update()`, `delete()`)

❌ **DON'T:**
- Query model dari domain lain langsung
- HTTP request handling di service
- Response formatting di service

---

## Model & Database

### Required Traits

Setiap model WAJIB menggunakan traits berikut:

```php
class MyModel extends Model
{
    use SoftDeletes;        // Soft delete
    use HashidBinding;      // ID encryption untuk URL
    use Blameable;          // Auto-fill created_by, updated_by, deleted_by
    use LogsActivity;       // Activity logging
}
```

### Migration Best Practices

```php
Schema::create('my_table', function (Blueprint $table) {
    $table->id();
    
    // String columns
    $table->string('name');
    $table->string('code')->unique();
    $table->string('email')->nullable();
    
    // Text columns
    $table->text('description')->nullable();
    $table->longText('content')->nullable();
    
    // Numeric columns
    $table->decimal('price', 12, 2)->default(0);
    $table->integer('quantity')->default(0);
    $table->boolean('is_active')->default(true);
    
    // Date/Time columns
    $table->date('start_date')->nullable();
    $table->datetime('published_at')->nullable();
    $table->timestamp('approved_at')->nullable();
    
    // Blameable columns (WAJIB)
    $table->string('created_by')->nullable();
    $table->string('updated_by')->nullable();
    $table->string('deleted_by')->nullable();
    
    // Timestamps & Soft Deletes (WAJIB)
    $table->timestamps();
    $table->softDeletes();
    
    // Indexes untuk performa
    $table->index('code');
    $table->index('is_active');
    $table->index(['created_at', 'status']);
});
```

### Eloquent Relationships

```php
class Product extends Model
{
    // BelongsTo
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // HasMany
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // HasManyThrough
    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderItem::class);
    }

    // BelongsToMany (Many-to-Many)
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    // Polymorphic
    public function images()
    {
        return $this->morphMany(Media::class, 'model');
    }
}
```

### Eager Loading (Prevent N+1)

```php
// ❌ BAD - N+1 query problem
$products = Product::all();
foreach ($products as $product) {
    echo $product->category->name; // Query tambahan untuk setiap product
}

// ✅ GOOD - Eager loading
$products = Product::with('category')->get();
foreach ($products as $product) {
    echo $product->category->name; // No additional query
}

// ✅ Load multiple relationships
$products = Product::with(['category', 'tags', 'images'])->get();

// ✅ Load specific columns only
$products = Product::with(['category:id,name'])
    ->select('id', 'name', 'price', 'category_id')
    ->get();
```

---

## Validation

### BaseRequest - Enhanced Form Request

Project ini menggunakan `BaseRequest` yang sudah diperkaya dengan fitur-fitur berikut:

#### ✅ Features

1. **Complete Indonesian Messages** - Semua validation messages sudah dalam Bahasa Indonesia
2. **Auto-resolve Attributes** - Field name otomatis dikonversi ke label readable
3. **Helper Methods** - Methods untuk common validation patterns
4. **No Repetitive Code** - Tidak perlu define messages di setiap request

---

### Basic Usage (MINIMAL CODE)

**BEFORE (Verbose):**
```php
class UserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return []; // Tidak perlu!
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Password',
        ]; // Tidak perlu! Auto-resolved
    }
}
```

**AFTER (Clean):**
```php
class UserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    // ✅ messages() tidak perlu - sudah ada di BaseRequest
    // ✅ attributes() tidak perlu - auto-resolved dari field name
}
```

**Auto-resolved Attributes:**
- `first_name` → "First Name"
- `no_hp` → "No Hp"
- `tanggal_lahir` → "Tanggal Lahir"
- `email` → "Email"

---

### Helper Methods

#### 1. `passwordRules()` - Password Validation

```php
class RegisterRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => $this->passwordRules(
                requireUppercase: true,
                requireLowercase: true,
                requireNumbers: true,
                requireSymbols: false
            ),
        ];
    }
}

// Output rules:
// ['required', 'string', 'min:8', 'confirmed', 'mixed', 'numbers']
```

#### 2. `phoneRules()` - Phone Number Validation

```php
class ContactRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'no_hp' => $this->phoneRules(required: true),
            'no_whatsapp' => $this->phoneRules(required: false),
        ];
    }
}

// Output rules:
// no_hp: ['required', 'string', 'regex:/^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,9}$/']
```

#### 3. `dateRules()` - Date Validation

```php
class EventRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'event_name' => 'required|string|max:255',
            'start_date' => $this->dateRules(
                required: true,
                min: '2026-01-01',
                max: '2026-12-31'
            ),
            'end_date' => $this->dateRules(
                required: true,
                min: '2026-01-01'
            ),
        ];
    }
}

// Output rules:
// start_date: ['date', 'required', 'after_or_equal:2026-01-01', 'before_or_equal:2026-12-31']
// end_date: ['date', 'required', 'after_or_equal:2026-01-01']
```

#### 4. `fileRules()` - File Upload Validation

```php
class DocumentUploadRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'document' => $this->fileRules(
                allowedMimes: ['pdf', 'doc', 'docx'],
                maxSizeKb: 2048, // 2MB
                required: true
            ),
            'attachment' => $this->fileRules(
                allowedMimes: ['jpg', 'png'],
                maxSizeKb: 1024, // 1MB
                required: false
            ),
        ];
    }
}

// Output rules:
// document: ['file', 'required', 'mimes:pdf,doc,docx', 'max:2048']
// attachment: ['file', 'nullable', 'mimes:jpg,png', 'max:1024']
```

#### 5. `imageRules()` - Image Upload Validation

```php
class AvatarUploadRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'avatar' => $this->imageRules(
                maxWidthKb: 2048, // 2MB
                minWidth: 100,
                maxWidth: 1920,
                minHeight: 100,
                maxHeight: 1920,
                required: true
            ),
        ];
    }
}

// Output rules:
// avatar: ['image', 'required', 'max:2048', 'min_width:100', 'max_width:1920', 'min_height:100', 'max_height:1920']
```

#### 6. `uniqueRule()` - Unique Validation

```php
class UserUpdateRequest extends BaseRequest
{
    public function rules(): array
    {
        $userId = $this->getDecryptedRouteId('user');

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|' . $this->uniqueRule('users', 'email', $userId),
            'username' => 'required|string|max:100|' . $this->uniqueRule('users', 'username', $userId),
        ];
    }
}

// Output rules:
// email: ['required', 'email', 'unique:users,email,123']
```

#### 7. `getDecryptedRouteId()` - Get Decrypted ID from Route

```php
class ProductUpdateRequest extends BaseRequest
{
    public function rules(): array
    {
        $productId = $this->getDecryptedRouteId('product');

        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code,' . $productId,
        ];
    }
}

// Automatically decrypts route parameter (supports hashid)
```

---

### Custom Attribute Labels

Jika auto-resolve tidak sesuai, override `customAttributes()`:

```php
class PegawaiRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'nip' => 'required|string|max:20|unique:hr_pegawai,nip',
            'nama_lengkap' => 'required|string|max:255',
            'email_kantor' => 'required|email|unique:hr_pegawai,email_kantor',
            'no_hp_pribadi' => 'nullable|string|max:20',
        ];
    }

    protected function customAttributes(): array
    {
        return [
            'nip' => 'NIP',
            'nama_lengkap' => 'Nama Lengkap',
            'email_kantor' => 'Email Kantor',
            'no_hp_pribadi' => 'Nomor HP Pribadi',
        ];
    }
}

// Auto-resolved (tidak perlu di-custom):
// nip → "Nip"
// nama_lengkap → "Nama Lengkap" ✅
// email_kantor → "Email Kantor" ✅
// no_hp_pribadi → "No Hp Pribadi" ✅
```

---

### Complete Example

```php
<?php

namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StoreRegistrationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Personal info
            'nik' => 'required|numeric|digits:16',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pmb_camaba,email',
            'no_hp' => $this->phoneRules(required: true),
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => $this->dateRules(required: true),
            'jenis_kelamin' => 'required|in:L,P',
            
            // Address
            'alamat_lengkap' => 'required|string|max:500',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'required|string|max:100',
            'kode_pos' => 'required|numeric|digits:5',
            
            // Education
            'asal_sekolah' => 'required|string|max:255',
            'nisn' => 'required|numeric|digits:10',
            'jurusan' => 'required|string|max:100',
            'tahun_lulus' => $this->dateRules(
                required: true,
                min: '2020-01-01',
                max: date('Y-12-31')
            ),
            
            // Program choices
            'pilihan_prodi_1' => 'required|exists:hr_struktur_organisasi,orgunit_id',
            'pilihan_prodi_2' => 'nullable|exists:hr_struktur_organisasi,orgunit_id',
            
            // Documents
            'foto' => $this->imageRules(
                maxWidthKb: 512, // 512KB
                minWidth: 300,
                maxWidth: 600,
                minHeight: 400,
                maxHeight: 800,
                required: true
            ),
            'ijazah' => $this->fileRules(
                allowedMimes: ['pdf', 'jpg', 'png'],
                maxSizeKb: 1024, // 1MB
                required: true
            ),
            'transkrip' => $this->fileRules(
                allowedMimes: ['pdf'],
                maxSizeKb: 1024,
                required: true
            ),
        ];
    }

    // Optional: Override for better labels
    protected function customAttributes(): array
    {
        return [
            'nik' => 'NIK',
            'nama' => 'Nama Lengkap',
            'no_hp' => 'Nomor HP',
            'tanggal_lahir' => 'Tanggal Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'alamat_lengkap' => 'Alamat Lengkap',
            'kode_pos' => 'Kode Pos',
            'asal_sekolah' => 'Asal Sekolah',
            'tahun_lulus' => 'Tahun Lulus',
            'pilihan_prodi_1' => 'Pilihan Program Studi 1',
            'pilihan_prodi_2' => 'Pilihan Program Studi 2',
            'foto' => 'Foto',
            'ijazah' => 'Ijazah',
            'transkrip' => 'Transkrip Nilai',
        ];
    }
}
```

**Messages yang otomatis tersedia:**
- `nik.required` → "NIK wajib diisi."
- `nik.numeric` → "NIK harus berupa angka."
- `nik.digits` → "NIK harus terdiri dari 16 digit."
- `email.email` → "Email harus berupa alamat email yang valid."
- `email.unique` → "Email sudah digunakan."
- `foto.image` → "Foto harus berupa gambar."
- `foto.max` → "Foto tidak boleh lebih dari 512 kilobita."
- `foto.min_width` → "Foto minimal 300 pixels."
- dll.

**TIDAK PERLU** define `messages()` di request!

---

### Validation Messages Reference

Semua validation messages berikut sudah tersedia dalam Bahasa Indonesia:

| Validation | Message |
|------------|---------|
| `required` | ":attribute wajib diisi." |
| `email` | ":attribute harus berupa alamat email yang valid." |
| `unique` | ":attribute sudah digunakan." |
| `exists` | ":attribute yang dipilih tidak valid." |
| `min.string` | ":attribute minimal :min karakter." |
| `max.string` | ":attribute tidak boleh lebih dari :max karakter." |
| `confirmed` | "Konfirmasi :attribute tidak cocok." |
| `date` | ":attribute bukan tanggal yang valid." |
| `file` | ":attribute harus berupa file." |
| `image` | ":attribute harus berupa gambar." |
| `mimes` | ":attribute harus berupa file berjenis: :values." |
| `regex` | "Format :attribute tidak valid." |
| `numeric` | ":attribute harus berupa angka." |
| `integer` | ":attribute harus berupa bilangan bulat." |
| `array` | ":attribute harus berupa array." |
| `in` | ":attribute yang dipilih tidak valid." |
| `url` | "Format :attribute tidak valid." |

Dan **100+ messages lainnya** sudah tersedia di `BaseRequest`!

---

### Best Practices

✅ **DO:**
- Extend `BaseRequest` untuk semua form requests
- Gunakan helper methods untuk common patterns
- Override `customAttributes()` hanya jika perlu label khusus
- Biarkan auto-resolve bekerja untuk field standard

❌ **DON'T:**
- Define `messages()` di setiap request (sudah ada di BaseRequest)
- Define `attributes()` langsung (gunakan `customAttributes()`)
- Duplicate validation messages
- Hardcode validation rules yang kompleks (gunakan helper)

---

---

## Frontend Development

### Blade Components

Project ini menyediakan 33+ Blade components Tabler:

```blade
{{-- Layout --}}
<x-layouts.admin>
    <x-slot name="title">Page Title</x-slot>
    <x-slot name="subtitle">Page Subtitle</x-slot>
    
    {{-- Card --}}
    <x-tabler.card>
        <x-tabler.card-header>
            <x-slot name="title">Card Title</x-slot>
            <x-slot name="actions">
                <button class="btn btn-primary">Action</button>
            </x-slot>
        </x-tabler.card-header>
        
        <x-tabler.card-body>
            Content here
        </x-tabler.card-body>
        
        <x-tabler.card-footer>
            Footer content
        </x-tabler.card-footer>
    </x-tabler.card>
</x-layouts.admin>
```

### Form Components

```blade
{{-- Text Input --}}
<x-tabler.form-input 
    name="name" 
    label="Nama Lengkap" 
    placeholder="Masukkan nama"
    required 
/>

{{-- Select Dropdown --}}
<x-tabler.form-select 
    name="category_id" 
    label="Kategori" 
    class="select2"
>
    <option value="">-- Pilih Kategori --</option>
    @foreach($categories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
    @endforeach
</x-tabler.form-select>

{{-- Textarea --}}
<x-tabler.form-textarea 
    name="description" 
    label="Deskripsi" 
    rows="5"
/>

{{-- File Upload dengan FilePond --}}
<x-tabler.form-input 
    type="file" 
    name="images[]" 
    label="Gambar Produk" 
    class="filepond-input"
    multiple
/>

{{-- Date Picker --}}
<x-tabler.form-input 
    type="date" 
    name="start_date" 
    label="Tanggal Mulai" 
    class="flatpickr"
/>

{{-- Checkbox --}}
<x-tabler.form-checkbox 
    name="is_active" 
    label="Aktif" 
    value="1"
/>
```

---

## AJAX Forms

Form dengan class `ajax-form` otomatis di-handle tanpa JavaScript tambahan:

```blade
<form class="ajax-form" action="{{ route('products.store') }}" method="POST">
    @csrf
    
    <x-tabler.form-input name="name" label="Nama" required />
    <x-tabler.form-input name="code" label="Kode" required />
    <x-tabler.form-input type="number" name="price" label="Harga" required />
    
    <div class="form-footer">
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-save"></i> Simpan
        </button>
    </div>
</form>
```

### Apa yang Terjadi Otomatis:

1. ✅ Form di-submit via Axios (bukan browser default)
2. ✅ Tombol disabled + spinner "Memproses..."
3. ✅ SweetAlert loading muncul
4. ✅ Success: Modal ditutup, DataTable reload, Toast success
5. ✅ Error 422: Inline error di bawah field yang salah
6. ✅ Error 500: SweetAlert error

---

## DataTables

### Server-Side DataTables

```blade
<x-tabler.datatable id="products-table">
    <x-slot name="columns">
        <th>No</th>
        <th>Kode</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
    </x-slot>
</x-tabler.datatable>

@push('scripts')
<script>
$(document).ready(function() {
    $('#products-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("products.paginate") }}',
        columns: [
            { 
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex', 
                orderable: false, 
                searchable: false 
            },
            { data: 'code', name: 'code' },
            { data: 'name', name: 'name' },
            { data: 'price_formatted', name: 'price' },
            { data: 'stock_status', name: 'stock' },
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false 
            }
        ],
        order: [[2, 'asc']] // Default sort by name
    });
});
</script>
@endpush
```

### Controller Method

```php
public function paginate(Request $request)
{
    $query = $this->ProductService->getBaseQuery();
    
    return datatables()->eloquent($query)
        ->addColumn('price_formatted', function($product) {
            return 'Rp ' . number_format($product->price, 0, ',', '.');
        })
        ->addColumn('stock_status', function($product) {
            return $product->stock > 0 
                ? '<span class="badge bg-success">Available</span>' 
                : '<span class="badge bg-danger">Out of Stock</span>';
        })
        ->addColumn('actions', function($product) {
            return view('pages.admin.products.partials.actions', compact('product'));
        })
        ->rawColumns(['stock_status', 'actions'])
        ->make(true);
}
```

---

## Media Handling

### Spatie MediaLibrary (PRIMARY PATTERN - Use for 95% of cases)

**When to use:** Simple file upload without complex workflow tracking.

**Examples:** Product images, document attachments, employee photos, event banners.

```php
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        // Single file collection
        $this->addMediaCollection('product_images')
            ->useFallbackUrl('/assets-admin/img/default-product.jpg')
            ->useFallbackPath(public_path('/assets-admin/img/default-product.jpg'))
            ->singleFile();

        // Multiple file collection
        $this->addMediaCollection('product_attachments')
            ->useFallbackUrl('/assets-admin/img/default-attachment.jpg')
            ->useFallbackPath(public_path('/assets-admin/img/default-attachment.jpg'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // Thumbnail conversion
        $this->addMediaConversion('thumb')
            ->fit(\Spatie\Image\Manipulations::FIT_CROP, 150, 150)
            ->nonQueued();

        // Preview conversion
        $this->addMediaConversion('preview')
            ->fit(\Spatie\Image\Manipulations::FIT_CROP, 400, 400)
            ->nonQueued();
    }
}
```

**Upload in Controller:**
```php
public function store(ProductRequest $request)
{
    $product = $this->ProductService->create($request->validated());

    // Upload product images
    if ($request->hasFile('product_images')) {
        foreach ($request->file('product_images') as $image) {
            if ($image->isValid()) {
                $product->addMedia($image)
                    ->withCustomProperties(['uploaded_by' => auth()->id()])
                    ->toMediaCollection('product_images');
            }
        }
    }

    // Upload attachments
    if ($request->hasFile('product_attachments')) {
        foreach ($request->file('product_attachments') as $attachment) {
            if ($attachment->isValid()) {
                $product->addMedia($attachment)
                    ->withCustomProperties(['uploaded_by' => auth()->id()])
                    ->toMediaCollection('product_attachments');
            }
        }
    }

    return jsonSuccess('Product berhasil dibuat');
}
```

**Display Media in View:**
```blade
{{-- Display product images --}}
@if($product->hasMedia('product_images'))
    @foreach($product->getMedia('product_images') as $media)
        <img src="{{ $media->getUrl('thumb') }}" alt="Product Image">
    @endforeach
@endif

{{-- Display attachments --}}
@if($product->hasMedia('product_attachments'))
    @foreach($product->getMedia('product_attachments') as $media)
        <a href="{{ $media->getUrl() }}" target="_blank">
            <i class="ti ti-file"></i> {{ $media->file_name }}
        </a>
    @endforeach
@endif

{{-- Download link --}}
<a href="{{ route('media.download', $media->id) }}">
    Download {{ $media->file_name }}
</a>
```

**Download in Controller:**
```php
public function download($mediaId)
{
    $media = Media::findOrFail($mediaId);
    return downloadStorageFile($media->getPath(), $media->file_name);
}
```

---

### Custom Upload Model (SPECIAL CASE - PMB Pattern Only)

**When to use:** ONLY when you need complex workflow tracking per file:
- Verification status per document
- Notes/comments per file
- Revision tracking
- Approval workflow per document

**Example: PMB Document Upload**

```php
// Model for metadata tracking (NOT for file storage)
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

// Parent model
class Pendaftaran extends Model
{
    // Relationship to uploads
    public function dokumenUploads()
    {
        return $this->hasMany(DokumenUpload::class, 'pendaftaran_id');
    }
}
```

**Upload in Controller:**
```php
public function uploadDokumen(FileUploadRequest $request)
{
    return DB::transaction(function () use ($request) {
        // Create metadata record
        $upload = DokumenUpload::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'jenis_dokumen_id' => $request->jenis_dokumen_id,
            'status_verifikasi' => 'Pending',
            'waktu_upload' => now(),
        ]);

        // Store actual file with Spatie Media Library
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
```

**Verify Document:**
```php
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

### Pattern Selection Guide

| Requirement | Use Pattern |
|-------------|-------------|
| Simple file upload | **Spatie MediaLibrary** |
| Multiple file uploads | **Spatie MediaLibrary** |
| Image conversions | **Spatie MediaLibrary** |
| File download tracking | **Spatie MediaLibrary** + custom properties |
| Verification workflow | **Custom Model** + Spatie Media |
| Per-file approval | **Custom Model** + Spatie Media |
| Revision history | **Custom Model** + Spatie Media |
| File metadata tracking | **Custom Model** + Spatie Media |

> ⚠️ **IMPORTANT:** Use **Custom Upload Model** ONLY when absolutely necessary. For 95% of cases, **Spatie MediaLibrary** alone is sufficient.

---

### Media Handling Best Practices

1. **Always use Spatie MediaLibrary** for file storage
2. **Use custom properties** for additional metadata
3. **Define conversions** for images (thumb, preview, etc)
4. **Use `downloadStorageFile()` helper** for safe downloads
5. **Add custom properties** to track uploader info:
   ```php
   ->withCustomProperties(['uploaded_by' => auth()->id()])
   ```
6. **Use fallback images** for collections that might be empty
7. **Queue conversions** for large images:
   ```php
   ->queued() // Instead of ->nonQueued()
   ```

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
```

---

## Activity Logging

### Automatic Logging (via Trait)

```php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
```

### Manual Logging

```php
// Simple log
logActivity('product_management', "Created product: {$product->name}", $product);

// With extra properties
activity('product_management')
    ->performedOn($product)
    ->causedBy(auth()->user())
    ->withProperties([
        'old_price' => $oldPrice,
        'new_price' => $newPrice,
    ])
    ->log('Updated product price');
```

---

## Authorization (RBAC)

### Spatie Laravel Permission

```php
// Di Model User
use Spatie\Permission\Traits\HasRoles;

class User extends Model
{
    use HasRoles;
}
```

### Create Permissions & Roles (Seeder)

```php
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Create permissions
Permission::create(['name' => 'view-products']);
Permission::create(['name' => 'create-products']);
Permission::create(['name' => 'edit-products']);
Permission::create(['name' => 'delete-products']);

// Create role and assign permissions
$role = Role::create(['name' => 'product-manager']);
$role->givePermissionTo(['view-products', 'create-products', 'edit-products']);

// Assign role to user
$user->assignRole('product-manager');
```

### Check Permissions di Controller

```php
// Using middleware
Route::middleware(['permission:edit-products'])->group(function () {
    Route::put('/products/{product}', [ProductController::class, 'update']);
});

// Using gate in controller
public function update(ProductRequest $request, Product $product)
{
    if (!auth()->user()->can('edit-products')) {
        abort(403, 'Unauthorized');
    }
    
    // ...
}
```

### Check Permissions di View

```blade
@if(auth()->user()->can('edit-products'))
    <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
        <i class="ti ti-edit"></i> Edit
    </a>
@endif

@if(auth()->user()->can('delete-products'))
    <button onclick="confirmDelete('{{ route('products.destroy', $product) }}')" 
            class="btn btn-danger">
        <i class="ti ti-trash"></i> Hapus
    </button>
@endif
```

---

## Helper Functions

### Global Helpers

```php
// Date formatting
formatTanggalIndo($date);           // "24 Maret 2026"
formatTanggalWaktuIndo($date);      // "Selasa, 24 Maret 2026 14:30"
formatWaktuSaja($time);             // "14:30"

// ID encryption
encryptId(102);                     // "Xj9a2S"
decryptId("Xj9a2S");               // 102
decryptIdIfEncrypted($id);         // Smart decrypt

// JSON responses
jsonSuccess('Success');
jsonSuccess('Success', route('products.index'));
jsonSuccess(['data' => $data, 'redirect' => route('products.index')]);
jsonError('Error occurred');
jsonError('Not found', 404);

// Activity logging
logActivity('product_management', "Created product", $product);

// Error logging
logError($exception);
logError('Something went wrong');

// QR Code generation
generateQrCodeImage($text, 'qrcode.png');
generateQrCodeBase64($text);

// Format bytes
formatBytes(1024);                  // "1 KB"
```

---

## Testing

### PHPUnit Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class ProductTest extends TestCase
{
    public function test_user_can_view_products()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get(route('products.index'));
        
        $response->assertStatus(200);
    }

    public function test_user_can_create_product()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        
        $data = [
            'name' => 'Test Product',
            'code' => 'TEST-001',
            'price' => 100000,
            'stock' => 10,
        ];
        
        $response = $this->actingAs($user)->post(route('products.store'), $data);
        
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('products', ['code' => 'TEST-001']);
    }

    public function test_user_cannot_create_product_without_permission()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post(route('products.store'), []);
        
        $response->assertStatus(403);
    }
}
```

### Run Tests

```bash
# Run all tests
composer test

# Run specific test
php artisan test --filter ProductTest

# Run with coverage
php artisan test --coverage
```

---

## View Composers

### Overview

Project ini menggunakan **View Composers** untuk inject data global ke views tertentu tanpa perlu passing data dari controller setiap kali. Ini adalah pattern yang **SANGAT PENTING** untuk dipahami.

### Apa itu View Composer?

View Composer adalah callback atau class yang dieksekusi setiap kali view tertentu di-render. Ini memungkinkan Anda untuk bind data ke view secara otomatis.

---

### View Composers di Project Ini

#### 1. **NotificationComposer** - Notifikasi Global

**File:** `app/View/Composers/NotificationComposer.php`

**Purpose:** Menyediakan data notifikasi ke header layout untuk ditampilkan di bell icon.

**Registered di:** `app/Providers/AppServiceProvider.php`

```php
// Di AppServiceProvider::boot()
View::composer('layouts.tabler.header', \App\View\Composers\NotificationComposer::class);
```

**Data yang di-share:**
- `$unreadCount` - Jumlah notifikasi belum dibaca
- `$topNotifications` - 10 notifikasi terbaru

**Implementasi Composer:**
```php
<?php

namespace App\View\Composers;

use Illuminate\View\View;

class NotificationComposer
{
    public function compose(View $view)
    {
        $user = auth()->user();

        if ($user) {
            $unreadCount = $user->unreadNotifications()->count();
            $topNotifications = $user->notifications()->take(10)->get();

            $view->with('unreadCount', $unreadCount)
                ->with('topNotifications', $topNotifications);
        } else {
            $view->with('unreadCount', 0)
                ->with('topNotifications', collect([]));
        }
    }
}
```

**Penggunaan di View (Header):**
```blade
{{-- resources/views/layouts/tabler/header.blade.php --}}
<div class="nav-item dropdown">
    <a class="nav-link icon" data-bs-toggle="dropdown">
        <i class="ti ti-bell"></i>
        
        @if(isset($unreadCount) && $unreadCount > 0)
            <span class="badge bg-red badge-notification badge-pill">
                {{ $unreadCount }}
            </span>
        @endif
    </a>
    
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
        <h6 class="dropdown-header">Notifikasi</h6>
        
        @if(isset($topNotifications) && $topNotifications->count() > 0)
            @foreach($topNotifications as $notification)
                <div class="dropdown-item">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p>{{ $notification->data['title'] ?? '' }}</p>
                            <small class="text-muted">
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="dropdown-item text-center text-muted">
                Tidak ada notifikasi baru
            </div>
        @endif
    </div>
</div>
```

**Di Controller (TIDAK PERLU passing data):**
```php
// ❌ SALAH - Tidak perlu passing notification data
public function index()
{
    $user = auth()->user();
    $unreadCount = $user->unreadNotifications()->count();
    $notifications = $user->notifications()->take(10)->get();
    
    return view('pages.dashboard', compact('unreadCount', 'notifications'));
}

// ✅ BENAR - Data sudah di-share otomatis oleh View Composer
public function index()
{
    return view('pages.dashboard');
}
```

---

#### 2. **SiklusSpmiComposer** - Data Siklus SPMI

**File:** `app/View/Composers/SiklusSpmiComposer.php`

**Purpose:** Menyediakan data siklus SPMI global untuk semua view yang terkait dengan modul Penjaminan Mutu.

**Registered di:** `app/Providers/AppServiceProvider.php`

```php
View::composer('layouts.tabler.header', \App\View\Composers\SiklusSpmiComposer::class);
```

**Dependencies:**
- `PeriodeSpmiService` - Service untuk mengambil data periode SPMI

**Data yang di-share:**
- `$globalSiklus` - Data siklus SPMI aktif

**Implementasi Composer:**
```php
<?php

namespace App\View\Composers;

use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\View\View;

class SiklusSpmiComposer
{
    protected $periodeSpmiService;

    public function __construct(PeriodeSpmiService $periodeSpmiService)
    {
        $this->periodeSpmiService = $periodeSpmiService;
    }

    public function compose(View $view)
    {
        $view->with('globalSiklus', $this->periodeSpmiService->getSiklusData());
    }
}
```

**Penggunaan di View:**
```blade
{{-- Di view Penjaminan Mutu --}}
@if(isset($globalSiklus))
    <div class="alert alert-info">
        <strong>Siklus Aktif:</strong> {{ $globalSiklus['nama_siklus'] }}
        <br>
        <small>Tahun: {{ $globalSiklus['tahun'] }}</small>
    </div>
@endif
```

---

### Cara Mendaftarkan View Composer Baru

#### Step 1: Buat Composer Class

```bash
php artisan make:view-composer UserStatsComposer
```

Atau manual:

```php
<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Services\Sys\UserService;

class UserStatsComposer
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function compose(View $view)
    {
        $view->with('userStats', [
            'total' => $this->userService->getBaseQuery()->count(),
            'active' => $this->userService->getBaseQuery()
                ->whereNull('expired_at')
                ->count(),
        ]);
    }
}
```

#### Step 2: Register di AppServiceProvider

```php
// app/Providers/AppServiceProvider.php

use Illuminate\Support\Facades\View;

public function boot(): void
{
    // Single view
    View::composer('pages.dashboard', \App\View\Composers\UserStatsComposer::class);
    
    // Multiple views
    View::composer([
        'pages.dashboard',
        'pages.users.index',
        'pages.users.show',
    ], \App\View\Composers\UserStatsComposer::class);
    
    // Wildcard pattern (semua view di folder admin)
    View::composer('pages.admin.*', \App\View\Composers\UserStatsComposer::class);
}
```

---

### Best Practices

#### ✅ DO:

1. **Gunakan untuk data global yang sering digunakan**
   - Notifikasi
   - User stats
   - Site settings
   - Navigation data

2. **Inject dependencies via constructor**
   ```php
   public function __construct(UserService $userService)
   {
       $this->userService = $userService;
   }
   ```

3. **Handle case ketika user tidak authenticated**
   ```php
   if (auth()->check()) {
       // Get user-specific data
   } else {
       // Set default values
   }
   ```

4. **Optimize query dengan cache untuk data yang jarang berubah**
   ```php
   use Illuminate\Support\Facades\Cache;
   
   public function compose(View $view)
   {
       $stats = Cache::remember('user_stats', 3600, function () {
           return [
               'total' => User::count(),
               'active' => User::where('is_active', true)->count(),
           ];
       });
       
       $view->with('userStats', $stats);
   }
   ```

5. **Gunakan nama yang deskriptif**
   - `NotificationComposer` ✅
   - `UserStatsComposer` ✅
   - `GlobalDataComposer` ❌ (terlalu umum)

#### ❌ DON'T:

1. **JANGAN gunakan untuk data yang hanya dibutuhkan di satu halaman**
   ```php
   // ❌ SALAH - Data hanya untuk halaman tertentu
   View::composer('pages.products.index', ProductComposer::class);
   
   // ✅ BENAR - Pass data dari controller
   public function index()
   {
       $products = Product::paginate(20);
       return view('pages.products.index', compact('products'));
   }
   ```

2. **JANGAN query berat tanpa cache**
   ```php
   // ❌ SALAH - Query berat setiap request
   public function compose(View $view)
   {
       $stats = DB::table('large_table')
           ->selectRaw('COUNT(*), SUM(column)')
           ->whereDate('created_at', '>=', now()->subYear())
           ->get();
       
       $view->with('stats', $stats);
   }
   
   // ✅ BENAR - Gunakan cache
   public function compose(View $view)
   {
       $stats = Cache::remember('yearly_stats', 3600, function () {
           return DB::table('large_table')
               ->selectRaw('COUNT(*), SUM(column)')
               ->whereDate('created_at', '>=', now()->subYear())
               ->get();
       });
       
       $view->with('stats', $stats);
   }
   ```

3. **JANGAN overuse - maksimal 3-5 composers**
   - Terlalu banyak composer akan memperlambat aplikasi
   - Setiap composer menambah query/processing time

4. **JANGAN gunakan untuk logic kompleks**
   - Composer hanya untuk bind data sederhana
   - Logic kompleks tetap di controller/service

---

### View Composer vs View Share

| Feature | View Composer | View Share |
|---------|--------------|------------|
| **Execution** | Setiap kali view di-render | Sekali per request |
| **Use Case** | Data dinamis (notifikasi) | Data statis (site name) |
| **Performance** | Lebih berat | Lebih ringan |
| **Flexibility** | Bisa conditional | Selalu tersedia |

**Example View Share:**
```php
// AppServiceProvider
View::share('siteName', config('app.name'));
View::share('currentYear', date('Y'));
```

---

### Troubleshooting

#### Issue: Variable tidak tersedia di view

**Solution:**
1. Cek apakah composer sudah di-register di `AppServiceProvider`
2. Pastikan view path benar (`layouts.tabler.header` = `resources/views/layouts/tabler/header.blade.php`)
3. Cek apakah user sudah authenticated (jika data user-specific)

#### Issue: Query lambat

**Solution:**
```php
// Tambahkan cache
use Illuminate\Support\Facades\Cache;

public function compose(View $view)
{
    $data = Cache::remember('composer_data', 3600, function () {
        // Heavy query here
    });
    
    $view->with('data', $data);
}
```

#### Issue: Data tidak update

**Solution:**
- Clear cache: `php artisan cache:clear`
- Clear view cache: `php artisan view:clear`
- Kurangi cache duration jika data perlu sering update

---

### Summary

**View Composers are perfect for:**
- ✅ Global notifications
- ✅ User-specific data (avatar, name, stats)
- ✅ Site-wide settings
- ✅ Navigation data
- ✅ Dashboard widgets

**NOT for:**
- ❌ Page-specific data
- ❌ Complex business logic
- ❌ Data that changes every request (without cache)
- ❌ Large datasets

---

## Code Quality

### Code Formatting

```bash
# Format code with Laravel Pint
composer format

# Or directly
./vendor/bin/pint
```

### Static Analysis

```bash
# PHPStan (if installed)
./vendor/bin/phpstan analyse

# Larastan (Laravel-specific PHPStan)
./vendor/bin/phpstan analyse --configuration=phpstan.neon
```

### Commit Message Convention

```
feat: add product export to Excel
fix: fix product price calculation
docs: update README.md
style: format code
refactor: refactor product service
test: add product unit tests
chore: update dependencies
```

---

## Next Steps

1. 📖 Baca [PROJECT_ARCHITECTURE.md](./PROJECT_ARCHITECTURE.md) untuk detail arsitektur
2. 📖 Baca [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) untuk API endpoints
3. 📖 Baca [TROUBLESHOOTING.md](./TROUBLESHOOTING.md) untuk common issues
