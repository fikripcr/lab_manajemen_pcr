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
        // Check if media table exists to avoid duplicate migration errors
        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->string('file_name');
                $table->string('file_path');
                $table->string('mime_type');
                $table->unsignedBigInteger('file_size');
                $table->string('collection_name'); // e.g., 'info_cover', 'info_attachment'
                $table->morphs('model'); // Allows association with any model
                $table->json('custom_properties')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};