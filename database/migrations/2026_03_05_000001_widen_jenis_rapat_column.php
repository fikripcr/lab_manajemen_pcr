<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Widen jenis_rapat column to fit 'RTM Pengendalian'
        Schema::table('event_rapat', function (Blueprint $table) {
            $table->string('jenis_rapat', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('event_rapat', function (Blueprint $table) {
            $table->string('jenis_rapat', 20)->change();
        });
    }
};
