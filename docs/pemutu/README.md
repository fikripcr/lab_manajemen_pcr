# Sistem Penjaminan Mutu Internal (SPMI) - Dokumentasi Lengkap

**Last Updated:** March 2026  
**Module:** Pemutu (Penjaminan Mutu)  
**Framework:** Laravel 12.46.0  

---

## 📋 Daftar Isi

### Bagian 1: General & Konsep Dasar
- [00-general-overview.md](./00-general-overview.md) - Gambaran umum sistem
- [01-dokumen-hierarchy.md](./01-dokumen-hierarchy.md) - Hierarki dokumen SPMI
- [02-indikator-types.md](./02-indikator-types.md) - Jenis-jenis indikator

### Bagian 2: Penetapan (Planning)
- [10-penetapan-overview.md](./10-penetapan-overview.md) - Overview Penetapan
- [11-dokumen-visi-misi.md](./11-dokumen-visi-misi.md) - Dokumen Visi, Misi, Tujuan
- [12-rjp-rpjp.md](./12-rjp-rpjp.md) - Rencana Jangka Panjang
- [13-renstra.md](./13-renstra.md) - Rencana Strategis
- [14-renop.md](./14-renop.md) - Rencana Operasional
- [15-standar-spmi.md](./15-standar-spmi.md) - Standar SPMI

### Bagian 3: Pelaksanaan (Implementation)
- [20-pelaksanaan-overview.md](./20-pelaksanaan-overview.md) - Overview Pelaksanaan
- [21-evaluasi-diri.md](./21-evaluasi-diri.md) - Evaluasi Diri (ED)
- [22-kpi-assignment.md](./22-kpi-assignment.md) - Penugasan Indikator ke Pegawai

### Bagian 4: Evaluasi (Evaluation)
- [30-evaluasi-overview.md](./30-evaluasi-overview.md) - Overview Evaluasi
- [31-ami-audit.md](./31-ami-audit.md) - Audit Mutu Internal (AMI)
- [32-indikator-summary.md](./32-indikator-summary.md) - Summary Indikator

### Bagian 5: Pengendalian (Control)
- [40-pengendalian-overview.md](./40-pengendalian-overview.md) - Overview Pengendalian
- [41-rtm-pengendalian.md](./41-rtm-pengendalian.md) - Rapat Tinjauan Manajemen
- [42-matrix-analysis.md](./42-matrix-analysis.md) - Matriks Penting & Mendesak

### Bagian 6: Peningkatan (Improvement)
- [50-peningkatan-overview.md](./50-peningkatan-overview.md) - Overview Peningkatan
- [51-rtm-peningkatan.md](./51-rtm-peningkatan.md) - Tindak Lanjut Peningkatan
- [52-duplikasi-indikator.md](./52-duplikasi-indikator.md) - Duplikasi Indikator ke Periode Berikutnya

### Lampiran
- [90-approval-workflow.md](./90-approval-workflow.md) - Workflow Approval
- [91-periode-management.md](./91-periode-management.md) - Manajemen Periode SPMI & KPI
- [92-data-structures.md](./92-data-structures.md) - Struktur Database & Relationship

---

## 🎯 Quick Reference

### SPMI Cycle (PPEPP)

```
┌─────────────────────────────────────────────────────────────┐
│                    SIKLUS SPMI (PPEPP)                      │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  1. PENETAPAN → 2. PELAKSANAAN → 3. EVALUASI               │
│       ↑                              │                      │
│       │                              ↓                      │
│  5. PENINGKATAN ← 4. PENGENDALIAN ←─┘                      │
│       │                              │                      │
│       └───────────→ Next Cycle ──────┘                      │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

### Dokumen Hierarchy

```
Visi → Misi → RJP → Renstra → Renop → Standar → Indikator
```

### Indikator Types

| Type | Deskripsi | Source |
|------|-----------|--------|
| `renop` | Indikator Rencana Operasional | Turunan dari Renop |
| `standar` | Indikator Standar SPMI | Turunan dari Standar |
| `performa` | Indikator Performa Individu | Assignment ke Pegawai |

---

## 📞 Support & Documentation

- **Technical Lead:** [Your Name]
- **Module Owner:** Tim Penjaminan Mutu
- **Documentation Repo:** `/docs/pemutu/`
- **Code Location:** `app/Models/Pemutu/`, `app/Services/Pemutu/`

---

**© 2026 Sistem Penjaminan Mutu - All Rights Reserved**
