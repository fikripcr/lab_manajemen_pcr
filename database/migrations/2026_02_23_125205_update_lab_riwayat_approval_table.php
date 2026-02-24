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
        Schema::table('lab_riwayat_approval', function (Blueprint $table) {
            $table->string('lampiran_url')->nullable()->after('keterangan');
            $table->renameColumn('keterangan', 'catatan');

            // Change audit columns to string for BlameableName trait
            $table->string('created_by')->nullable()->change();
            $table->string('updated_by')->nullable()->change();
            $table->string('deleted_by')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('lab_riwayat_approval', function (Blueprint $table) {
            $table->renameColumn('catatan', 'keterangan');
            $table->dropColumn('lampiran_url');

            $table->unsignedBigInteger('created_by')->nullable()->change();
            $table->unsignedBigInteger('updated_by')->nullable()->change();
            $table->unsignedBigInteger('deleted_by')->nullable()->change();
        });
    }
};
