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
    public function jabatanStruktural()
    {
        return $this->belongsTo(JabatanStruktural::class, 'jabstruktural_id', 'jabstruktural_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }

    // New relation using OrgUnit
    public function orgUnit()
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id', 'org_unit_id');
    }

    // Accessor to get name from either OrgUnit or legacy JabatanStruktural
    public function getNamaJabatanAttribute()
    {
        if ($this->orgUnit) {
            return $this->orgUnit->name;
        }
        if ($this->jabatanStruktural) {
            return $this->jabatanStruktural->jabstruktural;
        }
        return '-';
    }
}
