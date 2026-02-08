<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | label_types
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('pemutu_label_types')) {
            Schema::create('pemutu_label_types', function (Blueprint $table) {
                $table->id('labeltype_id');
                $table->string('name', 100);
                $table->text('description')->nullable();
                $table->string('color', 20)->default('blue'); // Added from 2026_02_07_145850
                $table->timestamps();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | label
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('pemutu_label')) {
            Schema::create('pemutu_label', function (Blueprint $table) {
                $table->id('label_id');
                $table->unsignedBigInteger('type_id');
                $table->string('name', 100);
                $table->string('slug', 100)->nullable();
                $table->text('description')->nullable();

                $table->foreign('type_id')->references('labeltype_id')->on('pemutu_label_types')->cascadeOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | org_unit
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('pemutu_org_unit')) {
            Schema::create('pemutu_org_unit', function (Blueprint $table) {
                $table->id('orgunit_id');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('name', 255);
                $table->string('type', 100)->nullable();

                                                                           // Added from multiple migrations:
                $table->integer('level')->default(1);                      // 2026_02_07_100112
                $table->integer('seq')->default(1);                        // 2026_02_07_100112
                $table->boolean('is_active')->default(true);               // 2026_02_07_161705
                $table->unsignedBigInteger('successor_id')->nullable();    // 2026_02_07_161705
                $table->unsignedBigInteger('auditee_user_id')->nullable(); // 2026_02_07_162559

                $table->string('code', 50)->nullable();

                $table->foreign('parent_id')->references('orgunit_id')->on('pemutu_org_unit')->nullOnDelete();
                $table->foreign('successor_id')->references('orgunit_id')->on('pemutu_org_unit')->nullOnDelete();
                $table->foreign('auditee_user_id')->references('id')->on('users')->nullOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | personil
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('pemutu_personil')) {
            Schema::create('pemutu_personil', function (Blueprint $table) {
                $table->id('personil_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('org_unit_id')->nullable();
                $table->string('nama', 100);
                $table->string('email', 100)->nullable();
                $table->string('ttd_digital', 250)->nullable();
                $table->string('jenis', 20)->nullable();
                $table->string('external_source', 50)->nullable();
                $table->string('external_id', 50)->nullable();

                $table->foreign('org_unit_id')->references('orgunit_id')->on('pemutu_org_unit')->nullOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | dokumen
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('pemutu_dokumen')) {
            Schema::create('pemutu_dokumen', function (Blueprint $table) {
                $table->id('dok_id');
                $table->unsignedBigInteger('parent_id')->nullable(); // Added from 2026_02_07_101219
                $table->unsignedBigInteger('parent_doksub_id')->nullable();
                $table->enum('jenis', ['visi', 'misi', 'rjp', 'renstra', 'renop', 'standar', 'formulir', 'dll'])->nullable();

                // Added from 2026_02_07_101219
                $table->integer('level')->default(1);
                $table->integer('seq')->default(1);

                $table->string('judul', 255);
                $table->string('kode', 20)->nullable();
                $table->integer('periode')->nullable();
                // $table->date('tgl_berlaku')->nullable(); // Removed in 2026_02_07_101219
                $table->boolean('std_is_staging')->default(false);
                $table->string('std_amirtn_id', 20)->nullable();
                $table->unsignedBigInteger('std_jeniskriteria_id')->nullable();

                $table->foreign('parent_id')->references('dok_id')->on('pemutu_dokumen')->onDelete('restrict');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | dok_sub
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('pemutu_dok_sub')) {
            Schema::create('pemutu_dok_sub', function (Blueprint $table) {
                $table->id('doksub_id');
                $table->unsignedBigInteger('dok_id');
                $table->string('judul', 150);
                $table->text('isi')->nullable();
                $table->integer('seq')->nullable();

                $table->foreign('dok_id')->references('dok_id')->on('pemutu_dokumen')->cascadeOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | indikator
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('pemutu_indikator')) {
            Schema::create('pemutu_indikator', function (Blueprint $table) {
                $table->id('indikator_id');
                $table->unsignedBigInteger('doksub_id')->nullable();
                $table->string('no_indikator', 20)->nullable();
                $table->text('indikator')->nullable();
                $table->text('target')->nullable();
                $table->string('jenis_indikator', 20)->nullable();
                $table->string('jenis_data', 15)->nullable();
                $table->string('periode_jenis', 15)->nullable();
                $table->dateTime('periode_mulai')->nullable();
                $table->dateTime('periode_selesai')->nullable();
                $table->text('keterangan')->nullable();
                $table->integer('seq')->nullable();
                $table->string('level_risk', 10)->nullable();
                $table->string('origin_from', 20)->nullable();
                $table->string('hash', 100)->nullable();
                $table->integer('peningkat_nonaktif_indik')->nullable();
                $table->integer('is_new_indik_after_peningkatan')->nullable();

                $table->foreign('doksub_id')->references('doksub_id')->on('pemutu_dok_sub')->nullOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | indikator_label (pivot)
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('pemutu_indikator_label')) {
            Schema::create('pemutu_indikator_label', function (Blueprint $table) {
                $table->id('indiklabel_id');
                $table->unsignedBigInteger('indikator_id');
                $table->unsignedBigInteger('label_id');

                $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
                $table->foreign('label_id')->references('label_id')->on('pemutu_label')->cascadeOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | indikator_orgunit (pivot)
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('pemutu_indikator_orgunit')) {
            Schema::create('pemutu_indikator_orgunit', function (Blueprint $table) {
                $table->id('indikorgunit_id');
                $table->unsignedBigInteger('indikator_id');
                $table->unsignedBigInteger('org_unit_id');
                $table->string('target', 255)->nullable(); // Added from 2026_02_07_210000
                $table->dateTime('created_at')->nullable();

                $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
                $table->foreign('org_unit_id')->references('orgunit_id')->on('pemutu_org_unit')->cascadeOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | indikator_doksub (pivot)
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('pemutu_indikator_doksub')) {
            Schema::create('pemutu_indikator_doksub', function (Blueprint $table) {
                $table->id('indikdoksub_id');
                $table->unsignedBigInteger('indikator_id');
                $table->unsignedBigInteger('doksub_id');
                $table->boolean('is_hasilkan_indikator')->default(false);

                $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
                $table->foreign('doksub_id')->references('doksub_id')->on('pemutu_dok_sub')->cascadeOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | kpi_personil
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('pemutu_kpi_personil')) {
            Schema::create('pemutu_kpi_personil', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('personil_id');
                $table->unsignedBigInteger('indikator_id');
                $table->integer('year');
                $table->string('semester', 20);
                $table->decimal('weight', 5, 2)->nullable();
                $table->decimal('target_value', 10, 2)->nullable();

                $table->foreign('personil_id')->references('personil_id')->on('pemutu_personil')->cascadeOnDelete();
                $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | dok_approval
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('pemutu_dok_approval')) {
            Schema::create('pemutu_dok_approval', function (Blueprint $table) {
                $table->id('dokapproval_id');
                $table->unsignedBigInteger('dok_id');
                $table->string('proses', 150)->nullable();
                $table->unsignedBigInteger('pegawai_id')->nullable();
                $table->unsignedBigInteger('pegawaiulaz_id')->nullable();
                $table->string('jabatan', 150)->nullable();

                $table->foreign('dok_id')->references('dok_id')->on('pemutu_dokumen')->cascadeOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | dok_approval_status
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('pemutu_dok_approval_status')) {
            Schema::create('pemutu_dok_approval_status', function (Blueprint $table) {
                $table->id('dokstatusapproval_id');
                $table->unsignedBigInteger('dokapproval_id');
                $table->string('status_approval', 20);
                $table->text('komentar')->nullable();

                $table->foreign('dokapproval_id')->references('dokapproval_id')->on('pemutu_dok_approval')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        // Add disable foreign key constraints to be safe
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('pemutu_dok_approval_status');
        Schema::dropIfExists('pemutu_dok_approval');
        Schema::dropIfExists('pemutu_kpi_personil');
        Schema::dropIfExists('pemutu_indikator_doksub');
        Schema::dropIfExists('pemutu_indikator_orgunit');
        Schema::dropIfExists('pemutu_indikator_label');
        Schema::dropIfExists('pemutu_indikator');
        Schema::dropIfExists('pemutu_dok_sub');
        Schema::dropIfExists('pemutu_dokumen');
        Schema::dropIfExists('pemutu_personil');
        Schema::dropIfExists('pemutu_org_unit');
        Schema::dropIfExists('pemutu_label');
        Schema::dropIfExists('pemutu_label_types');

        Schema::enableForeignKeyConstraints();
    }
};
