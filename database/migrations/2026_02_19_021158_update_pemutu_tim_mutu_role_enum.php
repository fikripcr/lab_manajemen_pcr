<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE pemutu_tim_mutu MODIFY COLUMN role ENUM('auditee', 'anggota', 'auditor', 'ketua_auditor') NOT NULL DEFAULT 'anggota'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum if possible. Note: data with new roles might cause issues if not handled.
        // We will just leave it or try to revert if sure no data exists.
        // For safety in dev:
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE pemutu_tim_mutu MODIFY COLUMN role ENUM('auditee', 'anggota') NOT NULL DEFAULT 'anggota'");
    }
};
