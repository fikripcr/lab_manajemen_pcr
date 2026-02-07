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
            $table->unsignedBigInteger('auditee_user_id')->nullable()->after('successor_id');
            $table->foreign('auditee_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('org_unit', function (Blueprint $table) {
            $table->dropForeign(['auditee_user_id']);
            $table->dropColumn('auditee_user_id');
        });
    }
};
