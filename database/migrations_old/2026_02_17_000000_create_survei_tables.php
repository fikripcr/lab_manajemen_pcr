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
        // 1. DEFINISI SURVEI (MASTER DATA)
        Schema::create('survei_survei', function (Blueprint $table) {
            $table->id('id'); // survei_id
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('slug')->unique();

            // Target & Akses
            $table->enum('target_role', ['Mahasiswa', 'Dosen', 'Tendik', 'Alumni', 'Umum']);
            $table->boolean('is_aktif')->default(true);
            $table->boolean('wajib_login')->default(true);
            $table->boolean('bisa_isi_ulang')->default(false); // Jika true, satu user bisa isi berkali-kali

            $table->dateTime('tanggal_mulai')->nullable();
            $table->dateTime('tanggal_selesai')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('survei_halaman', function (Blueprint $table) {
            $table->id('id'); // halaman_id
            $table->foreignId('survei_id')->constrained('survei_survei')->onDelete('cascade');
            $table->string('judul_halaman')->nullable(); // Bagian 1: Identitas, dst
            $table->integer('urutan')->default(0);
            $table->text('deskripsi_halaman')->nullable();
            $table->timestamps();
        });

        Schema::create('survei_pertanyaan', function (Blueprint $table) {
            $table->id('id'); // pertanyaan_id
            $table->foreignId('survei_id')->constrained('survei_survei')->onDelete('cascade');
            $table->foreignId('halaman_id')->constrained('survei_halaman')->onDelete('cascade');

            $table->text('teks_pertanyaan');
            $table->string('bantuan_teks')->nullable(); // Keterangan kecil di bawah soal

            // Tipe Input
            $table->enum('tipe', [
                'Teks_Singkat',
                'Esai',
                'Angka',
                'Pilihan_Ganda',
                'Kotak_Centang',
                'Dropdown',
                'Skala_Linear',
                'Tanggal',
                'Upload_File',
                'Rating_Bintang',
            ]);

            // Konfigurasi JSON untuk fleksibilitas
            $table->json('config_json')->nullable();

            $table->boolean('wajib_diisi')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('survei_opsi', function (Blueprint $table) {
            $table->id('id'); // opsi_id
            $table->foreignId('pertanyaan_id')->constrained('survei_pertanyaan')->onDelete('cascade');

            $table->string('label');                       // Teks yang muncul di layar
            $table->string('nilai_tersimpan')->nullable(); // Value database
            $table->integer('bobot_skor')->default(0);     // Penting untuk SPMI/Kuis

            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // 2. LOGIKA & KONTEKS
        Schema::create('survei_logika', function (Blueprint $table) {
            $table->id('id'); // logika_id
            $table->foreignId('survei_id')->constrained('survei_survei')->onDelete('cascade');

            $table->foreignId('pertanyaan_pemicu_id')->constrained('survei_pertanyaan')->onDelete('cascade');
            $table->enum('operator', ['Sama_Dengan', 'Tidak_Sama', 'Lebih_Dari', 'Kurang_Dari', 'Termasuk']); // Added basics
            $table->string('nilai_pemicu');                                                                   // Jawaban user yang memicu logika

            $table->enum('aksi', ['Lompat_Ke_Halaman', 'Sembunyikan_Pertanyaan', 'Selesai_Survei']);

            $table->foreignId('target_halaman_id')->nullable()->constrained('survei_halaman')->onDelete('set null');
            $table->foreignId('target_pertanyaan_id')->nullable()->constrained('survei_pertanyaan')->onDelete('set null');

            $table->timestamps();
        });

        Schema::create('survei_relasi_konteks', function (Blueprint $table) {
            $table->id('id'); // relasi_id
            $table->foreignId('survei_id')->constrained('survei_survei')->onDelete('cascade');
            $table->foreignId('pertanyaan_id')->nullable()->constrained('survei_pertanyaan')->onDelete('cascade');

                                          // Polymorphic Relation
            $table->string('model_type'); // Ex: 'App\Models\SpmiIndikator'
            $table->unsignedBigInteger('model_id');
            $table->index(['model_type', 'model_id']);

            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // 3. DATA PENGISIAN (TRANSAKSI)
        Schema::create('survei_pengisian', function (Blueprint $table) {
            $table->id('id'); // pengisian_id
            $table->foreignId('survei_id')->constrained('survei_survei')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Null jika anonim

                                                      // Konteks Runtime
            $table->nullableMorphs('entitas_target'); // Creates entitas_target_type & entitas_target_id

            $table->enum('status', ['Draft', 'Selesai'])->default('Draft');
            $table->timestamp('waktu_mulai')->useCurrent();
            $table->timestamp('waktu_selesai')->nullable();
            $table->string('ip_address')->nullable();

            $table->timestamps();
        });

        Schema::create('survei_jawaban', function (Blueprint $table) {
            $table->id('id'); // jawaban_id
            $table->foreignId('pengisian_id')->constrained('survei_pengisian')->onDelete('cascade');
            $table->foreignId('pertanyaan_id')->constrained('survei_pertanyaan')->onDelete('cascade');

                                                        // Penyimpanan Data Fleksibel
            $table->text('nilai_teks')->nullable();     // Untuk Esai/Teks Singkat
            $table->integer('nilai_angka')->nullable(); // Untuk Skala (1-5), Rating, Angka
            $table->date('nilai_tanggal')->nullable();
            $table->json('nilai_json')->nullable(); // Untuk Checkbox (Multi Select) atau Path Upload

            $table->foreignId('opsi_id')->nullable()->constrained('survei_opsi')->onDelete('set null');

            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survei_jawaban');
        Schema::dropIfExists('survei_pengisian');
        Schema::dropIfExists('survei_relasi_konteks');
        Schema::dropIfExists('survei_logika');
        Schema::dropIfExists('survei_opsi');
        Schema::dropIfExists('survei_pertanyaan');
        Schema::dropIfExists('survei_halaman');
        Schema::dropIfExists('survei_survei');
    }
};
