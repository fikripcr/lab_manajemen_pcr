<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Rename surat_bebas_labs to lab_surat_bebas_labs
        if (Schema::hasTable('surat_bebas_labs') && ! Schema::hasTable('lab_surat_bebas_labs')) {
            Schema::rename('surat_bebas_labs', 'lab_surat_bebas_labs');
        }

        // 2. Rename periode_softrequest to lab_periode_softrequest
        if (Schema::hasTable('periode_softrequest') && ! Schema::hasTable('lab_periode_softrequest')) {
            Schema::rename('periode_softrequest', 'lab_periode_softrequest');
        }

        // 3. Rename lab_inventarises to lab_inventaris
        if (Schema::hasTable('lab_inventarises') && ! Schema::hasTable('lab_inventaris')) {
            Schema::rename('lab_inventarises', 'lab_inventaris');
        }

        // 4. Cleanup redundant empty non-prefixed tables
        $tablesToCleanup = [
            'inventaris',
            'jadwal_kuliah',
            'kegiatans',
            'laporan_kerusakan',
            'log_penggunaan_labs',
            'log_penggunaan_pcs',
            'mata_kuliahs',
            'pc_assignments',
            'request_software',
            'semesters',
            'pengumuman',
            'labs',
        ];

        foreach ($tablesToCleanup as $table) {
            if (Schema::hasTable($table) && Schema::hasTable('lab_' . $table)) {
                // Keep the one with data (we verified lab_ versions have the data or both are empty)
                Schema::drop($table);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse renames if needed, but cleanup (drop) is usually not reversed in this context
        // unless we want to recreate empty tables.
    }
};
