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
                $table->softDeletes();
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
                $table->softDeletes();
            });
        }

        // Mata Kuliah table (not prefixed with sys_)
        if (!Schema::hasTable('mata_kuliahs')) {
            Schema::create('mata_kuliahs', function (Blueprint $table) {
                $table->id('mata_kuliah_id');
                $table->string('kode_mk');
                $table->string('nama_mk');
                $table->integer('sks');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Jadwal Kuliah table (not prefixed with sys_)
        if (!Schema::hasTable('jadwal_kuliah')) {
            Schema::create('jadwal_kuliah', function (Blueprint $table) {
                $table->id('jadwal_kuliah_id');
                $table->foreignId('semester_id')->constrained('semesters', 'semester_id');
                $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs', 'mata_kuliah_id');
                $table->foreignId('dosen_id')->constrained('users', 'id');
                $table->foreignId('lab_id')->constrained('labs', 'lab_id');
                $table->string('hari');
                $table->time('jam_mulai');
                $table->time('jam_selesai');
                $table->timestamps();
                $table->softDeletes();
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
                $table->softDeletes();
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
                $table->softDeletes();
            });
        }

        // Kegiatan table (not prefixed with sys_)
        if (!Schema::hasTable('kegiatans')) {
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
                $table->softDeletes();
            });
        }

        // Log Penggunaan Labs table (not prefixed with sys_)
        if (!Schema::hasTable('log_penggunaan_labs')) {
            Schema::create('log_penggunaan_labs', function (Blueprint $table) {
                $table->id('log_penggunaan_labs_id');
                $table->foreignId('kegiatan_id')->constrained('kegiatans', 'kegiatan_id');
                $table->foreignId('lab_id')->constrained('labs', 'lab_id');
                $table->timestamp('waktu_isi');
                $table->timestamps();
                $table->softDeletes();
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
                $table->softDeletes();
            });
        }

        // Inventaris table (not prefixed with sys_)
        if (!Schema::hasTable('inventaris')) {
            Schema::create('inventaris', function (Blueprint $table) {
                $table->id('inventaris_id');
                $table->string('nama_alat');
                $table->string('jenis_alat');
                $table->string('kondisi_terakhir');
                $table->date('tanggal_pengecekan')->nullable();
                $table->timestamps();
                $table->softDeletes();
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
                $table->softDeletes();
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
                $table->softDeletes();

                $table->foreign('penulis_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // Lab Inventaris table
        if (!Schema::hasTable('lab_inventaris')) {
            Schema::create('lab_inventaris', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inventaris_id')->constrained('inventaris', 'inventaris_id')->onDelete('cascade');
                $table->foreignId('lab_id')->constrained('labs', 'lab_id')->onDelete('cascade');
                $table->string('kode_inventaris')->unique(); // Format: LAB-INV-XXXX
                $table->string('no_series')->nullable(); // Nomor seri atau kode tambahan
                $table->timestamp('tanggal_penempatan')->nullable();
                $table->timestamp('tanggal_penghapusan')->nullable();
                $table->string('status')->default('active'); // active, moved, inactive
                $table->text('keterangan')->nullable();
                $table->timestamps();
                $table->softDeletes(); // Adds deleted_at column

                $table->index(['inventaris_id', 'lab_id']); // Index for faster joins
                $table->index('kode_inventaris');
            });
        }

        // Lab Teams table
        if (!Schema::hasTable('lab_teams')) {
            Schema::create('lab_teams', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lab_id')->constrained('labs', 'lab_id')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
                $table->string('jabatan')->nullable(); // PIC, Teknisi, dll
                $table->boolean('is_active')->default(true);
                $table->timestamp('tanggal_mulai')->nullable();
                $table->timestamp('tanggal_selesai')->nullable();
                $table->timestamps();
                $table->softDeletes(); // Adds deleted_at column

                $table->unique(['lab_id', 'user_id']); // One user per lab
            });
        }
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
