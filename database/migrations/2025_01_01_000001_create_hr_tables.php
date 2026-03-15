<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for HR module.
     * Consolidated from hr_core and hr_tables.
     */
    public function up(): void
    {
        // =====================================================================
        // 1. Core Structural Tables
        // =====================================================================
        Schema::create('hr_struktur_organisasi', function (Blueprint $table) {
            $table->id('orgunit_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name', 191);
            $table->string('code', 50)->nullable();
            $table->string('type', 100)->nullable();
            $table->integer('level')->default(1);
            $table->integer('seq')->default(1);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('successor_id')->nullable();
            $table->unsignedBigInteger('auditee_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('parent_id')->references('orgunit_id')->on('hr_struktur_organisasi')->onDelete('set null');
            $table->foreign('successor_id')->references('orgunit_id')->on('hr_struktur_organisasi')->nullOnDelete();
        });

        Schema::create('hr_pegawai', function (Blueprint $table) {
            $table->id('pegawai_id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('Foreign key to users table');
            $table->unsignedBigInteger('latest_riwayatdatadiri_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatstatpegawai_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatstataktifitas_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatinpassing_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatpendidikan_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatjabfungsional_id')->nullable();
            $table->unsignedBigInteger('latest_riwayatjabstruktural_id')->nullable();
            $table->unsignedBigInteger('atasan1')->nullable();
            $table->unsignedBigInteger('atasan2')->nullable();
            $table->string('photo', 255)->nullable()->comment('Employee photo for face recognition');
            $table->text('face_encoding')->nullable()->comment('Face encoding data for face matching');
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('hr_personil', function (Blueprint $table) {
            $table->id('personil_id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('Foreign key to users table');
            $table->unsignedBigInteger('org_unit_id')->nullable();
            $table->string('nama');
            $table->string('nip', 50)->unique()->nullable();
            $table->string('posisi')->nullable();
            $table->string('tipe', 30)->nullable()->comment('outsource, vendor_staff, etc.');
            $table->string('vendor')->nullable()->comment('Nama perusahaan vendor/penyedia');
            $table->string('ttd_digital', 191)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('org_unit_id')->references('orgunit_id')->on('hr_struktur_organisasi')->nullOnDelete();
        });

        // =====================================================================
        // 2. Reference Tables
        // =====================================================================
        Schema::create('hr_status_pegawai', function (Blueprint $table) {
            $table->id('statuspegawai_id');
            $table->string('kode_status', 10);
            $table->string('nama_status', 50);
            $table->string('organisasi', 50)->nullable();
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        Schema::create('hr_status_aktifitas', function (Blueprint $table) {
            $table->id('statusaktifitas_id');
            $table->string('kode_status', 10);
            $table->string('nama_status', 50);
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        Schema::create('hr_jabatan_fungsional', function (Blueprint $table) {
            $table->id('jabfungsional_id');
            $table->string('kode_jabatan', 10);
            $table->string('jabfungsional', 50);
            $table->integer('is_active')->default(1);
            $table->integer('tunjangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        Schema::create('hr_golongan_inpassing', function (Blueprint $table) {
            $table->id('gol_inpassing_id');
            $table->string('nama_pangkat', 50)->nullable();
            $table->string('golongan', 50)->nullable();
            $table->string('ruang', 50)->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        Schema::create('hr_jenis_file', function (Blueprint $table) {
            $table->id('jenisfile_id');
            $table->string('jenisfile', 50);
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        Schema::create('hr_jenis_indisipliner', function (Blueprint $table) {
            $table->id('jenisindisipliner_id');
            $table->string('jenis_indisipliner', 100);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
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
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        Schema::create('hr_jenis_shift', function (Blueprint $table) {
            $table->id('jenis_shift_id');
            $table->string('jenis_shift', 50);
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        Schema::create('hr_tanggal_libur', function (Blueprint $table) {
            $table->id('tanggallibur_id');
            $table->integer('tahun')->nullable();
            $table->date('tgl_libur')->nullable();
            $table->string('keterangan', 191)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        // =====================================================================
        // 3. Riwayat / History Tables
        // =====================================================================
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
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        Schema::create('hr_riwayat_datadiri', function (Blueprint $table) {
            $table->id('riwayatdatadiri_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('before_id')->nullable()->index();
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
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
            $table->foreign('orgunit_departemen_id')->references('orgunit_id')->on('hr_struktur_organisasi')->nullOnDelete();
            $table->foreign('orgunit_posisi_id')->references('orgunit_id')->on('hr_struktur_organisasi')->nullOnDelete();
        });

        Schema::create('hr_riwayat_pendidikan', function (Blueprint $table) {
            $table->id('riwayatpendidikan_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('before_id')->nullable()->index();
            $table->string('jenjang_pendidikan', 30)->nullable();
            $table->string('nama_pt', 191)->nullable();
            $table->integer('thn_lulus')->nullable();
            $table->string('bidang_ilmu', 191)->nullable();
            $table->string('kotaasal_pt', 100)->nullable();
            $table->string('kodenegara_pt', 100)->nullable();
            $table->date('tgl_ijazah')->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
        });

        Schema::create('hr_riwayat_statpegawai', function (Blueprint $table) {
            $table->id('riwayatstatpegawai_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('before_id')->nullable()->index();
            $table->unsignedBigInteger('statuspegawai_id')->nullable();
            $table->date('tmt')->nullable();
            $table->date('tgl_akhir')->nullable();
            $table->string('no_sk', 191)->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
            $table->foreign('statuspegawai_id')->references('statuspegawai_id')->on('hr_status_pegawai')->onDelete('set null');
        });

        Schema::create('hr_riwayat_stataktifitas', function (Blueprint $table) {
            $table->id('riwayatstataktifitas_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('before_id')->nullable()->index();
            $table->unsignedBigInteger('statusaktifitas_id')->nullable();
            $table->date('tmt')->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
            $table->foreign('statusaktifitas_id')->references('statusaktifitas_id')->on('hr_status_aktifitas')->onDelete('set null');
        });

        Schema::create('hr_riwayat_jabfungsional', function (Blueprint $table) {
            $table->id('riwayatjabfungsional_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('before_id')->nullable()->index();
            $table->unsignedBigInteger('jabfungsional_id')->nullable();
            $table->date('tmt')->nullable();
            $table->string('no_sk_internal', 191)->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
            $table->foreign('jabfungsional_id')->references('jabfungsional_id')->on('hr_jabatan_fungsional')->onDelete('set null');
        });

        Schema::create('hr_riwayat_jabstruktural', function (Blueprint $table) {
            $table->id('riwayatjabstruktural_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('before_id')->nullable()->index();
            $table->unsignedBigInteger('org_unit_id')->nullable();
            $table->string('no_sk', 191)->nullable();
            $table->date('tgl_awal')->nullable();
            $table->date('tgl_akhir')->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
            $table->foreign('org_unit_id')->references('orgunit_id')->on('hr_struktur_organisasi')->onDelete('set null');
        });

        Schema::create('hr_riwayat_inpassing', function (Blueprint $table) {
            $table->id('riwayatinpassing_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('before_id')->nullable()->index();
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
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
            $table->foreign('gol_inpassing_id')->references('gol_inpassing_id')->on('hr_golongan_inpassing')->onDelete('set null');
        });

        // =====================================================================
        // 4. Operational Tables (Presensi, Izin, Lembur, etc.)
        // =====================================================================
        Schema::create('hr_presensi', function (Blueprint $table) {
            $table->id('presensi_id');
            $table->unsignedBigInteger('pegawai_id')->nullable();
            $table->date('tanggal');
            $table->datetime('check_in_time')->nullable();
            $table->datetime('check_out_time')->nullable();
            $table->decimal('check_in_latitude', 10, 8)->nullable();
            $table->decimal('check_in_longitude', 11, 8)->nullable();
            $table->text('check_in_address')->nullable();
            $table->string('check_in_photo', 255)->nullable();
            $table->decimal('check_out_latitude', 10, 8)->nullable();
            $table->decimal('check_out_longitude', 11, 8)->nullable();
            $table->text('check_out_address')->nullable();
            $table->string('check_out_photo', 255)->nullable();
            $table->decimal('check_in_distance', 8, 2)->nullable();
            $table->decimal('check_out_distance', 8, 2)->nullable();
            $table->boolean('check_in_face_verified')->default(false);
            $table->boolean('check_out_face_verified')->default(false);
            $table->enum('status', ['on_time', 'late', 'absent', 'early_checkout'])->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('overtime_minutes')->nullable()->default(0);
            $table->integer('late_minutes')->nullable()->default(0);
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('set null');
            $table->foreign('shift_id')->references('jenis_shift_id')->on('hr_jenis_shift')->onDelete('set null');
        });

        Schema::create('hr_perizinan', function (Blueprint $table) {
            $table->id('perizinan_id');
            $table->unsignedBigInteger('jenisizin_id')->nullable();
            $table->unsignedBigInteger('pengusul')->nullable()->index();
            $table->text('pekerjaan_ditinggalkan')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('alamat_izin')->nullable();
            $table->date('tgl_awal')->nullable();
            $table->time('jam_awal')->nullable();
            $table->time('jam_akhir')->nullable();
            $table->string('periode', 20)->nullable();
            $table->date('tgl_akhir')->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('pengusul')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
            $table->foreign('jenisizin_id')->references('jenisizin_id')->on('hr_jenis_izin')->onDelete('set null');
        });

        Schema::create('hr_lembur', function (Blueprint $table) {
            $table->id('lembur_id');
            $table->unsignedBigInteger('pengusul_id');
            $table->string('judul', 255);
            $table->text('uraian_pekerjaan')->nullable();
            $table->text('alasan')->nullable();
            $table->date('tgl_pelaksanaan');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('durasi_menit')->nullable();
            $table->boolean('is_dibayar')->default(true);
            $table->string('metode_bayar', 50)->nullable();
            $table->decimal('nominal_per_jam', 10, 2)->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('pengusul_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
        });

        Schema::create('hr_lembur_pegawai', function (Blueprint $table) {
            $table->id('lemburpegawai_id');
            $table->unsignedBigInteger('lembur_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->decimal('override_nominal', 10, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('lembur_id')->references('lembur_id')->on('hr_lembur')->onDelete('cascade');
            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
        });

        // =====================================================================
        // 5. Other Tables
        // =====================================================================
        Schema::create('hr_keluarga', function (Blueprint $table) {
            $table->id('keluarga_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->string('nama', 100)->nullable();
            $table->string('hubungan', 30)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('jenis_kelamin', 1)->nullable();
            $table->text('alamat')->nullable();
            $table->string('telp', 20)->nullable();
            $table->unsignedBigInteger('latest_riwayatapproval_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
        });

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
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
        });

        Schema::create('hr_file_pegawai', function (Blueprint $table) {
            $table->id('filepegawai_id');
            $table->unsignedBigInteger('pegawai_id')->index();
            $table->unsignedBigInteger('jenisfile_id')->index();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->onDelete('cascade');
            $table->foreign('jenisfile_id')->references('jenisfile_id')->on('hr_jenis_file')->onDelete('cascade');
        });

        Schema::create('hr_indisipliner', function (Blueprint $table) {
            $table->id('indisipliner_id');
            $table->unsignedBigInteger('jenisindisipliner_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tgl_indisipliner')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('jenisindisipliner_id')->references('jenisindisipliner_id')->on('hr_jenis_indisipliner')->onDelete('set null');
        });

        Schema::create('hr_indisipliner_pegawai', function (Blueprint $table) {
            $table->id('indispegawai_id');
            $table->unsignedBigInteger('indisipliner_id')->nullable();
            $table->unsignedBigInteger('pegawai_id')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('indisipliner_id')->references('indisipliner_id')->on('hr_indisipliner')->cascadeOnDelete();
            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->cascadeOnDelete();
        });

        Schema::create('hr_tanggal_tidak_masuk', function (Blueprint $table) {
            $table->id('tidakmasuk_id');
            $table->unsignedBigInteger('perizinan_id');
            $table->date('tanggal');
            $table->string('status', 20)->default('pending');
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->foreign('perizinan_id')->references('perizinan_id')->on('hr_perizinan')->onDelete('cascade');
        });

        // =====================================================================
        // 6. Database Views
        // =====================================================================
        \Illuminate\Support\Facades\DB::statement('DROP VIEW IF EXISTS v_pegawai_info');
        \Illuminate\Support\Facades\DB::statement("
            CREATE VIEW v_pegawai_info AS
            SELECT p.pegawai_id, p.user_id, p.photo, p.latest_riwayatdatadiri_id, 
                   posisi.name AS posisi_nama, departemen.name AS departemen_nama, 
                   pend.jenjang_pendidikan AS pendidikan_terakhir, pend.bidang_ilmu AS pendidikan_jurusan, pend.nama_pt AS pendidikan_pt, 
                   sp.statuspegawai_id, msp.nama_status AS status_pegawai_nama, 
                   sa.statusaktifitas_id, 
                   dd.nip AS nip, dd.nidn AS nidn, dd.nama AS nama, dd.inisial AS inisial, dd.email AS email, dd.no_hp AS no_hp, 
                   dd.jenis_kelamin AS jenis_kelamin, dd.tempat_lahir AS tempat_lahir, dd.tgl_lahir AS tgl_lahir, dd.alamat AS alamat, 
                   dd.status_nikah AS status_nikah, dd.agama AS agama, 
                   dd.orgunit_posisi_id AS orgunit_posisi_id, dd.orgunit_departemen_id AS orgunit_departemen_id,
                   pen.org_unit_id AS penugasan_org_unit_id, pen.tgl_awal AS penugasan_tgl_mulai
            FROM hr_pegawai p
            LEFT JOIN hr_riwayat_datadiri dd ON dd.riwayatdatadiri_id = p.latest_riwayatdatadiri_id AND dd.deleted_at IS NULL
            LEFT JOIN hr_struktur_organisasi posisi ON posisi.orgunit_id = dd.orgunit_posisi_id
            LEFT JOIN hr_struktur_organisasi departemen ON departemen.orgunit_id = dd.orgunit_departemen_id
            LEFT JOIN hr_riwayat_pendidikan pend ON pend.riwayatpendidikan_id = p.latest_riwayatpendidikan_id AND pend.deleted_at IS NULL
            LEFT JOIN hr_riwayat_statpegawai sp ON sp.riwayatstatpegawai_id = p.latest_riwayatstatpegawai_id AND sp.deleted_at IS NULL
            LEFT JOIN hr_status_pegawai msp ON msp.statuspegawai_id = sp.statuspegawai_id
            LEFT JOIN hr_riwayat_stataktifitas sa ON sa.riwayatstataktifitas_id = p.latest_riwayatstataktifitas_id AND sa.deleted_at IS NULL
            LEFT JOIN hr_riwayat_jabstruktural pen ON pen.riwayatjabstruktural_id = p.latest_riwayatjabstruktural_id AND pen.deleted_at IS NULL
            WHERE p.deleted_at IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement('DROP VIEW IF EXISTS v_pegawai_info');
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('hr_tanggal_tidak_masuk');
        Schema::dropIfExists('hr_indisipliner_pegawai');
        Schema::dropIfExists('hr_indisipliner');
        Schema::dropIfExists('hr_file_pegawai');
        Schema::dropIfExists('hr_pengembangan_diri');
        Schema::dropIfExists('hr_keluarga');
        Schema::dropIfExists('hr_lembur_pegawai');
        Schema::dropIfExists('hr_lembur');
        Schema::dropIfExists('hr_perizinan');
        Schema::dropIfExists('hr_presensi');
        Schema::dropIfExists('hr_riwayat_inpassing');
        Schema::dropIfExists('hr_riwayat_jabstruktural');
        Schema::dropIfExists('hr_riwayat_jabfungsional');
        Schema::dropIfExists('hr_riwayat_stataktifitas');
        Schema::dropIfExists('hr_riwayat_statpegawai');
        Schema::dropIfExists('hr_riwayat_pendidikan');
        Schema::dropIfExists('hr_riwayat_datadiri');
        Schema::dropIfExists('hr_riwayat_approval');
        Schema::dropIfExists('hr_tanggal_libur');
        Schema::dropIfExists('hr_jenis_shift');
        Schema::dropIfExists('hr_jenis_izin');
        Schema::dropIfExists('hr_jenis_indisipliner');
        Schema::dropIfExists('hr_jenis_file');
        Schema::dropIfExists('hr_golongan_inpassing');
        Schema::dropIfExists('hr_jabatan_fungsional');
        Schema::dropIfExists('hr_status_aktifitas');
        Schema::dropIfExists('hr_status_pegawai');
        Schema::dropIfExists('hr_personil');
        Schema::dropIfExists('hr_pegawai');
        Schema::dropIfExists('hr_struktur_organisasi');
        Schema::enableForeignKeyConstraints();
    }
};
