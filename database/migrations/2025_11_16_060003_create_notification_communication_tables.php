<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for notification and communication tables.
     */
    public function up(): void
    {
        // Pengumuman/News table
        if (!Schema::hasTable('pengumuman')) {
            Schema::create('pengumuman', function (Blueprint $table) {
                $table->char('pengumuman_id', 36)->primary();
                $table->string('judul');
                $table->text('isi');
                $table->string('gambar')->nullable();
                $table->enum('tipe', ['pengumuman', 'berita'])->default('pengumuman');
                $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
                $table->timestamp('published_at')->nullable();
                $table->unsignedBigInteger('created_by');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // Software requests table
        if (!Schema::hasTable('request_software')) {
            Schema::create('request_software', function (Blueprint $table) {
                $table->char('id', 36)->primary();
                $table->char('dosen_id', 36);
                $table->string('nama_software');
                $table->text('keperluan');
                $table->string('versi')->nullable();
                $table->string('license_info')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected', 'installed'])->default('pending');
                $table->text('catatan')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->char('approved_by', 36)->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('dosen_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Laporan kerusakan table
        if (!Schema::hasTable('laporan_kerusakan')) {
            Schema::create('laporan_kerusakan', function (Blueprint $table) {
                $table->char('id', 36)->primary();
                $table->char('inventaris_id', 36);
                $table->char('teknisi_id', 36)->nullable();
                $table->text('deskripsi_kerusakan');
                $table->enum('tingkat_kerusakan', ['ringan', 'sedang', 'berat'])->default('ringan');
                $table->enum('status', ['dilaporkan', 'diproses', 'diperbaiki', 'tidak_dapat_diperbaiki'])->default('dilaporkan');
                $table->text('tindakan_perbaikan')->nullable();
                $table->timestamp('tanggal_perbaikan')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('inventaris_id')->references('inventaris_id')->on('inventaris')->onDelete('cascade');
                $table->foreign('teknisi_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Kegiatan table
        if (!Schema::hasTable('kegiatan')) {
            Schema::create('kegiatan', function (Blueprint $table) {
                $table->char('id', 36)->primary();
                $table->char('penyelenggara_id', 36);
                $table->string('nama_kegiatan');
                $table->text('deskripsi');
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai');
                $table->char('lab_id', 36);
                $table->string('kuota_peserta');
                $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('penyelenggara_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('lab_id')->references('lab_id')->on('labs')->onDelete('restrict');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
        Schema::dropIfExists('laporan_kerusakan');
        Schema::dropIfExists('request_software');
        Schema::dropIfExists('pengumuman');
    }
};