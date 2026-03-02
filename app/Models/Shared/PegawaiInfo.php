<?php
namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;

/**
 * Read-only Eloquent model backed by the `v_pegawai_info` database view.
 *
 * This view flattens the latest approved data from:
 *   - hr_riwayat_datadiri (latest_riwayatdatadiri_id)
 *   - hr_riwayat_pendidikan (latest_riwayatpendidikan_id)
 *   - hr_riwayat_stat_pegawai (latest_riwayatstatpegawai_id)
 *   - hr_riwayat_stat_aktifitas (latest_riwayatstataktifitas_id)
 *   - hr_riwayat_jab_fungsional (latest_riwayatjabfungsional_id)
 *   - hr_riwayat_jabstruktural (latest_riwayatjabstruktural_id)
 *
 * Use this model ONLY for reading consolidated pegawai info.
 * Do NOT mutate via this model — use Pegawai + riwayat models for writes.
 */
class PegawaiInfo extends Model
{
    protected $table      = 'v_pegawai_info';
    protected $primaryKey = 'pegawai_id';

    /**
     * This model is backed by a view — no timestamps managed here.
     */
    public $timestamps = false;

    /**
     * The view is read-only. Prevent any writes.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(fn() => false);
        static::updating(fn() => false);
        static::deleting(fn() => false);
    }

    /**
     * Columns available in the view (informational).
     */
    protected $visible = [
        'pegawai_id',
        'user_id',
        'photo',
        'nama',
        'nip',
        'nidn',
        'inisial',
        'email',
        'no_hp',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'alamat',
        'status_nikah',
        'agama',
        'gelar_depan',
        'gelar_belakang',
        'bidang_ilmu',
        'orgunit_posisi_id',
        'orgunit_departemen_id',
        'posisi_nama',
        'departemen_nama',
        'pendidikan_terakhir',
        'pendidikan_jurusan',
        'pendidikan_pt',
        'status_pegawai_id',
        'status_pegawai_nama',
        'status_aktifitas',
        'jabatan_fungsional',
        'struktural_org_unit_id',
        'struktural_jabatan',
        'struktural_tgl_awal',
    ];
}
