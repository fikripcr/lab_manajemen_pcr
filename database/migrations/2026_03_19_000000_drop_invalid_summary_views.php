<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop invalid views temporarily
        DB::statement('DROP VIEW IF EXISTS vw_pemutu_summary_indikator_standar');
        DB::statement('DROP VIEW IF EXISTS vw_pemutu_summary_indikator');
    }

    public function down(): void
    {
        // Views will be recreated in future migration
    }
};
