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
        // 1. Create table lab_riwayat_approval (Clone of hr_riwayat_approval)
        Schema::create('lab_riwayat_approval', function (Blueprint $table) {
            $table->id('riwayatapproval_id');
            $table->string('model', 100)->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('pejabat', 191)->nullable();
            $table->string('jenis_jabatan', 191)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // 2. Add foreign key to lab_request_software
        Schema::table('lab_request_software', function (Blueprint $table) {
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable()->after('status');
            $table->foreign('latest_riwayatapproval_id', 'fk_lab_req_soft_app')
                ->references('riwayatapproval_id')->on('lab_riwayat_approval')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_request_software', function (Blueprint $table) {
            $table->dropForeign('fk_lab_req_soft_app'); // Custom name used above
            $table->dropColumn('latest_riwayatapproval_id');
        });

        Schema::dropIfExists('lab_riwayat_approval');
    }
};
