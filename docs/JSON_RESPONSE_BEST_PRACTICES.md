# JSON Response Best Practices - Laravel Boilerplate

**Last Updated:** March 2026

---

## 📋 Overview

Project ini menggunakan helper function `jsonSuccess()` dan `jsonError()` untuk response AJAX yang konsisten.

---

## 🎯 Pattern Dasar

### ✅ **BEST PRACTICE: Gunakan `url()->previous()`**

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
    $this->service->create($request->validated());
    
    return jsonSuccess('Data berhasil disimpan.', route('products.index'));
}

// ❌ SALAH - Tidak ada redirect, halaman tidak reload
public function store(Request $request)
{
    $this->service->create($request->validated());
    
    return jsonSuccess('Data berhasil disimpan.'); // ❌ No redirect!
}
```

---

## 🔧 Helper Functions

### `jsonSuccess($message, $redirect = null, $data = [], $code = 200)`

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$message` | string | `'Success'` | Pesan sukses |
| `$redirect` | string|null | `null` | URL untuk redirect setelah sukses |
| `$data` | array | `[]` | Data tambahan (optional) |
| `$code` | int | `200` | HTTP status code |

**Example:**
```php
// Simple success
return jsonSuccess('Data berhasil disimpan');

// With redirect (RECOMMENDED for AJAX forms)
return jsonSuccess('Data berhasil disimpan.', url()->previous());

// With specific redirect
return jsonSuccess('Data berhasil disimpan.', route('products.index'));

// With data
return jsonSuccess('Data berhasil disimpan.', null, [
    'id' => $product->id,
    'name' => $product->name
]);
```

### `jsonError($message, $code = 500, $data = [], $redirect = null)`

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$message` | string | `'Error'` | Pesan error |
| `$code` | int | `500` | HTTP status code |
| `$data` | array | `[]` | Data tambahan (optional) |
| `$redirect` | string|null | `null` | URL untuk redirect (optional) |

**Example:**
```php
// Simple error
return jsonError('Data tidak ditemukan');

// With 404 status
return jsonError('Data tidak ditemukan.', 404);

// With redirect
return jsonError('Session expired.', 401, [], route('login'));
```

---

## 🎯 Use Cases

### 1. **AJAX Form Submit (Modal)**

**Scenario:** User submit form di modal, halaman perlu reload untuk refresh data.

```php
public function store(Request $request)
{
    $this->service->create($request->validated());
    
    // ✅ Reload halaman sebelumnya (modal akan tertutup otomatis)
    return jsonSuccess('Data berhasil disimpan.', url()->previous());
}
```

**Why `url()->previous()`?**
- ✅ Tidak perlu hardcode route
- ✅ Otomatis reload halaman yang sama
- ✅ Lebih maintainable
- ✅ Modal akan tertutup otomatis (core-ajax.js handle)

---

### 2. **Create with Specific Redirect**

**Scenario:** User create data, setelah sukses redirect ke index.

```php
public function store(Request $request)
{
    $product = $this->service->create($request->validated());
    
    // ✅ Redirect ke index page
    return jsonSuccess('Product berhasil dibuat.', route('products.index'));
}
```

---

### 3. **Update/Delete with Back Button**

**Scenario:** User update/delete dari halaman detail, kembali ke detail setelah sukses.

```php
public function update(Request $request, Product $product)
{
    $this->service->update($product, $request->validated());
    
    // ✅ Kembali ke halaman sebelumnya (biasanya detail page)
    return jsonSuccess('Product berhasil diupdate.', url()->previous());
}

public function destroy(Request $request, Product $product)
{
    $this->service->delete($product);
    
    // ✅ Redirect ke index setelah delete
    return jsonSuccess('Product berhasil dihapus.', route('products.index'));
}
```

---

### 4. **Multi-Step Form**

**Scenario:** Form dengan beberapa step, setiap step reload halaman yang sama.

```php
public function step1(Request $request)
{
    $this->service->saveStep1($request->validated());
    
    return jsonSuccess('Step 1 berhasil disimpan.', url()->previous());
}

public function step2(Request $request)
{
    $this->service->saveStep2($request->validated());
    
    return jsonSuccess('Step 2 berhasil disimpan.', url()->previous());
}
```

---

## 🔄 How It Works

### Frontend Flow (core-ajax.js)

```javascript
// Di core-ajax.js
axios.post(url, formData)
    .then(response => {
        if (response.data.success !== false) {
            // Show success message
            showSuccessMessage(response.data.message);
            
            // ✅ Redirect if specified
            if (response.data.redirect) {
                window.location.href = response.data.redirect;
            }
            
            // Close modal (if in modal)
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
        }
    });
```

### Backend Flow

```php
// Controller
return jsonSuccess('Message', $redirectUrl);

// ↓ Helper function (GlobalHelper.php)
function jsonSuccess($arg1, $arg2 = null, $arg3 = [], $arg4 = 200)
{
    return jsonResponse(true, $arg1, $arg3, $arg4, $arg2);
}

// ↓ Returns
response()->json([
    'success' => true,
    'message' => 'Message',
    'redirect' => $redirectUrl  // ← Frontend detect this
]);
```

---

## 📋 Comparison Table

| Pattern | Code | Maintainability | Flexibility | Recommendation |
|---------|------|-----------------|-------------|----------------|
| **`url()->previous()`** | `jsonSuccess('OK', url()->previous())` | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ✅ **BEST** |
| **`route()`** | `jsonSuccess('OK', route('index'))` | ⭐⭐⭐⭐ | ⭐⭐⭐ | ✅ Good for specific redirect |
| **Hardcoded URL** | `jsonSuccess('OK', '/products')` | ⭐⭐ | ⭐⭐ | ❌ Avoid |
| **No Redirect** | `jsonSuccess('OK')` | ⭐⭐⭐ | ⭐ | ❌ Don't use for forms |

---

## 🚨 Common Mistakes

### ❌ Mistake 1: No Redirect

```php
// ❌ SALAH - Halaman tidak reload
public function store(Request $request)
{
    $this->service->create($request->validated());
    return jsonSuccess('Data berhasil disimpan.');
}

// ✅ BENAR
public function store(Request $request)
{
    $this->service->create($request->validated());
    return jsonSuccess('Data berhasil disimpan.', url()->previous());
}
```

---

### ❌ Mistake 2: Hardcoded URL

```php
// ❌ SALAH - Tidak maintainable
public function store(Request $request)
{
    $this->service->create($request->validated());
    return jsonSuccess('Data berhasil disimpan.', '/products/index');
}

// ✅ BENAR
public function store(Request $request)
{
    $this->service->create($request->validated());
    return jsonSuccess('Data berhasil disimpan.', route('products.index'));
}
```

---

### ❌ Mistake 3: Manual Redirect in Frontend

```javascript
// ❌ SALAH - Manual handle di JS
axios.post(url, formData)
    .then(response => {
        showSuccessMessage(response.data.message);
        window.location.reload(); // Manual reload
    });

// ✅ BENAR - Biarkan backend handle
// Backend: return jsonSuccess('OK', url()->previous());
// Frontend auto redirect (core-ajax.js already handle)
```

---

## 📚 Related Documentation

- [DEVELOPMENT_GUIDE.md](./DEVELOPMENT_GUIDE.md) - Development best practices
- [PROJECT_ARCHITECTURE.md](./PROJECT_ARCHITECTURE.md) - Global architecture
- [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) - API response format

---

**© 2026 Laravel Boilerplate - All Rights Reserved**
