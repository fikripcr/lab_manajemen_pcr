<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for E-Office module.
     */
    public function up(): void
    {
        // NOTE: eoffice_pegawai moved to shared migration as 'pegawai'
        // NOTE: eoffice_mahasiswa moved to shared migration as 'mahasiswa'

        // 2. Service Definitions
        Schema::create('eoffice_jenis_layanan', function (Blueprint $table) {
            $table->id('jenislayanan_id');
            $table->string('nama_layanan');
            $table->string('kategori');
            $table->string('bidang_terkait')->nullable();
            $table->integer('batas_pengerjaan')->default(0);
            $table->boolean('is_diskusi')->default(false);
            $table->boolean('is_fitur_keterlibatan')->default(false);
            $table->string('jenis_khusus')->nullable();
            $table->json('only_show_on')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('eoffice_jenis_layanan_pic', function (Blueprint $table) {
            $table->id('jlpic_id');
            $table->unsignedBigInteger('jenislayanan_id');
            $table->unsignedBigInteger('user_id');
            $table->date('expired')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('jenislayanan_id')->references('jenislayanan_id')->on('eoffice_jenis_layanan')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 3. Dynamic Fields
        Schema::create('eoffice_kategori_isian', function (Blueprint $table) {
            $table->id('kategoriisian_id');
            $table->string('nama_isian');
            $table->string('type')->comment('text/date/file/select');
            $table->json('type_value')->nullable();
            $table->text('keterangan_isian')->nullable();
            $table->string('alias_on_document')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('eoffice_jenis_layanan_isian', function (Blueprint $table) {
            $table->id('jlisian_id');
            $table->unsignedBigInteger('jenislayanan_id');
            $table->unsignedBigInteger('kategoriisian_id');
            $table->integer('seq')->default(1);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_show_on_validasi')->default(false);
            $table->string('fill_by')->default('Pemohon')->comment('Pemohon/Disposisi 1/Disposisi 2/Sistem');
            $table->string('rule')->nullable();
            $table->text('info_tambahan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('jenislayanan_id')->references('jenislayanan_id')->on('eoffice_jenis_layanan')->onDelete('cascade');
            $table->foreign('kategoriisian_id')->references('kategoriisian_id')->on('eoffice_kategori_isian')->onDelete('cascade');
        });

        // 4. Main Requests
        Schema::create('eoffice_layanan', function (Blueprint $table) {
            $table->id('layanan_id');
            $table->string('no_layanan');
            $table->unsignedBigInteger('jenislayanan_id');
            $table->string('pengusul_nama')->nullable();
            $table->string('pengusul_nim')->nullable();
            $table->string('pengusul_prodi')->nullable();
            $table->string('pengusul_email')->nullable();
            $table->string('pengusul_inisial')->nullable();
            $table->string('pengusul_jabstruktural')->nullable();
            $table->unsignedBigInteger('pic_awal')->nullable();
            $table->unsignedBigInteger('pic_pengganti')->nullable();
            $table->text('keterangan')->nullable();
            $table->json('disposisi_info')->nullable();
            $table->json('disposisi_list')->nullable();
            $table->unsignedBigInteger('latest_layananstatus_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('jenislayanan_id')->references('jenislayanan_id')->on('eoffice_jenis_layanan')->onDelete('cascade');
        });

        Schema::create('eoffice_layanan_status', function (Blueprint $table) {
            $table->id('layananstatus_id');
            $table->unsignedBigInteger('layanan_id');
            $table->string('status_layanan');
            $table->text('keterangan')->nullable();
            $table->string('file_lampiran')->nullable();
            $table->json('disposisi_info')->nullable();
            $table->dateTime('done_at')->nullable();
            $table->string('done_duration')->nullable();
            $table->string('done_by_email')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('layanan_id')->references('layanan_id')->on('eoffice_layanan')->onDelete('cascade');
        });

        Schema::create('eoffice_layanan_isian', function (Blueprint $table) {
            $table->id('layananisian_id');
            $table->unsignedBigInteger('layanan_id');
            $table->string('nama_isian');
            $table->text('isi')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('layanan_id')->references('layanan_id')->on('eoffice_layanan')->onDelete('cascade');
        });

        // 5. Internship & Partners
        Schema::create('eoffice_kategori_perusahaan', function (Blueprint $table) {
            $table->id('kategoriperusahaan_id');
            $table->string('nama_kategori');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('eoffice_perusahaan', function (Blueprint $table) {
            $table->id('perusahaan_id');
            $table->unsignedBigInteger('kategoriperusahaan_id');
            $table->string('nama_perusahaan');
            $table->string('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('telp')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('kategoriperusahaan_id')->references('kategoriperusahaan_id')->on('eoffice_kategori_perusahaan')->onDelete('cascade');
        });

        // 6. Discussion & Engagement
        Schema::create('eoffice_layanan_diskusi', function (Blueprint $table) {
            $table->id('diskusi_id');
            $table->unsignedBigInteger('layanan_id');
            $table->unsignedBigInteger('user_id');
            $table->text('pesan');
            $table->string('file_lampiran')->nullable();
            $table->string('status_pengirim')->nullable();
            $table->string('created_by_email')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('layanan_id')->references('layanan_id')->on('eoffice_layanan')->onDelete('cascade');
        });

        Schema::create('eoffice_layanan_keterlibatan', function (Blueprint $table) {
            $table->id('keterlibatan_id');
            $table->unsignedBigInteger('layanan_id');
            $table->unsignedBigInteger('user_id');
            $table->string('peran')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('layanan_id')->references('layanan_id')->on('eoffice_layanan')->onDelete('cascade');
        });

        // 7. Disposition Chain per Service Type
        Schema::create('eoffice_jenis_layanan_disposisi', function (Blueprint $table) {
            $table->id('jldisposisi_id');
            $table->unsignedBigInteger('jenislayanan_id');
            $table->integer('seq')->default(1);
            $table->string('model')->nullable()->comment('Posisi/JabatanStruktural/Lainnya');
            $table->string('value')->nullable();
            $table->string('text')->nullable();
            $table->boolean('is_notify_email')->default(true);
            $table->integer('batas_pengerjaan')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('jenislayanan_id')->references('jenislayanan_id')->on('eoffice_jenis_layanan')->onDelete('cascade');
        });

        // 8. Submission Periods per Service Type
        Schema::create('eoffice_jenis_layanan_periode', function (Blueprint $table) {
            $table->id('jlperiode_id');
            $table->unsignedBigInteger('jenislayanan_id');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('tahun_ajaran')->nullable();
            $table->string('semester')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('jenislayanan_id')->references('jenislayanan_id')->on('eoffice_jenis_layanan')->onDelete('cascade');
        });

        // 9. Layanan-Periode Link
        Schema::create('eoffice_layanan_periode', function (Blueprint $table) {
            $table->id('layananperiode_id');
            $table->unsignedBigInteger('layanan_id');
            $table->unsignedBigInteger('jlperiode_id');
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_selesai')->nullable();
            $table->string('tahun_ajaran')->nullable();
            $table->string('semester')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('layanan_id')->references('layanan_id')->on('eoffice_layanan')->onDelete('cascade');
            $table->foreign('jlperiode_id')->references('jlperiode_id')->on('eoffice_jenis_layanan_periode')->onDelete('cascade');
        });

        // 10. Absence Tracking
        Schema::create('eoffice_tanggal_tidak_hadir', function (Blueprint $table) {
            $table->id('tanggaltidakhadir_id');
            $table->string('jenis_ketidakhadiran');
            $table->date('tgl');
            $table->text('keterangan')->nullable();
            $table->json('additional_info')->nullable();
            $table->boolean('is_full_day')->nullable()->default(true);
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('model')->nullable()->comment('Layanan');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        // 11. Feedback
        Schema::create('eoffice_feedback', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->unsignedBigInteger('layanan_id');
            $table->integer('rating')->default(0);
            $table->text('feedback')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('layanan_id')->references('layanan_id')->on('eoffice_layanan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eoffice_feedback');
        Schema::dropIfExists('eoffice_tanggal_tidak_hadir');
        Schema::dropIfExists('eoffice_layanan_periode');
        Schema::dropIfExists('eoffice_jenis_layanan_periode');
        Schema::dropIfExists('eoffice_jenis_layanan_disposisi');
        Schema::dropIfExists('eoffice_layanan_keterlibatan');
        Schema::dropIfExists('eoffice_layanan_diskusi');
        Schema::dropIfExists('eoffice_perusahaan');
        Schema::dropIfExists('eoffice_kategori_perusahaan');
        Schema::dropIfExists('eoffice_layanan_isian');
        Schema::dropIfExists('eoffice_layanan_status');
        Schema::dropIfExists('eoffice_layanan');
        Schema::dropIfExists('eoffice_jenis_layanan_isian');
        Schema::dropIfExists('eoffice_kategori_isian');
        Schema::dropIfExists('eoffice_jenis_layanan_pic');
        Schema::dropIfExists('eoffice_jenis_layanan');
        // eoffice_mahasiswa and eoffice_pegawai now in shared migration
    }
};
