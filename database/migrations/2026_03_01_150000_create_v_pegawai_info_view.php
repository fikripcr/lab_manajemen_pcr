<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVPegawaiInfoView extends Migration
{
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_pegawai_info');

        $ddCols  = Schema::getColumnListing('hr_riwayat_datadiri');
        $jfCols  = Schema::getColumnListing('hr_riwayat_jabfungsional');
        $penCols = Schema::getColumnListing('hr_riwayat_penugasan');

        $selects = [
            'p.pegawai_id', 'p.user_id', 'p.photo', 'p.latest_riwayatdatadiri_id',
            'posisi.name AS posisi_nama',
            'departemen.name AS departemen_nama',
            'pend.jenjang_pendidikan AS pendidikan_terakhir',
            'pend.bidang_ilmu AS pendidikan_jurusan',
            'pend.nama_pt AS pendidikan_pt',
            'sp.statuspegawai_id',
            'msp.nama_status AS status_pegawai_nama',
            'sa.statusaktifitas_id',
        ];

        $ddFields = [
            'nip', 'nidn', 'nama', 'inisial', 'email', 'no_hp', 'jenis_kelamin',
            'tempat_lahir', 'tgl_lahir', 'alamat', 'status_nikah', 'agama',
            'bidang_ilmu', 'orgunit_posisi_id', 'orgunit_departemen_id',
        ];
        foreach ($ddFields as $field) {
            $selects[] = (in_array($field, $ddCols) ? "dd.{$field}" : 'NULL') . " AS {$field}";
        }

        $jfNama       = in_array('nama_jabatan', $jfCols) ? 'jf.nama_jabatan' : (in_array('nama', $jfCols) ? 'jf.nama' : 'NULL');
        $penOrgUnitId = in_array('org_unit_id', $penCols) ? 'pen.org_unit_id' : 'NULL';
        $penJabatan   = in_array('jabatan', $penCols) ? 'pen.jabatan' : (in_array('nama_jabatan', $penCols) ? 'pen.nama_jabatan' : 'NULL');
        $penTglMulai  = in_array('tgl_mulai', $penCols) ? 'pen.tgl_mulai' : 'NULL';

        array_push($selects,
            "{$jfNama} AS jabatan_fungsional",
            "{$penOrgUnitId} AS penugasan_org_unit_id",
            "{$penJabatan} AS penugasan_jabatan",
            "{$penTglMulai} AS penugasan_tgl_mulai"
        );

        $joinsSql = "
            FROM pegawai p
            LEFT JOIN hr_riwayat_datadiri dd ON dd.riwayatdatadiri_id = p.latest_riwayatdatadiri_id AND dd.deleted_at IS NULL
            LEFT JOIN struktur_organisasi posisi ON posisi.orgunit_id = dd.orgunit_posisi_id
            LEFT JOIN struktur_organisasi departemen ON departemen.orgunit_id = dd.orgunit_departemen_id
            LEFT JOIN hr_riwayat_pendidikan pend ON pend.riwayatpendidikan_id = p.latest_riwayatpendidikan_id AND pend.deleted_at IS NULL
            LEFT JOIN hr_riwayat_statpegawai sp ON sp.riwayatstatpegawai_id = p.latest_riwayatstatpegawai_id AND sp.deleted_at IS NULL
            LEFT JOIN hr_status_pegawai msp ON msp.statuspegawai_id = sp.statuspegawai_id
            LEFT JOIN hr_riwayat_stataktifitas sa ON sa.riwayatstataktifitas_id = p.latest_riwayatstataktifitas_id AND sa.deleted_at IS NULL
            LEFT JOIN hr_riwayat_jabfungsional jf ON jf.riwayatjabfungsional_id = p.latest_riwayatjabfungsional_id AND jf.deleted_at IS NULL
            LEFT JOIN hr_riwayat_penugasan pen ON pen.riwayatpenugasan_id = p.latest_riwayatpenugasan_id AND pen.deleted_at IS NULL
            WHERE p.deleted_at IS NULL
        ";

        $selectSql = implode(', ', $selects);
        DB::statement('CREATE VIEW v_pegawai_info AS SELECT ' . $selectSql . ' ' . $joinsSql);
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_pegawai_info');
    }
}
