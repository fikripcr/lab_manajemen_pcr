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
        Schema::table('hr_riwayat_inpassing', function (Blueprint $table) {
            $table->unsignedBigInteger('before_id')->nullable()->after('pegawai_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_riwayat_inpassing', function (Blueprint $table) {
            $table->dropColumn('before_id');
        });
    }
};
