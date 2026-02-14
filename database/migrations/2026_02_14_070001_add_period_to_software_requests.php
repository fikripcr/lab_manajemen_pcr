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
            $table->unsignedBigInteger('periodsoftreq_id')->nullable()->after('request_software_id');
            $table->foreign('periodsoftreq_id')->references('periodsoftreq_id')->on('periode_softrequest')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_request_software', function (Blueprint $table) {
            $table->dropForeign(['periodsoftreq_id']);
            $table->dropColumn('periodsoftreq_id');
        });
    }
};
