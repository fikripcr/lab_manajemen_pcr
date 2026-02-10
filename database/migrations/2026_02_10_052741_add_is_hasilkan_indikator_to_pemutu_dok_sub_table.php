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
        Schema::table('pemutu_dok_sub', function (Blueprint $table) {
            $table->boolean('is_hasilkan_indikator')->default(false)->after('seq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_dok_sub', function (Blueprint $table) {
            $table->dropColumn('is_hasilkan_indikator');
        });
    }
};
