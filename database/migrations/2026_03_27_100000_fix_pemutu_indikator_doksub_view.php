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
                i.target,
                i.seq,
                i.jenis_data,
                i.unit_ukuran,
                i.level_risk,
                i.origin_from,
                i.hash,
                i.skala,
                i.keterangan,
                i.created_at as indikator_created_at,
                i.updated_at as indikator_updated_at,
                pi.no_indikator as parent_no_indikator,
                pi.indikator as parent_indikator,
                COUNT(DISTINCT io.org_unit_id) AS ed_total_units,
                COUNT(DISTINCT CASE WHEN io.ed_capaian IS NOT NULL AND io.ed_capaian != '' THEN io.org_unit_id END) AS ed_filled_units,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir IS NOT NULL THEN io.org_unit_id END) AS ami_assessed_units,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir = 0 THEN io.org_unit_id END) AS ami_kts_units,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir = 1 THEN io.org_unit_id END) AS ami_terpenuhi_units,
                COUNT(DISTINCT CASE WHEN io.ami_hasil_akhir = 2 THEN io.org_unit_id END) AS ami_terlampaui_units,
                COUNT(DISTINCT CASE WHEN io.pengend_status IS NOT NULL AND io.pengend_status != '' THEN io.org_unit_id END) AS pengend_filled_units,
                GROUP_CONCAT(DISTINCT l.name SEPARATOR ', ') AS all_labels,
                GROUP_CONCAT(DISTINCT l.color SEPARATOR ', ') AS all_label_colors,
                GROUP_CONCAT(DISTINCT ds.judul SEPARATOR ' || ') AS all_doksub_judul,
                GROUP_CONCAT(DISTINCT ds.kode SEPARATOR ' || ') AS all_doksub_kode,
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
            LEFT JOIN pemutu_indikator_doksub ids ON i.indikator_id = ids.source_id AND ids.source_type = 'App\Models\Pemutu\Indikator'
            LEFT JOIN pemutu_dok_sub ds ON ids.doksub_id = ds.doksub_id
            LEFT JOIN pemutu_indikator_pegawai ip ON i.indikator_id = ip.indikator_id
            GROUP BY
                i.indikator_id, i.parent_id, i.type, i.kelompok_indikator, i.no_indikator, i.indikator, i.target, i.seq,
                i.jenis_data, i.unit_ukuran, i.level_risk, i.origin_from, i.hash, i.skala, i.keterangan,
                i.created_at, i.updated_at, pi.no_indikator, pi.indikator
            ORDER BY i.seq ASC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert view to previous definition
    }
};
