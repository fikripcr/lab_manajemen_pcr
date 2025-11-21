# CRUD Operations & Data Management

## Overview

The system implements comprehensive CRUD (Create, Read, Update, Delete) operations following Laravel best practices with resource controllers, custom request validation, and DataTables integration.

## Creating New Modules

Follow this standard pattern to add new functionality to the system:

### 1. Generate Model with Migration and Resource Controller

```bash
php artisan make:model Product -mcr
```

This creates:
- Model class (`app/Models/Product.php`)
- Migration file (`database/migrations/...create_products_table.php`)
- Resource controller (`app/Http/Controllers/Admin/ProductController.php`)

### 2. Add Resource Route

In `routes/admin.php`:
```php
Route::resource('products', ProductController::class);
```

### 3. Create Custom Request Validation

```bash
php artisan make:request ProductRequest
```

### 4. Use Request in Controller

```php
public function store(ProductRequest $request) {
    // Validation handled automatically by ProductRequest
    $product = Product::create($request->validated());
    return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
}
```

### 5. Implement DataTables for Index View

For server-side processing:

```php
public function paginate(Request $request)
{
    $products = Product::whereNull('deleted_at');

    return DataTables::of($products)
        ->addColumn('action', function($product) {
            return '
                <a href="'.route('admin.products.edit', $product->encrypted_id).'" class="btn btn-sm btn-primary">Edit</a>
                <form action="'.route('admin.products.destroy', $product->encrypted_id).'" method="POST" class="d-inline">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                </form>
            ';
        })
        ->editColumn('created_at', function($product) {
            return formatTanggalIndo($product->created_at);
        })
        ->rawColumns(['action'])
        ->make(true);
}
```

### 6. Create Views in Appropriate Directories

- `resources/views/pages/admin/products/index.blade.php`
- `resources/views/pages/admin/products/create.blade.php`
- `resources/views/pages/admin/products/edit.blade.php`
- `resources/views/pages/admin/products/show.blade.php`

## Resource Controllers

The system follows Laravel's resource controller pattern with these standard methods:

- `index()` - List all resources
- `create()` - Show form for creating new resource
- `store()` - Store new resource
- `show()` - Show single resource
- `edit()` - Show form for editing resource
- `update()` - Update resource
- `destroy()` - Delete resource
- `paginate()` - Server-side DataTable response (custom method)

## Form Validation with Custom Request Classes

### Creating Request Classes

```bash
php artisan make:request ProductRequest
```

### Example Request Class

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Implement authorization logic as needed
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga produk harus berupa angka.',
        ];
    }
}
```

## DataTables Integration

The system uses Yajra DataTables for dynamic data presentation with server-side processing.

### Basic Implementation

In your controller:
```php
public function index()
{
    return view('pages.admin.products.index');
}

public function paginate(Request $request)
{
    $products = Product::whereNull('deleted_at');

    return DataTables::of($products)
        ->addColumn('action', function($product) {
            return '
                <a href="'.route('admin.products.edit', $product->encrypted_id).'" class="btn btn-sm btn-primary">Edit</a>
                <form action="'.route('admin.products.destroy', $product->encrypted_id).'" method="POST" class="d-inline">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                </form>
            ';
        })
        ->editColumn('created_at', function($product) {
            return formatTanggalIndo($product->created_at);
        })
        ->rawColumns(['action'])
        ->make(true);
}
```

### In Your View

```blade
<x-datatable.datatable :columns="[
    ['data' => 'name', 'title' => 'Name'],
    ['data' => 'description', 'title' => 'Description'],
    ['data' => 'price', 'title' => 'Price', 'render' => 'formatRupiah(data)'],
    ['data' => 'created_at', 'title' => 'Created'],
    ['data' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]
]">
    {{ route('admin.products.paginate') }}
</x-datatable.datatable>
```

## ID Encryption

To prevent enumeration attacks, all model IDs are encrypted in URLs using the `encryptId()` and `decryptId()` helper functions.

### Model Implementation

In your model, create accessors for encrypted IDs:

```php
// For primary key 'id':
public function getEncryptedIdAttribute()
{
    return encryptId($this->id);
}

// For model with custom primary key 'product_id':
public function getEncryptedProductIdAttribute()
{
    return encryptId($this->product_id);
}

// For foreign keys:
public function getEncryptedUserIdAttribute()
{
    return encryptId($this->user_id);
}
```

### Usage in Views

```blade
<form action="{{ route('admin.products.update', $product->encrypted_id) }}" method="POST">
    @csrf
    @method('PUT')
    <!-- Form fields -->
</form>

<a href="{{ route('admin.products.edit', $product->encrypted_id) }}">Edit</a>
```

### Usage in Controllers

```php
public function show($encryptedId)
{
    $id = decryptId($encryptedId);
    $product = Product::findOrFail($id);
    
    return view('pages.admin.products.show', compact('product'));
}
```

## Soft Delete Implementation

The system implements soft deletes to maintain data integrity while providing the appearance of deletion.

### Migration

In your migration file:
```php
Schema::create('products', function (Blueprint $table) {
    // ... other fields
    $table->softDeletes(); // Adds deleted_at column
});
```

### Model Implementation

In your model:
```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    // ... rest of model
}
```

### Controller Implementation

For soft deletes:
```php
public function destroy($encryptedId)
{
    $id = decryptId($encryptedId);
    $product = Product::findOrFail($id);
    $product->delete(); // This will soft delete
    
    return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
}

// For permanent deletion
public function forceDestroy($encryptedId)
{
    $id = decryptId($encryptedId);
    $product = Product::withTrashed()->findOrFail($id);
    $product->forceDelete(); // This will permanently delete
    
    return redirect()->route('admin.products.index')->with('success', 'Product permanently deleted.');
}

// To restore a soft deleted record
public function restore($encryptedId)
{
    $id = decryptId($encryptedId);
    $product = Product::withTrashed()->findOrFail($id);
    $product->restore();
    
    return redirect()->route('admin.products.index')->with('success', 'Product restored successfully.');
}
```

## Bulk Operations

The system supports bulk operations for efficiency.

### Bulk Delete Example

In controller:
```php
public function bulkDestroy(Request $request)
{
    $ids = $request->input('ids', []);
    $decryptedIds = array_map('decryptId', $ids);
    
    Product::whereIn('id', $decryptedIds)->delete();
    
    return response()->json(['success' => true, 'message' => 'Products deleted successfully.']);
}
```

### In View with JavaScript

```blade
<button id="bulk-delete-btn" class="btn btn-danger" disabled>Bulk Delete</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="selected[]"]');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('input[name="selected[]"]:checked').length;
            bulkDeleteBtn.disabled = checkedCount === 0;
        });
    });
    
    bulkDeleteBtn.addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('input[name="selected[]"]:checked'))
            .map(cb => cb.value);
        
        if (confirm(`Are you sure you want to delete ${selectedIds.length} items?`)) {
            fetch('{{ route('admin.products.bulk-delete') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    });
});
</script>
```

## Data Export/Import

The system supports data import/export using the `Maatwebsite\Excel` package.

### Export Implementation

Create an export class:
```bash
php artisan make:export ProductExport --model=Product
```

```php
<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::select('id', 'name', 'description', 'price', 'created_at')->get();
    }
    
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Price',
            'Created At',
        ];
    }
}
```

### In Controller
```php
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

public function export()
{
    return Excel::download(new ProductExport, 'products.xlsx');
}
```

### Import Implementation

Create an import class:
```bash
php artisan make:import ProductImport --model=Product
```

```php
<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Product([
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
        ]);
    }
}
```

### In Controller
```php
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);
    
    Excel::import(new ProductImport, $request->file('file'));
    
    return redirect()->back()->with('success', 'Products imported successfully.');
}
```