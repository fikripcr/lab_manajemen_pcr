<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogPenggunaanLabsTable extends Migration
{
    public function up()
    {
        Schema::create('log_penggunaan_labs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans', 'id');
            $table->string('nama_peserta'); // Untuk peserta umum
            $table->string('email_peserta')->nullable();
            $table->string('npm_peserta')->nullable(); // Jika civitas
            $table->integer('nomor_pc')->nullable();
            $table->text('kondisi')->nullable();
            $table->text('catatan_umum')->nullable();
            $table->dateTime('waktu_isi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_penggunaan_labs');
    }
}
