<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarisTable extends Migration
{
    public function up()
    {
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs', 'lab_id');
            $table->string('nama_alat');
            $table->string('jenis_alat'); // AC, Loker, Meja, Switch, Router, dll
            $table->text('kondisi_terakhir')->nullable();
            $table->date('tanggal_pengecekan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventaris');
    }
}
