// ==========================================
// 1. BANK DATA (Gudang Soal & Mapel)
// ==========================================

Table users {
  id bigint [pk, increment]
  name varchar
  email varchar [unique]
  password varchar
  role enum('admin', 'camaba') [note: "Membedakan hak akses"]
  created_at timestamp
  updated_at timestamp
}

// Kategori Soal:
// Jika PMB: Isinya "TPA", "Bhs Inggris", "Matematika Dasar"
// Jika Akademik: Isinya "Algoritma", "Basis Data", "Akuntansi"
Table mata_uji {
  mata_uji_id int [pk, increment]
  nama_mata_uji varchar
  tipe enum('PMB', 'Akademik') [note: "Pemisah konteks penggunaan"]
  deskripsi text
}

Table soal {
  soal_id int [pk, increment]
  mata_uji_id int [ref: > mata_uji.mata_uji_id]
  
  tipe_soal enum('Pilihan_Ganda', 'Esai', 'Benar_Salah')
  konten_pertanyaan text [note: "Bisa HTML/Rich Text"]
  media_url varchar [note: "Gambar/Audio jika ada"]
  tingkat_kesulitan enum('Mudah', 'Sedang', 'Sulit')
  
  is_aktif boolean [default: true]
  dibuat_oleh bigint [ref: > users.id, note: "Dosen atau Tim PMB"]
  created_at timestamp
}

Table opsi_jawaban {
  opsi_id int [pk, increment]
  soal_id int [ref: > soal.soal_id]
  
  label varchar [note: "A, B, C, D, E"]
  teks_jawaban text
  media_url varchar
  
  is_kunci_jawaban boolean [note: "True jika ini jawaban benar"]
  bobot_nilai int [default: 0, note: "Jika benar poinnya berapa"]
}

// ==========================================
// 2. PERANCANGAN PAKET UJIAN (The Exam Paper)
// ==========================================

// Header Paket Ujian (Kertas Ujian Virtual)
Table paket_ujian {
  paket_id int [pk, increment]
  nama_paket varchar [note: "Ex: Paket Soal PMB 2024 - IPA A"]
  tipe_paket enum('PMB', 'Akademik')
  total_soal int
  total_durasi_menit int
  
  // Fitur Acak
  is_acak_soal boolean [default: true]
  is_acak_opsi boolean [default: true]
  
  kk_nilai_minimal int [note: "Passing grade spesifik paket ini"]
  dibuat_oleh bigint [ref: > users.id]
}

// Isi Paket: Menghubungkan Paket dengan Bank Soal
Table komposisi_paket {
  komposisi_id int [pk, increment]
  paket_id int [ref: > paket_ujian.paket_id]
  soal_id int [ref: > soal.soal_id]
  urutan_tampil int
}

// ==========================================
// 3. JADWAL & PELAKSANAAN (The Event)
// ==========================================

Table jadwal_ujian {
  jadwal_id int [pk, increment]
  paket_id int [ref: > paket_ujian.paket_id]
  
  nama_kegiatan varchar [note: "Ex: UTS Algoritma 2024 atau Ujian Masuk Gel 1"]
  waktu_mulai datetime
  waktu_selesai datetime
  
  token_ujian varchar [note: "Token 6 digit yang digenerate dosen/admin"]
  is_token_aktif boolean
  
  // Whitelist (Siapa yang boleh ikut?)
  // Jika NULL, berarti terbuka untuk semua yang punya link (jarang dipakai)
  // Biasanya diisi Logic ID Kelas atau ID Gelombang PMB di aplikasi
}

// Whitelist Peserta (Opsional tapi disarankan)
// Agar mahasiswa smt 1 tidak bisa iseng buka ujian smt 7
Table peserta_berhak {
  id int [pk, increment]
  jadwal_id int [ref: > jadwal_ujian.jadwal_id]
  user_id bigint [ref: > users.id, note: "Link ke tabel User PMB/Akademik"]
}

// ==========================================
// 4. SESI MAHASISWA (Run-time)
// ==========================================

// Mencatat satu kali percobaan ujian oleh user
Table riwayat_ujian_siswa {
  riwayat_id int [pk, increment]
  jadwal_id int [ref: > jadwal_ujian.jadwal_id]
  user_id bigint [ref: > users.id]
  
  waktu_mulai timestamp
  waktu_selesai timestamp
  sisa_waktu_terakhir int [note: "Snapshot detik tersisa jika crash"]
  
  nilai_akhir decimal
  status enum('Sedang_Mengerjakan', 'Selesai', 'Timeout', 'Didiskualifikasi')
  
  ip_address varchar
  browser_info varchar
}

// Menyimpan jawaban per nomor
Table jawaban_siswa {
  jawaban_id int [pk, increment]
  riwayat_id int [ref: > riwayat_ujian_siswa.riwayat_id]
  soal_id int [ref: > soal.soal_id]
  
  opsi_dipilih_id int [ref: > opsi_jawaban.opsi_id, note: "Null jika Essay/Kosong"]
  jawaban_esai text [note: "Diisi jika soal esai"]
  
  is_ragu boolean [note: "Fitur 'Ragu-ragu'"]
  nilai_didapat decimal [note: "Dihitung otomatis atau manual dosen"]
}

// ==========================================
// 5. KEAMANAN & LOG
// ==========================================

Table log_pelanggaran {
  log_id int [pk, increment]
  riwayat_id int [ref: > riwayat_ujian_siswa.riwayat_id]
  
  jenis_pelanggaran enum('Pindah_Tab', 'Keluar_Fullscreen', 'Multiple_Login')
  waktu_kejadian timestamp
  keterangan varchar
}