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
        Schema::table('pemutu_indikator', function (Blueprint $table) {
            // Remove unused columns
            $table->dropColumn([
                'jenis_indikator',
                'periode_jenis',
                'periode_mulai',
                'periode_selesai',
            ]);

            // jenis_data is already there, but we might want to ensure it's a nullable string
            // $table->string('jenis_data', 30)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_indikator', function (Blueprint $table) {
            $table->string('jenis_indikator', 30)->nullable();
            $table->string('periode_jenis', 30)->nullable();
            $table->dateTime('periode_mulai')->nullable();
            $table->dateTime('periode_selesai')->nullable();
        });
    }
};
