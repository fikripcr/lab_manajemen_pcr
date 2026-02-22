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
        Schema::table('pemutu_indikator_pegawai', function (Blueprint $table) {
            $table->dropColumn(['sasaran', 'keterangan']);
            $table->string('unit_ukuran')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_indikator_pegawai', function (Blueprint $table) {
            if (Schema::hasColumn('pemutu_indikator_pegawai', 'unit_ukuran')) {
                $table->dropColumn('unit_ukuran');
            }
            if (! Schema::hasColumn('pemutu_indikator_pegawai', 'sasaran')) {
                $table->text('sasaran')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('pemutu_indikator_pegawai', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('sasaran');
            }
        });
    }
};
