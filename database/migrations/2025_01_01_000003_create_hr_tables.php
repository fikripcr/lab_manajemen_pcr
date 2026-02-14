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
        Schema::create('hr_status_pegawai', function (Blueprint $table) {
            $table->id('statuspegawai_id');
            $table->string('kode_status', 10);
            $table->string('nama_status', 50);
            $table->string('organisasi', 50)->nullable();
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_status_aktifitas', function (Blueprint $table) {
            $table->id('statusaktifitas_id');
            $table->string('kode_status', 10);
            $table->string('nama_status', 50);
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_jabatan_fungsional', function (Blueprint $table) {
            $table->id('jabfungsional_id');
            $table->string('kode_jabatan', 10);
            $table->string('jabfungsional', 50);
            $table->integer('is_active')->default(1);
            $table->integer('tunjangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_golongan_inpassing', function (Blueprint $table) {
            $table->id('gol_inpassing_id');
            $table->string('nama_pangkat', 50)->nullable();
            $table->string('golongan', 50)->nullable();
            $table->string('ruang', 50)->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_jenis_file', function (Blueprint $table) {
            $table->id('jenisfile_id');
            $table->string('jenisfile', 50);
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_jenis_indisipliner', function (Blueprint $table) {
            $table->id('jenisindisipliner_id');
            $table->string('jenis_indisipliner', 100);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_jenis_shift', function (Blueprint $table) {
            $table->id('jenis_shift_id');
            $table->string('jenis_shift', 50);
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_tanggal_libur', function (Blueprint $table) {
            $table->id('tanggallibur_id');
            $table->integer('tahun')->nullable();
            $table->date('tgl_libur')->nullable();
            $table->string('keterangan', 191)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_org_unit', function (Blueprint $table) {
            $table->id('org_unit_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name', 191);
            $table->string('code', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->integer('level')->default(1);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('parent_id')->references('org_unit_id')->on('hr_org_unit')->onDelete('set null');
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
            $table->unsignedBigInteger('latest_riwayatpenugasan_id')->nullable(); // Added
            $table->unsignedBigInteger('atasan1')->nullable();
            $table->unsignedBigInteger('atasan2')->nullable();

            // Allow storing user name or FK? Original legacy used string, but mass_sync adds FK created_by.
            // Let's keep original columns too if code relies on them, but also add audit ones.
            // Original: created_by (string), updated_by (string).
            // Mass Sync adds: created_by (bigint). Conflict!
            // Solution: Rename original string columns if needed or Drop them.
            // The original migration had: $table->string('created_by', 100)->nullable();
            // Since I'm consolidating, I can change the type to unsignedBigInteger directly if the code is ready.
            // But to be safe, I'll use the BigInt version for Audit and maybe 'legacy_created_by' if needed?
            // Actually, standardizing on BigInt (User ID) is better. I will assume the refactor intends to standardise.

            $table->string('photo', 255)->nullable()->comment('Employee photo for face recognition');   // Added
            $table->text('face_encoding')->nullable()->comment('Face encoding data for face matching'); // Added

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_riwayat_approval', function (Blueprint $table) {
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

        Schema::create('hr_riwayat_datadiri', function (Blueprint $table) {
            $table->id('riwayatdatadiri_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->string('nip', 30)->nullable();
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
            $table->unsignedBigInteger('orgunit_departemen_id')->nullable();
            $table->unsignedBigInteger('orgunit_posisi_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('orgunit_departemen_id')->references('org_unit_id')->on('hr_org_unit')->nullOnDelete();
            $table->foreign('orgunit_posisi_id')->references('org_unit_id')->on('hr_org_unit')->nullOnDelete();
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
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_riwayat_statpegawai', function (Blueprint $table) {
            $table->id('riwayatstatpegawai_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('statuspegawai_id')->nullable();
            $table->date('tmt')->nullable();
            $table->date('tgl_akhir')->nullable();
            $table->string('no_sk', 191)->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_riwayat_stataktifitas', function (Blueprint $table) {
            $table->id('riwayatstataktifitas_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('statusaktifitas_id')->nullable();
            $table->date('tmt')->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_riwayat_jabfungsional', function (Blueprint $table) {
            $table->id('riwayatjabfungsional_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('jabfungsional_id')->nullable();
            $table->date('tmt')->nullable();
            $table->string('no_sk_internal', 191)->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_riwayat_jabstruktural', function (Blueprint $table) {
            $table->id('riwayatjabstruktural_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('org_unit_id')->nullable();
            $table->string('no_sk', 191)->nullable();
            $table->date('tgl_awal')->nullable();
            $table->date('tgl_akhir')->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_keluarga', function (Blueprint $table) {
            $table->id('keluarga_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->string('nama', 100)->nullable();
            $table->string('hubungan', 30)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('jenis_kelamin', 1)->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            // $table->timestamps() usually adds updated_at, but we need updated_by/deleted_by too
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_tanggal_tidak_masuk', function (Blueprint $table) {
            $table->id('tidakmasuk_id');
            $table->unsignedBigInteger('perizinan_id');
            $table->date('tanggal');
            $table->string('status', 20)->default('pending');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('perizinan_id')->references('perizinan_id')->on('hr_perizinan')->onDelete('cascade');
        });

        Schema::create('hr_indisipliner', function (Blueprint $table) {
            $table->id('indisipliner_id');
            $table->unsignedBigInteger('jenisindisipliner_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tgl_indisipliner')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_indisipliner_pegawai', function (Blueprint $table) {
            $table->id('indispegawai_id');
            $table->unsignedBigInteger('indisipliner_id')->nullable();
            $table->unsignedBigInteger('pegawai_id')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('indisipliner_id')->references('indisipliner_id')->on('hr_indisipliner')->cascadeOnDelete();
            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->cascadeOnDelete();
        });

        // New Tables from consolidation
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
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('hr_riwayat_inpassing', function (Blueprint $table) {
            $table->id('riwayatinpassing_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('before_id')->nullable()->index(); // Added
            $table->unsignedBigInteger('gol_inpassing_id')->nullable();
            $table->string('no_sk', 100)->nullable();
            $table->date('tgl_sk')->nullable();
            $table->date('tmt')->nullable();
            $table->integer('masa_kerja_tahun')->default(0);
            $table->integer('masa_kerja_bulan')->default(0);
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->string('file_sk', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

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
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

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
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->index(['pegawai_id', 'tanggal']);
            $table->index('tanggal');
            $table->index('status');
            $table->index(['check_in_time', 'check_out_time']);
            $table->index('shift_id');

            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('set null');
        });

        Schema::create('hr_file_pegawai', function (Blueprint $table) {
            $table->id('filepegawai_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('jenisfile_id')->index();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
            $table->foreign('jenisfile_id')->references('jenisfile_id')->on('hr_jenis_file')->onDelete('cascade');
        });

        // Add foreign keys for blameable if needed, generally implicit or added via separate schema call.
        // Given we are in a Consolidation, and users table exists, we can add them.
        // But to keep it simple and avoid potential issues, we rely on the column presence.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_file_pegawai');
        Schema::dropIfExists('hr_presensi');
        Schema::dropIfExists('hr_riwayat_penugasan');
        Schema::dropIfExists('hr_riwayat_inpassing');
        Schema::dropIfExists('hr_pengembangan_diri');

        Schema::dropIfExists('hr_indisipliner_pegawai');
        Schema::dropIfExists('hr_indisipliner');
        Schema::dropIfExists('hr_tanggal_tidak_masuk');
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
        Schema::dropIfExists('hr_org_unit');
        Schema::dropIfExists('hr_tanggal_libur');
        Schema::dropIfExists('hr_jenis_shift');
        Schema::dropIfExists('hr_jenis_izin');
        Schema::dropIfExists('hr_jenis_indisipliner');
        Schema::dropIfExists('hr_jenis_file');
        Schema::dropIfExists('hr_golongan_inpassing');
        Schema::dropIfExists('hr_jabatan_fungsional');
        Schema::dropIfExists('hr_status_aktifitas');
        Schema::dropIfExists('hr_status_pegawai');
    }
};
