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
        $sql = <<<SQL
CREATE OR REPLACE VIEW vw_pemutu_dashboard_indikator AS
SELECT 
    io.indikorgunit_id,
    io.indikator_id,
    io.org_unit_id,
    so.code as unit_name,
    i.kelompok_indikator,
    io.ami_hasil_akhir,
    io.pengend_status,
    io.pengend_important_matrix,
    io.pengend_urgent_matrix,
    io.ed_skala,
    d.periode as tahun,
    COALESCE(d.kode, d.judul) as dokumen_name,
    ds.dok_id
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
SQL;
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_pemutu_dashboard_indikator');
    }
};
