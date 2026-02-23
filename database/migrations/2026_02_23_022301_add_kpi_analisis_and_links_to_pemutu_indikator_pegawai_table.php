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
        Schema::table('pemutu_indikator_pegawai', function (Blueprint $table) {
            if (! Schema::hasColumn('pemutu_indikator_pegawai', 'kpi_analisis')) {
                $table->text('kpi_analisis')->nullable()->after('realization');
            }
            if (! Schema::hasColumn('pemutu_indikator_pegawai', 'kpi_links')) {
                $table->json('kpi_links')->nullable()->after('kpi_analisis');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_indikator_pegawai', function (Blueprint $table) {
            if (Schema::hasColumn('pemutu_indikator_pegawai', 'kpi_analisis')) {
                $table->dropColumn('kpi_analisis');
            }
            if (Schema::hasColumn('pemutu_indikator_pegawai', 'kpi_links')) {
                $table->dropColumn('kpi_links');
            }
        });
    }
};
