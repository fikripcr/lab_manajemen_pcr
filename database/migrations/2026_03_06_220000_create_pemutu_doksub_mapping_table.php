<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemutu_doksub_mapping', function (Blueprint $table) {
            $table->id('doksub_mapping_id');
            $table->unsignedBigInteger('doksub_id');        // Source poin (e.g. M1)
            $table->unsignedBigInteger('mapped_doksub_id'); // Target poin (e.g. V1)
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

            $table->foreign('doksub_id')
                ->references('doksub_id')
                ->on('pemutu_dok_sub')
                ->cascadeOnDelete();

            $table->foreign('mapped_doksub_id')
                ->references('doksub_id')
                ->on('pemutu_dok_sub')
                ->cascadeOnDelete();

            $table->unique(['doksub_id', 'mapped_doksub_id'], 'doksub_mapping_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemutu_doksub_mapping');
    }
};
