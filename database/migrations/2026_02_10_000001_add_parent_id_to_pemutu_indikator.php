<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemutu_indikator', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('indikator_id');
            $table->foreign('parent_id')->references('indikator_id')->on('pemutu_indikator')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pemutu_indikator', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
