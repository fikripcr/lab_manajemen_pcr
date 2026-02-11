<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatJabStruktural extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_riwayat_jabstruktural';
    protected $primaryKey = 'riwayatjabstruktural_id';
    protected $guarded    = ['riwayatjabstruktural_id'];

    protected $fillable = [
        'pegawai_id',
        'before_id',
        'org_unit_id',
        'tgl_awal',
        'tgl_akhir',
        'no_sk',
        'tgl_pengesahan',
        'keterangan',
        'created_by',
        'updated_by',
    ];

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

    public function orgUnit()
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id', 'org_unit_id');
    }

    public function before()
    {
        return $this->belongsTo(RiwayatJabStruktural::class, 'before_id', 'riwayatjabstruktural_id');
    }

    public function after()
    {
        return $this->hasOne(RiwayatJabStruktural::class, 'before_id', 'riwayatjabstruktural_id');
    }

    public function getNamaJabatanAttribute()
    {
        return $this->orgUnit?->name ?? '-';
    }
}
