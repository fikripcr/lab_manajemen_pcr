<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename column while keeping auto_increment and key
        DB::statement('ALTER TABLE pmb_camaba CHANGE profilmahasiswa_id camaba_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE pmb_camaba CHANGE camaba_id profilmahasiswa_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }
};
