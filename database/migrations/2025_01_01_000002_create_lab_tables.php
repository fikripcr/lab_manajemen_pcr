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
        // 1. Labs (Renamed to lab_labs)
        Schema::create('lab_labs', function (Blueprint $table) {
            $table->id('lab_id');
            $table->string('name');
            $table->string('location', 191)->nullable();
            $table->integer('capacity')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // 2. Semesters (Renamed to lab_semesters)
        Schema::create('lab_semesters', function (Blueprint $table) {
            $table->id('semester_id');
            $table->string('tahun_ajaran', 50);
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // 3. Mata Kuliahs (Renamed to lab_mata_kuliahs)
        Schema::create('lab_mata_kuliahs', function (Blueprint $table) {
            $table->id('mata_kuliah_id');
            $table->string('kode_mk', 50);
            $table->string('nama_mk', 191);
            $table->integer('sks');
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // 4. Jadwal Kuliah (Renamed to lab_jadwal_kuliah)
        Schema::create('lab_jadwal_kuliah', function (Blueprint $table) {
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

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('semester_id')->references('semester_id')->on('lab_semesters');
            $table->foreign('mata_kuliah_id')->references('mata_kuliah_id')->on('lab_mata_kuliahs');
            $table->foreign('dosen_id')->references('id')->on('users');
            $table->foreign('lab_id')->references('lab_id')->on('lab_labs');
            $table->index(['semester_id', 'mata_kuliah_id', 'dosen_id', 'lab_id'], 'idx_jadwal_main');
        });

        // 5. PC Assignments (Renamed to lab_pc_assignments)
        Schema::create('lab_pc_assignments', function (Blueprint $table) {
            $table->id('pc_assignment_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('jadwal_id');
            $table->unsignedBigInteger('lab_id');
            $table->string('pc_name', 100);
            $table->string('keterangan', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('jadwal_id')->references('jadwal_kuliah_id')->on('lab_jadwal_kuliah');
            $table->foreign('lab_id')->references('lab_id')->on('lab_labs');
            $table->index(['user_id', 'jadwal_id', 'lab_id'], 'idx_pc_assign_main');
        });

        // 6. Log Penggunaan PCs (Renamed to lab_log_penggunaan_pcs)
        Schema::create('lab_log_penggunaan_pcs', function (Blueprint $table) {
            $table->id('log_penggunaan_pcs_id');
            $table->unsignedBigInteger('pc_assignment_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('jadwal_id');
            $table->unsignedBigInteger('lab_id');
            $table->string('status', 50);
            $table->timestamp('waktu_isi');
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('pc_assignment_id')->references('pc_assignment_id')->on('lab_pc_assignments');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('jadwal_id')->references('jadwal_kuliah_id')->on('lab_jadwal_kuliah');
            $table->foreign('lab_id')->references('lab_id')->on('lab_labs');
            $table->index(['waktu_isi', 'status'], 'idx_pc_log_main');
        });

        // 7. Kegiatans (Renamed to lab_kegiatans)
        Schema::create('lab_kegiatans', function (Blueprint $table) {
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

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('lab_id')->references('lab_id')->on('lab_labs');
            $table->foreign('penyelenggara_id')->references('id')->on('users');
            $table->index(['tanggal', 'status']);
        });

        // 8. Log Penggunaan Labs (Renamed to lab_log_penggunaan_labs)
        Schema::create('lab_log_penggunaan_labs', function (Blueprint $table) {
            $table->id('log_penggunaan_labs_id');
            $table->unsignedBigInteger('kegiatan_id');
            $table->unsignedBigInteger('lab_id');

            // New Columns
            $table->string('nama_peserta')->nullable();
            $table->string('email_peserta')->nullable();
            $table->string('npm_peserta')->nullable();
            $table->integer('nomor_pc')->nullable();
            $table->string('kondisi')->nullable();
            $table->text('catatan_umum')->nullable();

            $table->timestamp('waktu_isi');
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('kegiatan_id')->references('kegiatan_id')->on('lab_kegiatans');
            $table->foreign('lab_id')->references('lab_id')->on('lab_labs');
            $table->index(['waktu_isi']);
        });

        // 9. Request Software (Renamed to lab_request_software)
        Schema::create('lab_request_software', function (Blueprint $table) {
            $table->id('request_software_id');
            $table->unsignedBigInteger('dosen_id');
            $table->string('nama_software', 191);
            $table->text('deskripsi');
            $table->string('status', 20)->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('dosen_id')->references('id')->on('users');
            $table->index(['status']);
        });

        // Pivot for lab_request_software_mata_kuliah (if exists/needed)
        Schema::create('lab_request_software_mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_software_id');
            $table->unsignedBigInteger('mata_kuliah_id');
            $table->timestamps();

            $table->foreign('request_software_id', 'fk_req_soft_mk_req')->references('request_software_id')->on('lab_request_software')->onDelete('cascade');
            $table->foreign('mata_kuliah_id', 'fk_req_soft_mk_mk')->references('mata_kuliah_id')->on('lab_mata_kuliahs')->onDelete('cascade');
        });

        // 10. Inventaris (Renamed to lab_inventarises)
        Schema::create('lab_inventarises', function (Blueprint $table) {
            $table->id('inventaris_id');
            $table->string('nama_alat', 191);
            $table->string('jenis_alat', 100);
            $table->string('kondisi_terakhir', 50);
            $table->date('tanggal_pengecekan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // 11. Laporan Kerusakan (Renamed to lab_laporan_kerusakan)
        Schema::create('lab_laporan_kerusakan', function (Blueprint $table) {
            $table->id('laporan_kerusakan_id');
            $table->unsignedBigInteger('inventaris_id');
            $table->unsignedBigInteger('teknisi_id');
            $table->text('deskripsi_kerusakan');
            $table->string('status', 20)->default('pending');
            $table->text('catatan_perbaikan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('inventaris_id')->references('inventaris_id')->on('lab_inventarises');
            $table->foreign('teknisi_id')->references('id')->on('users');
            $table->index(['status']);
        });

        // 12. Pengumuman (Renamed to lab_pengumuman)
        Schema::create('lab_pengumuman', function (Blueprint $table) {
            $table->id('pengumuman_id');
            $table->unsignedBigInteger('penulis_id');
            $table->string('judul', 191);
            $table->text('isi');
            $table->string('jenis', 50);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('penulis_id')->references('id')->on('users');
            $table->index(['jenis', 'is_published', 'published_at'], 'idx_pengumuman_main');
        });

        // 13. Lab Inventaris (Renamed to lab_inventaris_penempatan)
        Schema::create('lab_inventaris_penempatan', function (Blueprint $table) {
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

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('inventaris_id')->references('inventaris_id')->on('lab_inventarises')->onDelete('cascade');
            $table->foreign('lab_id')->references('lab_id')->on('lab_labs')->onDelete('cascade');
            $table->index(['inventaris_id', 'lab_id']);
        });

        // 14. Lab Teams
        Schema::create('lab_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lab_id');
            $table->unsignedBigInteger('user_id');
            $table->string('jabatan', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('lab_id')->references('lab_id')->on('lab_labs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['lab_id', 'user_id']);
        });

        // 15. Surat Bebas Lab
        Schema::create('surat_bebas_labs', function (Blueprint $table) {
            $table->id('surat_bebas_lab_id');
            $table->unsignedBigInteger('student_id');
            $table->string('status', 20)->default('pending'); // pending, approved, rejected
            $table->string('file_path')->nullable();          // Path to generated PDF
            $table->text('remarks')->nullable();              // Catatan penolakan/approval
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Blameable columns
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('student_id')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
        });

        // 16. Lab Mahasiswa
        Schema::create('lab_mahasiswa', function (Blueprint $table) {
            $table->id('mahasiswa_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nim')->unique();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('program_studi')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // 17. Lab Personil
        Schema::create('lab_personil', function (Blueprint $table) {
            $table->id('personil_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('nip')->unique()->nullable();
            $table->string('jabatan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_personil');
        Schema::dropIfExists('lab_mahasiswa');
        Schema::dropIfExists('surat_bebas_labs');
        Schema::dropIfExists('lab_teams');
        Schema::dropIfExists('lab_inventaris_penempatan');
        Schema::dropIfExists('lab_pengumuman');
        Schema::dropIfExists('lab_laporan_kerusakan');
        Schema::dropIfExists('lab_inventarises');
        Schema::dropIfExists('lab_request_software_mata_kuliah');
        Schema::dropIfExists('lab_request_software');
        Schema::dropIfExists('lab_log_penggunaan_labs');
        Schema::dropIfExists('lab_kegiatans');
        Schema::dropIfExists('lab_log_penggunaan_pcs');
        Schema::dropIfExists('lab_pc_assignments');
        Schema::dropIfExists('lab_jadwal_kuliah');
        Schema::dropIfExists('lab_mata_kuliahs');
        Schema::dropIfExists('lab_semesters');
        Schema::dropIfExists('lab_labs');
    }
};
