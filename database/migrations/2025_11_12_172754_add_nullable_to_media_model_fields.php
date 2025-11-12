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
        if (Schema::hasTable('media')) {
            // Drop existing morphs columns if they exist
            $columns = Schema::getColumnListing('media');
            if (in_array('model_type', $columns) && in_array('model_id', $columns)) {
                // If both columns exist and are non-nullable, we need to make them nullable
                // Since we can't directly change, let's recreate them
                Schema::table('media', function (Blueprint $table) {
                    $table->string('model_type')->nullable()->change();
                    $table->unsignedBigInteger('model_id')->nullable()->change();
                });
            } elseif (!in_array('model_type', $columns) && !in_array('model_id', $columns)) {
                // If morphs columns don't exist yet, add them as nullable  
                Schema::table('media', function (Blueprint $table) {
                    $table->string('model_type')->nullable();
                    $table->unsignedBigInteger('model_id')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['model_type', 'model_id']);
        });
    }
};