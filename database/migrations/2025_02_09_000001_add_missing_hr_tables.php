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
        // 1. Create hr_pengembangan_diri
        if (! Schema::hasTable('hr_pengembangan_diri')) {
            Schema::create('hr_pengembangan_diri', function (Blueprint $table) {
                $table->id('pengembangandiri_id');
                $table->unsignedBigInteger('pegawai_id')->index();
                $table->string('jenis_kegiatan', 100);
                $table->string('nama_kegiatan', 255);
                $table->string('nama_penyelenggara', 255)->nullable();
                $table->string('peran', 100)->nullable();
                $table->date('tgl_mulai');
                $table->date('tgl_selesai')->nullable();
                $table->date('berlaku_hingga')->nullable();
                $table->integer('tahun');
                $table->text('keterangan')->nullable();
                $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
                $table->string('created_by', 100)->nullable();
                $table->string('updated_by', 100)->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 2. Create hr_riwayat_inpassing
        if (! Schema::hasTable('hr_riwayat_inpassing')) {
            Schema::create('hr_riwayat_inpassing', function (Blueprint $table) {
                $table->id('riwayatinpassing_id');
                $table->unsignedBigInteger('pegawai_id')->index();
                $table->unsignedBigInteger('gol_inpassing_id')->nullable();
                $table->string('no_sk', 100)->nullable();
                $table->date('tgl_sk')->nullable();
                $table->date('tmt')->nullable();
                $table->integer('masa_kerja_tahun')->default(0);
                $table->integer('masa_kerja_bulan')->default(0);
                $table->decimal('gaji_pokok', 15, 2)->default(0);
                $table->string('file_sk', 255)->nullable();
                $table->string('created_by', 100)->nullable();
                $table->string('updated_by', 100)->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 3. Create hr_riwayat_penugasan
        if (! Schema::hasTable('hr_riwayat_penugasan')) {
            Schema::create('hr_riwayat_penugasan', function (Blueprint $table) {
                $table->id('riwayatpenugasan_id');
                $table->unsignedBigInteger('pegawai_id')->index();
                $table->unsignedBigInteger('org_unit_id')->nullable();
                $table->date('tgl_mulai');
                $table->date('tgl_selesai')->nullable();
                $table->date('tgl_sk')->nullable();
                $table->string('no_sk', 100)->nullable();
                $table->string('jabatan', 100)->nullable();
                $table->text('keterangan')->nullable();
                $table->string('status', 20)->default('approved');
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->string('created_by', 100)->nullable();
                $table->string('updated_by', 100)->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 4. Add missing column to hr_pegawai
        Schema::table('hr_pegawai', function (Blueprint $table) {
            if (! Schema::hasColumn('hr_pegawai', 'latest_riwayatpenugasan_id')) {
                $table->unsignedBigInteger('latest_riwayatpenugasan_id')->nullable()->after('latest_riwayatjabstruktural_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_pegawai', function (Blueprint $table) {
            $table->dropColumn('latest_riwayatpenugasan_id');
        });
        Schema::dropIfExists('hr_riwayat_penugasan');
        Schema::dropIfExists('hr_riwayat_inpassing');
        Schema::dropIfExists('hr_pengembangan_diri');
    }
};
