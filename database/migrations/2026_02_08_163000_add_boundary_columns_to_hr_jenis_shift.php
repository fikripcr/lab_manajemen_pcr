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
        Schema::table('hr_jenis_shift', function (Blueprint $table) {
            $table->time('jam_masuk_awal')->nullable()->after('jam_masuk');
            $table->time('jam_masuk_akhir')->nullable()->after('jam_masuk_awal');
            $table->time('jam_pulang_awal')->nullable()->after('jam_pulang');
            $table->time('jam_pulang_akhir')->nullable()->after('jam_pulang_awal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_jenis_shift', function (Blueprint $table) {
            $table->dropColumn(['jam_masuk_awal', 'jam_masuk_akhir', 'jam_pulang_awal', 'jam_pulang_akhir']);
        });
    }
};
