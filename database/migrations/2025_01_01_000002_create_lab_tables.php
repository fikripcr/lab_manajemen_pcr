<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for main application tables (Labs).
     */
    public function up(): void
    {
        Schema::create('labs', function (Blueprint $table) {
            $table->id('lab_id');
            $table->string('name');
            $table->string('location', 191)->nullable();
            $table->integer('capacity')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('semesters', function (Blueprint $table) {
            $table->id('semester_id');
            $table->string('tahun_ajaran', 50);
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->id('mata_kuliah_id');
            $table->string('kode_mk', 50);
            $table->string('nama_mk', 191);
            $table->integer('sks');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('jadwal_kuliah', function (Blueprint $table) {
            $table->id('jadwal_kuliah_id');
            $table->unsignedBigInteger('semester_id');
            $table->unsignedBigInteger('mata_kuliah_id');
            $table->unsignedBigInteger('dosen_id');
            $table->unsignedBigInteger('lab_id');
            $table->string('hari', 20);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('semester_id')->references('semester_id')->on('semesters');
            $table->foreign('mata_kuliah_id')->references('mata_kuliah_id')->on('mata_kuliahs');
            $table->foreign('dosen_id')->references('id')->on('users');
            $table->foreign('lab_id')->references('lab_id')->on('labs');
            $table->index(['semester_id', 'mata_kuliah_id', 'dosen_id', 'lab_id'], 'idx_jadwal_main');
        });

        Schema::create('pc_assignments', function (Blueprint $table) {
            $table->id('pc_assignment_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('jadwal_id');
            $table->unsignedBigInteger('lab_id');
            $table->string('pc_name', 100);
            $table->string('keterangan', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('jadwal_id')->references('jadwal_kuliah_id')->on('jadwal_kuliah');
            $table->foreign('lab_id')->references('lab_id')->on('labs');
            $table->index(['user_id', 'jadwal_id', 'lab_id'], 'idx_pc_assign_main');
        });

        Schema::create('log_penggunaan_pcs', function (Blueprint $table) {
            $table->id('log_penggunaan_pcs_id');
            $table->unsignedBigInteger('pc_assignment_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('jadwal_id');
            $table->unsignedBigInteger('lab_id');
            $table->string('status', 50);
            $table->timestamp('waktu_isi');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pc_assignment_id')->references('pc_assignment_id')->on('pc_assignments');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('jadwal_id')->references('jadwal_kuliah_id')->on('jadwal_kuliah');
            $table->foreign('lab_id')->references('lab_id')->on('labs');
            $table->index(['waktu_isi', 'status'], 'idx_pc_log_main');
        });

        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id('kegiatan_id');
            $table->unsignedBigInteger('lab_id');
            $table->unsignedBigInteger('penyelenggara_id');
            $table->string('nama_kegiatan', 191);
            $table->text('deskripsi');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('status', 20)->default('pending');
            $table->string('dokumentasi_path', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('lab_id')->references('lab_id')->on('labs');
            $table->foreign('penyelenggara_id')->references('id')->on('users');
            $table->index(['tanggal', 'status']);
        });

        Schema::create('log_penggunaan_labs', function (Blueprint $table) {
            $table->id('log_penggunaan_labs_id');
            $table->unsignedBigInteger('kegiatan_id');
            $table->unsignedBigInteger('lab_id');
            $table->timestamp('waktu_isi');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('kegiatan_id')->references('kegiatan_id')->on('kegiatans');
            $table->foreign('lab_id')->references('lab_id')->on('labs');
            $table->index(['waktu_isi']);
        });

        Schema::create('request_software', function (Blueprint $table) {
            $table->id('request_software_id');
            $table->unsignedBigInteger('dosen_id');
            $table->string('nama_software', 191);
            $table->text('deskripsi');
            $table->string('status', 20)->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('dosen_id')->references('id')->on('users');
            $table->index(['status']);
        });

        Schema::create('inventaris', function (Blueprint $table) {
            $table->id('inventaris_id');
            $table->string('nama_alat', 191);
            $table->string('jenis_alat', 100);
            $table->string('kondisi_terakhir', 50);
            $table->date('tanggal_pengecekan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('laporan_kerusakan', function (Blueprint $table) {
            $table->id('laporan_kerusakan_id');
            $table->unsignedBigInteger('inventaris_id');
            $table->unsignedBigInteger('teknisi_id');
            $table->text('deskripsi_kerusakan');
            $table->string('status', 20)->default('pending');
            $table->text('catatan_perbaikan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('inventaris_id')->references('inventaris_id')->on('inventaris');
            $table->foreign('teknisi_id')->references('id')->on('users');
            $table->index(['status']);
        });

        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id('pengumuman_id');
            $table->unsignedBigInteger('penulis_id');
            $table->string('judul', 191);
            $table->text('isi');
            $table->string('jenis', 50);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('penulis_id')->references('id')->on('users');
            $table->index(['jenis', 'is_published', 'published_at'], 'idx_pengumuman_main');
        });

        Schema::create('lab_inventaris', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventaris_id');
            $table->unsignedBigInteger('lab_id');
            $table->string('kode_inventaris', 100)->unique();
            $table->string('no_series', 100)->nullable();
            $table->timestamp('tanggal_penempatan')->nullable();
            $table->timestamp('tanggal_penghapusan')->nullable();
            $table->string('status', 20)->default('active');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('inventaris_id')->references('inventaris_id')->on('inventaris')->onDelete('cascade');
            $table->foreign('lab_id')->references('lab_id')->on('labs')->onDelete('cascade');
            $table->index(['inventaris_id', 'lab_id']);
        });

        Schema::create('lab_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lab_id');
            $table->unsignedBigInteger('user_id');
            $table->string('jabatan', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('lab_id')->references('lab_id')->on('labs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['lab_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_teams');
        Schema::dropIfExists('lab_inventaris');
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('laporan_kerusakan');
        Schema::dropIfExists('inventaris');
        Schema::dropIfExists('request_software');
        Schema::dropIfExists('log_penggunaan_labs');
        Schema::dropIfExists('kegiatans');
        Schema::dropIfExists('log_penggunaan_pcs');
        Schema::dropIfExists('pc_assignments');
        Schema::dropIfExists('jadwal_kuliah');
        Schema::dropIfExists('mata_kuliahs');
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('labs');
    }
};
