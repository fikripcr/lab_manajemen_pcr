<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemutu_dok_sub', function (Blueprint $table) {
            $table->string('kode', 50)->nullable()->after('judul');
        });
    }

    public function down(): void
    {
        Schema::table('pemutu_dok_sub', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
    }
};
