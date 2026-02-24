<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemutu_label_types', function (Blueprint $table) {
            $table->id('labeltype_id');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('color', 20)->default('blue');
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes(); // mass_sync added softDeletes too
        });

        Schema::create('pemutu_label', function (Blueprint $table) {
            $table->id('label_id');
            $table->unsignedBigInteger('type_id');
            $table->string('name', 100);
            $table->string('slug', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('color', 20)->nullable();
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('type_id')->references('labeltype_id')->on('pemutu_label_types')->cascadeOnDelete();
        });

        // NOTE: pemutu_org_unit moved to shared migration as 'struktur_organisasi'
        // NOTE: pemutu_personil moved to shared migration — pemutu now uses shared 'pegawai' table

        // Drop if exists to ensure order
        Schema::dropIfExists('pemutu_dok_sub');

        Schema::create('pemutu_dok_sub', function (Blueprint $table) {
            $table->id('doksub_id');
            $table->unsignedBigInteger('dok_id')->index();
            $table->string('judul', 191);
            $table->text('isi')->nullable();
            $table->integer('seq')->nullable();
            $table->boolean('is_hasilkan_indikator')->default(false);
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
        });

        Schema::create('pemutu_dokumen', function (Blueprint $table) {
            $table->id('dok_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('parent_doksub_id')->nullable();

            $table->enum('jenis', ['visi', 'misi', 'rjp', 'renstra', 'renop', 'standar', 'formulir', 'sop', 'manual_prosedur', 'dll'])->nullable();

            $table->integer('level')->default(1);
            $table->integer('seq')->default(1);
            $table->string('judul', 255);
            $table->longText('isi')->nullable();
            $table->string('kode', 50)->nullable();
            $table->integer('periode')->nullable();
            $table->boolean('std_is_staging')->default(false);
            $table->string('std_amirtn_id', 50)->nullable();
            $table->unsignedBigInteger('std_jeniskriteria_id')->nullable();
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('parent_id')->references('dok_id')->on('pemutu_dokumen')->onDelete('restrict');
            $table->foreign('parent_doksub_id')->references('doksub_id')->on('pemutu_dok_sub')->nullOnDelete();
        });

        // Add FK for dok_sub -> dokumen now that dokumen exists
        Schema::table('pemutu_dok_sub', function (Blueprint $table) {
            $table->foreign('dok_id')->references('dok_id')->on('pemutu_dokumen')->cascadeOnDelete();
        });

        // ---------------------------------------------------------------------
        // Period Tables
        // ---------------------------------------------------------------------
        Schema::create('pemutu_periode_spmi', function (Blueprint $table) {
            $table->id('periodespmi_id');
            $table->integer('periode');
            $table->string('jenis_periode', 20);
            $table->date('penetapan_awal')->nullable();
            $table->date('penetapan_akhir')->nullable();
            $table->date('ed_awal')->nullable();
            $table->date('ed_akhir')->nullable();
            $table->date('ami_awal')->nullable();
            $table->date('ami_akhir')->nullable();
            $table->date('pengendalian_awal')->nullable();
            $table->date('pengendalian_akhir')->nullable();
            $table->date('peningkatan_awal')->nullable();
            $table->date('peningkatan_akhir')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
        });

        Schema::create('pemutu_periode_kpi', function (Blueprint $table) {
            $table->id('periode_kpi_id');
            $table->string('nama', 100);
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->string('tahun_akademik', 20);
            $table->integer('tahun');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
        });

        Schema::create('pemutu_indikator', function (Blueprint $table) {
            $table->id('indikator_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->enum('type', ['renop', 'standar', 'performa'])->default('renop');
            $table->enum('kelompok_indikator', ['Akademik', 'Non Akademik'])->nullable();

            $table->string('no_indikator', 50)->nullable();
            $table->text('indikator')->nullable();
            $table->text('target')->nullable();
            $table->string('jenis_indikator', 30)->nullable();
            $table->string('jenis_data', 30)->nullable();
            $table->string('periode_jenis', 30)->nullable();
            $table->dateTime('periode_mulai')->nullable();
            $table->dateTime('periode_selesai')->nullable();
            $table->string('unit_ukuran', 50)->nullable();
            $table->string('level_risk', 20)->nullable();
            $table->string('origin_from', 30)->nullable();
            $table->string('hash', 100)->nullable();
            $table->integer('peningkat_nonaktif_indik')->nullable();
            $table->integer('is_new_indikator_after_peningkatan')->nullable();
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('parent_id')->references('indikator_id')->on('pemutu_indikator')->nullOnDelete();
        });

        Schema::create('pemutu_indikator_doksub', function (Blueprint $table) {
            $table->id('indikdoksub_id');
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedBigInteger('doksub_id');
            $table->boolean('is_hasilkan_indikator')->default(false);
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            $table->foreign('doksub_id')->references('doksub_id')->on('pemutu_dok_sub')->cascadeOnDelete();
        });

        Schema::create('pemutu_indikator_label', function (Blueprint $table) {
            $table->id('indiklabel_id');
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedBigInteger('label_id');
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            $table->foreign('label_id')->references('label_id')->on('pemutu_label')->cascadeOnDelete();
        });

        Schema::create('pemutu_indikator_orgunit', function (Blueprint $table) {
            $table->id('indikorgunit_id');
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedBigInteger('org_unit_id');
            $table->string('target', 255)->nullable();
            $table->text('ed_capaian')->nullable();
            $table->text('ed_analisis')->nullable();
            $table->string('ed_attachment')->nullable();
            $table->json('ed_links')->nullable();
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            $table->foreign('org_unit_id')->references('orgunit_id')->on('struktur_organisasi')->cascadeOnDelete();
        });

        Schema::create('pemutu_indikator_pegawai', function (Blueprint $table) {
            $table->id('indikator_pegawai_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedBigInteger('periode_kpi_id')->nullable();
            $table->integer('year');
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('target_value', 10, 2)->nullable();
            $table->text('realization')->nullable();
            $table->text('kpi_analisis')->nullable();
            $table->json('kpi_links')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->string('attachment')->nullable();
            $table->string('status', 20)->default('draft'); // draft, submitted, approved, rejected
            $table->text('notes')->nullable();
            $table->string('unit_ukuran')->nullable();
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('pegawai')->cascadeOnDelete();
            $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            $table->foreign('periode_kpi_id')->references('periode_kpi_id')->on('pemutu_periode_kpi')->onDelete('set null');
        });

        Schema::create('pemutu_dok_approval', function (Blueprint $table) {
            $table->id('dokapproval_id');
            $table->unsignedBigInteger('dok_id');
            $table->string('proses', 191)->nullable();
            $table->unsignedBigInteger('pegawai_id')->nullable();
            $table->string('jabatan', 191)->nullable();
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('dok_id')->references('dok_id')->on('pemutu_dokumen')->cascadeOnDelete();
        });

        Schema::create('pemutu_dok_approval_status', function (Blueprint $table) {
            $table->id('dokstatusapproval_id');
            $table->unsignedBigInteger('dokapproval_id');
            $table->string('status_approval', 50);
            $table->text('komentar')->nullable();
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('dokapproval_id')->references('dokapproval_id')->on('pemutu_dok_approval')->cascadeOnDelete();
        });

        Schema::create('pemutu_tim_mutu', function (Blueprint $table) {
            $table->id('tim_mutu_id');
            $table->unsignedBigInteger('periodespmi_id');
            $table->unsignedBigInteger('org_unit_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->enum('role', ['auditee', 'anggota', 'auditor', 'ketua_auditor'])->default('anggota');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('periodespmi_id')->references('periodespmi_id')->on('pemutu_periode_spmi')->cascadeOnDelete();
            $table->foreign('org_unit_id')->references('orgunit_id')->on('struktur_organisasi')->cascadeOnDelete();
            $table->foreign('pegawai_id')->references('pegawai_id')->on('pegawai')->cascadeOnDelete();

            $table->unique(['periodespmi_id', 'org_unit_id', 'pegawai_id', 'role'], 'tim_mutu_unique');
        });

        // Riwayat Approval for Pemutu
        Schema::create('pemutu_riwayat_approval', function (Blueprint $table) {
            $table->id('riwayatapproval_id');
            $table->string('model');
            $table->unsignedBigInteger('model_id');
            $table->enum('status', ['Draft', 'Pending', 'Approved', 'Rejected'])->default('Draft');
            $table->string('pejabat')->nullable();
            $table->string('jabatan')->nullable();
            $table->text('catatan')->nullable();
            $table->string('lampiran_url')->nullable();

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['model', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('pemutu_riwayat_approval');
        Schema::dropIfExists('pemutu_dok_approval_status');
        Schema::dropIfExists('pemutu_dok_approval');
        Schema::dropIfExists('pemutu_dok_approval'); // legacy table

        // pemutu_rapat tables removed
        Schema::dropIfExists('pemutu_periode_kpi');
        Schema::dropIfExists('pemutu_periode_spmi');
        Schema::dropIfExists('pemutu_indikator_pegawai');
        Schema::dropIfExists('pemutu_indikator_orgunit');
        Schema::dropIfExists('pemutu_indikator_label');
        Schema::dropIfExists('pemutu_indikator_doksub');
        Schema::dropIfExists('pemutu_indikator');
        Schema::dropIfExists('pemutu_dok_sub');
        Schema::dropIfExists('pemutu_dokumen');
        Schema::dropIfExists('pemutu_label');
        Schema::dropIfExists('pemutu_label_types');
        Schema::dropIfExists('pemutu_tim_mutu');

        Schema::enableForeignKeyConstraints();
    }
};
