# Denormalisasi Kolom Pegawai - Implementation Summary

## 📋 Overview

This implementation denormalizes common columns from `hr_riwayat_datadiri` directly into `hr_pegawai` table, allowing modules like **Pemutu** to query basic employee information without joining to history tables, while **HR** maintains full audit trail via RiwayatDataDiri.

---

## 🗂️ Files Changed

### 1. **Migration**
- **File:** `database/migrations/2025_01_02_000001_add_common_columns_to_hr_pegawai.php`
- **Purpose:** Add denormalized columns to `hr_pegawai` table
- **Columns Added:**
  - `nama`, `nip`, `email`, `inisial`, `no_hp`
  - `jenis_kelamin`, `tempat_lahir`, `tgl_lahir`
  - `orgunit_posisi_id`, `orgunit_departemen_id`
  - `nidn`, `no_ktp`, `alamat`, `status_nikah`, `agama`
  - `gelar_depan`, `gelar_belakang`, `bidang_ilmu`

### 2. **Model: Pegawai**
- **File:** `app/Models/Hr/Pegawai.php`
- **Changes:**
  - ✅ Added all common columns to `$fillable`
  - ✅ Added `posisi()` and `departemen()` relationships
  - ✅ Updated accessors to use direct columns with fallback to `latestDataDiri`
  - ✅ Backward compatible: if column is null, falls back to riwayat

### 3. **HR Service: PegawaiService**
- **File:** `app/Services/Hr/PegawaiService.php`
- **Changes:**
  - ✅ `createPegawai()`: Now populates both common columns AND creates RiwayatDataDiri
  - ✅ `getFilteredQuery()`: Uses direct columns instead of `whereHas('latestDataDiri')`
  - ✅ Added `syncCommonColumns()`: Syncs from RiwayatDataDiri after approval

### 4. **HR Service: RiwayatDataDiriService**
- **File:** `app/Services/Hr/RiwayatDataDiriService.php`
- **Changes:**
  - ✅ Injected `PegawaiService`
  - ✅ `processApproval()`: Calls `syncCommonColumns()` when status is "Approved"
  - ✅ Maintains backward compatibility for non-approved status

### 5. **Pemutu Service: PegawaiService**
- **File:** `app/Services/Pemutu/PegawaiService.php`
- **Changes:**
  - ✅ `getFilteredQuery()`: No joins needed - direct column access
  - ✅ `getUsersWithPegawaiData()`: Removed `latestDataDiri` eager load
  - ✅ Simplified CRUD operations

### 6. **Pemutu Controller: PegawaiController**
- **File:** `app/Http/Controllers/Pemutu/PegawaiController.php`
- **Changes:**
  - ✅ Fixed variable names (`$pegawai` instead of `$hr_pegawai`)
  - ✅ `data()`: Uses `departemen` relationship instead of `orgUnit`

### 7. **Pemutu Request: PegawaiRequest**
- **File:** `app/Http/Requests/Pemutu/PegawaiRequest.php`
- **Changes:**
  - ✅ Updated validation rules to match new columns
  - ✅ Removed non-existent fields (`jenis`, `external_id`)

### 8. **Pemutu Views**
- **Files:**
  - `resources/views/pages/pemutu/pegawai/index.blade.php`
  - `resources/views/pages/pemutu/pegawai/create-edit-ajax.blade.php`
- **Changes:**
  - ✅ Simplified form fields (nama, nip, email, orgunit_departemen_id)
  - ✅ Updated DataTables columns to use direct columns
  - ✅ Removed non-existent filters (`jenis`)

### 9. **Other Files Updated**
- `app/Http/Controllers/Hr/PegawaiController.php`: Select2 search uses direct columns
- `app/Services/Pemutu/TimMutuService.php`: Search uses direct columns
- `app/Services/Pemutu/IndikatorSummaryPerformaService.php`: Uses `whereNotNull('nama')` instead of `whereHas('latestDataDiri')`

### 10. **Data Migration Seeder**
- **File:** `database/seeders/SyncPegawaiCommonColumnsSeeder.php`
- **Purpose:** Populate common columns from existing `latestDataDiri` records
- **Usage:** `php artisan db:seed --class=SyncPegawaiCommonColumnsSeeder`

---

## 🔄 Deployment Steps

### Step 1: Run Migration
```bash
php artisan migrate --path=database/migrations/2025_01_02_000001_add_common_columns_to_hr_pegawai.php
```

### Step 2: Sync Existing Data
```bash
php artisan db:seed --class=SyncPegawaiCommonColumnsSeeder
```

### Step 3: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Step 4: Test
1. **HR Module:**
   - Create new employee → should populate both `hr_pegawai` common columns AND `hr_riwayat_datadiri`
   - Request data change → should create new RiwayatDataDiri with Pending status
   - Approve change → should sync common columns to `hr_pegawai`

2. **Pemutu Module:**
   - View employee list → should load faster (no joins)
   - Create/Edit employee → should update common columns directly
   - Search employee → should use direct column search

---

## 🏗️ Architecture

### Before
```
┌──────────────┐         ┌──────────────────────┐
│ hr_pegawai   │         │ hr_riwayat_datadiri  │
│──────────────│         │──────────────────────│
│ pegawai_id   │────┐    │ riwayatdatadiri_id   │
│ user_id      │    └──→ │ (latest_riwayatdatadiri_id) │
│ latest_...id │         │ nama, nip, email...  │
└──────────────┘         └──────────────────────┘

Query: Pegawai::with('latestDataDiri')->get()
```

### After
```
┌─────────────────────────────────────────────┐
│ hr_pegawai                                  │
│─────────────────────────────────────────────│
│ pegawai_id                                  │
│ user_id                                     │
│ nama, nip, email, ... (common columns)     │ ← Direct access
│ latest_riwayatdatadiri_id (pointer)        │
└─────────────────────────────────────────────┘
         │
         │ (still points to)
         ↓
┌──────────────────────┐
│ hr_riwayat_datadiri  │
│──────────────────────│
│ riwayatdatadiri_id   │
│ nama, nip, email...  │
│ before_id (chain)    │ ← Full history
│ latest_riwayatapproval_id
└──────────────────────┘

Query Pemutu: Pegawai::query()->get()  ← No joins!
Query HR: Pegawai::with('historyDataDiri')->get()  ← When needed
```

---

## ✅ Benefits

| Aspect | Before | After |
|--------|--------|-------|
| **Pemutu Query** | `Pegawai::with('latestDataDiri')` | `Pegawai::query()` |
| **Join Required** | Yes (2 tables) | No (1 table) |
| **Accessor Risk** | Can fail if riwayat null | Direct column access |
| **Create Flow** | Must create Riwayat first | Direct insert to Pegawai |
| **HR History** | ✅ Full audit trail | ✅ Full audit trail (unchanged) |
| **Performance** | Medium | Fast |

---

## ⚠️ Important Notes

1. **Data Consistency:**
   - Common columns are synced ONLY when approval is "Approved"
   - If HR creates pegawai, common columns are populated immediately
   - If HR requests change, common columns update AFTER approval

2. **Backward Compatibility:**
   - Accessors fallback to `latestDataDiri` if column is null
   - Existing code using `$pegawai->nama` still works
   - Old queries with `whereHas('latestDataDiri')` replaced with direct column queries

3. **Future Maintenance:**
   - If new common fields needed → add to both migration and `syncCommonColumns()`
   - If HR needs different fields → only update RiwayatDataDiri, not Pegawai
   - Keep `syncCommonColumns()` in sync with `$fillable` array

---

## 🐛 Troubleshooting

### Issue: "Column 'nama' not found"
**Solution:** Run the migration first
```bash
php artisan migrate
```

### Issue: Pegawai list shows "-" for names
**Solution:** Run the data sync seeder
```bash
php artisan db:seed --class=SyncPegawaiCommonColumnsSeeder
```

### Issue: HR create fails
**Solution:** Check if all required fields are in the request
- HR PegawaiRequest validates more fields than Pemutu
- Ensure form sends all validated fields

### Issue: Approval doesn't update Pegawai
**Solution:** Check if `syncCommonColumns()` is called
- Verify `RiwayatDataDiriService` has `PegawaiService` injected
- Check if approval status is "Approved" (case-sensitive)

---

## 📝 Next Steps (Optional Enhancements)

1. **Add Observer:** Auto-sync common columns when `latest_riwayatdatadiri_id` changes
2. **Add Indexes:** Index `nama`, `nip`, `email` for faster searches
3. **Add Validation:** Unique constraint on `nip` and `email` at Pegawai level
4. **Audit Trail:** Log when common columns are synced vs direct updates
5. **API Endpoint:** Expose simple Pegawai endpoint for external systems

---

## 👥 Module Responsibilities

| Module | Can Create | Can Edit | Needs Approval | Has History |
|--------|-----------|----------|----------------|-------------|
| **HR** | ✅ Yes | ✅ Yes | ✅ Yes | ✅ Full |
| **Pemutu** | ✅ Yes | ✅ Yes (basic) | ❌ No | ❌ No |

**Pemutu** manages only basic employee info (nama, nip, email, org_unit) without approval workflow. HR manages full lifecycle with complete audit trail.

---

**Implementation Date:** April 4, 2026  
**Status:** ✅ Code complete, pending migration execution  
**Breaking Changes:** None (backward compatible with fallback accessors)
