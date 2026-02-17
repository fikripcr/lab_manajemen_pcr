<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. DATA MASTER & PENGATURAN
        Schema::create('pmb_periode', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode')->comment('Contoh: 2025/2026 Ganjil');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pmb_jalur', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jalur')->comment('Contoh: Reguler, Prestasi, KIP-K');
            $table->decimal('biaya_pendaftaran', 15, 2);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pmb_jenis_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen')->comment('KTP, Ijazah, Sertifikat, Raport');
            $table->string('tipe_file')->nullable()->comment('pdf, jpg, png');
            $table->integer('max_size_kb')->default(2048);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pmb_syarat_dokumen_jalur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jalur_id')->constrained('pmb_jalur');
            $table->foreignId('jenis_dokumen_id')->constrained('pmb_jenis_dokumen');
            $table->boolean('is_wajib')->default(true);
            $table->text('keterangan_khusus')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. USER & PROFIL
        Schema::create('pmb_profil_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('nik', 16)->unique();
            $table->string('no_hp')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('nisn')->nullable();
            $table->string('nama_ibu_kandung')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. TRANSAKSI PENDAFTARAN
        Schema::create('pmb_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('no_pendaftaran')->unique()->comment('Format: REG-2025-XXXX');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('periode_id')->constrained('pmb_periode');
            $table->foreignId('jalur_id')->constrained('pmb_jalur');
            $table->enum('status_terkini', [
                'Draft',
                'Menunggu_Verifikasi_Bayar',
                'Menunggu_Verifikasi_Berkas',
                'Revisi_Berkas',
                'Siap_Ujian',
                'Selesai_Ujian',
                'Lulus',
                'Tidak_Lulus',
                'Daftar_Ulang',
            ])->default('Draft');
            $table->string('nim_final')->nullable()->unique();
            $table->unsignedBigInteger('orgunit_diterima_id')->nullable();
            $table->foreign('orgunit_diterima_id')->references('orgunit_id')->on('struktur_organisasi')->onDelete('set null');
            $table->timestamp('waktu_daftar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pmb_riwayat_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pmb_pendaftaran');
            $table->string('status_baru');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_pelaku_id')->constrained('users');
            $table->timestamp('waktu_kejadian')->useCurrent();
            $table->timestamps();
        });

        Schema::create('pmb_pilihan_prodi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pmb_pendaftaran');
            $table->unsignedBigInteger('orgunit_id');
            $table->foreign('orgunit_id')->references('orgunit_id')->on('struktur_organisasi');
            $table->integer('urutan');
            $table->enum('rekomendasi_sistem', ['Lulus', 'Gagal'])->nullable();
            $table->enum('keputusan_admin', ['Disetujui', 'Ditolak', 'Cadangan'])->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. UPLOAD & PEMBAYARAN
        Schema::create('pmb_dokumen_upload', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pmb_pendaftaran');
            $table->foreignId('jenis_dokumen_id')->constrained('pmb_jenis_dokumen');
            $table->string('path_file');
            $table->enum('status_verifikasi', ['Pending', 'Valid', 'Revisi', 'Ditolak'])->default('Pending');
            $table->text('catatan_revisi')->nullable();
            $table->foreignId('verifikator_id')->nullable()->constrained('users');
            $table->timestamp('waktu_upload')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pmb_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pmb_pendaftaran');
            $table->enum('jenis_bayar', ['Formulir', 'Daftar_Ulang']);
            $table->decimal('jumlah_bayar', 15, 2);
            $table->string('bukti_bayar_path')->nullable();
            $table->enum('status_verifikasi', ['Pending', 'Lunas', 'Ditolak'])->default('Pending');
            $table->foreignId('verifikator_id')->nullable()->constrained('users');
            $table->timestamp('waktu_bayar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. UJIAN
        Schema::create('pmb_sesi_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('pmb_periode');
            $table->string('nama_sesi');
            $table->datetime('waktu_mulai');
            $table->datetime('waktu_selesai');
            $table->string('lokasi')->nullable();
            $table->integer('kuota')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pmb_peserta_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->unique()->constrained('pmb_pendaftaran');
            $table->foreignId('sesi_id')->constrained('pmb_sesi_ujian');
            $table->string('username_cbt')->nullable();
            $table->string('password_cbt')->nullable();
            $table->decimal('nilai_akhir', 8, 2)->nullable();
            $table->boolean('status_kehadiran')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmb_peserta_ujian');
        Schema::dropIfExists('pmb_sesi_ujian');
        Schema::dropIfExists('pmb_pembayaran');
        Schema::dropIfExists('pmb_dokumen_upload');
        Schema::dropIfExists('pmb_pilihan_prodi');
        Schema::dropIfExists('pmb_riwayat_pendaftaran');
        Schema::dropIfExists('pmb_pendaftaran');
        Schema::dropIfExists('pmb_profil_mahasiswa');
        Schema::dropIfExists('pmb_syarat_dokumen_jalur');
        Schema::dropIfExists('pmb_jenis_dokumen');

        Schema::dropIfExists('pmb_jalur');
        Schema::dropIfExists('pmb_periode');
    }
};
