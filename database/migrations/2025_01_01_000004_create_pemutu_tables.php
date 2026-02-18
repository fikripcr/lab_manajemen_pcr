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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes(); // mass_sync added softDeletes too

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('type_id')->references('labeltype_id')->on('pemutu_label_types')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('pemutu_indikator', function (Blueprint $table) {
            $table->id('indikator_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->enum('type', ['renop', 'standar', 'performa'])->default('renop');

            $table->string('no_indikator', 50)->nullable();
            $table->text('indikator')->nullable();
            $table->text('target')->nullable();
            $table->string('jenis_indikator', 30)->nullable();
            $table->string('jenis_data', 30)->nullable();
            $table->string('periode_jenis', 30)->nullable();
            $table->dateTime('periode_mulai')->nullable();
            $table->dateTime('periode_selesai')->nullable();
            $table->string('unit_ukuran', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('seq')->nullable();
            $table->string('level_risk', 20)->nullable();
            $table->string('origin_from', 30)->nullable();
            $table->string('hash', 100)->nullable();
            $table->integer('peningkat_nonaktif_indik')->nullable();
            $table->integer('is_new_indik_after_peningkatan')->nullable();
            $table->timestamps();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('parent_id')->references('indikator_id')->on('pemutu_indikator')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('pemutu_indikator_doksub', function (Blueprint $table) {
            $table->id('indikdoksub_id');
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedBigInteger('doksub_id');
            $table->boolean('is_hasilkan_indikator')->default(false);
            $table->timestamps();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            $table->foreign('label_id')->references('label_id')->on('pemutu_label')->cascadeOnDelete();
        });

        Schema::create('pemutu_indikator_orgunit', function (Blueprint $table) {
            $table->id('indikorgunit_id');
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedBigInteger('org_unit_id');
            $table->string('target', 255)->nullable();
            $table->timestamps();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            $table->foreign('org_unit_id')->references('orgunit_id')->on('struktur_organisasi')->cascadeOnDelete();
        });

        Schema::create('pemutu_indikator_pegawai', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedBigInteger('periode_kpi_id')->nullable();
            $table->integer('year');
            $table->string('semester', 20);
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('target_value', 10, 2)->nullable();
            $table->text('realization')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->string('attachment')->nullable();
            $table->string('status', 20)->default('draft'); // draft, submitted, approved, rejected
            $table->text('notes')->nullable();
            $table->timestamps();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('dokapproval_id')->references('dokapproval_id')->on('pemutu_dok_approval')->cascadeOnDelete();
        });

        // Rapat tables moved to Event module (create_event_tables.php)
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('pemutu_dok_approval_status');
        Schema::dropIfExists('pemutu_dok_approval');

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
        // pemutu_personil and pemutu_org_unit now in shared migration
        Schema::dropIfExists('pemutu_label');
        Schema::dropIfExists('pemutu_label_types');
        Schema::enableForeignKeyConstraints();
    }
};
