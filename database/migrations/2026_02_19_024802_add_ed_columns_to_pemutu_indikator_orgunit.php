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
            $table->text('ed_capaian')->nullable()->after('target');
            $table->text('ed_analisis')->nullable()->after('ed_capaian');
            $table->string('ed_attachment')->nullable()->after('ed_analisis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_indikator_orgunit', function (Blueprint $table) {
            $table->dropColumn(['ed_capaian', 'ed_analisis', 'ed_attachment']);
        });
    }
};
