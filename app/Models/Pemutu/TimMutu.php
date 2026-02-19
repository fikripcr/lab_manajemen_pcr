<?php
namespace App\Models\Pemutu;

use App\Models\Shared\Pegawai;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimMutu extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pemutu_tim_mutu';
    protected $primaryKey = 'id';

    protected $fillable = [
        'periodespmi_id',
        'org_unit_id',
        'pegawai_id',
        'role',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // ----------------------------------------------------------------
    // Scopes
    // ----------------------------------------------------------------

    public function scopeForPeriode($query, $periodeId)
    {
        return $query->where('periodespmi_id', $periodeId);
    }

    public function scopeForUnit($query, $unitId)
    {
        return $query->where('org_unit_id', $unitId);
    }

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    public function periode()
    {
        return $this->belongsTo(PeriodeSpmi::class, 'periodespmi_id', 'periodespmi_id');
    }

    public function orgUnit()
    {
        return $this->belongsTo(\App\Models\Shared\StrukturOrganisasi::class, 'org_unit_id', 'orgunit_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }
}
