# Dokumentasi Lengkap Sistem SPMI (Pemutu & PPEPP)

**Last Updated:** March 2026  
**Framework:** Laravel 12.46.0  
**Module:** Penjaminan Mutu (Pemutu)

---

## 📋 Table of Contents

### 📘 Part 1: General Overview
1. [General Overview](#general-overview) - Gambaran umum sistem SPMI
2. [Dokumen Hierarchy](#dokumen-hierarchy) - Hierarki dokumen
3. [Indikator Types](#indikator-types) - Jenis-jenis indikator

### 📗 Part 2: PPEPP Cycle
4. [Penetapan (Planning)](#penetapan-planning)
5. [Pelaksanaan (Implementation)](#pelaksanaan-implementation)
6. [Evaluasi (Evaluation)](#evaluasi-evaluation)
7. [Pengendalian (Control)](#pengendalian-control)
8. [Peningkatan (Improvement)](#peningkatan-improvement)

### 📙 Part 3: Technical Details
9. [Approval Workflow](#approval-workflow)
10. [Periode Management](#periode-management)
11. [Data Structures](#data-structures)

---

## 🎯 Quick Start

### SPMI Cycle (PPEPP)

```
┌─────────────────────────────────────────────────────────────┐
│                    SIKLUS SPMI (PPEPP)                      │
├─────────────────────────────────────────────────────────────┤
│  1. PENETAPAN → 2. PELAKSANAAN → 3. EVALUASI               │
│       ↑                              │                      │
│       │                              ↓                      │
│  5. PENINGKATAN ← 4. PENGENDALIAN                          │
└─────────────────────────────────────────────────────────────┘
```

### Dokumen Hierarchy

```
Visi → Misi → RJP → Renstra → Renop → Standar → Indikator
```

---

## 📖 1. General Overview

### Apa itu SPMI?

Sistem Penjaminan Mutu Internal (SPMI) mengadopsi siklus **PPEPP**:

1. **Penetapan** - Menetapkan Visi, Misi, Tujuan, Sasaran, Strategi, Kebijakan, Program, Rencana Kerja, Indikator, Standar
2. **Pelaksanaan** - Melaksanakan kegiatan sesuai rencana
3. **Evaluasi** - Evaluasi Diri (ED) dan Audit Mutu Internal (AMI)
4. **Pengendalian** - Rapat Tinjauan Manajemen (RTM), analisis, tindak lanjut
5. **Peningkatan** - Pelaksanaan tindak lanjut, duplikasi ke periode berikutnya

### Key Entities

| Entity | Table | Purpose |
|--------|-------|---------|
| `Dokumen` | `pemutu_dokumen` | Dokumen SPMI (Visi, Renstra, Renop, Standar) |
| `DokSub` | `pemutu_dok_sub` | Poin-poin/sub-dokumen |
| `Indikator` | `pemutu_indikator` | Indikator capaian (Renop/Standar) |
| `IndikatorOrgUnit` | `pemutu_indikator_orgunit` | Assignment ke Unit Kerja |
| `IndikatorPegawai` | `pemutu_indikator_pegawai` | KPI Individual |
| `PeriodeSpmi` | `pemutu_periode_spmi` | Periode SPMI (Akademik/Non Akademik) |
| `PeriodeKpi` | `pemutu_periode_kpi` | Periode KPI (Tahunan) |
| `RiwayatApproval` | `pemutu_riwayat_approval` | Approval workflow (polymorphic) |

---

## 📊 2. Dokumen Hierarchy

### Level Structure

```
LEVEL 1: Visi
  └─ LEVEL 2: Misi
      └─ LEVEL 3: RJP (Rencana Jangka Panjang)
          └─ LEVEL 4: Renstra (Rencana Strategis)
              └─ LEVEL 5: Renop (Rencana Operasional)
                  └─ LEVEL 6: Standar SPMI
                      └─ LEVEL 7: Indikator
```

### Jenis Dokumen

| Jenis | Code | Parent | Can Generate Indikator |
|-------|------|--------|----------------------|
| Visi | `visi` | None | ❌ |
| Misi | `misi` | Visi | ❌ |
| RJP | `rjp` | Misi | ❌ |
| Renstra | `renstra` | RJP | ❌ |
| Renop | `renop` | Renstra | ✅ YES |
| Standar | `standar` | Renstra | ✅ YES |
| Kebijakan | `kebijakan` | Visi/Misi | ❌ |
| Manual | `manual_prosedur` | Standar | ❌ |
| Formulir | `formulir` | Manual | ❌ |

### Business Rules

**Renop:**
- Period: 1 tahun (annual)
- Can have sub-poin (recursive)
- **Can generate Indikator** (flag: `is_hasilkan_indikator`)
- Tab: "Indikator RENOP"

**Standar:**
- Period: 1-5 tahun
- Can have sub-poin
- **Can generate Indikator** (flag: `is_hasilkan_indikator`)
- Tab: "Indikator STANDAR"

---

## 🎯 3. Indikator Types

### 3 Types of Indikator

| Type | Source | Purpose | Table |
|------|--------|---------|-------|
| `renop` | Poin Renop | Measure Renop achievement | `pemutu_indikator` |
| `standar` | Poin Standar | Measure Standard compliance | `pemutu_indikator` |
| `performa` | Assignment | Individual KPI | `pemutu_indikator_pegawai` |

### Indikator Lifecycle

```
1. CREATION (Penetapan)
   └─ From Poin Renop/Standar
   └─ Set target, unit, scale (0-4)

2. ASSIGNMENT (Pelaksanaan)
   └─ Assign to OrgUnit
   └─ Assign to Pegawai (KPI)

3. EVALUATION (Evaluasi)
   ├─ Input ED Capaian
   ├─ AMI Assessment
   └─ Scoring (0-4)

4. CONTROL (Pengendalian)
   ├─ Important/Urgent Matrix
   ├─ RTM Pengendalian
   └─ Status determination

5. IMPROVEMENT (Peningkatan)
   ├─ Action plan
   └─ Duplicate to next period
```

### Scale System

```php
'skala' => [
    0 => 'Kurang',
    1 => 'Cukup',
    2 => 'Baik',
    3 => 'Sangat Baik',
    4 => 'Excellent'
]

// Score calculation
$score = ($edSkala / $maxIdx) * 100;
// Color coding:
// >= 80% → Green (success)
// >= 60% → Blue (primary)
// >= 40% → Yellow (warning)
// < 40% → Red (danger)
```

---

## 📗 4. Penetapan (Planning)

### Activities

1. **Dokumen Visi-Misi**
   - Penetapan Visi
   - Penetapan Misi
   - Approval workflow

2. **RJP (Rencana Jangka Panjang)**
   - 10-20 year planning
   - Mapped to Misi
   - Period-based (5-year cycles)

3. **Renstra (Rencana Strategis)**
   - 5-year planning
   - Mapped to RJP
   - Program-based structure

4. **Renop (Rencana Operasional)**
   - Annual planning
   - Mapped to Renstra
   - **Generate Indikator**

5. **Standar SPMI**
   - Standard setting
   - Mapped to Renstra
   - **Generate Indikator**

### Controllers

- `RenopController` - Manage Renop documents
- `StandarController` - Manage Standar documents
- `DokumenController` - General document management
- `IndikatorController` - Indicator management
- `DokumenApprovalController` - Approval workflow

### Services

- `DokumenService` - Document CRUD
- `DokumenApprovalService` - Approval handling
- `IndikatorService` - Indicator CRUD + logic
- `PeriodeSpmiService` - Period management

---

## 📗 5. Pelaksanaan (Implementation)

### Activities

1. **Evaluasi Diri (ED)**
   - Unit kerja input capaian
   - Upload bukti dokumen
   - Analisis capaian

2. **KPI Assignment**
   - Assign indikator ke pegawai
   - Set target individual
   - Monitoring realisasi

### Controllers

- `EvaluasiDiriController` - ED management
- `PegawaiController` - KPI assignment

### Services

- `EvaluasiKpiService` - ED + KPI logic
- `PegawaiService` - Employee management

### View Logic

**File:** `resources/views/pages/pemutu/evaluasi-diri/edit-ajax.blade.php`

```blade
{{-- Form input capaian ED --}}
<form class="ajax-form" action="{{ route('pemutu.evaluasi-diri.update', $ou->pivot->indikorgunit_id) }}">
    
    {{-- Target --}}
    <x-tabler.form-input name="target" label="Target" :value="$ou->pivot->target" readonly />
    
    {{-- Capaian ED --}}
    <x-tabler.form-textarea name="ed_capaian" label="Capaian Evaluasi Diri" 
                            required />
    
    {{-- Analisis --}}
    <x-tabler.form-textarea name="ed_analisis" label="Analisis Capaian" 
                            required />
    
    {{-- Bukti Dokumen --}}
    <x-tabler.form-input name="ed_links" label="Link Bukti Dokumen" 
                         placeholder="https://..." />
    
    {{-- Skala Penilaian (0-4) --}}
    <x-tabler.form-select name="ed_skala" label="Skala Penilaian" required>
        @foreach($indikator->skala as $idx => $label)
            <option value="{{ $idx }}">{{ $label }}</option>
        @endforeach
    </x-tabler.form-select>
    
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
```

---

## 📗 6. Evaluasi (Evaluation)

### Activities

1. **AMI (Audit Mutu Internal)**
   - Tim mutu audit
   - Input temuan
   - Validasi hasil ED
   - Set status (Lulus/Tidak)

2. **Summary Indikator**
   - Aggregasi capaian
   - Score calculation
   - Visualization

### Controllers

- `AmiController` - AMI management
- `IndikatorSummaryController` - Summary reports

### Services

- `AmiService` - AMI logic
- `IndikatorSummaryPerformaService` - Performance summary

### AMI Workflow

```
1. SELECT INDIKATOR
   └─ Filter by Periode
   └─ Filter by Unit

2. AUDIT
   ├─ Review ED capaian
   ├─ Verify bukti dokumen
   └─ Input temuan

3. ASSESSMENT
   ├─ Set hasil akhir (Lulus/Tidak)
   ├─ Input temuan detail
   └─ Rekomendasi

4. VALIDATION
   └─ Ketua Tim Mutu validate
   └─ Publish hasil
```

---

## 📗 7. Pengendalian (Control)

### Activities

1. **RTM Pengendalian (Rapat Tinjauan Manajemen)**
   - Review hasil AMI
   - Analisis Penting & Mendesak
   - Set status tindak lanjut

2. **Matrix Analysis**
   - Penting (Important): 1-4
   - Mendesak (Urgent): 1-4
   - Priority matrix

### Controllers

- `PengendalianController` - Control management

### Services

- `PengendalianService` - Control logic

### Important-Urgent Matrix

```
┌─────────────────────────────────────────────────────────────┐
│              IMPORTANT-URGENT MATRIX                        │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Mendesak (Urgent)                                          │
│       ↑                                                     │
│   4 │  Q2        │  Q1        │                             │
│     │  Penting   │  Penting   │                             │
│   3 │  TIDAK     │  YA        │                             │
│     │  (Do later)│  (Do now)  │                             │
│   2 │────────────┼────────────│                             │
│     │  Q3        │  Q4        │                             │
│   1 │  TIDAK     │  TIDAK     │                             │
│     │  (Delegate)│  (Eliminate)│                            │
│     └────────────┴────────────┘                             │
│          1    2    3    4    → Penting (Important)          │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

### View Logic

**File:** `resources/views/pages/pemutu/pengendalian/isi-modal.blade.php`

```blade
{{-- Matrix Input --}}
<div class="row">
    <div class="col-md-6">
        <label>Tingkat Pentingnya (1-4)</label>
        <select name="pengend_important_matrix" class="form-select">
            <option value="1">1 - Tidak Penting</option>
            <option value="2">2 - Cukup Penting</option>
            <option value="3">3 - Penting</option>
            <option value="4">4 - Sangat Penting</option>
        </select>
    </div>
    <div class="col-md-6">
        <label>Tingkat Mendesaknya (1-4)</label>
        <select name="pengend_urgent_matrix" class="form-select">
            <option value="1">1 - Tidak Mendesak</option>
            <option value="2">2 - Cukup Mendesak</option>
            <option value="3">3 - Mendesak</option>
            <option value="4">4 - Sangat Mendesak</option>
        </select>
    </div>
</div>

{{-- Status Pengendalian --}}
<select name="pengend_status" class="form-select">
    <option value="Belum Ditindaklanjuti">Belum Ditindaklanjuti</option>
    <option value="Dalam Tindak Lanjut">Dalam Tindak Lanjut</option>
    <option value="Selesai Ditindaklanjuti">Selesai Ditindaklanjuti</option>
</select>
```

---

## 📗 8. Peningkatan (Improvement)

### Activities

1. **RTM Peningkatan**
   - Monitoring tindak lanjut
   - Evaluasi hasil
   - Update status

2. **Duplikasi Indikator**
   - Copy ke periode berikutnya
   - Adjust target
   - Re-assign units

### Controllers

- `PeningkatanController` - Improvement management

### Services

- `PeningkatanService` - Improvement logic
- `DuplikasiService` - Duplication logic

### Duplication Process

```
1. SELECT SOURCE PERIOD
   └─ Periode: 2026
   └─ Filter: Indikator Lulus AMI

2. SELECT TARGET PERIOD
   └─ Periode: 2027

3. CONFIGURE DUPLICATION
   ├─ Adjust target (increase %)
   ├─ Re-assign units
   └─ Review scale

4. EXECUTE
   ├─ Copy indikator
   ├─ Copy orgunit assignments
   └─ Reset capaian (empty)

5. VALIDATION
   └─ Review hasil duplikasi
   └─ Publish to new period
```

---

## 📙 9. Approval Workflow

### Polymorphic System

**Model:** `RiwayatApproval`

**Table:** `pemutu_riwayat_approval`

**Used by:**
- Dokumen (all types)
- PMB Pendaftaran
- HR Perizinan
- HR Lembur
- Lab Request

### Status Flow

```
┌─────────────────────────────────────────────────────────────┐
│              APPROVAL STATUS FLOW                           │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  [Approver Ditambahkan]                                     │
│         ↓                                                   │
│    Status: 'Pending' ← DEFAULT                              │
│         ↓                                                   │
│    ┌─────────────────┐                                      │
│    │  Approver Inbox │                                      │
│    └────────┬────────┘                                      │
│             ↓                                               │
│     ┌───────┴───────┐                                       │
│     ↓               ↓                                       │
│ [Approve]      [Reject]                                     │
│     ↓               ↓                                       │
│ Status:         Status:                                     │
│ 'Approved'      'Rejected'                                  │
│     ↓               ↓                                       │
│     └───────┬───────┘                                       │
│             ↓                                               │
│     [Validation Check]                                       │
│             ↓                                               │
│     ┌───────┴───────┐                                       │
│     ↓               ↓                                       │
│ SEMUA =        ADA YANG                                     │
│ 'Approved'      'Rejected'                                  │
│     ↓               ↓                                       │
│ Dokumen         Dokumen                                      │
│ SAH ✅          TIDAK SAH ❌                                 │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

### Implementation

**Service:** `DokumenApprovalService::syncApprovers()`

```php
public function syncApprovers(Dokumen $dokumen, array $approvers): array
{
    DB::beginTransaction();
    try {
        // Remove old pending approvals
        $dokumen->riwayatApprovals()
            ->where('status', 'Pending')
            ->delete();

        // Add new approvers with 'Pending' status
        foreach ($approvers as $appData) {
            $pegawai = Pegawai::findOrFail($appData['pegawai_id']);
            
            $dokumen->riwayatApprovals()->create([
                'pegawai_id' => $appData['pegawai_id'],
                'pejabat' => $pegawai->nama,
                'jabatan' => $appData['jabatan'] ?? '-',
                'catatan' => null,
                'status' => 'Pending', // ✅ EXPLICIT SET
            ]);
        }

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}
```

---

## 📙 10. Periode Management

### Periode SPMI

**Model:** `PeriodeSpmi`

**Types:**
- **Akademik:** Follows academic year (Ganjil/Genap)
- **Non Akademik:** Calendar year (Jan-Dec)

**Date Ranges:**
- `penetapan_awal` - `penetapan_akhir`
- `ed_awal` - `ed_akhir` (Evaluasi Diri)
- `ami_awal` - `ami_akhir` (AMI)
- `pengendalian_awal` - `pengendalian_akhir`
- `peningkatan_awal` - `peningkatan_akhir`

### Periode KPI

**Model:** `PeriodeKpi`

**Purpose:** Annual KPI assignment period

**Fields:**
- `nama` - Period name
- `tahun` - Year
- `tanggal_mulai` - Start date
- `tanggal_selesai` - End date
- `is_active` - Active flag

### Session-based Period Selection

**Service:** `PeriodeSpmiService::getSiklusData()`

```php
public function getSiklusData(): array
{
    $years = $this->getAvailableYears();
    $tahun = (int) (session('siklus_spmi_tahun') ?? $years->first());
    
    $periodes = PeriodeSpmi::where('periode', $tahun)->get();
    
    return [
        'tahun' => $tahun,
        'years' => $years,
        'akademik' => $periodes->firstWhere('jenis_periode', 'Akademik'),
        'non_akademik' => $periodes->firstWhere('jenis_periode', 'Non Akademik'),
    ];
}
```

---

## 📙 11. Data Structures

### Database Tables

```sql
-- Main Documents
pemutu_dokumen          -- SPMI documents
pemutu_dok_sub          -- Document points/sub-documents

-- Indicators
pemutu_indikator        -- Indicators (Renop/Standar)
pemutu_indikator_orgunit -- Unit assignments
pemutu_indikator_pegawai -- KPI assignments
pemutu_indikator_label  -- Label categories
pemutu_indikator_doksub -- Morph mapping to documents

-- Summaries
pemutu_indikator_summary        -- Base summary table
pemutu_indikator_summary_standar -- Standard summary
pemutu_indikator_summary_performa -- Performance summary

-- Periods
pemutu_periode_spmi     -- SPMI periods
pemutu_periode_kpi      -- KPI periods

-- People
pemutu_tim_mutu         -- Quality audit team
pemutu_pegawai          -- Employee data (mirror from HR)

-- Workflow
pemutu_riwayat_approval -- Approval history (polymorphic)
pemutu_dokumen_mapping  -- Document cross-mapping
```

### Key Relationships

```php
// Dokumen ↔ DokSub (1:N)
Dokumen::dokSubs() → HasMany<DokSub>
DokSub::dokumen() → BelongsTo<Dokumen>

// DokSub ↔ DokSub (Recursive)
DokSub::parent() → BelongsTo<DokSub>
DokSub::children() → HasMany<DokSub>

// Indikator ↔ OrgUnit (N:M)
Indikator::orgUnits() → BelongsToMany<StrukturOrganisasi>
  Pivot: pemutu_indikator_orgunit
  Fields: target, ed_capaian, ed_skala, ami_hasil_akhir, ...

// Indikator ↔ Pegawai (1:N)
Indikator::pegawai() → HasMany<IndikatorPegawai>
IndikatorPegawai::indikator() → BelongsTo<Indikator>

// All Documents → Approval (Polymorphic)
Dokumen::riwayatApprovals() → MorphMany<RiwayatApproval>
```

---

## 📞 Support

- **Module:** Pemutu (Penjaminan Mutu)
- **Framework:** Laravel 12.46.0
- **PHP Version:** 8.4+
- **Database:** MySQL/MariaDB

---

**© 2026 Sistem Penjaminan Mutu Internal - All Rights Reserved**
