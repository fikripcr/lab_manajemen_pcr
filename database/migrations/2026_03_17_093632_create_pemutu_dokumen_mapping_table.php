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
        Schema::create('pemutu_dokumen_mapping', function (Blueprint $table) {
            $table->id('dokumen_mapping_id');
            $table->unsignedBigInteger('source_dok_id')->index();
            $table->unsignedBigInteger('target_dok_id')->index();
            $table->timestamps();

            $table->foreign('source_dok_id')->references('dok_id')->on('pemutu_dokumen')->cascadeOnDelete();
            $table->foreign('target_dok_id')->references('dok_id')->on('pemutu_dokumen')->cascadeOnDelete();

            $table->unique(['source_dok_id', 'target_dok_id'], 'pemutu_dokumen_mapping_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemutu_dokumen_mapping');
    }
};
