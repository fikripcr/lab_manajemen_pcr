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
        Schema::table('lab_kegiatans', function (Blueprint $table) {
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable()->after('status');
            $table->foreign('latest_riwayatapproval_id', 'fk_lab_keg_approval')
                ->references('riwayatapproval_id')->on('lab_riwayat_approval')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_kegiatans', function (Blueprint $table) {
            $table->dropForeign('fk_lab_keg_approval');
            $table->dropColumn('latest_riwayatapproval_id');
        });
    }
};
