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
        Schema::table('pemutu_dok_sub', function (Blueprint $table) {
            // Add jenis column to store poin type
            $table->string('jenis', 50)->nullable()->after('dok_id')
                ->comment('poin_visi, poin_misi, poin_rjp, poin_renstra, poin_renop, standar, manual_prosedur, formulir');

            // Add index for better query performance
            $table->index('jenis', 'idx_doksub_jenis');
        });

        // Backfill existing data
        DB::statement("
            UPDATE pemutu_dok_sub ds
            JOIN pemutu_dokumen d ON ds.dok_id = d.dok_id
            SET ds.jenis = CONCAT('poin_', d.jenis)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_dok_sub', function (Blueprint $table) {
            $table->dropIndex('idx_doksub_jenis');
            $table->dropColumn('jenis');
        });
    }
};
