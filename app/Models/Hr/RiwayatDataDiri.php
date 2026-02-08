<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatDataDiri extends Model
{
    use HasFactory, SoftDeletes;

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
        'no_kk',
        'gelar_depan',
        'gelar_belakang',
        'file_foto',
        'file_ttd_digital',
        'file_serdos',
        'posisi_id',
        'departemen_id',
        'prodi_id',
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
        return $this->belongsTo(Departemen::class, 'departemen_id', 'departemen_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id', 'prodi_id');
    }

    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'posisi_id', 'posisi_id');
    }

    public function approval()
    {
        return $this->belongsTo(RiwayatApproval::class, 'latest_riwayatapproval_id', 'riwayatapproval_id');
    }
}
