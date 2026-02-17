<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->foreignId('prodi_id')->nullable()->after('email')->constrained('pmb_prodi');
            $table->dropColumn('program_studi');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->string('program_studi')->nullable()->after('email');
            $table->dropConstrainedForeignId('prodi_id');
        });
    }
};
