<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. pmb_pilihan_prodi: Add orgunit_id
        Schema::table('pmb_pilihan_prodi', function (Blueprint $table) {
            $table->unsignedBigInteger('orgunit_id')->nullable()->after('pendaftaran_id'); // Adjust position if needed
            $table->foreign('orgunit_id')->references('orgunit_id')->on('struktur_organisasi')->onDelete('set null');
        });

        // 2. pmb_pendaftaran: Add orgunit_diterima_id
        Schema::table('pmb_pendaftaran', function (Blueprint $table) {
            $table->unsignedBigInteger('orgunit_diterima_id')->nullable()->after('nim_final');
            $table->foreign('orgunit_diterima_id')->references('orgunit_id')->on('struktur_organisasi')->onDelete('set null');
        });

        // 3. Migrate Data

        // Helper function to find OrgUnit from Prodi ID
        $findOrgUnit = function ($prodiId) {
            $prodi = DB::table('pmb_prodi')->where('id', $prodiId)->first();
            if (! $prodi) {
                return null;
            }

            // Try Code
            $orgUnit = DB::table('struktur_organisasi')
                ->where('code', $prodi->kode_prodi)
                ->where('type', 'LIKE', '%Prodi%')
                ->first();

            // Try Name
            if (! $orgUnit) {
                $orgUnit = DB::table('struktur_organisasi')
                    ->where('name', 'LIKE', '%' . $prodi->nama_prodi . '%')
                    ->where('type', 'LIKE', '%Prodi%')
                    ->first();
            }
            return $orgUnit;
        };

        // Migrate pmb_pilihan_prodi
        $pilihan = DB::table('pmb_pilihan_prodi')->whereNotNull('prodi_id')->get();
        foreach ($pilihan as $p) {
            $orgUnit = $findOrgUnit($p->prodi_id);
            if ($orgUnit) {
                DB::table('pmb_pilihan_prodi')
                    ->where('id', $p->id)
                    ->update(['orgunit_id' => $orgUnit->orgunit_id]);
            }
        }

        // Migrate pmb_pendaftaran
        $pendaftaran = DB::table('pmb_pendaftaran')->whereNotNull('prodi_diterima_id')->get();
        foreach ($pendaftaran as $p) {
            $orgUnit = $findOrgUnit($p->prodi_diterima_id);
            if ($orgUnit) {
                DB::table('pmb_pendaftaran')
                    ->where('id', $p->id)
                    ->update(['orgunit_diterima_id' => $orgUnit->orgunit_id]);
            }
        }

        // 4. Drop columns (and constraints)
        Schema::table('pmb_pilihan_prodi', function (Blueprint $table) {
            // Drop FK first. Warning: Constraint name might vary.
            // Usually table_column_foreign.
            // If strictly needed, we can use Schema::hasColumn but dropForeign might throw if name invalid.
            // We assume standard naming convention or rely on try-catch block for robust migration?
            // Composer migration usually requires strictness.
            // Since we know earlier migration created it (or table creation), likely pmb_pilihan_prodi_prodi_id_foreign.
            $table->dropForeign(['prodi_id']);
            $table->dropColumn('prodi_id');
        });

        Schema::table('pmb_pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['prodi_diterima_id']);
            $table->dropColumn('prodi_diterima_id');
        });

        // 5. Drop FK on mahasiswa table if exists (to allow dropping pmb_prodi)
        if (Schema::hasTable('mahasiswa') && Schema::hasColumn('mahasiswa', 'prodi_id')) {
            Schema::table('mahasiswa', function (Blueprint $table) {
                $table->dropForeign(['prodi_id']);
            });
        }

        // 6. Drop pmb_prodi table
        Schema::dropIfExists('pmb_prodi');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreating pmb_prodi is complex as data is lost/merged.
        // We can create the table struct.
        Schema::create('pmb_prodi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_prodi', 10)->nullable();
            $table->string('nama_prodi');
            $table->string('fakultas')->nullable();
            $table->integer('kuota_umum')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('pmb_pilihan_prodi', function (Blueprint $table) {
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->foreign('prodi_id')->references('id')->on('pmb_prodi')->onDelete('set null');
        });

        Schema::table('pmb_pendaftaran', function (Blueprint $table) {
            $table->unsignedBigInteger('prodi_diterima_id')->nullable();
            $table->foreign('prodi_diterima_id')->references('id')->on('pmb_prodi')->onDelete('set null');
        });

        // Restore data? Impossible without backup of specific mapping.
    }
};
