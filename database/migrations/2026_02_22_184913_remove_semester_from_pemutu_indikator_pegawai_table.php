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
            $table->dropColumn('semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_indikator_pegawai', function (Blueprint $table) {
            $table->string('semester', 2)->nullable()->after('year');
        });
    }
};
