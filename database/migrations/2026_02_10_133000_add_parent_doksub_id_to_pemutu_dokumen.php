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
        Schema::table('pemutu_dokumen', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_doksub_id')->nullable()->after('parent_id');
            $table->foreign('parent_doksub_id')->references('doksub_id')->on('pemutu_dok_sub')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_dokumen', function (Blueprint $table) {
            $table->dropForeign(['parent_doksub_id']);
            $table->dropColumn('parent_doksub_id');
        });
    }
};
