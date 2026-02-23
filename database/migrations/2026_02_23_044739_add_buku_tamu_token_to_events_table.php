<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('buku_tamu_token', 64)->nullable()->unique()->after('deskripsi')
                ->comment('Token unik untuk generate link Buku Tamu publik');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('buku_tamu_token');
        });
    }
};
