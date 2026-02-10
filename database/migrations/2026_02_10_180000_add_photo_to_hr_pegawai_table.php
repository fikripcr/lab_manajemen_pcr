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
        Schema::table('hr_pegawai', function (Blueprint $table) {
            $table->string('photo', 255)->nullable()->comment('Employee photo for face recognition');
            $table->text('face_encoding')->nullable()->comment('Face encoding data for face matching');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_pegawai', function (Blueprint $table) {
            $table->dropColumn(['photo', 'face_encoding']);
        });
    }
};
