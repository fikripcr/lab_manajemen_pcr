<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatDataDiri extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_riwayat_datadiri';
    protected $primaryKey = 'riwayatdatadiri_id';

    protected $fillable = [
        'pegawai_id',
        'nip',
        'nidn',
        'nama',
        'inisial',
        'email',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'alamat',
        'no_hp',
        'no_ktp',
        'status_nikah',
        'agama',
        'no_kk',
        'gelar_depan',
        'gelar_belakang',
        'file_foto',
        'file_ttd_digital',
        'file_serdos',
        'orgunit_posisi_id',
        'orgunit_departemen_id',
        'nama_buku',
        'no_rekening',
        'bank_pegawai',
        'npwp',
        'status_cuti',
        'absen_pin',
        'bidang_ilmu',
        'jenis_perubahan',
        'before_id',
        'latest_riwayatapproval_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }

    public function departemen()
    {
        return $this->belongsTo(OrgUnit::class, 'orgunit_departemen_id', 'orgunit_id');
    }

    public function posisi()
    {
        return $this->belongsTo(OrgUnit::class, 'orgunit_posisi_id', 'orgunit_id');
    }

    public function approval()
    {
        return $this->belongsTo(RiwayatApproval::class, 'latest_riwayatapproval_id', 'riwayatapproval_id');
    }
}
