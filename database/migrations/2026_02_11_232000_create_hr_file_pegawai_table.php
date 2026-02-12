<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hr_file_pegawai', function (Blueprint $table) {
            $table->id('filepegawai_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('jenisfile_id')->index();
            $table->text('keterangan')->nullable();

            // Blameable columns
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
            $table->foreign('jenisfile_id')->references('jenisfile_id')->on('hr_jenis_file')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_file_pegawai');
    }
};
