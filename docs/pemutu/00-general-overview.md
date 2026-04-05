# General Overview - Gambaran Umum SPMI

## Apa itu SPMI?

**Sistem Penjaminan Mutu Internal (SPMI)** adalah sistem yang mengelola siklus PPEPP:
**P**enetapan → **P**elaksanaan → **E**valuasi → **Peng**endalian → **P**eningkatan.

Tujuannya sederhana: **pastikan setiap unit kerja memenuhi standar mutu, lalu terus ditingkatkan.**

---

## Siklus PPEPP dalam 1 Kalimat per Fase

| Fase | Singkatnya |
|------|-----------|
| **1. Penetapan** | Tetapkan Visi, Misi, Renstra, Renop, Standar, dan Indikator |
| **2. Pelaksanaan** | Unit bekerja + isi Evaluasi Diri (capaian vs target) |
| **3. Evaluasi** | Tim Mutu audit (AMI), kasih temuan: KTS / Terpenuhi / Terlampaui |
| **4. Pengendalian** | Unit isi tindak lanjut, Pimpinan validasi, dibahas di RTM |
| **5. Peningkatan** | Duplikasi ke periode baru, approval, mulai siklus lagi |

---

## Struktur Data Singkat

```
Periode SPMI (tahun + jenis: Akademik/Non Akademik)
    └── Dokumen (Visi, Misi, Renstra, Renop, Standar)
            └── DokSub (poin-poin dalam dokumen)
                    └── Indikator (target terukur)
                            ├── per Unit Kerja (IndikatorOrgUnit)
                            └── per Pegawai (IndikatorPegawai / KPI)
```

**IndikatorOrgUnit** adalah tabel paling penting — di sini tersimpan:
- `ed_capaian` — capaian Evaluasi Diri
- `ed_analisis` — analisis kenapa capaian segitu
- `ami_hasil_akhir` — hasil audit (0=KTS, 1=Terpenuhi, 2=Terlampaui)
- `ami_hasil_temuan` — temuan auditor
- `ami_rtp_isi` — rencana tindakan perbaikan
- `ami_te_isi` — tinjauan efektivitas
- `pengend_status` — status tindak lanjut
- `pengend_analisis` — analisis pengendalian

---

## Siapa yang Bisa Apa?

| Role | Bisa Melihat | Bisa Edit |
|------|-------------|-----------|
| **Admin SPMI** | Semua | Semua |
| **Pimpinan Unit** | Semua | Validasi pengendalian + Approve dokumen |
| **Auditee** | Semua | ED, KPI, Pengendalian — **hanya unit sendiri** |
| **Auditor Internal** | Semua | AMI, Tinjauan Efektivitas — **hanya unit sendiri** |
| **Pegawai** | Semua | ❌ |
| **Guest** | Semua | ❌ (bahkan export tidak bisa) |

Unit assignment diatur di menu **Tim Mutu**.

---

## Fitur Tambahan yang Perlu Diketahui

### Export DOCX
Klik tombol **Export** di halaman dokumen → download file `.docx` berisi info dokumen, daftar approver, dan QR Code verifikasi.

### QR Code Verifikasi
Setiap dokumen yang sudah **semua approver-nya approve** akan punya QR Code. Scan QR → buka halaman verifikasi publik.

### Periode Guard
Sistem otomatis blokir edit jika periode sedang tidak aktif. Cek via fungsi `pemutu_can_modify($tahun)`.

### Data Scoping
Auditee dan Auditor Internal **tidak bisa edit unit yang bukan tugasannya**. Backend reject dengan error message, dan tombol "Isi" tidak muncul di datatable.

---

## Cara Deploy Perubahan Baru

```bash
php artisan migrate
php artisan db:seed --class=SyncPegawaiCommonColumnsSeeder
php artisan db:seed --class=RolePermissionPemutuSeeder
php artisan cache:clear
```
