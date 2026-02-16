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
            $table->id();
            $table->string('nama_mata_uji');
            $table->enum('tipe', ['PMB', 'Akademik'])->comment('Pemisah konteks penggunaan');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cbt_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_uji_id')->constrained('cbt_mata_uji');
            $table->enum('tipe_soal', ['Pilihan_Ganda', 'Esai', 'Benar_Salah']);
            $table->text('konten_pertanyaan')->comment('Bisa HTML/Rich Text');
            $table->string('media_url')->nullable()->comment('Gambar/Audio jika ada');
            $table->enum('tingkat_kesulitan', ['Mudah', 'Sedang', 'Sulit']);
            $table->boolean('is_aktif')->default(true);
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cbt_opsi_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_id')->constrained('cbt_soal');
            $table->string('label', 10)->comment('A, B, C, D, E');
            $table->text('teks_jawaban')->nullable();
            $table->string('media_url')->nullable();
            $table->boolean('is_kunci_jawaban')->default(false);
            $table->integer('bobot_nilai')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. PERANCANGAN PAKET UJIAN
        Schema::create('cbt_paket_ujian', function (Blueprint $table) {
            $table->id();
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
        });

        Schema::create('cbt_komposisi_paket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_id')->constrained('cbt_paket_ujian');
            $table->foreignId('soal_id')->constrained('cbt_soal');
            $table->integer('urutan_tampil')->default(0);
            $table->timestamps();
        });

        // 3. JADWAL & PELAKSANAAN
        Schema::create('cbt_jadwal_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_id')->constrained('cbt_paket_ujian');
            $table->string('nama_kegiatan');
            $table->datetime('waktu_mulai');
            $table->datetime('waktu_selesai');
            $table->string('token_ujian', 6)->nullable();
            $table->boolean('is_token_aktif')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cbt_peserta_berhak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('cbt_jadwal_ujian');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        // 4. SESI MAHASISWA (Run-time)
        Schema::create('cbt_riwayat_ujian_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('cbt_jadwal_ujian');
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
        });

        Schema::create('cbt_jawaban_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('riwayat_id')->constrained('cbt_riwayat_ujian_siswa');
            $table->foreignId('soal_id')->constrained('cbt_soal');
            $table->foreignId('opsi_dipilih_id')->nullable()->constrained('cbt_opsi_jawaban');
            $table->text('jawaban_esai')->nullable();
            $table->boolean('is_ragu')->default(false);
            $table->decimal('nilai_didapat', 8, 2)->default(0);
            $table->timestamps();
        });

        // 5. KEAMANAN & LOG
        Schema::create('cbt_log_pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('riwayat_id')->constrained('cbt_riwayat_ujian_siswa');
            $table->enum('jenis_pelanggaran', ['Pindah_Tab', 'Keluar_Fullscreen', 'Multiple_Login']);
            $table->timestamp('waktu_kejadian')->useCurrent();
            $table->string('keterangan')->nullable();
            $table->timestamps();
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
