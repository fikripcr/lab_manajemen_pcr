DOKUMEN SPESIFIKASI TEKNIS: SISTEM PMB & CBT TERINTEGRASI
Versi: 1.0 - Final Draft
Role: System Analyst
I. RINGKASAN EKSEKUTIF
Sistem ini dirancang untuk menangani siklus hidup calon mahasiswa mulai dari pendaftaran akun, verifikasi berkas, ujian seleksi (CBT), hingga penetapan kelulusan dan penerbitan NIM. Modul CBT dirancang bersifat dual-purpose, dapat digunakan untuk Seleksi Masuk (PMB) maupun Ujian Akademik (UTS/UAS) dengan arsitektur yang mengutamakan performa tinggi menggunakan strategi Hybrid Caching.
II. ARSITEKTUR TEKNOLOGI & INFRASTRUKTUR
1. Technology Stack
• Backend Framework: Laravel (PHP).
• Database Utama: MySQL / PostgreSQL (Relational Data).
• In-Memory Data Store: Redis (Wajib untuk Session, Cache, & Antrian Jawaban Ujian).
• Client-Side Storage: LocalStorage (Browser Buffer).
• Frontend: Blade + Livewire (untuk Admin) / Vue.js atau React (untuk Interface CBT Peserta agar reaktif).
2. Topologi Server (Rekomendasi)
• App Server: Menjalankan PHP/Laravel.
• DB Server: MySQL (Disarankan terpisah dari App Server jika user > 1000).
• Redis Server: Menyimpan session user dan data jawaban sementara (temp).
III. ALUR BISNIS (BUSINESS PROCESS FLOW)
A. Modul PMB (Penerimaan Mahasiswa Baru)
1. Registrasi & Profiling (Single Identity)
    ◦ User mendaftar menggunakan Email/No HP -> Masuk ke tabel users (Role: Camaba).
    ◦ Sistem membuat record di tabel profil_mahasiswa.
    ◦ Validasi: NIK Unique Check untuk mencegah akun ganda.
2. Pemilihan Jalur & Upload Berkas (Dynamic Mapping)
    ◦ User memilih Jalur (misal: Prestasi).
    ◦ Sistem mengecek tabel syarat_dokumen_jalur untuk me-render form upload yang sesuai.
    ◦ User mengupload berkas ke tabel dokumen_upload (Status: Pending).
3. Pembayaran & Verifikasi (Gatekeeper)
    ◦ User upload bukti bayar -> Admin Keuangan memverifikasi.
    ◦ Jika Valid -> Admin Berkas melakukan verifikasi dokumen syarat.
    ◦ Audit Trail: Setiap klik "Valid/Tolak" oleh admin dicatat di tabel riwayat_pendaftaran.
4. Penjadwalan Ujian
    ◦ User memilih sesi ujian yang tersedia (sesi_ujian).
    ◦ Sistem mengunci kuota (LOCK FOR UPDATE di database) untuk mencegah overbooking.
    ◦ Sistem generate Kartu Ujian (berisi QR Code & Credentials CBT).
5. Seleksi & Kelulusan (Decision Support)
    ◦ Nilai ditarik dari CBT.
    ◦ Sistem memberikan Rekomendasi (Lulus/Gagal) berdasarkan Passing Grade Prodi.
    ◦ Admin memiliki hak Veto/Override (misal: meluluskan jalur afirmasi).
    ◦ Status Final -> Publish Pengumuman.
6. Daftar Ulang & NIM
    ◦ User bayar daftar ulang -> Validasi -> Sync data ke Sistem Akademik -> Terbit NIM.
B. Modul CBT (Computer Based Test)
1. Inisialisasi Ujian
    ◦ Peserta login -> Sistem mengambil paket soal dari MySQL.
    ◦ Randomization: Soal diacak di server, urutannya disimpan di Redis session user tersebut.
2. Pengerjaan (The Hybrid Flow)
    ◦ Aksi User: Klik Jawaban "A".
    ◦ Layer 1 (Browser): Simpan ke LocalStorage (Instant Feedback, tahan refresh).
    ◦ Layer 2 (Background Sync): Script JS mengirim jawaban ke Redis via API (setiap 30 detik atau per 5 soal).
    ◦ Benefit: Jika server down/lemot, user tidak sadar karena LocalStorage menghandle UI.
3. Submit & Grading
    ◦ User klik "Selesai".
    ◦ Sistem memindahkan data jawaban dari Redis -> MySQL (jawaban_siswa).
    ◦ Sistem menghitung nilai (Scoring) dan simpan ke peserta_ujian.
    ◦ Data di Redis & LocalStorage dibersihkan.
IV. STRATEGI IMPLEMENTASI REDIS & LOCALSTORAGE (CRITICAL)
Ini adalah bagian paling vital untuk performa CBT.
1. Mengapa Redis?
Tanpa Redis, setiap klik jawaban adalah 1 query UPDATE ke MySQL. Jika 1.000 peserta ujian menjawab 50 soal dalam 1 jam, MySQL akan menerima beban 50.000 transaksi tulis. Ini akan membuat server hang atau deadlock.
• Dengan Redis: Jawaban ditulis ke RAM (Memory). Sangat cepat. MySQL hanya diupdate SEKALI saja saat ujian selesai (Bulk Insert).
2. Mengapa LocalStorage?
Redis ada di sisi server. Bagaimana jika internet peserta putus ("RTO")?
• Fungsi: Sebagai "Buffer Darurat". Jawaban disimpan di browser.
• Mekanisme Recovery: Saat internet nyambung kembali, JS akan mengecek: "Hei Server, jawaban nomor 5 kamu kosong ya? Ini aku punya datanya di LocalStorage, aku kirim sekarang ya."
3. Diagram Alur Data Hybrid
User Klik -> Simpan LocalStorage -> Kirim API (Async) -> Simpan di Redis -> (Ujian Selesai) -> Pindah ke MySQL.
V. DATABASE DESIGN HIGHLIGHTS (Catatan Penting)
Berdasarkan diskusi DBML kita sebelumnya, perhatikan poin ini saat coding:
1. Tabel users (Unified):
    ◦ Jangan membuat tabel admin terpisah. Gunakan tabel users dengan kolom role. Ini memudahkan fitur Auth Laravel.
2. Tabel syarat_dokumen_jalur (Pivot):
    ◦ Pastikan Admin memiliki UI untuk mencentang dokumen apa saja yang wajib per jalur. Frontend pendaftaran harus merender form berdasarkan query ke tabel ini, bukan hardcode.
3. Tabel riwayat_pendaftaran (Audit):
    ◦ Gunakan prinsip Append-Only. Jangan pernah meng-update/menghapus baris di tabel ini. Jika status berubah, insert baris baru. Ini penting untuk jejak hukum/audit jika ada protes dari camaba.
VI. POTENSI RISIKO & MITIGASI (Risk Analysis)
Sebagai System Analyst, saya mengidentifikasi titik rawan berikut:RisikoDampakStrategi Mitigasi (Solusi)Server Down saat UjianData jawaban hilang, ujian batal.Implementasi LocalStorage. Data tersimpan di browser peserta. Saat server UP, data disinkron otomatis.Joki / KecuranganPeserta digantikan orang lain.1. Fitur Face Recognition sederhana (ambil foto webcam acak tiap 5 menit). 
 2. Kunci browser (Fullscreen/Detect Tab Switching).Race Condition KuotaKuota prodi/ujian minus (Overbooked).Gunakan Database Transaction & Atomic Lock (DB::transaction di Laravel) saat user memilih kursi ujian.Manipulasi NilaiAdmin nakal mengubah nilai CBT.Log setiap perubahan nilai di tabel audit. Kunci nilai CBT di database agar Read-Only setelah submit, kecuali oleh Superadmin.Upload Berkas BesarStorage server penuh cepat.Batasi ukuran file (max 2MB). Gunakan Object Storage (AWS S3 / MinIO) jangan simpan di folder public server app.
VII. CHECKLIST PRA-DEPLOYMENT
Sebelum sistem ini diluncurkan (Go-Live), pastikan:
1. [ ] Load Testing: Uji CBT dengan simulasi 500-1000 user bersamaan (gunakan tool seperti JMeter atau K6).
2. [ ] Redis Config: Pastikan maxmemory-policy di Redis diset ke allkeys-lru atau config yang sesuai agar tidak crash saat RAM penuh.
3. [ ] Indexing DB: Pastikan kolom yang sering di-query (nik, email, status_terkini) sudah memiliki Index di MySQL.
4. [ ] Fallback Mode: Pastikan di file .env ada opsi untuk mematikan Redis dan kembali ke Database driver jika Redis server mati mendadak.

================

