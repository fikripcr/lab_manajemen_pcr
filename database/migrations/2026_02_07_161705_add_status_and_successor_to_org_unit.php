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
        Schema::table('org_unit', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('seq');
            $table->unsignedBigInteger('successor_id')->nullable()->after('is_active');
            $table->foreign('successor_id')->references('orgunit_id')->on('org_unit')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('org_unit', function (Blueprint $table) {
            $table->dropForeign(['successor_id']);
            $table->dropColumn(['is_active', 'successor_id']);
        });
    }
};
