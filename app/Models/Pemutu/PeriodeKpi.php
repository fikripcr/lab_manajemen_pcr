<?php
namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeriodeKpi extends Model
{
    use HasFactory, Blameable, HashidBinding, SoftDeletes;

    protected $table      = 'pemutu_periode_kpi';
    protected $primaryKey = 'periode_kpi_id';
    protected $appends    = ['encrypted_periode_kpi_id'];
    protected $fillable   = [
        'nama',
        'semester',
        'tahun_akademik',
        'tahun',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_active'       => 'boolean',
    ];

    public function getEncryptedPeriodeKpiIdAttribute()
    {
        return encryptId($this->periode_kpi_id);
    }

    // Relationships
    public function kpiAssignments()
    {
        return $this->hasMany(IndikatorPersonil::class, 'periode_kpi_id', 'periode_kpi_id');
    }
}
