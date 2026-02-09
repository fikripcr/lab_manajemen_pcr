<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for HR module.
     */
    public function up(): void
    {
        // Reference Tables
        Schema::create('hr_posisi', function (Blueprint $table) {
            $table->id('posisi_id');
            $table->string('posisi', 50);
            $table->string('alias', 30)->nullable();
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        // Legacy Tables Removed: hr_departemen, hr_prodi

        Schema::create('hr_status_pegawai', function (Blueprint $table) {
            $table->id('statuspegawai_id');
            $table->string('kode_status', 10);
            $table->string('nama_status', 50);
            $table->string('organisasi', 50)->nullable();
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_status_aktifitas', function (Blueprint $table) {
            $table->id('statusaktifitas_id');
            $table->string('kode_status', 10);
            $table->string('nama_status', 50);
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_jabatan_fungsional', function (Blueprint $table) {
            $table->id('jabfungsional_id');
            $table->string('kode_jabatan', 10);
            $table->string('jabfungsional', 50);
            $table->integer('is_active')->default(1);
            $table->integer('tunjangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Legacy Table Removed: hr_jabatan_struktural

        Schema::create('hr_golongan_inpassing', function (Blueprint $table) {
            $table->id('gol_inpassing_id');
            $table->string('nama_pangkat', 50)->nullable();
            $table->string('golongan', 50)->nullable();
            $table->string('ruang', 50)->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_jenis_file', function (Blueprint $table) {
            $table->id('jenisfile_id');
            $table->string('jenisfile', 50);
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_jenis_indisipliner', function (Blueprint $table) {
            $table->id('jenisindisipliner_id');
            $table->string('jenis_indisipliner', 100);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_jenis_izin', function (Blueprint $table) {
            $table->id('jenisizin_id');
            $table->string('nama', 50);
            $table->string('kategori', 10)->nullable();
            $table->integer('max_hari')->nullable();
            $table->string('pemilihan_waktu', 20)->nullable();
            $table->text('urutan_approval')->nullable();
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_jenis_shift', function (Blueprint $table) {
            $table->id('jenis_shift_id');
            $table->string('jenis_shift', 50);
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_tanggal_libur', function (Blueprint $table) {
            $table->id('tanggallibur_id');
            $table->integer('tahun')->nullable();
            $table->date('tgl_libur')->nullable();
            $table->string('keterangan', 191)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_org_unit', function (Blueprint $table) {
            $table->id('org_unit_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name', 191);
            $table->string('code', 50)->nullable();
            $table->string('type', 50)->nullable(); // departemen, prodi, unit, jabatan_struktural, posisi
            $table->integer('level')->default(1);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();

            // Blameable columns
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign key for self-reference (hierarchy)
            $table->foreign('parent_id')
                ->references('org_unit_id')
                ->on('hr_org_unit')
                ->onDelete('set null');
        });

        // Core Tables
        Schema::create('hr_pegawai', function (Blueprint $table) {
            $table->id('pegawai_id');
            $table->unsignedBigInteger('latest_riwayatdatadiri_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatstatpegawai_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatstataktifitas_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatinpassing_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatpendidikan_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatjabfungsional_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatjabstruktural_id')->nullable();
            $table->unsignedBigInteger('atasan1')->nullable();
            $table->unsignedBigInteger('atasan2')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_riwayat_approval', function (Blueprint $table) {
            $table->id('riwayatapproval_id');
            $table->string('model', 100)->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('pejabat', 191)->nullable();
            $table->string('jenis_jabatan', 191)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_riwayat_datadiri', function (Blueprint $table) {
            $table->id('riwayatdatadiri_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->string('nip', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('nama', 100)->nullable();
            $table->string('inisial', 10)->nullable();
            $table->string('jenis_kelamin', 1)->nullable();
            $table->string('tempat_lahir', 191)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp', 30)->nullable();
            $table->string('status_nikah', 30)->nullable();
            $table->string('agama', 20)->nullable();
            $table->string('nidn', 20)->nullable();
            $table->unsignedBigInteger('org_unit_id')->nullable(); // Replaces departemen_id
            $table->unsignedBigInteger('posisi_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('org_unit_id')->references('org_unit_id')->on('hr_org_unit')->nullOnDelete();
            $table->foreign('posisi_id')->references('posisi_id')->on('hr_posisi')->nullOnDelete();
        });

        Schema::create('hr_riwayat_pendidikan', function (Blueprint $table) {
            $table->id('riwayatpendidikan_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->string('jenjang_pendidikan', 30)->nullable();
            $table->string('nama_pt', 191)->nullable();
            $table->integer('thn_lulus')->nullable();
            $table->string('bidang_ilmu', 191)->nullable();
            $table->date('tgl_ijazah')->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_riwayat_statpegawai', function (Blueprint $table) {
            $table->id('riwayatstatpegawai_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('statuspegawai_id')->nullable();
            $table->date('tmt')->nullable();
            $table->date('tgl_akhir')->nullable();
            $table->string('no_sk', 191)->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_riwayat_stataktifitas', function (Blueprint $table) {
            $table->id('riwayatstataktifitas_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('statusaktifitas_id')->nullable();
            $table->date('tmt')->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_riwayat_jabfungsional', function (Blueprint $table) {
            $table->id('riwayatjabfungsional_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('jabfungsional_id')->nullable();
            $table->date('tmt')->nullable();
            $table->string('no_sk_internal', 191)->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_riwayat_jabstruktural', function (Blueprint $table) {
            $table->id('riwayatjabstruktural_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            // $table->unsignedBigInteger('jabstruktural_id')->nullable(); // Removed, use org_unit_id
            $table->unsignedBigInteger('org_unit_id')->nullable();
            $table->string('no_sk', 191)->nullable();
            $table->date('tgl_awal')->nullable();
            $table->date('tgl_akhir')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_keluarga', function (Blueprint $table) {
            $table->id('keluarga_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->string('nama', 100)->nullable();
            $table->string('hubungan', 30)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('jenis_kelamin', 1)->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_perizinan', function (Blueprint $table) {
            $table->id('perizinan_id');
            $table->unsignedBigInteger('jenisizin_id')->nullable();
            $table->unsignedBigInteger('pengusul')->nullable()->index();
            $table->text('pekerjaan_ditinggalkan')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tgl_awal')->nullable();
            $table->date('tgl_akhir')->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_tanggal_tidak_masuk', function (Blueprint $table) {
            $table->id('tidakmasuk_id');
            $table->unsignedBigInteger('perizinan_id');
            $table->date('tanggal');
            $table->string('status', 20)->default('pending'); // pending, approved, rejected
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('perizinan_id')
                ->references('perizinan_id')
                ->on('hr_perizinan')
                ->onDelete('cascade');
        });

        Schema::create('hr_indisipliner', function (Blueprint $table) {
            $table->id('indisipliner_id');
            $table->unsignedBigInteger('jenisindisipliner_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tgl_indisipliner')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hr_indisipliner_pegawai', function (Blueprint $table) {
            $table->id('indispegawai_id');
            $table->unsignedBigInteger('indisipliner_id')->nullable();
            $table->unsignedBigInteger('pegawai_id')->nullable();
            $table->timestamps();

            $table->foreign('indisipliner_id')->references('indisipliner_id')->on('hr_indisipliner')->cascadeOnDelete();
            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_tanggal_tidak_masuk');
        Schema::dropIfExists('hr_org_unit');
        Schema::dropIfExists('hr_indisipliner_pegawai');
        Schema::dropIfExists('hr_indisipliner');
        Schema::dropIfExists('hr_perizinan');
        Schema::dropIfExists('hr_keluarga');
        Schema::dropIfExists('hr_riwayat_jabstruktural');
        Schema::dropIfExists('hr_riwayat_jabfungsional');
        Schema::dropIfExists('hr_riwayat_stataktifitas');
        Schema::dropIfExists('hr_riwayat_statpegawai');
        Schema::dropIfExists('hr_riwayat_pendidikan');
        Schema::dropIfExists('hr_riwayat_datadiri');
        Schema::dropIfExists('hr_riwayat_approval');
        Schema::dropIfExists('hr_pegawai');
        Schema::dropIfExists('hr_tanggal_libur');
        Schema::dropIfExists('hr_jenis_shift');
        Schema::dropIfExists('hr_jenis_izin');
        Schema::dropIfExists('hr_jenis_indisipliner');
        Schema::dropIfExists('hr_jenis_file');
        // Schema::dropIfExists('hr_jabatan_struktural');
        Schema::dropIfExists('hr_jabatan_fungsional');
        Schema::dropIfExists('hr_status_aktifitas');
        Schema::dropIfExists('hr_status_pegawai');
        // Schema::dropIfExists('hr_prodi');
        // Schema::dropIfExists('hr_departemen');
        Schema::dropIfExists('hr_posisi');
    }
};
