# Sistem Penjaminan Mutu Internal (SPMI)

Dokumentasi modul Pemutu — versi terbaru.

---

## Siklus SPMI (PPEPP)

```
Penetapan → Pelaksanaan → Evaluasi → Pengendalian → Peningkatan
   ↑                                                    │
   └───────────── kembali ke Penetapan ─────────────────┘
```

| Fase | Apa yang dilakukan |
|------|-------------------|
| **1. Penetapan** | Buat Visi, Misi, Renstra, Renop, Standar, dan Indikator |
| **2. Pelaksanaan** | Unit melaksanakan kegiatan + isi Evaluasi Diri (ED) |
| **3. Evaluasi** | Tim Mutu audit (AMI), isi temuan & rekomendasi |
| **4. Pengendalian** | Unit isi tindak lanjut, Pimpinan validasi, RTM |
| **5. Peningkatan** | Duplikasi ke periode baru, approval staging |

---

## Hierarki Dokumen

```
Visi → Misi → RJP → Renstra → Renop → Standar
                                          ↓
                                       Indikator
                                    ├── per Unit (OrgUnit)
                                    └── per Pegawai (KPI)
```

---

## Roles & Permissions

Ada 7 role di sistem ini. User yang tidak punya role **tidak bisa login**.

| Role | Bisa Apa |
|------|----------|
| **Admin SPMI** | Semua fitur — kelola dokumen, approval, periode, export |
| **Pimpinan Unit** | Lihat data unit sendiri + validasi pengendalian + approve dokumen |
| **Auditee** | Isi ED, KPI, Pengendalian — **hanya untuk unit yang di-assign** |
| **Auditor Internal** | Isi AMI, Tinjauan Efektivitas — **hanya untuk unit yang di-assign** |
| **Pegawai** | Lihat semua data + export (read-only) |
| **Guest** | Lihat semua data (read-only, **tidak bisa export**) |
| **Auditor Eksternal** | Belum ada permission spesifik — bisa ditambahkan nanti |

### Cara Kerja Unit Assignment

Via menu **Tim Mutu**, admin menetapkan siapa auditee & auditor untuk setiap unit:

- **Auditee** hanya bisa edit data unit tempat dia ditugaskan
- **Auditor** hanya bisa isi AMI unit tempat dia ditugaskan
- **Semua role** bisa **melihat** data unit lain (read-only)
- **Admin SPMI** tidak ada batasan — bisa edit semua unit

---

## Fitur Penting

### Export Dokumen (DOCX)

Tombol **Export** ada di halaman dokumen. Hasil DOCX berisi:
- Info dokumen (kode, judul, jenis, periode)
- Isi dokumen
- Tabel daftar approver (nama, jabatan, status, tanggal)
- QR Code verifikasi (jika semua approver sudah approve)

QR Code bisa di-scan → mengarah ke halaman verifikasi publik.

> **Syarat:** PHP extension `gd` harus aktif (sudah ada di hampir semua server).

### Approval Dokumen

Setiap dokumen bisa punya banyak approver (paralel, bukan berurutan):
- Semua approver harus **Approved** → dokumen sah
- Ada QR Code + tombol "Buka Pranala Asli"
- Tombol Export muncul setelah dokumen sah

### Periode SPMI & KPI

| | Periode SPMI | Periode KPI |
|--|-------------|-------------|
| **Tujuan** | Siklus PPEPP (Akademik/Non Akademik) | Penilaian KPI tahunan |
| **Isi** | Tanggal tiap fase (Penetapan, Pelaksanaan, ED, AMI, dll) | Nama, tahun, tanggal mulai/selesai |
| **Aktif** | Bisa ada 2 (Akademik + Non Akademik) | Hanya 1 yang aktif |

---

## Struktur Kode Singkat

```
app/
├── Models/Pemutu/          # Model utama: Dokumen, Indikator, PeriodeSpmi, TimMutu
├── Services/Pemutu/        # Business logic per fitur
├── Http/Controllers/Pemutu/ # 25 controller, semua pakai middleware permission
├── Traits/ScopesTimMutu.php # Trait untuk cek unit access
└── Helpers/                 # Helper global (format tanggal, dll)

routes/pemutu.php            # Semua route Pemutu
resources/views/pages/pemutu/ # Blade views
```

---

## Yang Perlu Diperbaiki di Sistem

| Issue | Lokasi | Apa yang Salah |
|-------|--------|---------------|
| **Approval route tidak konsisten** | `DokumenApprovalController` | Route `pemutu.dokumen.approve.create` dipakai di view tapi tidak ada di routes |
| **IndikatorSummaryController** | Controller vs docs | Di docs disebut sebagai "model", padahal ini controller |
| **Services tidak sesuai docs** | `00-general-overview.md` | Doc sebut `AmiService`, `PengendalianService` — yang ada sebenarnya `IndikatorOrgUnitService`, `AmiExportService`, dll |
| **Missing migration execution** | Semua migration baru | Migration sudah dibuat tapi belum dijalankan di database |

---

## Cara Deploy

```bash
# 1. Jalankan semua migration baru
php artisan migrate

# 2. Sync data pegawai yang sudah ada
php artisan db:seed --class=SyncPegawaiCommonColumnsSeeder

# 3. Setup permissions & roles
php artisan db:seed --class=RolePermissionPemutuSeeder

# 4. Clear cache
php artisan cache:clear && php artisan view:clear && php artisan config:clear
```

---

**File dokumentasi lain yang tersedia:**
- `COMPLETE_DOCUMENTATION.md` — dokumentasi lengkap semua fitur
- `../../docs/GLOBAL_APPROVAL_SERVICE.md` — cara kerja approval global
