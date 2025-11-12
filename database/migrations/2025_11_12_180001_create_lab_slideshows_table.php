<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabSlideshowsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lab_slideshows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs', 'lab_id');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('gambar_path'); // Path to slideshow image/file
            $table->integer('urutan')->default(0); // Order of slideshow
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('lab_slideshows');
    }
}