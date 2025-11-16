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
        // Activity log table with sys_ prefix
        if (!Schema::hasTable('sys_activity_log')) {
            Schema::create('sys_activity_log', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('log_name')->nullable();
                $table->text('description'); // Description of the activity
                $table->string('subject_type')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->string('causer_type')->nullable();
                $table->unsignedBigInteger('causer_id')->nullable();
                $table->json('properties')->nullable();
                $table->timestamps();

                $table->index(['log_name', 'subject_type', 'subject_id']);
            });
        }

        // Labs table
        if (!Schema::hasTable('labs')) {
            Schema::create('labs', function (Blueprint $table) {
                $table->char('lab_id', 36)->primary();
                $table->string('nama_lab');
                $table->text('deskripsi')->nullable();
                $table->string('lokasi');
                $table->unsignedInteger('kapasitas');
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Inventaris table
        if (!Schema::hasTable('inventaris')) {
            Schema::create('inventaris', function (Blueprint $table) {
                $table->char('inventaris_id', 36)->primary();
                $table->string('nama_barang');
                $table->text('deskripsi')->nullable();
                $table->string('kategori');
                $table->unsignedInteger('jumlah');
                $table->char('lab_id', 36)->nullable();
                $table->foreign('lab_id')->references('lab_id')->on('labs')->onDelete('set null');
                $table->enum('kondisi', ['baik', 'rusak', 'hilang'])->default('baik');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Mata Kuliah table
        if (!Schema::hasTable('mata_kuliah')) {
            Schema::create('mata_kuliah', function (Blueprint $table) {
                $table->char('mata_kuliah_id', 36)->primary();
                $table->string('kode_mk');
                $table->string('nama_mk');
                $table->unsignedInteger('sks');
                $table->text('deskripsi')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Semesters table
        if (!Schema::hasTable('semesters')) {
            Schema::create('semesters', function (Blueprint $table) {
                $table->char('semester_id', 36)->primary();
                $table->string('nama_semester');
                $table->year('tahun_akademik');
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai');
                $table->enum('status', ['aktif', 'nonaktif'])->default('nonaktif');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('inventaris');
        Schema::dropIfExists('labs');
        Schema::dropIfExists('sys_activity_log');
    }
};