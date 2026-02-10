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
        Schema::create('pemutu_indikator_doksub', function (Blueprint $table) {
            $table->id('indikdoksub_id');
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedBigInteger('doksub_id');
            $table->boolean('is_hasilkan_indikator')->default(false);
            $table->timestamps();

            $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            $table->foreign('doksub_id')->references('doksub_id')->on('pemutu_dok_sub')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemutu_indikator_doksub');
    }
};
