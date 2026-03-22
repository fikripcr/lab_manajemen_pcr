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
            $table->dropColumn('ed_attachment');
        });

        Schema::table('pemutu_indikator_pegawai', function (Blueprint $table) {
            $table->dropColumn('attachment');
        });

        Schema::table('pemutu_diskusi', function (Blueprint $table) {
            $table->dropColumn('attachment_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_indikator_orgunit', function (Blueprint $table) {
            $table->text('ed_attachment')->nullable();
        });

        Schema::table('pemutu_indikator_pegawai', function (Blueprint $table) {
            $table->text('attachment')->nullable();
        });

        Schema::table('pemutu_diskusi', function (Blueprint $table) {
            $table->text('attachment_file')->nullable();
        });
    }
};
