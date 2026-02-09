<?php
namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatJabStruktural extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'hr_riwayat_jabstruktural';
    protected $primaryKey = 'riwayatjabstruktural_id';
    protected $guarded    = ['riwayatjabstruktural_id'];

    protected $casts = [
        'tgl_awal'       => 'date',
        'tgl_akhir'      => 'date',
        'tgl_pengesahan' => 'date',
    ];

    // Legacy relation - kept for backward compatibility
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }

    // Accessed via OrgUnit
    public function orgUnit()
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id', 'org_unit_id');
    }

    public function getNamaJabatanAttribute()
    {
        return $this->orgUnit ? $this->orgUnit->name : '-';
    }
}
