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
        // Create labs table
        Schema::create('labs', function (Blueprint $table) {
            $table->id('lab_id');
            $table->string('name');
            $table->string('location')->nullable();
            $table->integer('capacity')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create semesters table
        Schema::create('semesters', function (Blueprint $table) {
            $table->id('semester_id');
            $table->string('tahun_ajaran');
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Create mata_kuliahs table
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->id('mata_kuliah_id');
            $table->string('kode_mk');
            $table->string('nama_mk');
            $table->integer('sks');
            $table->timestamps();
        });

        // Create jadwal_kuliah table (renamed from jadwals)
        Schema::create('jadwal_kuliah', function (Blueprint $table) {
            $table->id('jadwal_id');
            $table->foreignId('semester_id')->constrained('semesters', 'semester_id');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs', 'mata_kuliah_id');
            $table->foreignId('dosen_id')->constrained('users', 'id');
            $table->foreignId('lab_id')->constrained('labs', 'lab_id');
            $table->string('hari');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });

        // Create pc_assignments table
        Schema::create('pc_assignments', function (Blueprint $table) {
            $table->id('pc_assignment_id');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('jadwal_id')->constrained('jadwal_kuliah', 'jadwal_id');
            $table->foreignId('lab_id')->constrained('labs', 'lab_id');
            $table->string('pc_name');
            $table->string('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create log_penggunaan_pcs table
        Schema::create('log_penggunaan_pcs', function (Blueprint $table) {
            $table->id('log_penggunaan_pcs_id');
            $table->foreignId('pc_assignment_id')->constrained('pc_assignments', 'pc_assignment_id');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('jadwal_id')->constrained('jadwal_kuliah', 'jadwal_id');
            $table->foreignId('lab_id')->constrained('labs', 'lab_id');
            $table->string('status');
            $table->timestamp('waktu_isi');
            $table->timestamps();
        });

        // Create kegiatans table
        Schema::create('kegiatans', function (Blueprint $table) {
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

        // Create log_penggunaan_labs table
        Schema::create('log_penggunaan_labs', function (Blueprint $table) {
            $table->id('log_penggunaan_labs_id');
            $table->foreignId('kegiatan_id')->constrained('kegiatans', 'kegiatan_id');
            $table->foreignId('lab_id')->constrained('labs', 'lab_id');
            $table->timestamp('waktu_isi');
            $table->timestamps();
        });

        // Create request_software table
        Schema::create('request_software', function (Blueprint $table) {
            $table->id('request_software_id');
            $table->foreignId('dosen_id')->constrained('users', 'id');
            $table->string('nama_software');
            $table->text('deskripsi');
            $table->string('status')->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Create inventaris table
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id('inventaris_id');
            $table->foreignId('lab_id')->constrained('labs', 'lab_id');
            $table->string('nama_alat');
            $table->string('jenis_alat');
            $table->string('kondisi_terakhir');
            $table->date('tanggal_pengecekan')->nullable();
            $table->timestamps();
        });

        // Create laporan_kerusakan table
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kerusakan');
        Schema::dropIfExists('inventaris');
        Schema::dropIfExists('kegiatans');
        Schema::dropIfExists('request_software');
        Schema::dropIfExists('log_penggunaan_labs');
        Schema::dropIfExists('log_penggunaan_pcs');
        Schema::dropIfExists('pc_assignments');
        Schema::dropIfExists('jadwal_kuliah');
        Schema::dropIfExists('mata_kuliahs');
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('labs');
    }
};