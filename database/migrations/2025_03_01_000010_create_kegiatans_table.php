<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatansTable extends Migration
{
    public function up()
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs', 'lab_id');
            $table->foreignId('penyelenggara_id')->constrained('users', 'id'); // Penyelenggara (explicit reference to users.id)
            $table->string('nama_kegiatan');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending');
            $table->text('catatan_pic')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->string('berita_acara_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kegiatans');
    }
}
