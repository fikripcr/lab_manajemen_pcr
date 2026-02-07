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
        if (! Schema::hasTable('label_types')) {
            Schema::create('label_types', function (Blueprint $table) {
                $table->id('labeltype_id');
                $table->string('name', 100);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | label
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('label')) {
            Schema::create('label', function (Blueprint $table) {
                $table->id('label_id');
                $table->unsignedBigInteger('type_id');
                $table->string('name', 100);
                $table->string('slug', 100)->nullable();
                $table->text('description')->nullable();

                $table->foreign('type_id')->references('labeltype_id')->on('label_types')->cascadeOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | org_unit
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('org_unit')) {
            Schema::create('org_unit', function (Blueprint $table) {
                $table->id('orgunit_id');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('name', 255);
                $table->string('type', 100)->nullable();
                $table->string('code', 50)->nullable();

                $table->foreign('parent_id')->references('orgunit_id')->on('org_unit')->nullOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | personil
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('personil')) {
            Schema::create('personil', function (Blueprint $table) {
                $table->id('personil_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('org_unit_id')->nullable();
                $table->string('nama', 100);
                $table->string('email', 100)->nullable();
                $table->string('ttd_digital', 250)->nullable();
                $table->string('jenis', 20)->nullable();
                $table->string('external_source', 50)->nullable();
                $table->string('external_id', 50)->nullable();

                $table->foreign('org_unit_id')->references('orgunit_id')->on('org_unit')->nullOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | dokumen
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('dokumen')) {
            Schema::create('dokumen', function (Blueprint $table) {
                $table->id('dok_id');
                $table->unsignedBigInteger('parent_doksub_id')->nullable();
                $table->enum('jenis', ['visi', 'misi', 'rjp', 'renstra', 'renop', 'standar', 'formulir', 'dll'])->nullable();
                $table->string('judul', 255);
                $table->string('kode', 20)->nullable();
                $table->integer('periode')->nullable();
                $table->date('tgl_berlaku')->nullable();
                $table->boolean('std_is_staging')->default(false);
                $table->string('std_amirtn_id', 20)->nullable();
                $table->unsignedBigInteger('std_jeniskriteria_id')->nullable();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | dok_sub
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('dok_sub')) {
            Schema::create('dok_sub', function (Blueprint $table) {
                $table->id('doksub_id');
                $table->unsignedBigInteger('dok_id');
                $table->string('judul', 150);
                $table->text('isi')->nullable();
                $table->integer('seq')->nullable();

                $table->foreign('dok_id')->references('dok_id')->on('dokumen')->cascadeOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | indikator
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('indikator')) {
            Schema::create('indikator', function (Blueprint $table) {
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

                $table->foreign('doksub_id')->references('doksub_id')->on('dok_sub')->nullOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | indikator_label (pivot)
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('indikator_label')) {
            Schema::create('indikator_label', function (Blueprint $table) {
                $table->id('indiklabel_id');
                $table->unsignedBigInteger('indikator_id');
                $table->unsignedBigInteger('label_id');

                $table->foreign('indikator_id')->references('indikator_id')->on('indikator')->cascadeOnDelete();
                $table->foreign('label_id')->references('label_id')->on('label')->cascadeOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | indikator_orgunit (pivot)
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('indikator_orgunit')) {
            Schema::create('indikator_orgunit', function (Blueprint $table) {
                $table->id('indikorgunit_id');
                $table->unsignedBigInteger('indikator_id');
                $table->unsignedBigInteger('org_unit_id');
                $table->dateTime('created_at')->nullable();

                $table->foreign('indikator_id')->references('indikator_id')->on('indikator')->cascadeOnDelete();
                $table->foreign('org_unit_id')->references('orgunit_id')->on('org_unit')->cascadeOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | indikator_doksub (pivot)
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('indikator_doksub')) {
            Schema::create('indikator_doksub', function (Blueprint $table) {
                $table->id('indikdoksub_id');
                $table->unsignedBigInteger('indikator_id');
                $table->unsignedBigInteger('doksub_id');
                $table->boolean('is_hasilkan_indikator')->default(false);

                $table->foreign('indikator_id')->references('indikator_id')->on('indikator')->cascadeOnDelete();
                $table->foreign('doksub_id')->references('doksub_id')->on('dok_sub')->cascadeOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | kpi_personil
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('kpi_personil')) {
            Schema::create('kpi_personil', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('personil_id');
                $table->unsignedBigInteger('indikator_id');
                $table->integer('year');
                $table->string('semester', 20);
                $table->decimal('weight', 5, 2)->nullable();
                $table->decimal('target_value', 10, 2)->nullable();

                $table->foreign('personil_id')->references('personil_id')->on('personil')->cascadeOnDelete();
                $table->foreign('indikator_id')->references('indikator_id')->on('indikator')->cascadeOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | dok_approval
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('dok_approval')) {
            Schema::create('dok_approval', function (Blueprint $table) {
                $table->id('dokapproval_id');
                $table->unsignedBigInteger('dok_id');
                $table->string('proses', 150)->nullable();
                $table->unsignedBigInteger('pegawai_id')->nullable();
                $table->unsignedBigInteger('pegawaiulaz_id')->nullable();
                $table->string('jabatan', 150)->nullable();

                $table->foreign('dok_id')->references('dok_id')->on('dokumen')->cascadeOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | dok_approval_status
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('dok_approval_status')) {
            Schema::create('dok_approval_status', function (Blueprint $table) {
                $table->id('dokstatusapproval_id');
                $table->unsignedBigInteger('dokapproval_id');
                $table->string('status_approval', 20);
                $table->text('komentar')->nullable();

                $table->foreign('dokapproval_id')->references('dokapproval_id')->on('dok_approval')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dok_approval_status');
        Schema::dropIfExists('dok_approval');
        Schema::dropIfExists('kpi_personil');
        Schema::dropIfExists('indikator_doksub');
        Schema::dropIfExists('indikator_orgunit');
        Schema::dropIfExists('indikator_label');
        Schema::dropIfExists('indikator');
        Schema::dropIfExists('dok_sub');
        Schema::dropIfExists('dokumen');
        Schema::dropIfExists('personil');
        Schema::dropIfExists('org_unit');
        Schema::dropIfExists('label');
        Schema::dropIfExists('label_types');
    }
};
