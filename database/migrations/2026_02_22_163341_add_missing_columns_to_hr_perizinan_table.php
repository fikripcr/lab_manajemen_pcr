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
        Schema::table('hr_perizinan', function (Blueprint $table) {
            // Add missing columns for perizinan
            if (!Schema::hasColumn('hr_perizinan', 'alamat_izin')) {
                $table->text('alamat_izin')->nullable()->after('keterangan');
            }
            
            if (!Schema::hasColumn('hr_perizinan', 'jam_awal')) {
                $table->time('jam_awal')->nullable()->after('tgl_awal');
            }
            
            if (!Schema::hasColumn('hr_perizinan', 'jam_akhir')) {
                $table->time('jam_akhir')->nullable()->after('jam_awal');
            }
            
            if (!Schema::hasColumn('hr_perizinan', 'periode')) {
                $table->string('periode', 20)->nullable()->after('jam_akhir');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_perizinan', function (Blueprint $table) {
            $table->dropColumn(['alamat_izin', 'jam_awal', 'jam_akhir', 'periode']);
        });
    }
};
