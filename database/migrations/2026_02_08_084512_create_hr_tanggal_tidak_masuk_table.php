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
        Schema::create('hr_tanggal_tidak_masuk', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->index();
            $table->integer('tahun'); // Can be derived, but explicit is good for queries
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_tanggal_tidak_masuk');
    }
};
