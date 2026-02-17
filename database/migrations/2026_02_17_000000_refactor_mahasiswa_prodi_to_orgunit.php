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
        // 1. Add orgunit_id column
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->unsignedBigInteger('orgunit_id')->nullable()->after('email');
            $table->foreign('orgunit_id')->references('orgunit_id')->on('struktur_organisasi')->onDelete('set null');
        });

        // 2. Migrate Data
        $students = DB::table('mahasiswa')->whereNotNull('prodi_id')->get();
        foreach ($students as $student) {
            $prodi = DB::table('pmb_prodi')->where('id', $student->prodi_id)->first();

            if ($prodi) {
                // Try match by Code
                $orgUnit = DB::table('struktur_organisasi')
                    ->where('code', $prodi->kode_prodi)
                    ->where('type', 'LIKE', '%Prodi%') // Optional filter
                    ->first();

                // Fallback: Match by Name
                if (! $orgUnit) {
                    $orgUnit = DB::table('struktur_organisasi')
                        ->where('name', 'LIKE', '%' . $prodi->nama_prodi . '%')
                        ->where('type', 'LIKE', '%Prodi%')
                        ->first();
                }

                if ($orgUnit) {
                    DB::table('mahasiswa')
                        ->where('mahasiswa_id', $student->mahasiswa_id)
                        ->update(['orgunit_id' => $orgUnit->orgunit_id]);
                }
            }
        }

        // 3. Drop prodi_id column
        // We first drop foreign key constraint if it exists.
        // Step 325 added it. The constraint name is usually mahasiswa_prodi_id_foreign.
        Schema::table('mahasiswa', function (Blueprint $table) {
            // Check if FK exists or try-catch? Schema::hasColumn logic usually implies FK standard naming.
            $table->dropForeign(['prodi_id']);
            $table->dropColumn('prodi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->foreign('prodi_id')->references('id')->on('pmb_prodi')->onDelete('set null');
        });

        // Restore data (Reverse mapping is hard without keeping prodi_id, checking orgunit code/name back to pmb_prodi)
        // ... simplistic restore
    }
};
