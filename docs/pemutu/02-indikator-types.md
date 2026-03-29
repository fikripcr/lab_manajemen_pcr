# 02. Indikator Types - Jenis-jenis Indikator

**Last Updated:** March 2026

---

## 📊 Overview Indikator

Indikator adalah alat ukur untuk menilai capaian dari dokumen SPMI (Renop atau Standar).

### Model: `Indikator`

**Table:** `pemutu_indikator`

**Primary Key:** `indikator_id`

---

## 🎯 3 Jenis Indikator

### 1. Indikator RENOP (`type = 'renop'`)

**Source:** Poin Renop

**Purpose:** Mengukur capaian Rencana Operasional

**Characteristics:**
- Annual target (tahunan)
- Ditugaskan ke Unit Kerja
- Dievaluasi melalui ED (Evaluasi Diri)

**Example:**
```
No Indikator: 1.1.1
Nama: "Jumlah Mata Kuliah yang Direview"
Target: 50 MK
Unit: Fakultas Teknik
```

**Creation Flow:**
```
Renop 2026
└─ Poin 1.1: Review Kurikulum
   └─ [✓ HasILKAN INDIKATOR]
      └─ Indikator 1.1.1: Jumlah MK yang direview
```

---

### 2. Indikator STANDAR (`type = 'standar'`)

**Source:** Poin Standar SPMI

**Purpose:** Mengukur capaian Standar yang ditetapkan

**Characteristics:**
- Baseline assessment
- Skala penilaian 0-4
- AMI validation

**Example:**
```
No Indikator: S.1.1
Nama: "Ketersediaan RPS"
Target: 100%
Unit: Prodi Teknik Informatika
Skala: [0, 1, 2, 3, 4]
```

**Creation Flow:**
```
Standar Pendidikan 2026
└─ Poin 1.1: Standar Isi
   └─ [✓ HasILKAN INDIKATOR]
      └─ Indikator S.1.1: Ketersediaan RPS
```

---

### 3. Indikator PERFORMA (`type = 'performa'`)

**Source:** Assignment dari Indikator Renop/Standar

**Purpose:** KPI Individual Pegawai

**Characteristics:**
- Personal target
- Linked to employee (Pegawai)
- Annual performance review

**Model:** `IndikatorPegawai`

**Example:**
```
Pegawai: John Doe
Indikator: Jumlah MK yang direview
Target: 10 MK
Realisasi: 8 MK
Status: 80% Achieved
```

---

## 🔄 Indikator Lifecycle

```
┌─────────────────────────────────────────────────────────────┐
│              INDIKATOR LIFECYCLE                            │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  1. CREATION (Penetapan)                                    │
│     └─ From Poin Renop OR Poin Standar                     │
│     └─ Set target, unit, scale                             │
│                                                             │
│  2. ASSIGNMENT (Pelaksanaan)                                │
│     └─ Assign to Unit Kerja (OrgUnit)                      │
│     └─ Assign to Pegawai (KPI)                             │
│                                                             │
│  3. EVALUATION (Evaluasi)                                   │
│     ├─ Input Capaian ED (Evaluasi Diri)                   │
│     ├─ AMI Assessment (Audit)                              │
│     └─ Scoring (0-4 scale)                                 │
│                                                             │
│  4. CONTROL (Pengendalian)                                  │
│     ├─ Analysis (Penting & Mendesak)                       │
│     ├─ RTM Pengendalian                                    │
│     └─ Status determination                                │
│                                                             │
│  5. IMPROVEMENT (Peningkatan)                               │
│     ├─ Tindak lanjut                                       │
│     └─ Duplikasi ke periode berikut                        │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 📊 IndikatorOrgUnit Pivot

**Table:** `pemutu_indikator_orgunit`

**Purpose:** Link Indikator ke Unit Kerja dengan target dan capaian

### Columns

| Column | Type | Description |
|--------|------|-------------|
| `indikorgunit_id` | bigint | Primary key |
| `indikator_id` | bigint | FK to indikator |
| `org_unit_id` | bigint | FK to unit kerja |
| `target` | text | Target spesifik untuk unit ini |
| `ed_capaian` | text | Capaian Evaluasi Diri |
| `ed_analisis` | text | Analisis ED |
| `ed_links` | text | Link bukti dokumen |
| `ed_skala` | int | Skor ED (0-4) |
| `ami_hasil_akhir` | enum | Hasil AMI (Lulus/Tidak) |
| `ami_hasil_temuan` | text | Temuan AMI |
| `ami_hasil_temuan_sebab` | text | Sebab temuan |
| `ami_hasil_temuan_rekom` | text | Rekomendasi AMI |
| `ami_rtp_isi` | text | RTP (Rencana Tindak lanjut) |
| `ami_te_isi` | text | Tindak Evaluasi |
| `pengend_status` | enum | Status Pengendalian |
| `pengend_analisis` | text | Analisis Pengendalian |
| `pengend_important_matrix` | int | Matriks Penting (1-4) |
| `pengend_urgent_matrix` | int | Matriks Mendesak (1-4) |

---

## 🎯 Skala Penilaian

### Default Scale (0-4)

```php
// Di Model Indikator
protected $casts = [
    'skala' => 'array',
];

// Example data:
'skala' => [
    0 => 'Kurang',
    1 => 'Cukup',
    2 => 'Baik',
    3 => 'Sangat Baik',
    4 => 'Excellent'
]
```

### Score Calculation

```php
// Di view summary_chain.blade.php
$edSkala = $ou->pivot->ed_skala;
$skalaArr = $ind->skala ?? [];
$skalaLabel = (is_array($skalaArr) && isset($skalaArr[$edSkala])) 
    ? $skalaArr[$edSkala] 
    : '-';

// Calculate percentage score
$maxIdx = count($skalaArr) - 1;
$score = ($edSkala !== null && $maxIdx > 0) 
    ? round(($edSkala / $maxIdx) * 100) 
    : null;

// Determine color
$scoreColor = 'secondary';
if ($score !== null) {
    if ($score >= 80) $scoreColor = 'success';
    elseif ($score >= 60) $scoreColor = 'primary';
    elseif ($score >= 40) $scoreColor = 'warning';
    else $scoreColor = 'danger';
}
```

---

## 📋 Business Rules

### Creation Rules

1. **Source Requirement:**
   - Indikator Renop HARUS dari Poin Renop
   - Indikator Standar HARUS dari Poin Standar

2. **Flag Requirement:**
   - Poin harus di-set `is_hasilkan_indikator = true`
   - Only then "Tambah Indikator" button appears

3. **Unit Assignment:**
   - Minimal 1 Unit Kerja harus di-assign
   - Target harus spesifik per unit

### Assessment Rules

1. **ED First:**
   - Unit mengisi capaian ED dulu
   - Upload bukti dokumen

2. **AMI Validation:**
   - Tim Mutu validasi hasil ED
   - Input temuan (jika ada)
   - Set status Lulus/Tidak

3. **Scoring:**
   - Gunakan skala 0-4
   - Konsisten per jenis indikator

---

## 🔍 View Logic: Indikator Display

**File:** `resources/views/pages/pemutu/summary/_summary_chain.blade.php`

```blade
@if($hasIndicators)
<div class="ms-4 mt-2">
    @foreach($childIndicators as $ind)
    @php
        $parentInd = $ind->parent;
    @endphp
    
    <div class="border rounded p-2 mb-2 bg-white">
        {{-- Indicator Header --}}
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-teal-lt text-teal">
                <i class="ti ti-target"></i>{{ $ind->no_indikator }}
            </span>
            <span class="small fw-medium">
                {{ $ind->indikator }}
            </span>
        </div>

        {{-- Parent Standard Reference --}}
        @if($parentInd)
        <div class="small text-muted mb-2">
            <i class="ti ti-arrow-right"></i> Standar Induk:
            <span class="badge bg-secondary-lt">
                {{ $parentInd->no_indikator }}
            </span>
            {{ Str::limit($parentInd->indikator, 60) }}
        </div>
        @endif

        {{-- OrgUnit Results Table --}}
        @if($ind->orgUnits->isNotEmpty())
        <table class="table table-sm table-vcenter small">
            <thead>
                <tr>
                    <th>Unit</th>
                    <th>Target</th>
                    <th>Capaian ED</th>
                    <th>Skala</th>
                    <th class="text-end">Skor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ind->orgUnits as $ou)
                @php
                    $edSkala = $ou->pivot->ed_skala;
                    $skalaLabel = $skalaArr[$edSkala] ?? '-';
                    $score = round(($edSkala / $maxIdx) * 100);
                @endphp
                <tr>
                    <td>{{ $ou->name }}</td>
                    <td>{{ $ou->pivot->target ?? '-' }}</td>
                    <td>{{ $ou->pivot->ed_capaian ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $scoreColor }}-lt">
                            {{ $skalaLabel }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-{{ $scoreColor }}" 
                                 style="width: {{ $score }}%"></div>
                        </div>
                        {{ $score }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endforeach
</div>
@endif
```

---

## 📊 Indikator Summary Models

### IndikatorSummary (Base)

**Purpose:** Aggregasi capaian indikator

**Subclasses:**
- `IndikatorSummaryStandar` - Summary untuk Indikator Standar
- `IndikatorSummaryPerforma` - Summary untuk Indikator Performa

---

## 🎯 KPI Assignment

**Model:** `IndikatorPegawai`

**Table:** `pemutu_indikator_pegawai`

**Purpose:** Assignment indikator ke pegawai sebagai KPI tahunan

### Workflow

```
1. SET PERIODE KPI
   └─ Tahun: 2026
   └─ Start: 2026-01-01
   └─ End: 2026-12-31

2. SELECT INDIKATOR
   └─ From Indikator Renop
   └─ Filter by Unit Kerja

3. ASSIGN TO PEGAWAI
   └─ Select Pegawai
   └─ Set Target Individual
   └─ Save

4. MONITORING
   └─ Pegawai input realisasi
   └─ Atasan validasi
   └─ Score calculation
```

---

**Next:** [10-penetapan-overview.md](./10-penetapan-overview.md) - Penetapan (Planning)
