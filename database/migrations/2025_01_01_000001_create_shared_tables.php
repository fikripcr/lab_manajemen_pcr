<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Shared (cross-module) tables.
     * This migration runs BEFORE all module migrations.
     */
    public function up(): void
    {
        // =====================================================================
        // 1. Struktur Organisasi (merged from hr_org_unit + pemutu_org_unit)
        // =====================================================================
        Schema::create('struktur_organisasi', function (Blueprint $table) {
            $table->id('orgunit_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name', 191);
            $table->string('code', 50)->nullable();
            $table->string('type', 100)->nullable();
            $table->integer('level')->default(1);
            $table->integer('seq')->default(1);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('successor_id')->nullable();
            $table->unsignedBigInteger('auditee_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('parent_id')->references('orgunit_id')->on('struktur_organisasi')->onDelete('set null');
            $table->foreign('successor_id')->references('orgunit_id')->on('struktur_organisasi')->nullOnDelete();
            $table->foreign('auditee_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });

        // =====================================================================
        // 2. Pegawai (from hr_pegawai, shared across all modules)
        // =====================================================================
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id('pegawai_id');
            $table->unsignedBigInteger('latest_riwayatdatadiri_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatstatpegawai_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatstataktifitas_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatinpassing_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatpendidikan_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatjabfungsional_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatjabstruktural_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatpenugasan_id')->nullable();
            $table->unsignedBigInteger('atasan1')->nullable();
            $table->unsignedBigInteger('atasan2')->nullable();
            $table->string('photo', 255)->nullable()->comment('Employee photo for face recognition');
            $table->text('face_encoding')->nullable()->comment('Face encoding data for face matching');
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // Add foreign key to users table referencing pegawai
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('pegawai_id')->references('pegawai_id')->on('pegawai')->nullOnDelete();
        });

        // =====================================================================
        // 3. Mahasiswa (unified from lab_mahasiswa + eoffice_mahasiswa)
        // =====================================================================
        Schema::create('mahasiswa', function (Blueprint $table) {
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

        // =====================================================================
        // 4. Pengumuman (from lab_pengumuman, shared across all modules)
        // =====================================================================
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

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('penulis_id')->references('id')->on('users');
            $table->index(['jenis', 'is_published', 'published_at'], 'idx_pengumuman_main');
        });

        // =====================================================================
        // 5. Personil (for outsource workers: janitor, security, etc.)
        // =====================================================================
        Schema::create('personil', function (Blueprint $table) {
            $table->id('personil_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('org_unit_id')->nullable();
            $table->string('nama', 100);
            $table->string('email', 100)->nullable();
            $table->string('nip')->unique()->nullable();
            $table->string('jabatan')->nullable();
            $table->string('tipe', 30)->nullable()->comment('outsource, vendor_staff, etc.');
            $table->string('vendor')->nullable()->comment('Nama perusahaan vendor/penyedia');
            $table->string('ttd_digital', 191)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('org_unit_id')->references('orgunit_id')->on('struktur_organisasi')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('personil');
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('pegawai');
        Schema::dropIfExists('struktur_organisasi');
        Schema::enableForeignKeyConstraints();
    }
};
