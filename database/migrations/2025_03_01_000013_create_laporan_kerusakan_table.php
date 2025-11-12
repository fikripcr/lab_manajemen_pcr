<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanKerusakanTable extends Migration
{
    public function up()
    {
        Schema::create('laporan_kerusakan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaris_id')->constrained('inventaris', 'id');
            $table->foreignId('teknisi_id')->nullable()->constrained('users', 'id');
            $table->text('deskripsi_kerusakan');
            $table->enum('status', ['No Status', 'In Progress', 'Done'])->default('No Status');
            $table->text('catatan_perbaikan')->nullable();
            $table->string('foto_sebelum')->nullable();
            $table->string('foto_sesudah')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_kerusakan');
    }
}
