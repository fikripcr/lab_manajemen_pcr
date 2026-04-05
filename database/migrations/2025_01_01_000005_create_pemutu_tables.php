<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ═══════════════════════════════════════════════════════
        // TABLES
        // ═══════════════════════════════════════════════════════

        Schema::create('pemutu_label', function (Blueprint $table) {
            $table->id('label_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name', 100);
            $table->string('slug', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('color', 20)->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('parent_id')->references('label_id')->on('pemutu_label')->nullOnDelete();
        });

        Schema::create('pemutu_dokumen', function (Blueprint $table) {
            $table->id('dok_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('parent_doksub_id')->nullable();
            $table->string('jenis', 100)->nullable();
            $table->integer('level')->default(1);
            $table->integer('seq')->default(1);
            $table->string('judul', 255);
            $table->longText('isi')->nullable();
            $table->string('kode', 50)->nullable();
            $table->integer('periode')->nullable();
            $table->boolean('std_is_staging')->default(false);
            $table->string('std_amirtn_id', 50)->nullable();
            $table->unsignedBigInteger('std_jeniskriteria_id')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('parent_id')->references('dok_id')->on('pemutu_dokumen')->onDelete('restrict');
        });

        Schema::create('pemutu_dok_sub', function (Blueprint $table) {
            $table->id('doksub_id');
            $table->unsignedBigInteger('dok_id')->index();
            $table->string('jenis', 50)->nullable()->comment('poin_visi, poin_misi, poin_rjp, poin_renstra, poin_renop, standar, manual_prosedur, formulir');
            $table->string('judul', 191);
            $table->string('kode', 50)->nullable();
            $table->text('isi')->nullable();
            $table->integer('seq')->nullable();
            $table->boolean('is_hasilkan_indikator')->default(false);
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('dok_id')->references('dok_id')->on('pemutu_dokumen')->cascadeOnDelete();
            $table->index('jenis', 'idx_doksub_jenis');
        });

        Schema::table('pemutu_dokumen', function (Blueprint $table) {
            $table->foreign('parent_doksub_id')->references('doksub_id')->on('pemutu_dok_sub')->nullOnDelete();
        });

        // ---------------------------------------------------------------------
        // Period Tables
        // ---------------------------------------------------------------------
        Schema::create('pemutu_periode_spmi', function (Blueprint $table) {
            $table->id('periodespmi_id');
            $table->integer('periode');
            $table->string('jenis_periode', 20);
            $table->date('penetapan_awal')->nullable();
            $table->date('penetapan_akhir')->nullable();
            $table->date('pelaksanaan_awal')->nullable();
            $table->date('pelaksanaan_akhir')->nullable();
            $table->date('ed_awal')->nullable();
            $table->date('ed_akhir')->nullable();
            $table->date('ami_awal')->nullable();
            $table->date('ami_akhir')->nullable();
            $table->date('pengendalian_awal')->nullable();
            $table->date('pengendalian_akhir')->nullable();
            $table->date('peningkatan_awal')->nullable();
            $table->date('peningkatan_akhir')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
        });

        Schema::create('pemutu_periode_kpi', function (Blueprint $table) {
            $table->id('periode_kpi_id');
            $table->string('nama', 100);
            $table->integer('tahun');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
        });

        Schema::create('pemutu_indikator', function (Blueprint $table) {
            $table->id('indikator_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('prev_indikator_id')->nullable();
            $table->unsignedBigInteger('renstra_id')->nullable();
            $table->unsignedBigInteger('renstra_poin_id')->nullable();
            $table->enum('type', ['renop', 'standar', 'performa'])->default('renop');
            $table->enum('kelompok_indikator', ['Akademik', 'Non Akademik'])->nullable();
            $table->string('no_indikator', 50)->nullable();
            $table->text('indikator')->nullable();
            $table->text('target')->nullable();
            $table->integer('seq')->default(1);
            $table->string('jenis_data', 30)->nullable();
            $table->string('unit_ukuran', 50)->nullable();
            $table->string('level_risk', 20)->nullable();
            $table->string('origin_from', 30)->nullable();
            $table->string('hash', 100)->nullable();
            $table->integer('peningkat_nonaktif_indik')->nullable();
            $table->integer('is_new_indikator_after_peningkatan')->nullable();
            $table->json('skala')->nullable()->comment('Skala penilaian 0-4');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('parent_id')->references('indikator_id')->on('pemutu_indikator')->nullOnDelete();
            $table->foreign('prev_indikator_id')->references('indikator_id')->on('pemutu_indikator')->nullOnDelete();
            $table->foreign('renstra_id')->references('doksub_id')->on('pemutu_dok_sub')->nullOnDelete();
            $table->foreign('renstra_poin_id')->references('doksub_id')->on('pemutu_dok_sub')->nullOnDelete();
        });

        // Polymorphic: source_type + source_id (instead of old indikator_id)
        Schema::create('pemutu_indikator_doksub', function (Blueprint $table) {
            $table->id('indikdoksub_id');
            $table->string('source_type');
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('doksub_id');
            $table->boolean('is_hasilkan_indikator')->default(false);
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('source_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            $table->foreign('doksub_id')->references('doksub_id')->on('pemutu_dok_sub')->cascadeOnDelete();
            $table->index(['source_type', 'source_id'], 'pemutu_indikator_doksub_source_index');
        });

        Schema::create('pemutu_indikator_label', function (Blueprint $table) {
            $table->id('indiklabel_id');
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedBigInteger('label_id');
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            $table->foreign('label_id')->references('label_id')->on('pemutu_label')->cascadeOnDelete();
        });

        // No ed_attachment column — uses MediaLibrary instead
        Schema::create('pemutu_indikator_orgunit', function (Blueprint $table) {
            $table->id('indikorgunit_id');
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedBigInteger('org_unit_id');
            $table->unsignedBigInteger('prev_indikorgunit_id')->nullable();
            $table->string('target', 255)->nullable();
            $table->text('ed_capaian')->nullable();
            $table->text('ed_analisis')->nullable();
            $table->json('ed_links')->nullable();
            $table->tinyInteger('ed_skala')->nullable()->comment('Skala 0-4');
            $table->text('ed_ptp_isi')->nullable();
            $table->tinyInteger('ami_hasil_akhir')->nullable()->comment('0=KTS, 1=Terpenuhi, 2=Terlampaui');
            $table->text('ami_hasil_temuan')->nullable();
            $table->text('ami_hasil_temuan_sebab')->nullable();
            $table->text('ami_hasil_temuan_akibat')->nullable();
            $table->text('ami_hasil_temuan_rekom')->nullable();
            $table->text('ami_rtp_isi')->nullable();
            $table->date('ami_rtp_tgl_pelaksanaan')->nullable();
            $table->text('ami_te_isi')->nullable();
            $table->string('pengend_status', 20)->nullable();
            $table->string('pengend_status_atsn', 20)->nullable();
            $table->text('pengend_analisis')->nullable();
            $table->text('pengend_analisis_atsn')->nullable();
            $table->string('pengend_important_matrix', 20)->nullable();
            $table->string('pengend_important_matrix_atsn', 20)->nullable();
            $table->string('pengend_urgent_matrix', 20)->nullable();
            $table->string('pengend_urgent_matrix_atsn', 20)->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            $table->foreign('org_unit_id')->references('orgunit_id')->on('hr_struktur_organisasi')->cascadeOnDelete();
            $table->foreign('prev_indikorgunit_id')->references('indikorgunit_id')->on('pemutu_indikator_orgunit')->nullOnDelete();
        });

        // No attachment column — uses MediaLibrary instead
        Schema::create('pemutu_indikator_pegawai', function (Blueprint $table) {
            $table->id('indikator_pegawai_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedBigInteger('periode_kpi_id')->nullable();
            $table->integer('year');
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('target_value', 10, 2)->nullable();
            $table->text('realization')->nullable();
            $table->text('kpi_analisis')->nullable();
            $table->json('kpi_links')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->string('status', 20)->default('draft');
            $table->text('notes')->nullable();
            $table->string('unit_ukuran')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->cascadeOnDelete();
            $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            $table->foreign('periode_kpi_id')->references('periode_kpi_id')->on('pemutu_periode_kpi')->onDelete('set null');
        });

        Schema::create('pemutu_dok_approval', function (Blueprint $table) {
            $table->id('dokapproval_id');
            $table->unsignedBigInteger('dok_id');
            $table->string('proses', 191)->nullable();
            $table->unsignedBigInteger('pegawai_id')->nullable();
            $table->string('jabatan', 191)->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('dok_id')->references('dok_id')->on('pemutu_dokumen')->cascadeOnDelete();
            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->nullOnDelete();
        });

        Schema::create('pemutu_dok_approval_status', function (Blueprint $table) {
            $table->id('dokstatusapproval_id');
            $table->unsignedBigInteger('dokapproval_id');
            $table->string('status_approval', 50);
            $table->text('komentar')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('dokapproval_id')->references('dokapproval_id')->on('pemutu_dok_approval')->cascadeOnDelete();
        });

        Schema::create('pemutu_tim_mutu', function (Blueprint $table) {
            $table->id('tim_mutu_id');
            $table->unsignedBigInteger('periodespmi_id');
            $table->unsignedBigInteger('org_unit_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->enum('role', ['auditee', 'anggota', 'auditor', 'ketua_auditor'])->default('anggota');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->foreign('periodespmi_id')->references('periodespmi_id')->on('pemutu_periode_spmi')->cascadeOnDelete();
            $table->foreign('org_unit_id')->references('orgunit_id')->on('hr_struktur_organisasi')->cascadeOnDelete();
            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->cascadeOnDelete();
            $table->unique(['periodespmi_id', 'org_unit_id', 'pegawai_id', 'role'], 'tim_mutu_unique');
        });

        Schema::create('pemutu_riwayat_approval', function (Blueprint $table) {
            $table->id('riwayatapproval_id');
            $table->string('model');
            $table->unsignedBigInteger('model_id');
            $table->enum('status', ['Draft', 'Pending', 'Approved', 'Rejected'])->default('Draft');
            $table->unsignedBigInteger('pegawai_id')->nullable();
            $table->string('pejabat')->nullable();
            $table->string('jabatan')->nullable();
            $table->text('catatan')->nullable();
            $table->string('lampiran_url')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['model', 'model_id']);
            $table->foreign('pegawai_id')->references('pegawai_id')->on('hr_pegawai')->nullOnDelete();
        });

        // No attachment_file column — uses MediaLibrary instead
        Schema::create('pemutu_diskusi', function (Blueprint $table) {
            $table->id('diskusi_id');
            $table->unsignedBigInteger('pengirim_user_id');
            $table->string('jenis_pengirim', 50)->comment('auditor / auditee / admin');
            $table->string('jenis_diskusi', 50)->default('ami');
            $table->morphs('model');
            $table->text('isi');
            $table->json('attachment_link')->nullable()->comment('Array link dengan name+url');
            $table->boolean('is_done')->default(false);
            $table->timestamps();
            $table->foreign('pengirim_user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('pemutu_doksub_mapping', function (Blueprint $table) {
            $table->id('doksub_mapping_id');
            $table->unsignedBigInteger('doksub_id');
            $table->unsignedBigInteger('mapped_doksub_id');
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->foreign('doksub_id')->references('doksub_id')->on('pemutu_dok_sub')->cascadeOnDelete();
            $table->foreign('mapped_doksub_id')->references('doksub_id')->on('pemutu_dok_sub')->cascadeOnDelete();
            $table->unique(['doksub_id', 'mapped_doksub_id'], 'doksub_mapping_unique');
        });

        Schema::create('pemutu_dokumen_mapping', function (Blueprint $table) {
            $table->id('dokumen_mapping_id');
            $table->unsignedBigInteger('source_dok_id')->index();
            $table->unsignedBigInteger('target_dok_id')->index();
            $table->timestamps();
            $table->foreign('source_dok_id')->references('dok_id')->on('pemutu_dokumen')->cascadeOnDelete();
            $table->foreign('target_dok_id')->references('dok_id')->on('pemutu_dokumen')->cascadeOnDelete();
            $table->unique(['source_dok_id', 'target_dok_id'], 'pemutu_dokumen_mapping_unique');
        });

        // ═══════════════════════════════════════════════════════
        // VIEWS
        // ═══════════════════════════════════════════════════════

        // vw_pemutu_summary_indikator
        DB::statement("
            CREATE OR REPLACE VIEW vw_pemutu_summary_indikator AS
            SELECT
                i.indikator_id, i.parent_id, i.type, i.kelompok_indikator,
                i.no_indikator, i.indikator, i.target, i.seq, i.jenis_data,
                i.unit_ukuran, i.level_risk, i.origin_from, i.hash, i.skala, i.keterangan,
                i.created_at as indikator_created_at, i.updated_at as indikator_updated_at,
                pi.no_indikator as parent_no_indikator, pi.indikator as parent_indikator,
                COUNT(DISTINCT io.org_unit_id) AS ed_total_units,
                COUNT(DISTINCT CASE WHEN io.ed_capaian IS NOT NULL AND io.ed_capaian != '' THEN io.org_unit_id END) AS ed_filled_units,
                GROUP_CONCAT(DISTINCT CONCAT(so.name, ': ', io.ed_capaian) SEPARATOR ' || ') AS ed_capaian_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.ed_capaian IS NOT NULL AND io.ed_capaian != '' THEN CONCAT(so.name, ': ', io.ed_analisis) END SEPARATOR ' || ') AS ed_analisis_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.ed_skala IS NOT NULL THEN CONCAT(so.name, '(', io.ed_skala, ')') END SEPARATOR ' || ') AS ed_skala_all_units,
                MAX(io.ed_skala) AS ed_skala_max, MIN(io.ed_skala) AS ed_skala_min, AVG(io.ed_skala) AS ed_skala_avg,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir IS NOT NULL THEN io.org_unit_id END) AS ami_assessed_units,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir = 0 THEN io.org_unit_id END) AS ami_kts_units,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir = 1 THEN io.org_unit_id END) AS ami_terpenuhi_units,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir = 2 THEN io.org_unit_id END) AS ami_terlampaui_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.ami_hasil_temuan IS NOT NULL AND io.ami_hasil_temuan != '' THEN CONCAT(so.name, ': ', io.ami_hasil_temuan) END SEPARATOR ' || ') AS ami_temuan_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.ami_hasil_temuan_rekom IS NOT NULL AND io.ami_hasil_temuan_rekom != '' THEN CONCAT(so.name, ': ', io.ami_hasil_temuan_rekom) END SEPARATOR ' || ') AS ami_rekomendasi_all_units,
                COUNT(DISTINCT CASE WHEN io.pengend_status IS NOT NULL AND io.pengend_status != '' THEN io.org_unit_id END) AS pengend_filled_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_status IS NOT NULL THEN CONCAT(so.name, ': ', io.pengend_status) END SEPARATOR ' || ') AS pengend_status_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_status_atsn IS NOT NULL THEN CONCAT(so.name, ': ', io.pengend_status_atsn) END SEPARATOR ' || ') AS pengend_status_atsn_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_analisis IS NOT NULL AND io.pengend_analisis != '' THEN CONCAT(so.name, ': ', io.pengend_analisis) END SEPARATOR ' || ') AS pengend_analisis_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_analisis_atsn IS NOT NULL AND io.pengend_analisis_atsn != '' THEN CONCAT(so.name, ': ', io.pengend_analisis_atsn) END SEPARATOR ' || ') AS pengend_analisis_atsn_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_important_matrix_atsn IS NOT NULL THEN CONCAT(so.name, ': ', io.pengend_important_matrix_atsn) END SEPARATOR ' || ') AS pengend_important_atsn_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_urgent_matrix_atsn IS NOT NULL THEN CONCAT(so.name, ': ', io.pengend_urgent_matrix_atsn) END SEPARATOR ' || ') AS pengend_urgent_atsn_all_units,
                GROUP_CONCAT(DISTINCT so.name SEPARATOR ' || ') AS all_org_units,
                GROUP_CONCAT(DISTINCT so.code SEPARATOR ' || ') AS all_org_unit_codes,
                COUNT(DISTINCT io.org_unit_id) AS total_org_units,
                GROUP_CONCAT(DISTINCT CONCAT(so.name, ': ', io.target) SEPARATOR ' || ') AS target_all_units,
                GROUP_CONCAT(DISTINCT l.name SEPARATOR ', ') AS all_labels,
                GROUP_CONCAT(DISTINCT l.color SEPARATOR ', ') AS all_label_colors,
                GROUP_CONCAT(DISTINCT ds.judul SEPARATOR ' || ') AS all_doksub_judul,
                GROUP_CONCAT(DISTINCT ds.kode SEPARATOR ' || ') AS all_doksub_kode,
                COUNT(DISTINCT ip.pegawai_id) AS total_pegawai_with_kpi,
                GROUP_CONCAT(DISTINCT CONCAT('Pegawai ID:', ip.pegawai_id, ': ', ip.status) SEPARATOR ' || ') AS kpi_pegawai_status,
                AVG(ip.score) AS kpi_avg_score, MIN(ip.score) AS kpi_min_score, MAX(ip.score) AS kpi_max_score
            FROM pemutu_indikator i
            LEFT JOIN pemutu_indikator pi ON i.parent_id = pi.indikator_id
            LEFT JOIN pemutu_indikator_orgunit io ON i.indikator_id = io.indikator_id
            LEFT JOIN hr_struktur_organisasi so ON io.org_unit_id = so.orgunit_id
            LEFT JOIN pemutu_indikator_label il ON i.indikator_id = il.indikator_id
            LEFT JOIN pemutu_label l ON il.label_id = l.label_id
            LEFT JOIN pemutu_indikator_doksub ids ON i.indikator_id = ids.source_id AND ids.source_type = 'App\\Models\\Pemutu\\Indikator'
            LEFT JOIN pemutu_dok_sub ds ON ids.doksub_id = ds.doksub_id
            LEFT JOIN pemutu_indikator_pegawai ip ON i.indikator_id = ip.indikator_id
            GROUP BY
                i.indikator_id, i.parent_id, i.type, i.kelompok_indikator, i.no_indikator, i.indikator, i.target, i.seq,
                i.jenis_data, i.unit_ukuran, i.level_risk, i.origin_from, i.hash, i.skala, i.keterangan,
                i.created_at, i.updated_at, pi.no_indikator, pi.indikator
            ORDER BY i.seq ASC
        ");

        // vw_pemutu_summary_indikator_standar
        DB::statement("
            CREATE OR REPLACE VIEW vw_pemutu_summary_indikator_standar AS
            SELECT
                i.indikator_id, i.parent_id, i.type, i.kelompok_indikator,
                i.no_indikator, i.indikator, i.target AS target_indikator, i.seq,
                i.jenis_data, i.unit_ukuran,
                pi.no_indikator AS parent_no_indikator, pi.indikator AS parent_indikator,
                GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ed_capaian, '-')) SEPARATOR ' ;; ') AS ed_capaian_detail,
                GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ed_analisis, '-')) SEPARATOR ' ;; ') AS ed_analisis_detail,
                GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', CASE io.ami_hasil_akhir WHEN 0 THEN 'KTS' WHEN 1 THEN 'Terpenuhi' WHEN 2 THEN 'Terlampaui' ELSE '-' END, '|', COALESCE(io.ami_hasil_temuan, '-')) SEPARATOR ' ;; ') AS ami_hasil_detail,
                GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ami_hasil_temuan_rekom, '-')) SEPARATOR ' ;; ') AS ami_rekomendasi_detail,
                GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.pengend_status, '-'), '|', COALESCE(io.pengend_analisis, '-'), '|', COALESCE(io.pengend_status_atsn, '-')) SEPARATOR ' ;; ') AS pengend_status_detail,
                GROUP_CONCAT(DISTINCT so.name SEPARATOR ' ;; ') AS all_unit_names,
                GROUP_CONCAT(DISTINCT CONCAT(l.name, '|', l.color) SEPARATOR ', ') AS label_details
            FROM pemutu_indikator i
            LEFT JOIN pemutu_indikator pi ON i.parent_id = pi.indikator_id
            LEFT JOIN pemutu_indikator_orgunit io ON i.indikator_id = io.indikator_id
            LEFT JOIN hr_struktur_organisasi so ON io.org_unit_id = so.orgunit_id
            LEFT JOIN pemutu_indikator_label il ON i.indikator_id = il.indikator_id
            LEFT JOIN pemutu_label l ON il.label_id = l.label_id
            WHERE i.type = 'standar'
            GROUP BY i.indikator_id, i.parent_id, i.type, i.kelompok_indikator, i.no_indikator, i.indikator, i.target,
                i.seq, i.jenis_data, i.unit_ukuran, pi.no_indikator, pi.indikator
        ");

        // vw_pemutu_dashboard_indikator
        DB::statement("
            CREATE OR REPLACE VIEW vw_pemutu_dashboard_indikator AS
            SELECT
                io.indikorgunit_id, io.indikator_id, io.org_unit_id,
                so.code as unit_name, i.kelompok_indikator,
                io.ami_hasil_akhir, io.pengend_status,
                io.pengend_important_matrix, io.pengend_urgent_matrix,
                io.ed_skala, d.periode as tahun,
                COALESCE(d.kode, d.judul) as dokumen_name, ds.dok_id
            FROM pemutu_indikator_orgunit io
            JOIN pemutu_indikator i ON io.indikator_id = i.indikator_id
            JOIN hr_struktur_organisasi so ON io.org_unit_id = so.orgunit_id
            JOIN (
                SELECT DISTINCT source_id as indikator_id, doksub_id
                FROM pemutu_indikator_doksub
                WHERE source_type = 'App\\\\Models\\\\Pemutu\\\\Indikator'
            ) ids ON ids.indikator_id = i.indikator_id
            JOIN pemutu_dok_sub ds ON ids.doksub_id = ds.doksub_id
            JOIN pemutu_dokumen d ON ds.dok_id = d.dok_id
            WHERE i.deleted_at IS NULL AND i.type = 'standar'
            GROUP BY
                io.indikorgunit_id, io.indikator_id, io.org_unit_id, so.code,
                i.kelompok_indikator, io.ami_hasil_akhir, io.pengend_status,
                io.pengend_important_matrix, io.pengend_urgent_matrix, io.ed_skala,
                d.periode, COALESCE(d.kode, d.judul), ds.dok_id
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_pemutu_dashboard_indikator');
        DB::statement('DROP VIEW IF EXISTS vw_pemutu_summary_indikator_standar');
        DB::statement('DROP VIEW IF EXISTS vw_pemutu_summary_indikator');

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('pemutu_dokumen_mapping');
        Schema::dropIfExists('pemutu_doksub_mapping');
        Schema::dropIfExists('pemutu_diskusi');
        Schema::dropIfExists('pemutu_riwayat_approval');
        Schema::dropIfExists('pemutu_dok_approval_status');
        Schema::dropIfExists('pemutu_dok_approval');
        Schema::dropIfExists('pemutu_tim_mutu');
        Schema::dropIfExists('pemutu_periode_kpi');
        Schema::dropIfExists('pemutu_periode_spmi');
        Schema::dropIfExists('pemutu_indikator_pegawai');
        Schema::dropIfExists('pemutu_indikator_orgunit');
        Schema::dropIfExists('pemutu_indikator_label');
        Schema::dropIfExists('pemutu_indikator_doksub');
        Schema::dropIfExists('pemutu_indikator');
        Schema::dropIfExists('pemutu_dok_sub');
        Schema::dropIfExists('pemutu_dokumen');
        Schema::dropIfExists('pemutu_label');
        Schema::enableForeignKeyConstraints();
    }
};
