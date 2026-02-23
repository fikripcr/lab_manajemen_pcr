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
        Schema::table('pemutu_indikator_orgunit', function (Blueprint $table) {
            $table->json('ed_links')->nullable()->after('ed_attachment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_indikator_orgunit', function (Blueprint $table) {
            $table->dropColumn('ed_links');
        });
    }
};
