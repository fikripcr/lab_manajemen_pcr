<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perizinan extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_perizinan';
    protected $primaryKey = 'perizinan_id';

    protected $fillable = [
        'jenisizin_id',
        'pengusul',
        'pekerjaan_ditinggalkan',
        'keterangan',
        'alamat_izin',
        'file_pendukung',
        'tgl_awal',
        'tgl_akhir',
        'jam_awal',
        'jam_akhir',
        'list_tgl_tidakmasuk',
        'uang_cuti',
        'latest_riwayatapproval_id',
        'uang_cuti_bayar',
        'keluarga_id',
        'periode',        'created_by',        'updated_by',        'deleted_by',
    
    
    
    ];

    protected $casts = [
        'tgl_awal'  => 'date',
        'tgl_akhir' => 'date',
    ];

    /**
     * Get the jenis izin.
     */
    public function jenisIzin()
    {
        return $this->belongsTo(JenisIzin::class, 'jenisizin_id', 'jenisizin_id');
    }

    /**
     * Get the pegawai (pengusul).
     */
    public function pengusulPegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pengusul', 'pegawai_id');
    }

    /**
     * Get the latest approval record.
     */
    public function latestApproval()
    {
        return $this->belongsTo(RiwayatApproval::class, 'latest_riwayatapproval_id', 'riwayatapproval_id');
    }

    /**
     * Get all approval history.
     */
    public function approvalHistory()
    {
        return $this->hasMany(RiwayatApproval::class, 'model_id', 'perizinan_id')
            ->where('model', 'Perizinan')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the keluarga if applicable.
     */
    public function keluarga()
    {
        return $this->belongsTo(Keluarga::class, 'keluarga_id', 'keluarga_id');
    }

    /**
     * Get status from latest approval.
     */
    public function getStatusAttribute()
    {
        return $this->latestApproval?->status ?? 'Draft';
    }
}
