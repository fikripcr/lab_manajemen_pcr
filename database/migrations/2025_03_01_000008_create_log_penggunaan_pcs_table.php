<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogPenggunaanPcsTable extends Migration
{
    public function up()
    {
        Schema::create('log_penggunaan_pcs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pc_assignment_id')->constrained('pc_assignments', 'id');
            $table->foreignId('user_id')->constrained('users', 'id'); // Mahasiswa (explicit reference to users.id)
            $table->foreignId('jadwal_id')->constrained();
            $table->enum('status_pc', ['Baik', 'Rusak', 'Tidak Berfungsi']);
            $table->text('kondisi')->nullable();
            $table->text('catatan_umum')->nullable();
            $table->dateTime('waktu_isi'); // Waktu isi log
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_penggunaan_pcs');
    }
}
