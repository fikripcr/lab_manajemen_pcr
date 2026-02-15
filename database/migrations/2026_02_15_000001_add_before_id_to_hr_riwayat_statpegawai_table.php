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
        Schema::table('hr_riwayat_statpegawai', function (Blueprint $table) {
            if (! Schema::hasColumn('hr_riwayat_statpegawai', 'before_id')) {
                $table->uuid('before_id')->nullable()->after('pegawai_id')->index();

                // Optional: Add foreign key if strictly needed, but nullable/self-referencing logic might be handled in code.
                // $table->foreign('before_id')->references('riwayatstatpegawai_id')->on('hr_riwayat_statpegawai')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_riwayat_statpegawai', function (Blueprint $table) {
            $table->dropColumn('before_id');
        });
    }
};
