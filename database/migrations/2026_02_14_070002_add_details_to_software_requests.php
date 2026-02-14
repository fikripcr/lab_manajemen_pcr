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
        Schema::table('lab_request_software', function (Blueprint $table) {
            $table->string('versi', 50)->nullable()->after('nama_software');
            $table->string('url_download')->nullable()->after('versi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_request_software', function (Blueprint $table) {
            $table->dropColumn(['versi', 'url_download']);
        });
    }
};
