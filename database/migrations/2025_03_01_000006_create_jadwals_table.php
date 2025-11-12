<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalsTable extends Migration
{
    public function up()
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id')->constrained('semesters', 'semester_id');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs', 'id');
            $table->foreignId('dosen_id')->constrained('users', 'id'); // Dosen (explicit reference to users.id)
            $table->string('hari'); // Senin, Selasa, ...
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwals');
    }
}
