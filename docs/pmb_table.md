// ==========================================
// 1. DATA MASTER & PENGATURAN (ADMIN)
// ==========================================

Table periode {
  periode_id int [pk, increment]
  nama_periode varchar [note: "Contoh: 2025/2026 Ganjil"]
  tanggal_mulai date
  tanggal_selesai date
  is_aktif boolean [default: true]
}

Table jalur {
  jalur_id int [pk, increment]
  nama_jalur varchar [note: "Contoh: Reguler, Prestasi, KIP-K"]
  biaya_pendaftaran decimal
  is_aktif boolean
}

Table prodi {
  prodi_id int [pk, increment]
  kode_prodi varchar [unique]
  nama_prodi varchar
  fakultas varchar
  kuota_umum int
}

Table jenis_dokumen {
  jenis_dokumen_id int [pk, increment]
  nama_dokumen varchar [note: "Master Data: KTP, Ijazah, Sertifikat, Raport"]
  tipe_file varchar [note: "pdf, jpg, png"]
  max_size_kb int
}

// [FITUR KUNCI] Mapping Syarat Dokumen
// Admin mengatur: Jalur 'Prestasi' WAJIB upload 'Sertifikat'
Table syarat_dokumen_jalur {
  syarat_id int [pk, increment]
  jalur_id int [ref: > jalur.jalur_id]
  jenis_dokumen_id int [ref: > jenis_dokumen.jenis_dokumen_id]
  
  is_wajib boolean [default: true, note: "Jika false, user boleh kosongkan"]
  keterangan_khusus text [note: "Ex: Minimal juara tingkat Kabupaten"]
}

// ==========================================
// 2. USER & PROFIL (LARAVEL STANDARD)
// ==========================================

// Gunakan tabel bawaan Laravel untuk Auth
Table users {
  id bigint [pk, increment]
  name varchar
  email varchar [unique]
  password varchar
  role enum('admin', 'camaba') [note: "Membedakan hak akses"]
  created_at timestamp
  updated_at timestamp
}

// Detail Biodata dipisah (Relasi 1-to-1)
Table profil_mahasiswa {
  profil_id int [pk, increment]
  user_id bigint [ref: - users.id]
  
  nik varchar(16) [unique]
  no_hp varchar
  tempat_lahir varchar
  tanggal_lahir date
  jenis_kelamin enum('L', 'P')
  alamat_lengkap text
  asal_sekolah varchar
  nisn varchar
  nama_ibu_kandung varchar [note: "Wajib untuk sinkron PDDIKTI"]
}

// ==========================================
// 3. TRANSAKSI PENDAFTARAN (INTI)
// ==========================================

Table pendaftaran {
  pendaftaran_id int [pk, increment]
  no_pendaftaran varchar [unique, note: "Format: REG-2025-XXXX"]
  
  // Foreign Keys
  user_id bigint [ref: > users.id]
  periode_id int [ref: > periode.periode_id]
  jalur_id int [ref: > jalur.jalur_id]
  
  // Status Utama (Untuk query cepat)
  status_terkini enum('Draft', 'Menunggu_Verifikasi_Bayar', 'Menunggu_Verifikasi_Berkas', 'Revisi_Berkas', 'Siap_Ujian', 'Selesai_Ujian', 'Lulus', 'Tidak_Lulus', 'Daftar_Ulang')
  
  // Hasil Akhir
  nim_final varchar [unique, note: "Diisi setelah daftar ulang"]
  prodi_diterima_id int [ref: > prodi.prodi_id, note: "Diisi jika lulus"]
  
  waktu_daftar timestamp
}

// Log Sejarah (Audit Trail)
Table riwayat_pendaftaran {
  riwayat_id int [pk, increment]
  pendaftaran_id int [ref: > pendaftaran.pendaftaran_id]
  
  status_baru varchar
  keterangan text [note: "Alasan tolak / revisi"]
  
  user_pelaku_id bigint [ref: > users.id, note: "Siapa yang mengubah status?"]
  waktu_kejadian timestamp [default: `now()`]
}

Table pilihan_prodi {
  pilihan_id int [pk, increment]
  pendaftaran_id int [ref: > pendaftaran.pendaftaran_id]
  prodi_id int [ref: > prodi.prodi_id]
  urutan int [note: "1, 2, atau 3"]
  
  // Logic Kelulusan
  rekomendasi_sistem enum('Lulus', 'Gagal')
  keputusan_admin enum('Disetujui', 'Ditolak', 'Cadangan')
}

// ==========================================
// 4. UPLOAD & PEMBAYARAN
// ==========================================

// User mengupload file sesuai syarat di tabel syarat_dokumen_jalur
Table dokumen_upload {
  upload_id int [pk, increment]
  pendaftaran_id int [ref: > pendaftaran.pendaftaran_id]
  jenis_dokumen_id int [ref: > jenis_dokumen.jenis_dokumen_id]
  
  path_file varchar
  status_verifikasi enum('Pending', 'Valid', 'Revisi', 'Ditolak')
  catatan_revisi text
  
  verifikator_id bigint [ref: > users.id]
  waktu_upload timestamp
}

Table pembayaran {
  pembayaran_id int [pk, increment]
  pendaftaran_id int [ref: > pendaftaran.pendaftaran_id]
  jenis_bayar enum('Formulir', 'Daftar_Ulang')
  
  jumlah_bayar decimal
  bukti_bayar_path varchar
  
  status_verifikasi enum('Pending', 'Lunas', 'Ditolak')
  verifikator_id bigint [ref: > users.id]
  waktu_bayar timestamp
}

// ==========================================
// 5. UJIAN (CBT INTEGRATION)
// ==========================================

Table sesi_ujian {
  sesi_id int [pk, increment]
  periode_id int [ref: > periode.periode_id]
  nama_sesi varchar [note: "Ex: Gelombang 1 - Sesi Pagi"]
  waktu_mulai datetime
  waktu_selesai datetime
  lokasi varchar
  kuota int
}

Table peserta_ujian {
  peserta_ujian_id int [pk, increment]
  pendaftaran_id int [ref: - pendaftaran.pendaftaran_id] // 1-to-1
  sesi_id int [ref: > sesi_ujian.sesi_id]
  
  // Akun CBT Sementara
  username_cbt varchar
  password_cbt varchar
  
  nilai_akhir decimal
  status_kehadiran boolean
}