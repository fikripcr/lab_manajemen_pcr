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
        Schema::table('hr_riwayat_pendidikan', function (Blueprint $table) {
            $table->string('kotaasal_pt', 100)->nullable()->after('bidang_ilmu');
            $table->string('kodenegara_pt', 100)->nullable()->after('kotaasal_pt');
        });

        Schema::table('hr_keluarga', function (Blueprint $table) {
            $table->text('alamat')->nullable()->after('jenis_kelamin');
            $table->string('telp', 20)->nullable()->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_riwayat_pendidikan', function (Blueprint $table) {
            $table->dropColumn(['kotaasal_pt', 'kodenegara_pt']);
        });

        Schema::table('hr_keluarga', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'telp']);
        });
    }
};
