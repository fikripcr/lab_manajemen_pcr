<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for logging and activity tables.
     */
    public function up(): void
    {
        // Create the sys_notifications table with the same structure as notifications
        if (!Schema::hasTable('sys_notifications')) {
            Schema::create('sys_notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // Jadwal Kuliah table
        if (!Schema::hasTable('jadwal_kuliah')) {
            Schema::create('jadwal_kuliah', function (Blueprint $table) {
                $table->char('jadwal_kuliah_id', 36)->primary();
                $table->char('mata_kuliah_id', 36);
                $table->char('dosen_id', 36);
                $table->char('lab_id', 36);
                $table->char('semester_id', 36);
                $table->string('kelas');
                $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
                $table->time('jam_mulai');
                $table->time('jam_selesai');
                $table->unsignedInteger('jumlah_peserta')->default(0);
                $table->enum('status', ['aktif', 'batal', 'selesai'])->default('aktif');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('mata_kuliah_id')->references('mata_kuliah_id')->on('mata_kuliah')->onDelete('restrict');
                $table->foreign('dosen_id')->references('id')->on('users')->onDelete('restrict');
                $table->foreign('lab_id')->references('lab_id')->on('labs')->onDelete('restrict');
                $table->foreign('semester_id')->references('semester_id')->on('semesters')->onDelete('restrict');
            });
        }

        // Pc Assignments table
        if (!Schema::hasTable('pc_assignments')) {
            Schema::create('pc_assignments', function (Blueprint $table) {
                $table->char('id', 36)->primary();
                $table->char('user_id', 36);
                $table->char('lab_id', 36);
                $table->char('jadwal_kuliah_id', 36)->nullable();
                $table->string('pc_number');
                $table->enum('status', ['assigned', 'available', 'maintenance', 'decommissioned'])->default('available');
                $table->timestamp('assigned_at')->nullable();
                $table->timestamp('released_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('lab_id')->references('lab_id')->on('labs')->onDelete('cascade');
                $table->foreign('jadwal_kuliah_id')->references('jadwal_kuliah_id')->on('jadwal_kuliah')->onDelete('set null');
            });
        }

        // Log Penggunaan PC table
        if (!Schema::hasTable('log_penggunaan_pcs')) {
            Schema::create('log_penggunaan_pcs', function (Blueprint $table) {
                $table->char('id', 36)->primary();
                $table->char('user_id', 36);
                $table->char('pc_assignment_id', 36);
                $table->timestamp('waktu_mulai');
                $table->timestamp('waktu_selesai')->nullable();
                $table->text('aktivitas')->nullable();
                $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('pc_assignment_id')->references('id')->on('pc_assignments')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_penggunaan_pcs');
        Schema::dropIfExists('pc_assignments');
        Schema::dropIfExists('jadwal_kuliah');
        Schema::dropIfExists('sys_notifications');
    }
};