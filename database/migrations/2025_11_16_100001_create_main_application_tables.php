<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for main application tables.
     */
    public function up(): void
    {

        // Labs table (not prefixed with sys_)
        if (!Schema::hasTable('labs')) {
            Schema::create('labs', function (Blueprint $table) {
                $table->id('lab_id');
                $table->string('name');
                $table->string('location')->nullable();
                $table->integer('capacity')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Semesters table (not prefixed with sys_)
        if (!Schema::hasTable('semesters')) {
            Schema::create('semesters', function (Blueprint $table) {
                $table->id('semester_id');
                $table->string('tahun_ajaran');
                $table->enum('semester', ['Ganjil', 'Genap']);
                $table->date('start_date');
                $table->date('end_date');
                $table->boolean('is_active')->default(false);
                $table->timestamps();
            });
        }

        // Mata Kuliah table (not prefixed with sys_)
        if (!Schema::hasTable('mata_kuliah')) {
            Schema::create('mata_kuliah', function (Blueprint $table) {
                $table->id('mata_kuliah_id');
                $table->string('kode_mk');
                $table->string('nama_mk');
                $table->integer('sks');
                $table->timestamps();
            });
        }

        // Jadwal Kuliah table (not prefixed with sys_)
        if (!Schema::hasTable('jadwal_kuliah')) {
            Schema::create('jadwal_kuliah', function (Blueprint $table) {
                $table->id('jadwal_kuliah_id');
                $table->foreignId('semester_id')->constrained('semesters', 'semester_id');
                $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah', 'mata_kuliah_id');
                $table->foreignId('dosen_id')->constrained('users', 'id');
                $table->foreignId('lab_id')->constrained('labs', 'lab_id');
                $table->string('hari');
                $table->time('jam_mulai');
                $table->time('jam_selesai');
                $table->timestamps();
            });
        }

        // Pc Assignments table (not prefixed with sys_)
        if (!Schema::hasTable('pc_assignments')) {
            Schema::create('pc_assignments', function (Blueprint $table) {
                $table->id('pc_assignment_id');
                $table->foreignId('user_id')->constrained('users', 'id');
                $table->foreignId('jadwal_id')->constrained('jadwal_kuliah', 'jadwal_kuliah_id'); // assuming id field name
                $table->foreignId('lab_id')->constrained('labs', 'lab_id');
                $table->string('pc_name');
                $table->string('keterangan')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Log Penggunaan PC table (not prefixed with sys_)
        if (!Schema::hasTable('log_penggunaan_pcs')) {
            Schema::create('log_penggunaan_pcs', function (Blueprint $table) {
                $table->id('log_penggunaan_pcs_id');
                $table->foreignId('pc_assignment_id')->constrained('pc_assignments', 'pc_assignment_id');
                $table->foreignId('user_id')->constrained('users', 'id');
                $table->foreignId('jadwal_id')->constrained('jadwal_kuliah', 'jadwal_kuliah_id');
                $table->foreignId('lab_id')->constrained('labs', 'lab_id');
                $table->string('status');
                $table->timestamp('waktu_isi');
                $table->timestamps();
            });
        }

        // Kegiatan table (not prefixed with sys_)
        if (!Schema::hasTable('kegiatan')) {
            Schema::create('kegiatan', function (Blueprint $table) {
                $table->id('kegiatan_id');
                $table->foreignId('lab_id')->constrained('labs', 'lab_id');
                $table->foreignId('penyelenggara_id')->constrained('users', 'id');
                $table->string('nama_kegiatan');
                $table->text('deskripsi');
                $table->date('tanggal');
                $table->time('jam_mulai');
                $table->time('jam_selesai');
                $table->string('status')->default('pending');
                $table->string('dokumentasi_path')->nullable();
                $table->timestamps();
            });
        }

        // Log Penggunaan Labs table (not prefixed with sys_)
        if (!Schema::hasTable('log_penggunaan_labs')) {
            Schema::create('log_penggunaan_labs', function (Blueprint $table) {
                $table->id('log_penggunaan_labs_id');
                $table->foreignId('kegiatan_id')->constrained('kegiatan', 'kegiatan_id');
                $table->foreignId('lab_id')->constrained('labs', 'lab_id');
                $table->timestamp('waktu_isi');
                $table->timestamps();
            });
        }

        // Software requests table (not prefixed with sys_)
        if (!Schema::hasTable('request_software')) {
            Schema::create('request_software', function (Blueprint $table) {
                $table->id('request_software_id');
                $table->foreignId('dosen_id')->constrained('users', 'id');
                $table->string('nama_software');
                $table->text('deskripsi');
                $table->string('status')->default('pending');
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }

        // Inventaris table (not prefixed with sys_)
        if (!Schema::hasTable('inventaris')) {
            Schema::create('inventaris', function (Blueprint $table) {
                $table->id('inventaris_id');
                $table->foreignId('lab_id')->constrained('labs', 'lab_id');
                $table->string('nama_alat');
                $table->string('jenis_alat');
                $table->string('kondisi_terakhir');
                $table->date('tanggal_pengecekan')->nullable();
                $table->timestamps();
            });
        }

        // Laporan kerusakan table (not prefixed with sys_)
        if (!Schema::hasTable('laporan_kerusakan')) {
            Schema::create('laporan_kerusakan', function (Blueprint $table) {
                $table->id('laporan_kerusakan_id');
                $table->foreignId('inventaris_id')->constrained('inventaris', 'inventaris_id');
                $table->foreignId('teknisi_id')->constrained('users', 'id');
                $table->text('deskripsi_kerusakan');
                $table->string('status')->default('pending');
                $table->text('catatan_perbaikan')->nullable();
                $table->timestamps();
            });
        }

        // Pengumuman/News table (not prefixed with sys_)
        if (!Schema::hasTable('pengumuman')) {
            Schema::create('pengumuman', function (Blueprint $table) {
                $table->id('pengumuman_id');
                $table->unsignedBigInteger('penulis_id');
                $table->string('judul');
                $table->text('isi');
                $table->string('jenis'); // 'pengumuman' or 'artikel_berita'
                $table->boolean('is_published')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->timestamps();

                $table->foreign('penulis_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('laporan_kerusakan');
        Schema::dropIfExists('inventaris');
        Schema::dropIfExists('request_software');
        Schema::dropIfExists('log_penggunaan_labs');
        Schema::dropIfExists('kegiatan');
        Schema::dropIfExists('log_penggunaan_pcs');
        Schema::dropIfExists('pc_assignments');
        Schema::dropIfExists('jadwal_kuliah');
        Schema::dropIfExists('mata_kuliah');
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('labs');
    }
};
