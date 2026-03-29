# 00. General Overview - Gambaran Umum Sistem SPMI

**Last Updated:** March 2026  
**Module:** Pemutu (Penjaminan Mutu)  

---

## 📖 Apa itu SPMI?

**Sistem Penjaminan Mutu Internal (SPMI)** adalah sistem manajemen mutu yang mengadopsi siklus **PPEPP** (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan).

### Tujuan SPMI

1. **Menjamin** penyelenggaraan pendidikan sesuai dengan standar yang ditetapkan
2. **Meningkatkan** mutu penyelenggaraan pendidikan secara berkelanjutan
3. **Menyediakan** data dan informasi untuk pengambilan keputusan
4. **Memenuhi** persyaratan akreditasi dan regulasi

---

## 🔄 Siklus PPEPP

```
┌─────────────────────────────────────────────────────────────┐
│                    SIKLUS SPMI (PPEPP)                      │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│         ┌─────────────────────────────────────┐            │
│         │         1. PENETAPAN                │            │
│         │  - Visi, Misi, Tujuan, Sasaran     │            │
│         │  - RJP, Renstra, Renop            │            │
│         │  - Standar SPMI                    │            │
│         └──────────────┬──────────────────────┘            │
│                        ↓                                    │
│         ┌─────────────────────────────────────┐            │
│         │         2. PELAKSANAAN              │            │
│         │  - Pelaksanaan kegiatan            │            │
│         │  - Evaluasi Diri (ED)              │            │
│         │  - Pengumpulan data capaian        │            │
│         └──────────────┬──────────────────────┘            │
│                        ↓                                    │
│         ┌─────────────────────────────────────┐            │
│         │         3. EVALUASI                 │            │
│         │  - Audit Mutu Internal (AMI)       │            │
│         │  - Analisis hasil ED               │            │
│         │  - Identifikasi temuan             │            │
│         └──────────────┬──────────────────────┘            │
│                        ↓                                    │
│         ┌─────────────────────────────────────┐            │
│         │         4. PENGENDALIAN             │            │
│         │  - Rapat Tinjauan Manajemen (RTM)  │            │
│         │  - Matriks Penting & Mendesak      │            │
│         │  - Penetapan tindak lanjut         │            │
│         └──────────────┬──────────────────────┘            │
│                        ↓                                    │
│         ┌─────────────────────────────────────┐            │
│         │         5. PENINGKATAN              │            │
│         │  - Pelaksanaan tindak lanjut       │            │
│         │  - Monitoring hasil                │            │
│         │  - Duplikasi ke periode berikut    │            │
│         └─────────────────────────────────────┘            │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 🏗️ Arsitektur Sistem

### Module Structure

```
app/
├── Models/Pemutu/
│   ├── Dokumen.php           # Dokumen SPMI (Visi, Misi, Renstra, dll)
│   ├── DokSub.php            # Sub-dokumen (Poin-poin)
│   ├── Indikator.php         # Indikator (Renop & Standar)
│   ├── IndikatorOrgUnit.php  # Pivot Indikator ↔ Unit Kerja
│   ├── IndikatorPegawai.php  # Assignment Indikator ke Pegawai
│   ├── PeriodeSpmi.php       # Periode SPMI (Akademik/Non Akademik)
│   ├── PeriodeKpi.php        # Periode KPI (Tahunan)
│   ├── Label.php             # Label/Kategori indikator
│   ├── TimMutu.php           # Tim Audit Mutu
│   └── RiwayatApproval.php   # Approval workflow (polymorphic)
│
├── Services/Pemutu/
│   ├── DokumenService.php
│   ├── IndikatorService.php
│   ├── EvaluasiKpiService.php
│   ├── AmiService.php
│   ├── PengendalianService.php
│   ├── PeningkatanService.php
│   └── ...
│
└── Http/Controllers/Pemutu/
    ├── DokumenController.php
    ├── IndikatorController.php
    ├── EvaluasiDiriController.php
    ├── AmiController.php
    ├── PengendalianController.php
    └── PeningkatanController.php
```

---

## 📊 Entity Relationship Diagram (Simplified)

```
┌─────────────────┐
│   PeriodeSpmi   │
│  - Akademik     │
│  - Non Akademik │
└────────┬────────┘
         │
         │ 1:N
         ↓
┌─────────────────┐       1:N       ┌─────────────────┐
│    Dokumen      │────────────────→│    DokSub       │
│  - Visi/Misi    │   (parent_id)   │  - Poin-poin    │
│  - RJP          │                 │  - Sub-poin     │
│  - Renstra      │                 │  - Target       │
│  - Renop        │                 └────────┬────────┘
│  - Standar      │                          │
└─────────────────┘                          │ morphMany
                                             ↓
                                    ┌─────────────────┐
                                    │   Indikator     │
                                    │  - Renop        │
                                    │  - Standar      │
                                    │  - Performa     │
                                    └────────┬────────┘
                                             │
                    ┌────────────────────────┼────────────────────────┐
                    │                        │                        │
               1:N  │                   N:N  │                   1:N  │
                    ↓                        ↓                        ↓
         ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
         │IndikatorOrgUnit │    │IndikatorPegawai │    │IndikatorSummary │
         │  - Target       │    │  - KPI          │    │  - Aggregates   │
         │  - Capaian ED   │    │  - Assignment   │    │  - Scores       │
         │  - Hasil AMI    │    │  - Realisasi    │    │                 │
         │  - Pengendalian │    │                 │    │                 │
         └─────────────────┘    └─────────────────┘    └─────────────────┘
```

---

## 🎯 Key Concepts

### 1. Dokumen Hierarchy

```
DOKUMEN INDUK (Dokumen)
├── DOKUMEN TURUNAN 1 (DokSub)
│   ├── Sub-Dokumen 1.1 (DokSub child)
│   └── Sub-Dokumen 1.2 (DokSub child)
├── DOKUMEN TURUNAN 2 (DokSub)
└── DOKUMEN TURUNAN 3 (DokSub)
```

**Jenis Dokumen:**
- `kebijakan` - Kebijakan umum
- `misi` - Rumusan Misi
- `rjp` - Rencana Jangka Panjang
- `renstra` - Rencana Strategis
- `renop` - Rencana Operasional
- `standar` - Standar SPMI
- `manual` - Manual Prosedur
- `formulir` - Formulir

### 2. Indikator Types

| Type | Source | Purpose |
|------|--------|---------|
| `renop` | Renop Poin | Mengukur capaian Renop |
| `standar` | Standar SPMI | Mengukur capaian Standar |
| `performa` | Assignment | KPI Individual Pegawai |

### 3. Periode Management

**Periode SPMI:**
- **Akademik:** Mengikuti tahun akademik (Ganjil/Genap)
- **Non Akademik:** Tahun kalender (Jan-Des)

**Periode KPI:**
- Tahunan
- Untuk assignment indikator ke pegawai

---

## 📋 Business Rules

### General Rules

1. **Approval Required:** Semua dokumen harus melalui workflow approval sebelum berlaku
2. **Hierarchical Integrity:** Dokumen turunan harus memiliki dokumen induk
3. **Period Lock:** Periode yang sudah ditutup tidak dapat dimodifikasi
4. **Audit Trail:** Semua perubahan dicatat di activity log

### Indicator Rules

1. **Source Validation:** Indikator Renop harus berasal dari Poin Renop
2. **Source Validation:** Indikator Standar harus berasal dari Poin Standar
3. **Unit Assignment:** Setiap indikator harus ditugaskan ke minimal 1 unit kerja
4. **Scale Definition:** Indikator harus memiliki skala penilaian (0-4)

### Assessment Rules

1. **ED First:** Evaluasi Diri harus dilakukan sebelum AMI
2. **AMI Validation:** Hasil AMI harus divalidasi oleh Tim Mutu
3. **Evidence Required:** Setiap capaian harus memiliki bukti dokumen

---

## 🔐 User Roles & Permissions

| Role | Permissions |
|------|-------------|
| **Admin SPMI** | Full access ke semua fitur SPMI |
| **Ketua Tim Mutu** | Manage AMI, validasi hasil, RTM |
| **Auditor** | Execute AMI, input temuan |
| **Auditee** | View assignment, input ED, respond temuan |
| **Pimpinan** | View dashboard, approve dokumen |
| **Staff** | View dokumen publik, input data dasar |

---

## 📱 Navigation Structure

```
SPMI Dashboard
├── Penetapan
│   ├── Dokumen SPMI
│   ├── Renstra
│   ├── Renop
│   └── Standar
├── Pelaksanaan
│   ├── Evaluasi Diri
│   └── KPI Saya
├── Evaluasi
│   ├── Audit Mutu Internal
│   └── Summary Indikator
├── Pengendalian
│   ├── RTM Pengendalian
│   └── Matriks Analisis
└── Peningkatan
    ├── RTM Peningkatan
    └── Duplikasi Indikator
```

---

## 📊 Dashboard Metrics

### Key Performance Indicators

1. **Dokumen Coverage:** % dokumen yang sudah ditetapkan
2. **ED Completion:** % unit yang sudah melakukan ED
3. **AMI Progress:** % audit yang sudah dilaksanakan
4. **Target Achievement:** % indikator yang mencapai target
5. **Improvement Rate:** % tindak lanjut yang diselesaikan

---

**Next:** [01-dokumen-hierarchy.md](./01-dokumen-hierarchy.md) - Hierarki Dokumen SPMI
