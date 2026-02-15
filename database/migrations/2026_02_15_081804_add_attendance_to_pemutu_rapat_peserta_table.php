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
        Schema::table('pemutu_rapat_peserta', function (Blueprint $table) {
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa'])->nullable()->after('jabatan');
            $table->timestamp('waktu_hadir')->nullable()->after('status');
            $table->text('notes')->nullable()->after('waktu_hadir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_rapat_peserta', function (Blueprint $table) {
            $table->dropColumn(['status', 'waktu_hadir', 'notes']);
        });
    }
};
