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
            $table->id('periode_id');
            $table->string('nama_periode')->comment('Contoh: 2025/2026 Ganjil');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pmb_jalur', function (Blueprint $table) {
            $table->id('jalur_id');
            $table->string('nama_jalur')->comment('Contoh: Reguler, Prestasi, KIP-K');
            $table->decimal('biaya_pendaftaran', 15, 2);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pmb_jenis_dokumen', function (Blueprint $table) {
            $table->id('jenis_dokumen_id');
            $table->string('nama_dokumen')->comment('KTP, Ijazah, Sertifikat, Raport');
            $table->string('tipe_file')->nullable()->comment('pdf, jpg, png');
            $table->integer('max_size_kb')->default(2048);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pmb_syarat_dokumen_jalur', function (Blueprint $table) {
            $table->id('syaratdokumenjalur_id');
            $table->unsignedBigInteger('jalur_id');
            $table->unsignedBigInteger('jenis_dokumen_id');
            $table->boolean('is_wajib')->default(true);
            $table->text('keterangan_khusus')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('jalur_id')->references('jalur_id')->on('pmb_jalur')->onDelete('cascade');
            $table->foreign('jenis_dokumen_id')->references('jenis_dokumen_id')->on('pmb_jenis_dokumen')->onDelete('cascade');
        });

        // 2. USER & PROFIL
        Schema::create('pmb_profil_mahasiswa', function (Blueprint $table) {
            $table->id('profilmahasiswa_id');
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
            $table->id('pendaftaran_id');
            $table->string('no_pendaftaran')->unique()->comment('Format: REG-2025-XXXX');
            $table->foreignId('user_id')->constrained('users');
            $table->unsignedBigInteger('periode_id');
            $table->unsignedBigInteger('jalur_id');
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

            $table->foreign('periode_id')->references('periode_id')->on('pmb_periode')->onDelete('cascade');
            $table->foreign('jalur_id')->references('jalur_id')->on('pmb_jalur')->onDelete('cascade');
        });

        Schema::create('pmb_riwayat_pendaftaran', function (Blueprint $table) {
            $table->id('riwayatpendaftaran_id');
            $table->unsignedBigInteger('pendaftaran_id');
            $table->string('status_baru');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_pelaku_id')->constrained('users');
            $table->timestamp('waktu_kejadian')->useCurrent();
            $table->timestamps();

            $table->foreign('pendaftaran_id')->references('pendaftaran_id')->on('pmb_pendaftaran')->onDelete('cascade');
        });

        Schema::create('pmb_pilihan_prodi', function (Blueprint $table) {
            $table->id('pilihanprodi_id');
            $table->unsignedBigInteger('pendaftaran_id');
            $table->unsignedBigInteger('orgunit_id');
            $table->foreign('orgunit_id')->references('orgunit_id')->on('struktur_organisasi');
            $table->integer('urutan');
            $table->enum('rekomendasi_sistem', ['Lulus', 'Gagal'])->nullable();
            $table->enum('keputusan_admin', ['Disetujui', 'Ditolak', 'Cadangan'])->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pendaftaran_id')->references('pendaftaran_id')->on('pmb_pendaftaran')->onDelete('cascade');
        });

        // 4. UPLOAD & PEMBAYARAN
        Schema::create('pmb_dokumen_upload', function (Blueprint $table) {
            $table->id('dokumenupload_id');
            $table->unsignedBigInteger('pendaftaran_id');
            $table->unsignedBigInteger('jenis_dokumen_id');
            $table->string('path_file');
            $table->enum('status_verifikasi', ['Pending', 'Valid', 'Revisi', 'Ditolak'])->default('Pending');
            $table->text('catatan_revisi')->nullable();
            $table->foreignId('verifikator_id')->nullable()->constrained('users');
            $table->timestamp('waktu_upload')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pendaftaran_id')->references('pendaftaran_id')->on('pmb_pendaftaran')->onDelete('cascade');
            $table->foreign('jenis_dokumen_id')->references('jenis_dokumen_id')->on('pmb_jenis_dokumen')->onDelete('cascade');
        });

        Schema::create('pmb_pembayaran', function (Blueprint $table) {
            $table->id('pembayaran_id');
            $table->unsignedBigInteger('pendaftaran_id');
            $table->enum('jenis_bayar', ['Formulir', 'Daftar_Ulang']);
            $table->decimal('jumlah_bayar', 15, 2);
            $table->string('bukti_bayar_path')->nullable();
            $table->enum('status_verifikasi', ['Pending', 'Lunas', 'Ditolak'])->default('Pending');
            $table->foreignId('verifikator_id')->nullable()->constrained('users');
            $table->timestamp('waktu_bayar')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pendaftaran_id')->references('pendaftaran_id')->on('pmb_pendaftaran')->onDelete('cascade');
        });

        // 5. UJIAN
        Schema::create('pmb_sesi_ujian', function (Blueprint $table) {
            $table->id('sesiujian_id');
            $table->unsignedBigInteger('periode_id');
            $table->string('nama_sesi');
            $table->datetime('waktu_mulai');
            $table->datetime('waktu_selesai');
            $table->string('lokasi')->nullable();
            $table->integer('kuota')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('periode_id')->references('periode_id')->on('pmb_periode')->onDelete('cascade');
        });

        Schema::create('pmb_peserta_ujian', function (Blueprint $table) {
            $table->id('pesertaujian_id');
            $table->unsignedBigInteger('pendaftaran_id');
            $table->unsignedBigInteger('sesi_id');
            $table->string('username_cbt')->nullable();
            $table->string('password_cbt')->nullable();
            $table->decimal('nilai_akhir', 8, 2)->nullable();
            $table->boolean('status_kehadiran')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->unique('pendaftaran_id');
            $table->foreign('pendaftaran_id')->references('pendaftaran_id')->on('pmb_pendaftaran')->onDelete('cascade');
            $table->foreign('sesi_id')->references('sesiujian_id')->on('pmb_sesi_ujian')->onDelete('cascade');
        });

        // Riwayat Approval for PMB
        Schema::create('pmb_riwayat_approval', function (Blueprint $table) {
            $table->id('riwayatapproval_id');
            $table->string('model');
            $table->unsignedBigInteger('model_id');
            $table->enum('status', ['Draft', 'Pending', 'Approved', 'Rejected'])->default('Draft');
            $table->string('pejabat')->nullable();
            $table->string('jabatan')->nullable();
            $table->text('catatan')->nullable();
            $table->string('lampiran_url')->nullable();

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['model', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pmb_riwayat_approval');
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
