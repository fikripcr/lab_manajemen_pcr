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
        Schema::create('pemutu_periode_kpi', function (Blueprint $table) {
            $table->id('periode_kpi_id');
            $table->string('nama', 100); // e.g., "Ganjil 2024/2025"
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->string('tahun_akademik', 20); // e.g., "2024/2025"
            $table->integer('tahun');             // e.g., 2024 (for filtering)
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            // Blameable
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });

        // Add periode_kpi_id to pemutu_indikator_personil
        Schema::table('pemutu_indikator_personil', function (Blueprint $table) {
            $table->unsignedBigInteger('periode_kpi_id')->nullable()->after('indikator_id');
            $table->foreign('periode_kpi_id')->references('periode_kpi_id')->on('pemutu_periode_kpi')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemutu_indikator_personil', function (Blueprint $table) {
            $table->dropForeign(['periode_kpi_id']);
            $table->dropColumn('periode_kpi_id');
        });

        Schema::dropIfExists('pemutu_periode_kpi');
    }
};
