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
        Schema::table('lab_media', function (Blueprint $table) {
            // Just drop the unnecessary columns - Laravel handles foreign key removal appropriately
            // Use try-catch to safely handle cases where columns might not exist anymore
            $columnsToDrop = ['gambar_path', 'urutan', 'is_active'];

            // Get current columns to check what exists
            $currentColumns = \Illuminate\Support\Facades\Schema::getColumnListing('lab_media');

            // Determine which columns actually exist before dropping
            $existingColumns = array_intersect($columnsToDrop, $currentColumns);

            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }

            // Check if media_id column already exists before adding it
            if (!\Illuminate\Support\Facades\Schema::hasColumn('lab_media', 'media_id')) {
                $table->unsignedBigInteger('media_id');
                $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_media', function (Blueprint $table) {
            // Drop foreign key constraints
            try {
                $table->dropForeign(['media_id']);
            } catch (\Exception $e) {
                // Foreign key might not exist, continue
            }

            // Drop media_id column
            $table->dropColumn('media_id');

            // Add back the old columns
            $table->string('gambar_path'); // Path to slideshow image/file
            $table->integer('urutan')->default(0); // Order of slideshow
            $table->boolean('is_active')->default(true);

            // Recreate the old foreign key constraint
            $table->foreign('lab_id')->references('lab_id')->on('labs')->onDelete('cascade');
        });
    }
};
