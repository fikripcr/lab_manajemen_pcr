<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update vw_pemutu_summary_indikator
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
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_status_atsn IS NOT NULL THEN CONCAT(so.name, ': ', io.pengend_status_atsn) END SEPARATOR ' || ') AS pengend_status_atsn_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_analisis IS NOT NULL AND io.pengend_analisis != '' THEN CONCAT(so.name, ': ', io.pengend_analisis) END SEPARATOR ' || ') AS pengend_analisis_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_analisis_atsn IS NOT NULL AND io.pengend_analisis_atsn != '' THEN CONCAT(so.name, ': ', io.pengend_analisis_atsn) END SEPARATOR ' || ') AS pengend_analisis_atsn_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_important_matrix_atsn IS NOT NULL THEN CONCAT(so.name, ': ', io.pengend_important_matrix_atsn) END SEPARATOR ' || ') AS pengend_important_atsn_all_units,
                GROUP_CONCAT(DISTINCT CASE WHEN io.pengend_urgent_matrix_atsn IS NOT NULL THEN CONCAT(so.name, ': ', io.pengend_urgent_matrix_atsn) END SEPARATOR ' || ') AS pengend_urgent_atsn_all_units,

                -- Info Unit Organisasi (semua unit yang terkait)
                GROUP_CONCAT(DISTINCT so.name SEPARATOR ' || ') AS all_org_units,
                GROUP_CONCAT(DISTINCT so.code SEPARATOR ' || ') AS all_org_unit_codes,
                COUNT(DISTINCT io.org_unit_id) AS total_org_units,

                -- Agregasi Target per Unit
                GROUP_CONCAT(DISTINCT CONCAT(so.name, ': ', io.target) SEPARATOR ' || ') AS target_all_units,

                -- Labels
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
            LEFT JOIN hr_struktur_organisasi so ON io.org_unit_id = so.orgunit_id
            LEFT JOIN pemutu_indikator_label il ON i.indikator_id = il.indikator_id
            LEFT JOIN pemutu_label l ON il.label_id = l.label_id
            LEFT JOIN pemutu_indikator_doksub ids ON i.indikator_id = ids.indikator_id
            LEFT JOIN pemutu_dok_sub ds ON ids.doksub_id = ds.doksub_id
            LEFT JOIN pemutu_indikator_pegawai ip ON i.indikator_id = ip.indikator_id
            GROUP BY
                i.indikator_id, i.parent_id, i.type, i.kelompok_indikator, i.no_indikator, i.indikator, i.target, i.seq,
                i.jenis_data, i.unit_ukuran, i.level_risk, i.origin_from, i.hash, i.skala, i.keterangan,
                i.created_at, i.updated_at, pi.no_indikator, pi.indikator
        ");

        // 2. Update vw_pemutu_summary_indikator_standar
        DB::statement("
            CREATE OR REPLACE VIEW vw_pemutu_summary_indikator_standar AS
            WITH ed_data AS (
                SELECT io.indikator_id,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ed_capaian, '-')) SEPARATOR ' ;; ') AS ed_capaian_detail,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ed_analisis, '-')) SEPARATOR ' ;; ') AS ed_analisis_detail,
                    GROUP_CONCAT(DISTINCT so.name SEPARATOR ' ;; ') AS all_unit_names
                FROM pemutu_indikator_orgunit io
                LEFT JOIN hr_struktur_organisasi so ON io.org_unit_id = so.orgunit_id
                GROUP BY io.indikator_id
            ),
            ami_data AS (
                SELECT io.indikator_id,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', CASE io.ami_hasil_akhir WHEN 0 THEN 'KTS' WHEN 1 THEN 'Terpenuhi' WHEN 2 THEN 'Terlampaui' ELSE '-' END, '|', COALESCE(io.ami_hasil_temuan, '-')) SEPARATOR ' ;; ') AS ami_hasil_detail,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ami_hasil_temuan_rekom, '-')) SEPARATOR ' ;; ') AS ami_rekomendasi_detail
                FROM pemutu_indikator_orgunit io
                LEFT JOIN hr_struktur_organisasi so ON io.org_unit_id = so.orgunit_id
                WHERE io.ami_hasil_akhir IS NOT NULL
                GROUP BY io.indikator_id
            ),
            pengend_data AS (
                SELECT io.indikator_id,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.pengend_status, '-'), '|', COALESCE(io.pengend_analisis, '-'), '|', COALESCE(io.pengend_status_atsn, '-')) SEPARATOR ' ;; ') AS pengend_status_detail
                FROM pemutu_indikator_orgunit io
                LEFT JOIN hr_struktur_organisasi so ON io.org_unit_id = so.orgunit_id
                WHERE (io.pengend_status IS NOT NULL AND io.pengend_status != '') OR (io.pengend_status_atsn IS NOT NULL AND io.pengend_status_atsn != '')
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
            ORDER BY i.seq ASC
        ");
    }

    public function down(): void
    {
    }
};
