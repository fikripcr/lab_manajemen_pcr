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
        // 1. BANK DATA (Gudang Soal & Mapel)
        Schema::create('cbt_mata_uji', function (Blueprint $table) {
            $table->id('mata_uji_id');
            $table->string('nama_mata_uji');
            $table->enum('tipe', ['PMB', 'Akademik'])->comment('Pemisah konteks penggunaan');
            $table->integer('durasi_menit')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        Schema::create('cbt_soal', function (Blueprint $table) {
            $table->id('soal_id');
            $table->unsignedBigInteger('mata_uji_id');
            $table->enum('tipe_soal', ['Pilihan_Ganda', 'Esai', 'Benar_Salah']);
            $table->text('konten_pertanyaan')->comment('Bisa HTML/Rich Text');
            $table->string('media_url')->nullable()->comment('Gambar/Audio jika ada');
            $table->enum('tingkat_kesulitan', ['Mudah', 'Sedang', 'Sulit']);
            $table->boolean('is_aktif')->default(true);
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('mata_uji_id')->references('mata_uji_id')->on('cbt_mata_uji')->onDelete('cascade');
        });

        Schema::create('cbt_opsi_jawaban', function (Blueprint $table) {
            $table->id('opsi_jawaban_id');
            $table->unsignedBigInteger('soal_id');
            $table->string('label', 10)->comment('A, B, C, D, E');
            $table->text('teks_jawaban')->nullable();
            $table->string('media_url')->nullable();
            $table->boolean('is_kunci_jawaban')->default(false);
            $table->integer('bobot_nilai')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('soal_id')->references('soal_id')->on('cbt_soal')->onDelete('cascade');
        });

        // 2. PERANCANGAN PAKET UJIAN
        Schema::create('cbt_paket_ujian', function (Blueprint $table) {
            $table->id('paket_ujian_id');
            $table->string('nama_paket');
            $table->enum('tipe_paket', ['PMB', 'Akademik']);
            $table->integer('total_soal')->default(0);
            $table->integer('total_durasi_menit')->default(60);
            $table->boolean('is_acak_soal')->default(true);
            $table->boolean('is_acak_opsi')->default(true);
            $table->integer('kk_nilai_minimal')->default(0);
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        Schema::create('cbt_komposisi_paket', function (Blueprint $table) {
            $table->id('komposisi_paket_id');
            $table->unsignedBigInteger('paket_id');
            $table->unsignedBigInteger('soal_id');
            $table->integer('urutan_tampil')->default(0);
            $table->timestamps();

            $table->foreign('paket_id')->references('paket_ujian_id')->on('cbt_paket_ujian')->onDelete('cascade');
            $table->foreign('soal_id')->references('soal_id')->on('cbt_soal')->onDelete('cascade');
        });

        // 3. JADWAL & PELAKSANAAN
        Schema::create('cbt_jadwal_ujian', function (Blueprint $table) {
            $table->id('jadwal_ujian_id');
            $table->unsignedBigInteger('paket_id');
            $table->string('nama_kegiatan');
            $table->datetime('waktu_mulai');
            $table->datetime('waktu_selesai');
            $table->string('token_ujian', 6)->nullable();
            $table->boolean('is_token_aktif')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('paket_id')->references('paket_ujian_id')->on('cbt_paket_ujian')->onDelete('cascade');
        });

        Schema::create('cbt_peserta_berhak', function (Blueprint $table) {
            $table->id('peserta_berhak_id');
            $table->unsignedBigInteger('jadwal_id');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();

            $table->foreign('jadwal_id')->references('jadwal_ujian_id')->on('cbt_jadwal_ujian')->onDelete('cascade');
        });

        // 4. SESI MAHASISWA (Run-time)
        Schema::create('cbt_riwayat_ujian_siswa', function (Blueprint $table) {
            $table->id('riwayat_ujian_id');
            $table->unsignedBigInteger('jadwal_id');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('sisa_waktu_terakhir')->nullable()->comment('Snapshot detik tersisa jika crash');
            $table->decimal('nilai_akhir', 8, 2)->default(0);
            $table->enum('status', ['Sedang_Mengerjakan', 'Selesai', 'Timeout', 'Didiskualifikasi'])->default('Sedang_Mengerjakan');
            $table->string('ip_address')->nullable();
            $table->string('browser_info')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('jadwal_id')->references('jadwal_ujian_id')->on('cbt_jadwal_ujian')->onDelete('cascade');
        });

        Schema::create('cbt_jawaban_siswa', function (Blueprint $table) {
            $table->id('jawaban_siswa_id');
            $table->unsignedBigInteger('riwayat_id');
            $table->unsignedBigInteger('soal_id');
            $table->unsignedBigInteger('opsi_dipilih_id')->nullable();
            $table->text('jawaban_esai')->nullable();
            $table->boolean('is_ragu')->default(false);
            $table->decimal('nilai_didapat', 8, 2)->default(0);
            $table->timestamps();

            $table->foreign('riwayat_id')->references('riwayat_ujian_id')->on('cbt_riwayat_ujian_siswa')->onDelete('cascade');
            $table->foreign('soal_id')->references('soal_id')->on('cbt_soal')->onDelete('cascade');
            $table->foreign('opsi_dipilih_id')->references('opsi_jawaban_id')->on('cbt_opsi_jawaban')->onDelete('cascade');
        });

        // 5. KEAMANAN & LOG
        Schema::create('cbt_log_pelanggaran', function (Blueprint $table) {
            $table->id('log_pelanggaran_id');
            $table->unsignedBigInteger('riwayat_id');
            $table->enum('jenis_pelanggaran', ['Pindah_Tab', 'Keluar_Fullscreen', 'Multiple_Login']);
            $table->timestamp('waktu_kejadian')->useCurrent();
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('riwayat_id')->references('riwayat_ujian_id')->on('cbt_riwayat_ujian_siswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_log_pelanggaran');
        Schema::dropIfExists('cbt_jawaban_siswa');
        Schema::dropIfExists('cbt_riwayat_ujian_siswa');
        Schema::dropIfExists('cbt_peserta_berhak');
        Schema::dropIfExists('cbt_jadwal_ujian');
        Schema::dropIfExists('cbt_komposisi_paket');
        Schema::dropIfExists('cbt_paket_ujian');
        Schema::dropIfExists('cbt_opsi_jawaban');
        Schema::dropIfExists('cbt_soal');
        Schema::dropIfExists('cbt_mata_uji');
    }
};
