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
        // 1. Refactor pemutu_indikator
        Schema::table('pemutu_indikator', function (Blueprint $table) {
            $table->enum('type', ['renop', 'standar', 'performa'])->default('renop')->after('indikator_id');

            // Note: We'll keep doksub_id temporarily if data needs migration,
            // but the user wants to remove it. Let's drop the foreign key and column.
            $table->dropForeign(['doksub_id']);
            $table->dropColumn('doksub_id');
        });

        // 2. Rename pemutu_kpi_personil to pemutu_indikator_personil
        Schema::rename('pemutu_kpi_personil', 'pemutu_indikator_personil');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('pemutu_indikator_personil', 'pemutu_kpi_personil');

        Schema::table('pemutu_indikator', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->unsignedBigInteger('doksub_id')->nullable()->after('indikator_id');
            $table->foreign('doksub_id')->references('doksub_id')->on('pemutu_dok_sub')->nullOnDelete();
        });
    }
};
