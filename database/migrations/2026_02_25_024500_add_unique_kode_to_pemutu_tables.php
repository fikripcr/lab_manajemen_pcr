<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * NOTE: MySQL/MariaDB does not support partial unique indexes (WHERE clause).
     * In standard MySQL, multiple NULL values ARE allowed in a unique index, but
     * some MariaDB versions treat NULL as duplicate in unique index.
     *
     * Strategy: Enforce uniqueness at application layer via Request validation
     * (Rule::unique()->whereNotNull()). This migration only cleans empty strings
     * so they become NULL (not "" which could cause issues).
     */
    public function up(): void
    {
        // Convert empty string kode to NULL for clean data
        DB::statement("UPDATE pemutu_dokumen SET kode = NULL WHERE kode = ''");
        DB::statement("UPDATE pemutu_dok_sub SET kode = NULL WHERE kode = ''");
    }

    public function down(): void
    {
        // Nothing to revert for this data cleanup
    }
};
