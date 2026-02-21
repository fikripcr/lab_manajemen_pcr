# Project Standardization Guide (High Detail)

Dokumen ini adalah referensi teknis mendalam (*Single Source of Truth*) untuk seluruh arsitektur, standar koding, dan keamanan proyek ini. Seluruh pengembang wajib mematuhi pedoman ini tanpa pengecualian.

---

## 1. Arsitektur Backend

### A. Pattern & Encapsulation (Service Layer)
Proyek ini menggunakan **Service-Oriented Architecture** untuk memisahkan logika bisnis dari HTTP respons.

- **Thin Controller**: Controller hanya bertugas memanggil service dan mengembalikan respons (View/JSON). Lakukan pengambilan data awal (*data retrieval*) di sini.
- **Thick Service**: Semua logika bisnis, manipulasi database, dan interaksi pihak ketiga ada di Service.
- **Object-Oriented Service**: Fungsi di dalam Service **WAJIB** menerima parameter berupa **Objek Model**, bukan ID mentah, untuk menghindari query `find` yang redudan.
- **Dependency Injection**: Gunakan constructor injection. Nama properti service **WAJIB camelCase** (Contoh: `protected pegawaiService $pegawaiService`).
- **Mediator Pattern**: Controller bertugas sebagai penengah yang menghubungkan input (Request), logika (Service), dan output (jsonResponse).
  ```php
  // app/Http/Controllers/Shared/SlideshowController.php
  public function __construct(protected SlideshowService $slideshowService) {}
  ```

### B. Route Model Binding & Security (Encrypted ID)
Keamanan ID adalah prioritas utama (**Encrypted ID Everywhere**).

- **Trait HashidBinding**: Wajib ditambahkan pada setiap model yang diekspos ke URL.
  - Trait ini otomatis meng-enkripsi ID di route dan men-dekripsi saat resolve binding.
- **Controller-Level Retrieval**: Manfaatkan **Route Model Binding** di parameter controller untuk mengambil data secara otomatis. Hindari melakukan `findOrFail($id)` di dalam Service.
- **View-Level Encryption**: Gunakan atribut `encrypted_{entity}_id` (Contoh: `encrypted_slideshow_id`) jika ID perlu diekspos ke View (misal untuk parameter route di JS atau link).
- **Helper Encryption**:
  - `encryptId($id)`: Enkripsi ID mentah.
  - `decryptId($hash, $throwException = true)`: Dekripsi hashid.
  - `decryptIdIfEncrypted($id)`: Dekripsi jika formatnya hashid, biarkan jika numeric.

### C. Validation (Form Requests)
Validasi harus dipisahkan dari controller menggunakan class Request khusus.

- **Naming**: `{Entity}Request.php` atau `{Entity}{Action}Request.php`.
  - Contoh: `SlideshowRequest.php`, `PegawaiStoreRequest.php`.
- **Integrasi**: Panggil langsung di method controller.
  ```php
  public function store(SlideshowRequest $request) { ... }
  ```

### D. Model Traits & Best Practices
- **Eager Loading**: **WAJIB** gunakan `with(['relation'])` pada query untuk mencegah masalah **N+1**.
- **Traits Standar**:
  - `HashidBinding`: Penanganan ID terenkripsi.
  - `Blameable`: Otomasi audit trail (`created_by`, `updated_by`, `deleted_by`).
  - `SoftDeletes`: Penghapusan logis tanpa menghapus row fisik.
- **Primary Key**: Harus spesifik `{entity}_id` (Contoh: `slideshow_id`).

### E. Responses (Global Helpers & Error Handling)
Seluruh aksi di Controller **WAJIB** dibungkus dalam blok `try-catch` untuk memastikan error tertangani secara sistematis dan seragam.

- **Success**: Gunakan `jsonSuccess($message, $redirectUrl)` untuk mengembalikan respons sukses dan memicu penutupan modal/reload tabel otomatis di frontend.
- **Error**: Gunakan `jsonError($message)` di dalam blok `catch`. Pastikan memanggil `logError($e)` sebelum return agar stack trace tersimpan di log.
- **Helper Referensi**:
  - `jsonSuccess($message, $redirectUrl, $data, $code)`
  - `jsonError($message, $code, $data, $redirect)`
  - `jsonResponse($success, $message, $data, $code, $redirect)`

---

## 2. Frontend & UI Standardization (Tabler & AJAX)

### A. Blade Components (x-tabler)
Hampir seluruh elemen UI dibungkus dalam komponen Blade untuk konsistensi layout.

| Komponen | Kegunaan |
|----------|----------|
| `<x-tabler.page-header>` | Judul halaman dan breadcrumb. |
| `<x-tabler.button>` | Tombol standar (type: submit, back, delete, modal). |
| `<x-tabler.form-input>` | Input teks, date (Flatpickr), password (eye toggle), file (FilePond). |
| `<x-tabler.form-select>` | Select dropdown, otomatis mendukung Select2 (offline). |
| `<x-tabler.form-textarea>` | Textarea teks atau Rich Text Editor (`type="editor"`). |
| `<x-tabler.empty-state>` | UI placeholder jika data tabel/list kosong (**WAJIB DIGUNAKAN**). |
| `<x-tabler.flash-message>`| Menampilkan notifikasi error/sukses dari session flash. |

**CATATAN**: Dilarang membuat elemen UI ad-hoc jika komponen `<x-tabler.*>` sudah tersedia.

### B. DataTables Action Standardization
Hindari pembuatan file `_action.blade.php` yang berulang. Gunakan komponen standar `components.tabler.datatables-actions` di dalam kolom `action` pada DataTable:
```php
->addColumn('action', function ($row) {
    return view('components.tabler.datatables-actions', [
        'editUrl'   => route('module.sub.edit', $row->encrypted_id),
        'editModal' => true, // Gunakan true jika edit menggunakan AJAX modal
        'deleteUrl' => route('module.sub.destroy', $row->encrypted_id),
    ])->render();
})
```

### C. Unified Views (create-edit-ajax)
Untuk mengurangi duplikasi file, gunakan pola satu file untuk tambah dan edit data:
- Nama file: `create-edit-ajax.blade.php`.
- Gunakan `@if($model->exists)` untuk membedakan judul, method form (`PUT`/`POST`), dan teks tombol.
- Parameter `data-redirect="true"` pada `<x-tabler.form-modal>` digunakan jika halaman perlu di-refresh/pindah setelah sukses (biasanya untuk aksi *Create*).

### C. JavaScript Loading & Interop
Proyek ini menggunakan pemuatan asset secara granular melalui `tabler.js`:

- **Granular Loading**: Gunakan fungsi `window.load...` untuk memuat library hanya saat dibutuhkan:
  - `window.loadHugeRTE('#selector')`: Memuat HugeRTE (Rich Text Editor).
  - `window.loadFlatpickr()`: Memuat Flatpickr.
  - `window.loadFilePond()`: Memuat FilePond.
  - `window.loadSelect2()`: Memuat Select2.
- **Auto Initialization**: Fungsi `window.initFlatpickr()`, `window.initFilePond()`, dan `window.initOfflineSelect2()` dijalankan otomatis pada `DOMContentLoaded` dan setelah AJAX modal dimuat.

### D. AJAX Handlers
Logic utama berada di `FormHandlerAjax.js`:
- **class="ajax-form"**: Form akan dikirim via Axios. Menangani:
  - Loading spinner pada tombol submit.
  - Menutup modal secara otomatis.
  - Reload DataTable (`.dataTable`) secara otomatis.
  - Notifikasi sukses/error (SweetAlert2).
- **class="ajax-delete"**: Digunakan pada tombol hapus.
  - Atribut: `data-url` (endpoint hapus), `data-title` (judul konfirmasi).
  - Melakukan konfirmasi SweetAlert dan penghapusan otomatis.
- **class="ajax-modal-btn"**: Membuka modal dan memuat konten secara dinamis via AJAX.

---

## 3. Database & System Helpers (SysHelper.php)
- **Activity Log**: `logActivity($logName, $description, $subject)`.
- **Error Log**: `logError($exception)`.
- **Tanggal Indonesia**: `formatTanggalIndo($date)` atau `formatTanggalWaktuIndo($date)`.

---
## 4. Struktur Folder & Naming
- **Services**: `app/Services/{Module}/{Entity}Service.php`.
- **Requests**: `app/Http/Requests/{Module}/{Entity}Request.php`.
- **Views**: `resources/views/pages/{module}/{entity}/`.
- **Assets**: Proyek ini menggunakan **Vite JS** untuk kompilasi asset. Seluruh file JS/CSS utama berada di folder `resources/js/` dan `resources/css/`.

---
*Dokumen ini bersifat definitif dan diperbarui secara berkala. Terakhir diperbarui: Februari 2026.*
