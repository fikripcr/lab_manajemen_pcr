<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestSoftwareTable extends Migration
{
    public function up()
    {
        Schema::create('request_software', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('users', 'id');
            $table->string('nama_software');
            $table->text('alasan');
            $table->enum('status', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_software');
    }
}
