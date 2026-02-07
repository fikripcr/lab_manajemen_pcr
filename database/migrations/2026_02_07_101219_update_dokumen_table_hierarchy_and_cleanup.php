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
        Schema::table('dokumen', function (Blueprint $table) {
            if (! Schema::hasColumn('dokumen', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('dok_id');
                $table->foreign('parent_id')->references('dok_id')->on('dokumen')->onDelete('restrict');
            }
            if (! Schema::hasColumn('dokumen', 'level')) {
                $table->integer('level')->default(1)->after('jenis');
            }
            if (! Schema::hasColumn('dokumen', 'seq')) {
                $table->integer('seq')->default(1)->after('level');
            }
            if (Schema::hasColumn('dokumen', 'tgl_berlaku')) {
                $table->dropColumn('tgl_berlaku');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dokumen', function (Blueprint $table) {
            $table->date('tgl_berlaku')->nullable();
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'level', 'seq']);
        });
    }
};
