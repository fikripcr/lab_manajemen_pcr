<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW vw_pemutu_summary_indikator AS
            SELECT
                i.indikator_id,
                i.parent_id,
                i.type,
                i.kelompok_indikator,
                i.no_indikator,
                i.indikator,
                i.target AS target_indikator,
                i.jenis_indikator,
                i.seq,
                i.jenis_data,
                i.periode_jenis,
                i.periode_mulai,
                i.periode_selesai,
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

            -- Parent Indikator (self-join)
            LEFT JOIN pemutu_indikator pi ON i.parent_id = pi.indikator_id

            -- Pivot ke OrgUnit (untuk ED, AMI, Pengendalian)
            LEFT JOIN pemutu_indikator_orgunit io ON i.indikator_id = io.indikator_id

            -- OrgUnit Info
            LEFT JOIN struktur_organisasi so ON io.org_unit_id = so.orgunit_id

            -- Labels (melalui pivot table)
            LEFT JOIN pemutu_indikator_label il ON i.indikator_id = il.indikator_id
            LEFT JOIN pemutu_label l ON il.label_id = l.label_id

            -- DokSub (melalui pivot table)
            LEFT JOIN pemutu_indikator_doksub ids ON i.indikator_id = ids.indikator_id
            LEFT JOIN pemutu_dok_sub ds ON ids.doksub_id = ds.doksub_id

            -- KPI Pegawai
            LEFT JOIN pemutu_indikator_pegawai ip ON i.indikator_id = ip.indikator_id

            GROUP BY i.indikator_id
            ORDER BY i.seq ASC
        ");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_pemutu_summary_indikator");
    }
};
