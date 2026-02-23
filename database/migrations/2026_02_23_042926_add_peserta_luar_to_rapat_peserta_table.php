<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_rapat_peserta', function (Blueprint $table) {
            $table->string('nama_luar')->nullable()->after('jabatan')->comment('Nama peserta dari luar sistem (bukan user terdaftar)');
            $table->string('email_luar')->nullable()->after('nama_luar')->comment('Email peserta dari luar sistem');
        });
    }

    public function down(): void
    {
        Schema::table('event_rapat_peserta', function (Blueprint $table) {
            $table->dropColumn(['nama_luar', 'email_luar']);
        });
    }
};
