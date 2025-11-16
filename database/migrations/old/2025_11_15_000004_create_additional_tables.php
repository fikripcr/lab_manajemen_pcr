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
        // Create media table
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size');
            $table->string('collection_name');
            $table->morphs('model_type');
            $table->string('model_id');
            $table->json('custom_properties');
            $table->timestamps();
        });

        // Create pengumuman table
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id('pengumuman_id');
            $table->unsignedBigInteger('penulis_id');
            $table->string('judul');
            $table->text('isi');
            $table->string('jenis'); // 'pengumuman' or 'artikel_berita'
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->foreign('penulis_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Create lab_media table (renamed from lab_slideshows)
        Schema::create('lab_media', function (Blueprint $table) {
            $table->id('lab_media_id');
            $table->foreignId('lab_id')->constrained('labs', 'lab_id');
            $table->string('judul');
            $table->string('deskripsi')->nullable();
            $table->string('media_path');
            $table->string('jenis_media'); // image, video, pdf, etc.
            $table->timestamps();
        });

        // Create request_software_mata_kuliah pivot table
        Schema::create('request_software_mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_software_id')->constrained('request_software', 'request_software_id')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs', 'mata_kuliah_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_software_mata_kuliah');
        Schema::dropIfExists('lab_media');
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('media');
    }
};
