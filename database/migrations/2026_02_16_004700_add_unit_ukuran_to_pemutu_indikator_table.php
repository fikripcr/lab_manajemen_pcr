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
            if (! Schema::hasColumn('pemutu_indikator', 'unit_ukuran')) {
                $table->string('unit_ukuran', 50)->nullable()->after('target');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_indikator', function (Blueprint $table) {
            $table->dropColumn('unit_ukuran');
        });
    }
};
