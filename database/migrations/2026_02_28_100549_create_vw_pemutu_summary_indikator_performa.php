<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_pemutu_summary_indikator_performa;');
        
        DB::statement("
            CREATE OR REPLACE VIEW vw_pemutu_summary_indikator_performa AS
            SELECT 
                pip.indikator_pegawai_id,
                pi.indikator_id,
                pip.pegawai_id,
                rd.nama AS pegawai_name,
                rd.nip AS pegawai_nip,
                so.orgunit_id AS unit_id,
                so.name AS unit_name,
                
                pi.periode_jenis,
                pi.periode_mulai,
                pi.periode_selesai,
                pi.kelompok_indikator,
                pi.no_indikator,
                pi.indikator,
                
                -- Parent Indikator (if any)
                (SELECT parent.no_indikator FROM pemutu_indikator parent WHERE parent.indikator_id = pi.parent_id LIMIT 1) as parent_no_indikator,
                
                -- Labels Aggregation
                (
                    SELECT GROUP_CONCAT(l.name SEPARATOR ', ')
                    FROM pemutu_indikator_label pil
                    JOIN pemutu_label l ON pil.label_id = l.label_id
                    WHERE pil.indikator_id = pi.indikator_id
                ) AS all_labels,
                (
                    SELECT GROUP_CONCAT(l.color SEPARATOR ', ')
                    FROM pemutu_indikator_label pil
                    JOIN pemutu_label l ON pil.label_id = l.label_id
                    WHERE pil.indikator_id = pi.indikator_id
                ) AS all_label_colors,

                -- KPI Details for this specific assignment
                pip.status AS kpi_status,
                pip.score AS kpi_score,
                pip.realization AS kpi_realization,
                pip.target_value AS kpi_target,
                pip.weight AS kpi_weight
                
            FROM pemutu_indikator_pegawai pip
            JOIN pemutu_indikator pi ON pip.indikator_id = pi.indikator_id
            LEFT JOIN pegawai p ON pip.pegawai_id = p.pegawai_id
            LEFT JOIN hr_riwayat_datadiri rd ON p.latest_riwayatdatadiri_id = rd.riwayatdatadiri_id
            LEFT JOIN hr_riwayat_penugasan rp ON p.latest_riwayatpenugasan_id = rp.riwayatpenugasan_id
            LEFT JOIN struktur_organisasi so ON rp.org_unit_id = so.orgunit_id
            WHERE pi.type = 'performa' AND pi.deleted_at IS NULL AND pip.deleted_at IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vw_pemutu_summary_indikator_performa');
    }
};
