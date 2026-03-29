<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Recreate vw_pemutu_summary_indikator with new polymorphic schema
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
                pi.no_indikator AS parent_no_indikator,
                pi.indikator AS parent_indikator,
                COUNT(DISTINCT io.org_unit_id) AS ed_total_units,
                COUNT(DISTINCT CASE WHEN io.ed_capaian IS NOT NULL AND io.ed_capaian != '' THEN io.org_unit_id END) AS ed_filled_units,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir IS NOT NULL THEN io.org_unit_id END) AS ami_assessed_units,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir = 0 THEN io.org_unit_id END) AS ami_kts_units,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir = 1 THEN io.org_unit_id END) AS ami_terpenuhi_units,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir = 2 THEN io.org_unit_id END) AS ami_terlampaui_units,
                COUNT(DISTINCT CASE WHEN io.pengend_status IS NOT NULL AND io.pengend_status != '' THEN io.org_unit_id END) AS pengend_filled_units,
                GROUP_CONCAT(DISTINCT l.name SEPARATOR ', ') AS all_labels,
                GROUP_CONCAT(DISTINCT l.color SEPARATOR ', ') AS all_label_colors
            FROM pemutu_indikator i
            LEFT JOIN pemutu_indikator pi ON i.parent_id = pi.indikator_id
            LEFT JOIN pemutu_indikator_orgunit io ON i.indikator_id = io.indikator_id
            LEFT JOIN pemutu_indikator_label il ON i.indikator_id = il.indikator_id
            LEFT JOIN pemutu_label l ON il.label_id = l.label_id
            GROUP BY
                i.indikator_id, i.parent_id, i.type, i.kelompok_indikator, i.no_indikator, i.indikator, i.target, i.seq,
                i.jenis_data, i.unit_ukuran, i.level_risk, i.origin_from, i.hash, i.skala, i.keterangan,
                i.created_at, i.updated_at, pi.no_indikator, pi.indikator
        ");

        // Recreate vw_pemutu_summary_indikator_standar with new polymorphic schema
        DB::statement("
            CREATE OR REPLACE VIEW vw_pemutu_summary_indikator_standar AS
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
                pi.no_indikator AS parent_no_indikator,
                pi.indikator AS parent_indikator,
                GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ed_capaian, '-')) SEPARATOR ' ;; ') AS ed_capaian_detail,
                GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ed_analisis, '-')) SEPARATOR ' ;; ') AS ed_analisis_detail,
                GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', CASE io.ami_hasil_akhir WHEN 0 THEN 'KTS' WHEN 1 THEN 'Terpenuhi' WHEN 2 THEN 'Terlampaui' ELSE '-' END, '|', COALESCE(io.ami_hasil_temuan, '-')) SEPARATOR ' ;; ') AS ami_hasil_detail,
                GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ami_hasil_temuan_rekom, '-')) SEPARATOR ' ;; ') AS ami_rekomendasi_detail,
                GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.pengend_status, '-')) SEPARATOR ' ;; ') AS pengend_status_detail,
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
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_pemutu_summary_indikator_standar');
        DB::statement('DROP VIEW IF EXISTS vw_pemutu_summary_indikator');
    }
};
