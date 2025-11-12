<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePcAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('pc_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id'); // Mahasiswa (explicit reference to users.id)
            $table->foreignId('jadwal_id')->constrained();
            $table->integer('nomor_pc');
            $table->integer('nomor_loker')->nullable();
            $table->date('assigned_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pc_assignments');
    }
}
