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
        Schema::table('pmb_dokumen_upload', function (Blueprint $table) {
            $table->text('catatan_verifikasi')->nullable()->after('status_verifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pmb_dokumen_upload', function (Blueprint $table) {
            $table->dropColumn('catatan_verifikasi');
        });
    }
};
