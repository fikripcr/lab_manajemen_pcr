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
        Schema::table('survei_survei', function (Blueprint $table) {
            $table->enum('mode', ['Linear', 'Bercabang'])->default('Linear')->after('slug');
        });

        Schema::table('survei_pertanyaan', function (Blueprint $table) {
            $table->foreignId('next_pertanyaan_id')->nullable()->after('urutan')->constrained('survei_pertanyaan')->onDelete('set null');
        });

        Schema::table('survei_opsi', function (Blueprint $table) {
            $table->foreignId('next_pertanyaan_id')->nullable()->after('urutan')->constrained('survei_pertanyaan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survei_opsi', function (Blueprint $table) {
            $table->dropForeign(['next_pertanyaan_id']);
            $table->dropColumn('next_pertanyaan_id');
        });

        Schema::table('survei_pertanyaan', function (Blueprint $table) {
            $table->dropForeign(['next_pertanyaan_id']);
            $table->dropColumn('next_pertanyaan_id');
        });

        Schema::table('survei_survei', function (Blueprint $table) {
            $table->dropColumn('mode');
        });
    }
};
