<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestSoftwareMataKuliahPivotTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('request_software_mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_software_id')->constrained('request_software', 'id')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('request_software_mata_kuliah');
    }
}