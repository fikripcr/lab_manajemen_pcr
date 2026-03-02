<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'hr_riwayat_datadiri',
            'hr_riwayat_pendidikan',
            'hr_riwayat_stataktifitas',
            'hr_riwayat_jabfungsional',
            'hr_riwayat_jabstruktural',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && ! Schema::hasColumn($tableName, 'before_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedBigInteger('before_id')->nullable()->after('pegawai_id')->index();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'hr_riwayat_datadiri',
            'hr_riwayat_pendidikan',
            'hr_riwayat_stataktifitas',
            'hr_riwayat_jabfungsional',
            'hr_riwayat_jabstruktural',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'before_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('before_id');
                });
            }
        }
    }
};
