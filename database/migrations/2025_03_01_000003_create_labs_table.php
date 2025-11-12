<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabsTable extends Migration
{
    public function up()
    {
        Schema::create('labs', function (Blueprint $table) {
            $table->id('lab_id');
            $table->string('name');
            $table->string('location')->nullable();
            $table->integer('capacity')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('labs');
    }
}
