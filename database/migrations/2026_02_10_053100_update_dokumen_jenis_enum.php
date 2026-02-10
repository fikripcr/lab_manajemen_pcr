<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update existing 'dll' to 'manual_prosedur'
        DB::table('pemutu_dokumen')->where('jenis', 'dll')->update(['jenis' => 'manual_prosedur']);

        // Since SQLite/Certain DBs don't support modifying enum easily,
        // we normally use a temp column or just rely on the fact that 'manual_prosedur' and 'sop'
        // will be added to the code logic.
        // For MySQL/Postgres:
        // Schema::table('pemutu_dokumen', function (Blueprint $table) {
        //     $table->enum('jenis', ['visi', 'misi', 'rjp', 'renstra', 'renop', 'standar', 'formulir', 'sop', 'manual_prosedur'])->change();
        // });

        // However, changing enum is tricky. A common robust way is:
        // 1. Rename dll to manual_prosedur in data (done above)
        // 2. The code will handle the new values.

        // Let's try to update the enum definition if possible.
        try {
            DB::statement("ALTER TABLE pemutu_dokumen MODIFY COLUMN jenis ENUM('visi', 'misi', 'rjp', 'renstra', 'renop', 'standar', 'formulir', 'sop', 'manual_prosedur')");
        } catch (\Exception $e) {
            // Fallback for drivers that don't support MODIFY COLUMN (like SQLite in tests)
            // In Laravel, we can sometimes use ->change() with doctrine/dbal but enums are special.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pemutu_dokumen')->where('jenis', 'manual_prosedur')->update(['jenis' => 'dll']);

        try {
            DB::statement("ALTER TABLE pemutu_dokumen MODIFY COLUMN jenis ENUM('visi', 'misi', 'rjp', 'renstra', 'renop', 'standar', 'formulir', 'dll')");
        } catch (\Exception $e) {
            //
        }
    }
};
