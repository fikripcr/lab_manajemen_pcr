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
        // ============================================================
        // VIEW 1: vw_pemutu_summary_indikator_standar
        // Untuk Indikator Standar - Tampil detail ED, AMI, Pengendalian
        // ============================================================
        DB::statement("
            CREATE OR REPLACE VIEW vw_pemutu_summary_indikator_standar AS
            WITH ed_data AS (
                -- Ambil ED data per indikator (GROUP_CONCAT untuk multiple units)
                SELECT 
                    io.indikator_id,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ed_capaian, '-')) SEPARATOR ' ;; ') AS ed_capaian_detail,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ed_analisis, '-')) SEPARATOR ' ;; ') AS ed_analisis_detail,
                    GROUP_CONCAT(DISTINCT so.name SEPARATOR ' ;; ') AS all_unit_names
                FROM pemutu_indikator_orgunit io
                LEFT JOIN struktur_organisasi so ON io.org_unit_id = so.orgunit_id
                GROUP BY io.indikator_id
            ),
            ami_data AS (
                -- Ambil AMI hasil per indikator
                SELECT 
                    io.indikator_id,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', 
                        CASE io.ami_hasil_akhir 
                            WHEN 0 THEN 'KTS' 
                            WHEN 1 THEN 'Terpenuhi' 
                            WHEN 2 THEN 'Terlampaui' 
                            ELSE '-' 
                        END, '|',
                        COALESCE(io.ami_hasil_temuan, '-')
                    ) SEPARATOR ' ;; ') AS ami_hasil_detail,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.ami_hasil_temuan_rekom, '-')) SEPARATOR ' ;; ') AS ami_rekomendasi_detail
                FROM pemutu_indikator_orgunit io
                LEFT JOIN struktur_organisasi so ON io.org_unit_id = so.orgunit_id
                WHERE io.ami_hasil_akhir IS NOT NULL
                GROUP BY io.indikator_id
            ),
            pengend_data AS (
                -- Ambil Pengendalian status per indikator
                SELECT 
                    io.indikator_id,
                    GROUP_CONCAT(DISTINCT CONCAT(so.name, '|', COALESCE(io.pengend_status, '-'), '|', COALESCE(io.pengend_analisis, '-')) SEPARATOR ' ;; ') AS pengend_status_detail
                FROM pemutu_indikator_orgunit io
                LEFT JOIN struktur_organisasi so ON io.org_unit_id = so.orgunit_id
                WHERE io.pengend_status IS NOT NULL AND io.pengend_status != ''
                GROUP BY io.indikator_id
            ),
            label_data AS (
                SELECT 
                    il.indikator_id,
                    GROUP_CONCAT(DISTINCT CONCAT(l.name, '|', l.color) SEPARATOR ', ') AS label_details
                FROM pemutu_indikator_label il
                LEFT JOIN pemutu_label l ON il.label_id = l.label_id
                GROUP BY il.indikator_id
            )
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
                
                -- ED Detail (capaian & analisis per unit)
                COALESCE(ed.ed_capaian_detail, '-') AS ed_capaian_detail,
                COALESCE(ed.ed_analisis_detail, '-') AS ed_analisis_detail,
                
                -- AMI Detail (hasil & rekomendasi per unit)
                COALESCE(ami.ami_hasil_detail, '-') AS ami_hasil_detail,
                COALESCE(ami.ami_rekomendasi_detail, '-') AS ami_rekomendasi_detail,
                
                -- Pengendalian Detail (status & analisis per unit)
                COALESCE(pengend.pengend_status_detail, '-') AS pengend_status_detail,
                
                -- Unit Names (untuk badge)
                COALESCE(ed.all_unit_names, '-') AS all_unit_names,
                
                -- Labels
                COALESCE(lbl.label_details, '-') AS label_details,
                
                -- DokSub terkait
                COALESCE((
                    SELECT GROUP_CONCAT(DISTINCT CONCAT(ds.judul, '|', ds.kode) SEPARATOR ' ;; ')
                    FROM pemutu_indikator_doksub ids
                    LEFT JOIN pemutu_dok_sub ds ON ids.doksub_id = ds.doksub_id
                    WHERE ids.indikator_id = i.indikator_id
                ), '-') AS doksub_details
                
            FROM pemutu_indikator i
            
            -- Parent Indikator (self-join)
            LEFT JOIN pemutu_indikator pi ON i.parent_id = pi.indikator_id
            
            -- ED Data
            LEFT JOIN ed_data ed ON i.indikator_id = ed.indikator_id
            
            -- AMI Data
            LEFT JOIN ami_data ami ON i.indikator_id = ami.indikator_id
            
            -- Pengendalian Data
            LEFT JOIN pengend_data pengend ON i.indikator_id = pengend.indikator_id
            
            -- Labels
            LEFT JOIN label_data lbl ON i.indikator_id = lbl.indikator_id
            
            WHERE i.type = 'standar'
            
            ORDER BY i.seq ASC
        ");

        // ============================================================
        // VIEW 2: vw_pemutu_summary_indikator_performa
        // Untuk Indikator Performa - Tampil detail KPI per pegawai
        // ============================================================
        DB::statement("
            CREATE OR REPLACE VIEW vw_pemutu_summary_indikator_performa AS
            WITH kpi_data AS (
                -- Ambil KPI detail per pegawai
                SELECT 
                    ip.indikator_id,
                    GROUP_CONCAT(DISTINCT CONCAT(
                        COALESCE(rd.nama, CONCAT('Pegawai ID:', ip.pegawai_id)), '|',
                        ip.status, '|',
                        COALESCE(ip.score, 0), '|',
                        COALESCE(ip.realization, '-')
                    ) SEPARATOR ' ;; ') AS kpi_pegawai_detail,
                    COUNT(DISTINCT ip.pegawai_id) AS total_pegawai,
                    COUNT(DISTINCT CASE WHEN ip.status = 'draft' THEN ip.pegawai_id END) AS kpi_draft_count,
                    COUNT(DISTINCT CASE WHEN ip.status = 'submitted' THEN ip.pegawai_id END) AS kpi_submitted_count,
                    COUNT(DISTINCT CASE WHEN ip.status = 'approved' THEN ip.pegawai_id END) AS kpi_approved_count,
                    COUNT(DISTINCT CASE WHEN ip.status = 'rejected' THEN ip.pegawai_id END) AS kpi_rejected_count,
                    AVG(ip.score) AS kpi_avg_score,
                    MIN(ip.score) AS kpi_min_score,
                    MAX(ip.score) AS kpi_max_score
                FROM pemutu_indikator_pegawai ip
                LEFT JOIN pegawai p_pegawai ON ip.pegawai_id = p_pegawai.pegawai_id
                LEFT JOIN hr_riwayat_datadiri rd ON p_pegawai.latest_riwayatdatadiri_id = rd.riwayatdatadiri_id
                GROUP BY ip.indikator_id
            ),
            label_data AS (
                SELECT 
                    il.indikator_id,
                    GROUP_CONCAT(DISTINCT CONCAT(l.name, '|', l.color) SEPARATOR ', ') AS label_details
                FROM pemutu_indikator_label il
                LEFT JOIN pemutu_label l ON il.label_id = l.label_id
                GROUP BY il.indikator_id
            ),
            unit_data AS (
                SELECT 
                    io.indikator_id,
                    GROUP_CONCAT(DISTINCT so.name SEPARATOR ' ;; ') AS all_unit_names
                FROM pemutu_indikator_orgunit io
                LEFT JOIN struktur_organisasi so ON io.org_unit_id = so.orgunit_id
                GROUP BY io.indikator_id
            )
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
                
                -- KPI Detail (per pegawai)
                COALESCE(kpi.kpi_pegawai_detail, '-') AS kpi_pegawai_detail,
                COALESCE(kpi.total_pegawai, 0) AS total_pegawai,
                COALESCE(kpi.kpi_draft_count, 0) AS kpi_draft_count,
                COALESCE(kpi.kpi_submitted_count, 0) AS kpi_submitted_count,
                COALESCE(kpi.kpi_approved_count, 0) AS kpi_approved_count,
                COALESCE(kpi.kpi_rejected_count, 0) AS kpi_rejected_count,
                COALESCE(kpi.kpi_avg_score, 0) AS kpi_avg_score,
                COALESCE(kpi.kpi_min_score, 0) AS kpi_min_score,
                COALESCE(kpi.kpi_max_score, 0) AS kpi_max_score,
                
                -- Labels
                COALESCE(lbl.label_details, '-') AS label_details,
                
                -- Unit Names (untuk badge)
                COALESCE(uni.all_unit_names, '-') AS all_unit_names,
                
                -- DokSub terkait
                COALESCE((
                    SELECT GROUP_CONCAT(DISTINCT CONCAT(ds.judul, '|', ds.kode) SEPARATOR ' ;; ')
                    FROM pemutu_indikator_doksub ids
                    LEFT JOIN pemutu_dok_sub ds ON ids.doksub_id = ds.doksub_id
                    WHERE ids.indikator_id = i.indikator_id
                ), '-') AS doksub_details
                
            FROM pemutu_indikator i
            
            -- Parent Indikator (self-join)
            LEFT JOIN pemutu_indikator pi ON i.parent_id = pi.indikator_id
            
            -- KPI Data
            LEFT JOIN kpi_data kpi ON i.indikator_id = kpi.indikator_id
            
            -- Labels
            LEFT JOIN label_data lbl ON i.indikator_id = lbl.indikator_id
            
            -- Units
            LEFT JOIN unit_data uni ON i.indikator_id = uni.indikator_id
            
            WHERE i.type = 'performa'
            
            ORDER BY i.seq ASC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_pemutu_summary_indikator_standar");
        DB::statement("DROP VIEW IF EXISTS vw_pemutu_summary_indikator_performa");
    }
};
