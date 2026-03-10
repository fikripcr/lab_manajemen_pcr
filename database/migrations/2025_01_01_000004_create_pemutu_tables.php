<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemutu_label_type', function (Blueprint $table) {
            $table->id('labeltype_id');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('color', 20)->default('blue');
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes(); // mass_sync added softDeletes too
        });

        Schema::create('pemutu_label', function (Blueprint $table) {
            $table->id('label_id');
            $table->unsignedBigInteger('type_id');
            $table->string('name', 100);
            $table->string('slug', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('color', 20)->nullable();
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('type_id')->references('labeltype_id')->on('pemutu_label_type')->cascadeOnDelete();
        });

        // NOTE: pemutu_org_unit moved to shared migration as 'struktur_organisasi'
        // NOTE: pemutu_personil moved to shared migration — pemutu now uses shared 'pegawai' table

        // Drop if exists to ensure order
        Schema::dropIfExists('pemutu_dok_sub');

        Schema::create('pemutu_dok_sub', function (Blueprint $table) {
            $table->id('doksub_id');
            $table->unsignedBigInteger('dok_id')->index();
            $table->string('judul', 191);
            $table->string('kode', 50)->nullable();
            $table->text('isi')->nullable();
            $table->integer('seq')->nullable();
            $table->boolean('is_hasilkan_indikator')->default(false);
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
        });

        Schema::create('pemutu_dokumen', function (Blueprint $table) {
            $table->id('dok_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('parent_doksub_id')->nullable();

            $table->enum('jenis', ['visi', 'misi', 'rjp', 'renstra', 'renop', 'standar', 'formulir', 'sop', 'manual_prosedur', 'dll'])->nullable();

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

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('parent_id')->references('dok_id')->on('pemutu_dokumen')->onDelete('restrict');
            $table->foreign('parent_doksub_id')->references('doksub_id')->on('pemutu_dok_sub')->nullOnDelete();
        });

        // Add FK for dok_sub -> dokumen now that dokumen exists
        Schema::table('pemutu_dok_sub', function (Blueprint $table) {
            $table->foreign('dok_id')->references('dok_id')->on('pemutu_dokumen')->cascadeOnDelete();
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
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->string('tahun_akademik', 20);
            $table->integer('tahun');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
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
            $table->json('skala')->nullable()->comment('Skala penilaian 0-4 dengan deskripsi masing-masing level');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('parent_id')->references('indikator_id')->on('pemutu_indikator')->nullOnDelete();
            $table->foreign('prev_indikator_id')->references('indikator_id')->on('pemutu_indikator')->nullOnDelete();
        });

        Schema::create('pemutu_indikator_doksub', function (Blueprint $table) {
            $table->id('indikdoksub_id');
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedBigInteger('doksub_id');
            $table->boolean('is_hasilkan_indikator')->default(false);
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            $table->foreign('doksub_id')->references('doksub_id')->on('pemutu_dok_sub')->cascadeOnDelete();
        });

        Schema::create('pemutu_indikator_label', function (Blueprint $table) {
            $table->id('indiklabel_id');
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedBigInteger('label_id');
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            $table->foreign('label_id')->references('label_id')->on('pemutu_label')->cascadeOnDelete();
        });

        Schema::create('pemutu_indikator_orgunit', function (Blueprint $table) {
            $table->id('indikorgunit_id');
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedBigInteger('org_unit_id');
            $table->unsignedBigInteger('prev_indikorgunit_id')->nullable();
            $table->string('target', 255)->nullable();
            $table->text('ed_capaian')->nullable();
            $table->text('ed_analisis')->nullable();
            $table->string('ed_attachment')->nullable();
            $table->json('ed_links')->nullable();
            $table->tinyInteger('ed_skala')->nullable()->comment('Skala yang dipilih auditee saat Evaluasi Diri (0-4)');
            $table->text('ed_ptp_isi')->nullable();
            $table->tinyInteger('ami_hasil_akhir')->nullable()->comment('Hasil AMI: 0=KTS, 1=Terpenuhi, 2=Terlampaui');
            $table->text('ami_hasil_temuan')->nullable()->comment('Catatan temuan auditor saat AMI');
            $table->text('ami_hasil_temuan_sebab')->nullable();
            $table->text('ami_hasil_temuan_akibat')->nullable();
            $table->text('ami_hasil_temuan_rekom')->nullable();
            $table->text('ami_rtp_isi')->nullable();
            $table->date('ami_rtp_tgl_pelaksanaan')->nullable();
            $table->text('ami_te_isi')->nullable();
            $table->string('pengend_status', 20)->nullable();
            $table->text('pengend_target')->nullable();
            $table->text('pengend_analisis')->nullable();
            $table->text('pengend_penyesuaian')->nullable();
            $table->string('pengend_important_matrix', 20)->nullable();
            $table->string('pengend_urgent_matrix', 20)->nullable();
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('indikator_id')->references('indikator_id')->on('pemutu_indikator')->cascadeOnDelete();
            $table->foreign('org_unit_id')->references('orgunit_id')->on('struktur_organisasi')->cascadeOnDelete();
            $table->foreign('prev_indikorgunit_id')->references('indikorgunit_id')->on('pemutu_indikator_orgunit')->nullOnDelete();
        });

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
            $table->string('attachment')->nullable();
            $table->string('status', 20)->default('draft'); // draft, submitted, approved, rejected
            $table->text('notes')->nullable();
            $table->string('unit_ukuran')->nullable();
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('pegawai_id')->references('pegawai_id')->on('pegawai')->cascadeOnDelete();
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

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('dok_id')->references('dok_id')->on('pemutu_dokumen')->cascadeOnDelete();
        });

        Schema::create('pemutu_dok_approval_status', function (Blueprint $table) {
            $table->id('dokstatusapproval_id');
            $table->unsignedBigInteger('dokapproval_id');
            $table->string('status_approval', 50);
            $table->text('komentar')->nullable();
            $table->timestamps();

            // Blameable
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
            $table->foreign('org_unit_id')->references('orgunit_id')->on('struktur_organisasi')->cascadeOnDelete();
            $table->foreign('pegawai_id')->references('pegawai_id')->on('pegawai')->cascadeOnDelete();

            $table->unique(['periodespmi_id', 'org_unit_id', 'pegawai_id', 'role'], 'tim_mutu_unique');
        });

        // Riwayat Approval for Pemutu
        Schema::create('pemutu_riwayat_approval', function (Blueprint $table) {
            $table->id('riwayatapproval_id');
            $table->string('model');
            $table->unsignedBigInteger('model_id');
            $table->enum('status', ['Draft', 'Pending', 'Approved', 'Rejected'])->default('Draft');
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
        });

        Schema::create('pemutu_diskusi', function (Blueprint $table) {
            $table->id('diskusi_id');
            $table->unsignedBigInteger('pengirim_user_id');
            $table->string('jenis_pengirim', 50)->comment('auditor / auditee / admin');
            $table->string('jenis_diskusi', 50)->default('ami')->comment('Konteks diskusi: ami, ed, dll');
            $table->morphs('model'); // Generates model_type (varchar) dan model_id (unsignedBigInteger)
            $table->text('isi');
            $table->string('attachment_file')->nullable();
            $table->json('attachment_link')->nullable()->comment('Array link dengan name+url');
            $table->boolean('is_done')->default(false);
            $table->timestamps();

            $table->foreign('pengirim_user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('pemutu_doksub_mapping', function (Blueprint $table) {
            $table->id('doksub_mapping_id');
            $table->unsignedBigInteger('doksub_id');        // Source poin (e.g. M1)
            $table->unsignedBigInteger('mapped_doksub_id'); // Target poin (e.g. V1)
            $table->timestamps();

            // Blameable
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

            $table->foreign('doksub_id')
                ->references('doksub_id')
                ->on('pemutu_dok_sub')
                ->cascadeOnDelete();

            $table->foreign('mapped_doksub_id')
                ->references('doksub_id')
                ->on('pemutu_dok_sub')
                ->cascadeOnDelete();

            $table->unique(['doksub_id', 'mapped_doksub_id'], 'doksub_mapping_unique');
        });

        // ============================================================
        // VIEWS
        // ============================================================

        \Illuminate\Support\Facades\DB::statement("
            CREATE OR REPLACE VIEW vw_pemutu_summary_indikator AS
            SELECT
                i.indikator_id,
                i.parent_id,
                i.type,
                i.kelompok_indikator,
                i.no_indikator,
                i.indikator,
                i.target AS target_indikator,
                i.seq,
                i.jenis_data,
                i.unit_ukuran,
                i.level_risk,
                i.origin_from,
                i.hash,
                i.skala,
                i.keterangan,
                i.created_at AS indikator_created_at,
                i.updated_at AS indikator_updated_at,

                -- Parent Indikator Info
                pi.no_indikator AS parent_no_indikator,
                pi.indikator AS parent_indikator,

                -- Agregasi Data ED (Evaluasi Diri) dari semua unit
                COUNT(DISTINCT io.ed_capaian) AS ed_total_units,
                COUNT(DISTINCT CASE WHEN io.ed_capaian IS NOT NULL AND io.ed_capaian != '' THEN io.org_unit_id END) AS ed_filled_units,
                GROUP_CONCAT(DISTINCT CONCAT(so.name, ': ', io.ed_capaian) SEPARATOR ' || ') AS ed_capaian_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.ed_capaian IS NOT NULL AND io.ed_capaian != '' THEN CONCAT(so.name, ': ', io.ed_analisis) END SEPARATOR ' || ') AS ed_analisis_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.ed_skala IS NOT NULL THEN CONCAT(so.name, '(', io.ed_skala, ')') END SEPARATOR ' || ') AS ed_skala_all_units,
                MAX(io.ed_skala) AS ed_skala_max,
                MIN(io.ed_skala) AS ed_skala_min,
                AVG(io.ed_skala) AS ed_skala_avg,

                -- Agregasi Data AMI (Audit Mutu Internal)
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir IS NOT NULL THEN io.org_unit_id END) AS ami_assessed_units,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir = 0 THEN io.org_unit_id END) AS ami_kts_units,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir = 1 THEN io.org_unit_id END) AS ami_terpenuhi_units,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir = 2 THEN io.org_unit_id END) AS ami_terlampaui_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.ami_hasil_temuan IS NOT NULL AND io.ami_hasil_temuan != '' THEN CONCAT(so.name, ': ', io.ami_hasil_temuan) END SEPARATOR ' || ') AS ami_temuan_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.ami_hasil_temuan_rekom IS NOT NULL AND io.ami_hasil_temuan_rekom != '' THEN CONCAT(so.name, ': ', io.ami_hasil_temuan_rekom) END SEPARATOR ' || ') AS ami_rekomendasi_all_units,

                -- Agregasi Data Pengendalian
                COUNT(DISTINCT CASE WHEN io.pengend_status IS NOT NULL AND io.pengend_status != '' THEN io.org_unit_id END) AS pengend_filled_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_status IS NOT NULL THEN CONCAT(so.name, ': ', io.pengend_status) END SEPARATOR ' || ') AS pengend_status_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_analisis IS NOT NULL AND io.pengend_analisis != '' THEN CONCAT(so.name, ': ', io.pengend_analisis) END SEPARATOR ' || ') AS pengend_analisis_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_important_matrix IS NOT NULL THEN CONCAT(so.name, ': ', io.pengend_important_matrix) END SEPARATOR ' || ') AS pengend_important_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_urgent_matrix IS NOT NULL THEN CONCAT(so.name, ': ', io.pengend_urgent_matrix) END SEPARATOR ' || ') AS pengend_urgent_all_units,

                -- Info Unit Organisasi (semua unit yang terkait)
                GROUP_CONCAT(DISTINCT so.name SEPARATOR ' || ') AS all_org_units,
                GROUP_CONCAT(DISTINCT so.code SEPARATOR ' || ') AS all_org_unit_codes,
                COUNT(DISTINCT io.org_unit_id) AS total_org_units,

                -- Agregasi Target per Unit
                GROUP_CONCAT(DISTINCT CONCAT(so.name, ': ', io.target) SEPARATOR ' || ') AS target_all_units,

                -- Labels (akan di-join terpisah)
                GROUP_CONCAT(DISTINCT l.name SEPARATOR ', ') AS all_labels,
                GROUP_CONCAT(DISTINCT l.color SEPARATOR ', ') AS all_label_colors,

                -- DokSub terkait
                GROUP_CONCAT(DISTINCT ds.judul SEPARATOR ' || ') AS all_doksub_judul,
                GROUP_CONCAT(DISTINCT ds.kode SEPARATOR ' || ') AS all_doksub_kode,

                -- KPI Pegawai (agregasi)
                COUNT(DISTINCT ip.pegawai_id) AS total_pegawai_with_kpi,
                GROUP_CONCAT(DISTINCT CONCAT('Pegawai ID:', ip.pegawai_id, ': ', ip.status) SEPARATOR ' || ') AS kpi_pegawai_status,
                AVG(ip.score) AS kpi_avg_score,
                MIN(ip.score) AS kpi_min_score,
                MAX(ip.score) AS kpi_max_score

            FROM pemutu_indikator i
            LEFT JOIN pemutu_indikator pi ON i.parent_id = pi.indikator_id
            LEFT JOIN pemutu_indikator_orgunit io ON i.indikator_id = io.indikator_id
            LEFT JOIN struktur_organisasi so ON io.org_unit_id = so.orgunit_id
            LEFT JOIN pemutu_indikator_label il ON i.indikator_id = il.indikator_id
            LEFT JOIN pemutu_label l ON il.label_id = l.label_id
            LEFT JOIN pemutu_indikator_doksub ids ON i.indikator_id = ids.indikator_id
            LEFT JOIN pemutu_dok_sub ds ON ids.doksub_id = ds.doksub_id
            LEFT JOIN pemutu_indikator_pegawai ip ON i.indikator_id = ip.indikator_id
            GROUP BY
                i.indikator_id, i.parent_id, i.type, i.kelompok_indikator, i.no_indikator, i.indikator, i.target, i.seq,
                i.jenis_data, i.unit_ukuran, i.level_risk, i.origin_from, i.hash, i.skala, i.keterangan,
                i.created_at, i.updated_at, pi.no_indikator, pi.indikator
            ORDER BY i.seq ASC;
        ");

        \Illuminate\Support\Facades\DB::statement("
            CREATE OR REPLACE VIEW vw_pemutu_summary_indikator_standar AS
            WITH ed_data AS (
                SELECT io.indikator_id,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ed_capaian, '-')) SEPARATOR ' ;; ') AS ed_capaian_detail,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ed_analisis, '-')) SEPARATOR ' ;; ') AS ed_analisis_detail,
                    GROUP_CONCAT(DISTINCT so.name SEPARATOR ' ;; ') AS all_unit_names
                FROM pemutu_indikator_orgunit io
                LEFT JOIN struktur_organisasi so ON io.org_unit_id = so.orgunit_id
                GROUP BY io.indikator_id
            ),
            ami_data AS (
                SELECT io.indikator_id,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', CASE io.ami_hasil_akhir WHEN 0 THEN 'KTS' WHEN 1 THEN 'Terpenuhi' WHEN 2 THEN 'Terlampaui' ELSE '-' END, '|', COALESCE(io.ami_hasil_temuan, '-')) SEPARATOR ' ;; ') AS ami_hasil_detail,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ami_hasil_temuan_rekom, '-')) SEPARATOR ' ;; ') AS ami_rekomendasi_detail
                FROM pemutu_indikator_orgunit io
                LEFT JOIN struktur_organisasi so ON io.org_unit_id = so.orgunit_id
                WHERE io.ami_hasil_akhir IS NOT NULL
                GROUP BY io.indikator_id
            ),
            pengend_data AS (
                SELECT io.indikator_id,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.pengend_status, '-'), '|', COALESCE(io.pengend_analisis, '-')) SEPARATOR ' ;; ') AS pengend_status_detail
                FROM pemutu_indikator_orgunit io
                LEFT JOIN struktur_organisasi so ON io.org_unit_id = so.orgunit_id
                WHERE io.pengend_status IS NOT NULL AND io.pengend_status != ''
                GROUP BY io.indikator_id
            ),
            label_data AS (
                SELECT il.indikator_id,
                    GROUP_CONCAT(DISTINCT CONCAT(l.name, '|', l.color) SEPARATOR ', ') AS label_details
                FROM pemutu_indikator_label il
                LEFT JOIN pemutu_label l ON il.label_id = l.label_id
                GROUP BY il.indikator_id
            )
            SELECT
                i.indikator_id, i.parent_id, i.type, i.kelompok_indikator, i.no_indikator, i.indikator, i.target AS target_indikator,
                i.seq, i.jenis_data, i.unit_ukuran, i.level_risk, i.origin_from, i.hash, i.skala, i.keterangan,
                i.created_at AS indikator_created_at, i.updated_at AS indikator_updated_at,
                pi.no_indikator AS parent_no_indikator, pi.indikator AS parent_indikator,
                COALESCE(ed.ed_capaian_detail, '-') AS ed_capaian_detail, COALESCE(ed.ed_analisis_detail, '-') AS ed_analisis_detail,
                COALESCE(ami.ami_hasil_detail, '-') AS ami_hasil_detail, COALESCE(ami.ami_rekomendasi_detail, '-') AS ami_rekomendasi_detail,
                COALESCE(pengend.pengend_status_detail, '-') AS pengend_status_detail,
                COALESCE(ed.all_unit_names, '-') AS all_unit_names,
                COALESCE(lbl.label_details, '-') AS label_details,
                COALESCE((
                    SELECT GROUP_CONCAT(DISTINCT CONCAT(COALESCE(dp.judul, d.judul), '|', COALESCE(dp.kode, d.kode)) SEPARATOR ' ;; ')
                    FROM pemutu_indikator_doksub ids
                    LEFT JOIN pemutu_dok_sub ds ON ids.doksub_id = ds.doksub_id
                    LEFT JOIN pemutu_dokumen d ON ds.dok_id = d.dok_id
                    LEFT JOIN pemutu_dokumen dp ON d.parent_id = dp.dok_id
                    WHERE ids.indikator_id = i.indikator_id
                ), '-') AS doksub_details
            FROM pemutu_indikator i
            LEFT JOIN pemutu_indikator pi ON i.parent_id = pi.indikator_id
            LEFT JOIN ed_data ed ON i.indikator_id = ed.indikator_id
            LEFT JOIN ami_data ami ON i.indikator_id = ami.indikator_id
            LEFT JOIN pengend_data pengend ON i.indikator_id = pengend.indikator_id
            LEFT JOIN label_data lbl ON i.indikator_id = lbl.indikator_id
            WHERE i.type = 'standar'
            ORDER BY i.seq ASC;
        ");

        \Illuminate\Support\Facades\DB::statement("
            CREATE OR REPLACE VIEW vw_pemutu_summary_indikator_performa AS
            SELECT
                pip.indikator_pegawai_id,
                pi.indikator_id,
                pip.pegawai_id,
                rd.nama AS pegawai_name,
                rd.nip AS pegawai_nip,
                so.orgunit_id AS unit_id,
                so.name AS unit_name,

                pi.kelompok_indikator,
                pi.no_indikator,
                pi.indikator,

                (SELECT parent.no_indikator FROM pemutu_indikator parent WHERE parent.indikator_id = pi.parent_id LIMIT 1) as parent_no_indikator,

                (SELECT GROUP_CONCAT(l.name SEPARATOR ', ') FROM pemutu_indikator_label pil JOIN pemutu_label l ON pil.label_id = l.label_id WHERE pil.indikator_id = pi.indikator_id) AS all_labels,
                (SELECT GROUP_CONCAT(l.color SEPARATOR ', ') FROM pemutu_indikator_label pil JOIN pemutu_label l ON pil.label_id = l.label_id WHERE pil.indikator_id = pi.indikator_id) AS all_label_colors,

                pip.status AS kpi_status,
                pip.score AS kpi_score,
                pip.realization AS kpi_realization,
                pip.target_value AS kpi_target,
                pip.weight AS kpi_weight

            FROM pemutu_indikator_pegawai pip
            JOIN pemutu_indikator pi ON pip.indikator_id = pi.indikator_id
            LEFT JOIN pegawai p ON pip.pegawai_id = p.pegawai_id
            LEFT JOIN hr_riwayat_datadiri rd ON p.latest_riwayatdatadiri_id = rd.riwayatdatadiri_id
            LEFT JOIN hr_riwayat_jabstruktural rp ON p.latest_riwayatjabstruktural_id = rp.riwayatjabstruktural_id
            LEFT JOIN struktur_organisasi so ON rp.org_unit_id = so.orgunit_id
            WHERE pi.type = 'performa' AND pi.deleted_at IS NULL AND pip.deleted_at IS NULL;
        ");
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("DROP VIEW IF EXISTS vw_pemutu_summary_indikator");
        \Illuminate\Support\Facades\DB::statement("DROP VIEW IF EXISTS vw_pemutu_summary_indikator_standar");
        \Illuminate\Support\Facades\DB::statement("DROP VIEW IF EXISTS vw_pemutu_summary_indikator_performa");

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('pemutu_doksub_mapping');
        Schema::dropIfExists('pemutu_diskusi');
        Schema::dropIfExists('pemutu_riwayat_approval');
        Schema::dropIfExists('pemutu_dok_approval_status');
        Schema::dropIfExists('pemutu_dok_approval');
        Schema::dropIfExists('pemutu_dok_approval'); // legacy table

        // pemutu_rapat tables removed
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
        Schema::dropIfExists('pemutu_label_type');
        Schema::dropIfExists('pemutu_tim_mutu');

        Schema::enableForeignKeyConstraints();
    }
};
