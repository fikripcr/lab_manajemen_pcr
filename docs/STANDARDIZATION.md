# Project Maturity & Standardization

Dokumen ini merangkum tingkat kematangan project dan standar yang telah diimplementasikan untuk memastikan konsistensi dan kemudahan pengembangan lebih lanjut.

## 1. Form & Input Standards

Project ini telah beralih dari input HTML mentah ke penggunaan komponen Blade yang seragam.

### Komponen Utama: `x-tabler.form-input`
Komponen ini menangani label, pesan error, help text, dan inisialisasi library pihak ketiga secara otomatis.

- **Date Picker (Flatpickr)**: Otomatis diinisialisasi untuk `type="date"`, `datetime`, `range`, atau `multiple`.
- **Password Toggle**: Otomatis menambahkan icon mata untuk melihat/menyembunyikan password pada `type="password"`.
- **Number Formatting**: Mendukung `step`, `min`, dan `max` secara native.

**Contoh Penggunaan:**
```blade
<x-tabler.form-input type="date" name="tanggal" label="Tanggal Lahir" required="true" />
<x-tabler.form-input type="password" name="password" label="Kata Sandi" />
```

## 2. Global JavaScript Handling

Untuk menghindari kode JS yang berulang pada setiap halaman, logika global telah dipusatkan:

- **Automatic Flatpickr**: `window.initFlatpickr()` dijalankan otomatis pada setiap load halaman atau saat modal ditampilkan.
- **AJAX Form Handler**: Penggunaan `class="ajax-form"` pada tag `<form>` akan menangani submission lewat Axios, menampilkan loading, SweetAlert Berhasil/Gagal, dan reload tabel/halaman secara cerdas.
- **Modal Support**: Logika AJAX form menyadari jika di dalam modal, ia akan menutup modal dan memperbarui UI setelah sukses.

## 3. UI Consistency

- **Table Layout**: Standarisasi penggunaan class `table-vcenter`, `card-table`, dan logic input di dalam tabel (`mb-0`, `form-control-sm`).
- **Standard Action Buttons**: Penggunaan komponen `x-tabler.button` untuk tombol Back, Submit, Delete, dll.
- **Loading States**: Semua interaksi berat (AJAX) memiliki loading state yang terintegrasi di `admin.js`.

## 4. Status Maturitas (Februari 2026)

| Modul | Status Standarisasi | Catatan |
|-------|---------------------|---------|
| **SYS** | ✅ Full | Semua form konvensional telah dimigrasi. |
| **HR** | ✅ Full | Termasuk sub-modul pegawai dan presensi. |
| **LAB** | ✅ Full | Termasuk manajemen semester dan inventaris. |
| **PEMUTU** | ✅ Full | Termasuk KPI, Indikator, dan Dokumen. |
| **EOFFICE** | ✅ Full | Termasuk konfigurasi layanan dan feedback. |

## Panduan untuk Agent Selanjutnya

Saat menambahkan fitur baru:
1. **Gunakan Service Pattern**: Logic bisnis di `app/Services`, panggil dari Controller.
2. **Gunakan x-tabler Components**: Jangan gunakan tag `<input>` mentah.
3. **Gunakan ajax-form**: Hindari menulis script submit JS manual jika memungkinkan.
4. **Cek docs/ archive**: Untuk dokumentasi lama yang mungkin masih relevan secara arsitektur.
