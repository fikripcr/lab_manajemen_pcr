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
        // 1. Rename existing lab_* tables to temporary or specific names to avoid initial conflicts
        // lab_inventaris (junction) -> lab_inventaris_penempatan
        if (Schema::hasTable('lab_inventaris')) {
            Schema::rename('lab_inventaris', 'lab_inventaris_penempatan');
        }

        // 2. Rename core tables
        Schema::rename('labs', 'lab_labs');
        Schema::rename('semesters', 'lab_semesters');
        Schema::rename('mata_kuliahs', 'lab_mata_kuliahs');
        Schema::rename('jadwal_kuliah', 'lab_jadwal_kuliah');
        Schema::rename('pc_assignments', 'lab_pc_assignments');
        Schema::rename('log_penggunaan_pcs', 'lab_log_penggunaan_pcs');
        Schema::rename('kegiatans', 'lab_kegiatans');
        Schema::rename('log_penggunaan_labs', 'lab_log_penggunaan_labs');
        Schema::rename('request_software', 'lab_request_software');
        Schema::rename('inventaris', 'lab_inventarises');
        Schema::rename('laporan_kerusakan', 'lab_laporan_kerusakan');
        Schema::rename('pengumuman', 'lab_pengumuman');

        // 3. Rename pivot tables
        if (Schema::hasTable('request_software_mata_kuliah')) {
            Schema::rename('request_software_mata_kuliah', 'lab_request_software_mata_kuliah');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse pivots
        if (Schema::hasTable('lab_request_software_mata_kuliah')) {
            Schema::rename('lab_request_software_mata_kuliah', 'request_software_mata_kuliah');
        }

        // Reverse core
        Schema::rename('lab_pengumuman', 'pengumuman');
        Schema::rename('lab_laporan_kerusakan', 'laporan_kerusakan');
        Schema::rename('lab_inventarises', 'inventaris');
        Schema::rename('lab_request_software', 'request_software');
        Schema::rename('lab_log_penggunaan_labs', 'log_penggunaan_labs');
        Schema::rename('lab_kegiatans', 'kegiatans');
        Schema::rename('lab_log_penggunaan_pcs', 'log_penggunaan_pcs');
        Schema::rename('lab_pc_assignments', 'pc_assignments');
        Schema::rename('lab_jadwal_kuliah', 'jadwal_kuliah');
        Schema::rename('lab_mata_kuliahs', 'mata_kuliahs');
        Schema::rename('lab_semesters', 'semesters');
        Schema::rename('lab_labs', 'labs');

        // Reverse specific conflict avoiding name
        if (Schema::hasTable('lab_inventaris_penempatan')) {
            Schema::rename('lab_inventaris_penempatan', 'lab_inventaris');
        }
    }
};
