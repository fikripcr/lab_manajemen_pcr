## Qwen Added Memories
- ## Proyek Laravel SPMI (Penjaminan Mutu Internal)

### Status Implementasi yang Sudah Selesai:

**1. Pegawai - Denormalisasi Kolom**
- Migration: `2025_01_02_000001_add_common_columns_to_hr_pegawai.php`
- Kolom denormalisasi dari `latestDataDiri` ke `hr_pegawai` langsung
- Pemutu bisa CRUD pegawai tanpa perlu riwayat lengkap
- HR tetap punya audit trail via RiwayatDataDiri
- Seeder: `SyncPegawaiCommonColumnsSeeder.php`

**2. Periode SPMI & KPI - Optimasi**
- Migration: `2025_01_02_000002_add_pelaksanaan_dates_to_pemutu_periode_spmi.php`
- Pelaksanaan sekarang punya `pelaksanaan_awal` dan `pelaksanaan_akhir`
- Removed unused DataTables methods dari controllers
- Cleaned up service methods yang tidak dipakai

**3. Permissions & Roles**
- Seeder: `RolePermissionPemutuSeeder.php` (85 permissions, 7 roles)
- Roles: Pegawai, Auditee, Auditor Internal, Pimpinan Unit, Admin SPMI, Guest, Auditor Eksternal
- Semua 25 Pemutu controllers sudah pakai Spatie middleware langsung
- Base Controller disederhanakan (tidak ada helper kompleks)

**4. Data Scoping (Tim Mutu)**
- Trait: `app/Traits/ScopesTimMutu.php` - hanya `canEditUnit()` method
- Auditee/Auditor hanya bisa EDIT unit yang di-assign, LIHAT semua unit
- Admin bisa semua tanpa batas

**5. Approval Ownership**
- ApprovalController: cek apakah user = approver yang ditugaskan
- Hanya approver yang ditugaskan (atau admin) yang bisa process approval

**6. Menu & Views**
- `menu-pemutu.blade.php` - permission-based visibility
- Action columns di controller cek `canEditUnit()` sebelum tampilkan tombol "Isi"
- Modal forms sudah handle `?readonly=1`
- Export DOCX route: `pemutu.dokumen-spmi.export`

**7. Export DOCX**
- `DokumenApprovalService::exportToDocx()` - generate DOCX dengan tabel approver + QR code
- QR code memerlukan extension `imagick` untuk SVG→PNG conversion
- Tombol export di: `_workspace.blade.php`, `_approval_form.blade.php`, `approval/show.blade.php`

### Yang Belum/Kurang:
- QR code sudah ada di modul `sys` tapi belum diintegrasikan dengan export DOCX
- Perlu cek apakah ada library QR code yang sudah terinstall selain BaconQrCode
- Migration belum dijalankan (perlu PHP 8.4+)

### Struktur Key:
- Base Controller: `app/Http/Controllers/Controller.php` (simple, hanya AuthorizesRequests + ValidatesRequests)
- Spatie Permission sudah terinstall (`spatie/laravel-permission ^6.23`)
- QR Library: `BaconQrCode` sudah ada
- DOCX Library: `PhpOffice\PhpWord` sudah ada
- View components di: `resources/tabler-core/views/components/tabler/`
- Pages di: `resources/views/pages/pemutu/`
- ## Migration & Seeder Status - UPDATED

### Migrations Consolidated
- **HR**: `2025_01_01_000001_create_hr_tables.php` — includes denormalized pegawai columns
- **Pemutu**: `2025_01_01_000005_create_pemutu_tables.php` — includes pelaksanaan_awal/akhir, NO views (views moved to later migrations)
- **Standalone migrations deleted**: `2025_01_02_000001` (hr_pegawai columns) and `2025_01_02_000002` (pelaksanaan dates) — merged into main files

### All migrations ran successfully via Docker
```bash
docker-compose exec app php artisan migrate:fresh --force
docker-compose exec app php artisan db:seed --class=RolePermissionPemutuSeeder --force
docker-compose exec app php artisan db:seed --class=SyncPegawaiCommonColumnsSeeder --force
```

### Key fixes made:
1. Removed view creation from main Pemutu migration (views created in `2026_03_19_000001_recreate_summary_views_fixed.php`)
2. Added missing `pemutu.ami.view-all` permission to seeder
3. QR Code export uses `GDLibRenderer` (PNG direct, no imagick needed)

### Container: `laravel-boilerplate-app` (PHP-FPM), `laravel-boilerplate-nginx` (Nginx on port 9028)
