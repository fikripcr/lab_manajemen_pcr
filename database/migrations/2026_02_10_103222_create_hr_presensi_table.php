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
        Schema::create('hr_presensi', function (Blueprint $table) {
            $table->id('presensi_id');
            $table->unsignedBigInteger('pegawai_id')->nullable();
            $table->date('tanggal');
            $table->datetime('check_in_time')->nullable();
            $table->datetime('check_out_time')->nullable();
            $table->decimal('check_in_latitude', 10, 8)->nullable();
            $table->decimal('check_in_longitude', 11, 8)->nullable();
            $table->text('check_in_address')->nullable();
            $table->string('check_in_photo', 255)->nullable()->comment('Photo path for check-in face verification');
            $table->decimal('check_out_latitude', 10, 8)->nullable();
            $table->decimal('check_out_longitude', 11, 8)->nullable();
            $table->text('check_out_address')->nullable();
            $table->string('check_out_photo', 255)->nullable()->comment('Photo path for check-out face verification');
            $table->decimal('check_in_distance', 8, 2)->nullable()->comment('Distance from office in meters');
            $table->decimal('check_out_distance', 8, 2)->nullable()->comment('Distance from office in meters');
            $table->boolean('check_in_face_verified')->default(false)->comment('Face verification status for check-in');
            $table->boolean('check_out_face_verified')->default(false)->comment('Face verification status for check-out');
            $table->enum('status', ['on_time', 'late', 'absent', 'early_checkout'])->nullable();
            $table->integer('duration_minutes')->nullable()->comment('Total working minutes');
            $table->integer('overtime_minutes')->nullable()->default(0)->comment('Overtime minutes');
            $table->integer('late_minutes')->nullable()->default(0)->comment('Late arrival minutes');
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['pegawai_id', 'tanggal']);
            $table->index('tanggal');
            $table->index('status');
            $table->index(['check_in_time', 'check_out_time']);
            $table->index('shift_id');

            // Foreign keys
            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('set null');
            // $table->foreign('shift_id')->references('jenis_shift_id')->on('hr_jenis_shift')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_presensi');
    }
};
