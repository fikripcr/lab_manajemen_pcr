# 01. Dokumen Hierarchy - Hierarki Dokumen SPMI

**Last Updated:** March 2026

---

## 📊 Hierarki Dokumen

```
┌─────────────────────────────────────────────────────────────┐
│                    DOKUMEN HIERARCHY                        │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  LEVEL 1: VISI                                              │
│    └─ LEVEL 2: MISI                                         │
│       └─ LEVEL 3: RJP (Rencana Jangka Panjang)             │
│          └─ LEVEL 4: RENSTRA (Rencana Strategis)           │
│             └─ LEVEL 5: RENOP (Rencana Operasional)        │
│                └─ LEVEL 6: STANDAR SPMI                    │
│                   └─ LEVEL 7: INDIKATOR                   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 🏗️ Struktur Dokumen

### Level 1: Visi

**Model:** `Dokumen` dengan `jenis = 'visi'`

**Karakteristik:**
- Dokumen induk tertinggi
- Tidak memiliki parent
- Berlaku jangka panjang (10-20 tahun)

**Contoh:**
```
"Menjadi Universitas Berkelas Dunia pada Tahun 2040"
```

---

### Level 2: Misi

**Model:** `Dokumen` dengan `jenis = 'misi'`

**Parent:** Visi

**Karakteristik:**
- Turunan langsung dari Visi
- Biasanya 5-10 poin misi
- Menjadi dasar penyusunan RJP

**Contoh:**
```
Misi 1: "Menyelenggarakan pendidikan tinggi berkualitas..."
Misi 2: "Melaksanakan penelitian inovatif..."
Misi 3: "Melakukan pengabdian kepada masyarakat..."
```

---

### Level 3: RJP (Rencana Jangka Panjang)

**Model:** `Dokumen` dengan `jenis = 'rjp'`

**Parent:** Misi

**Karakteristik:**
- Periode 10-20 tahun
- Terstruktur per periode (5 tahunan)
- Mapping ke Misi yang relevan

**Structure:**
```
RJP 2020-2040
├── Periode 1 (2020-2025)
│   ├── Poin 1.1: Pengembangan Infrastruktur
│   ├── Poin 1.2: Peningkatan Kualitas SDM
│   └── ...
├── Periode 2 (2025-2030)
└── ...
```

---

### Level 4: Renstra (Rencana Strategis)

**Model:** `Dokumen` dengan `jenis = 'renstra'`

**Parent:** RJP (mapping via `parent_doksub_id`)

**Karakteristik:**
- Periode 5 tahun
- Lebih detail dari RJP
- Menjadi acuan Renop

**Structure:**
```
Renstra 2020-2025
├── Bidang Akademik
│   ├── Program 1: Kurikulum
│   ├── Program 2: Pembelajaran
│   └── ...
├── Bidang Penelitian
└── Bidang Pengabdian
```

---

### Level 5: Renop (Rencana Operasional)

**Model:** `Dokumen` dengan `jenis = 'renop'`

**Parent:** Renstra

**Karakteristik:**
- Periode 1 tahun (tahunan)
- Paling detail, dapat dieksekusi
- **Menghasilkan Indikator**

**Business Logic:**
```php
// Di view workspace.blade.php
$isRenopPoint = $jenis === 'renop';
$showIndikatorSection = $isRenopPoint && ($item->is_hasilkan_indikator ?? false);

// Jika true → muncul tombol "Tambah Indikator"
```

**Structure:**
```
Renop 2026
├── Kegiatan 1: Penyusunan Kurikulum
│   ├── Poin 1.1: Review MK
│   ├── Poin 1.2: Validasi Kurikulum
│   └── ...
├── Kegiatan 2: Pembelajaran
└── ...
```

---

### Level 6: Standar SPMI

**Model:** `Dokumen` dengan `jenis = 'standar'`

**Parent:** Renstra (mapping)

**Karakteristik:**
- Standar yang ditetapkan
- Dapat menghasilkan Indikator
- Digunakan sebagai baseline penilaian

**Structure:**
```
Standar Pendidikan
├── Standar Isi
│   ├── Poin 1.1: Kompetensi Lulusan
│   ├── Poin 1.2: Bahan Kajian
│   └── ...
├── Standar Proses
└── Standar Penilaian
```

---

### Level 7: Indikator

**Model:** `Indikator`

**Source:**
- `type = 'renop'` → Dari Poin Renop
- `type = 'standar'` → Dari Poin Standar

**Karakteristik:**
- Terukur (memiliki target)
- Ditugaskan ke Unit Kerja
- Memiliki skala penilaian (0-4)

**Structure:**
```
Indikator Renop:
├── No: 1.1.1
├── Nama: "Jumlah MK yang direview"
├── Target: 50 MK
├── Unit: Fakultas Teknik
└── Skala: [0, 1, 2, 3, 4]

Indikator Standar:
├── No: S.1.1
├── Nama: "Ketersediaan RPS"
├── Target: 100%
├── Unit: Prodi Teknik Informatika
└── Skala: [Kurang, Cukup, Baik, Sangat Baik, Excellent]
```

---

## 🔄 Mapping Relationships

### Dokumen → DokSub (1:N)

```php
// Di Model Dokumen
public function dokSubs()
{
    return $this->hasMany(DokSub::class, 'dok_id', 'dok_id')
        ->orderBy('seq');
}

// Di Model DokSub
public function dokumen()
{
    return $this->belongsTo(Dokumen::class, 'dok_id', 'dok_id');
}
```

### DokSub → DokSub (Recursive Parent-Child)

```php
// Di Model DokSub
public function parent()
{
    return $this->belongsTo(DokSub::class, 'parent_doksub_id', 'doksub_id');
}

public function children()
{
    return $this->hasMany(DokSub::class, 'parent_doksub_id', 'doksub_id')
        ->orderBy('seq');
}
```

### DokSub → Indikator (1:N via Morph)

```php
// Di Model DokSub
public function indicators()
{
    return $this->morphToMany(Indikator::class, 'source', 
        'pemutu_indikator_doksub', 'doksub_id', 'source_id')
        ->withPivot('is_hasilkan_indikator')
        ->withTimestamps();
}

// Di Model Indikator
public function dokSubs()
{
    return $this->morphToMany(DokSub::class, 'source', 
        'pemutu_indikator_doksub', 'source_id', 'doksub_id')
        ->withPivot('is_hasilkan_indikator')
        ->withTimestamps();
}
```

---

## 📋 Workflow Penetapan Dokumen

```
┌─────────────────────────────────────────────────────────────┐
│              DOKUMEN CREATION WORKFLOW                      │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  1. CREATE DOKUMEN INDUK                                    │
│     └─ Input: Judul, Jenis, Periode, Isi                   │
│     └─ Status: Draft                                       │
│                                                             │
│  2. ADD APPROVAL                                            │
│     └─ Pilih Approver (Pegawai + Jabatan)                  │
│     └─ Status: Pending Approval                            │
│                                                             │
│  3. APPROVAL PROCESS                                        │
│     ├─ Approver Review → Approved                          │
│     └─ Approver Reject → Rejected                          │
│                                                             │
│  4. ADD POIN (DOKSUB)                                       │
│     └─ Input: Judul Poin, Kode, Urutan                     │
│     └─ Mapping ke Dokumen Induk                            │
│                                                             │
│  5. ADD SUB-POIN (Optional)                                 │
│     └─ Recursive: Poin → Sub-Poin → Sub-Sub-Poin          │
│                                                             │
│  6. SET INDIKATOR (Jika Renop/Standar)                     │
│     └─ Check: "Hasilkan Indikator"                         │
│     └─ Add Indikator dari Poin                             │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 🎯 Business Rules per Jenis Dokumen

### Kebijakan (`kebijakan`)

| Rule | Value |
|------|-------|
| Parent | Visi/Misi |
| Can have Poin | ✅ Yes |
| Can map to Indikator | ❌ No |
| Approval required | ✅ Yes |
| Show in tree | ✅ Yes |

### Misi (`misi`)

| Rule | Value |
|------|-------|
| Parent | Visi |
| Can have Poin | ✅ Yes |
| Can map to RJP | ✅ Yes |
| Approval required | ✅ Yes |

### RJP (`rjp`)

| Rule | Value |
|------|-------|
| Parent | Misi |
| Can have Poin | ✅ Yes |
| Can map to Renstra | ✅ Yes |
| Periode-based | ✅ Yes (10-20 tahun) |

### Renstra (`renstra`)

| Rule | Value |
|------|-------|
| Parent | RJP |
| Can have Poin | ✅ Yes |
| Can map to Renop | ✅ Yes |
| Can map to Standar | ✅ Yes |
| Periode-based | ✅ Yes (5 tahun) |

### Renop (`renop`)

| Rule | Value |
|------|-------|
| Parent | Renstra |
| Can have Poin | ✅ Yes |
| **Can generate Indikator** | ✅ **YES** |
| Periode-based | ✅ Yes (1 tahun) |
| Show "Indikator RENOP" tab | ✅ Yes |

### Standar (`standar`)

| Rule | Value |
|------|-------|
| Parent | Renstra (mapping) |
| Can have Poin | ✅ Yes |
| **Can generate Indikator** | ✅ **YES** |
| Show "Indikator STANDAR" tab | ✅ Yes |

---

## 🔍 View Logic: Workspace Tabs

**File:** `resources/views/pages/pemutu/dokumen/_workspace.blade.php`

### Tab Logic untuk DOKUMEN

```php
@php
$jenis = strtolower(trim($item->dokumen->jenis ?? ''));
$isKebijakan = !in_array($jenis, ['standar', 'renop']);
$isRenopPoint = $jenis === 'renop';
$showIndikatorSection = $isRenopPoint && ($item->is_hasilkan_indikator ?? false);
@endphp

@if($jenis === 'renop')
    {{-- Tab Indikator RENOP --}}
    <a href="#tab-indikator-renop" class="nav-link">
        <i class="ti ti-target"></i> Indikator RENOP
    </a>
@elseif($jenis === 'formulir')
    {{-- Tab Mapping --}}
    <a href="#tab-mapping" class="nav-link">
        <i class="ti ti-link"></i> Mapping
    </a>
@elseif($jenis !== 'manual_prosedur')
    {{-- Tab Poin --}}
    <a href="#tab-subdokumen" class="nav-link">
        <i class="ti ti-file-description"></i> Poin
    </a>
@endif
```

### Tab Logic untuk POIN

```php
@php
$poinJenis = $item->jenis; // poin_renop, poin_standar, dll
@endphp

@if(in_array($poinJenis, ['poin_renop', 'poin_standar']) && $item->is_hasilkan_indikator)
    {{-- Tab Daftar Indikator --}}
    <a href="#tab-subdokumen" class="nav-link">
        <i class="ti ti-target"></i> Daftar Indikator
    </a>
@endif

@if(in_array($poinJenis, ['poin_misi', 'poin_rjp', 'poin_renstra', 'poin_renop']))
    {{-- Tab Mapping --}}
    <a href="#tab-mapping" class="nav-link">
        <i class="ti ti-link"></i> Mapping
    </a>
@endif
```

---

## 📊 Summary Chain Visualization

**File:** `resources/views/pages/pemutu/summary/_summary_chain.blade.php`

```
Visi (Level 1)
└─ Misi (Level 2)
   └─ RJP (Level 3)
      └─ Renstra (Level 4)
         └─ Renop (Level 5)
            └─ Poin Renop
               └─ Indikator Renop ← Capaian ED, AMI, Pengendalian
```

**Recursive Rendering:**
```blade
@foreach($chain as $node)
    @include('pemutu.summary._summary_chain', [
        'chain' => $node['chain'], 
        'depth' => $depth + 1
    ])
@endforeach
```

---

**Next:** [02-indikator-types.md](./02-indikator-types.md) - Jenis-jenis Indikator
