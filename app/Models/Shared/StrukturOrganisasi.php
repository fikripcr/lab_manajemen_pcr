<?php
namespace App\Models\Shared;

use App\Models\Hr\RiwayatJabStruktural;
use App\Models\Pemutu\Indikator;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StrukturOrganisasi extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'struktur_organisasi';
    protected $primaryKey = 'orgunit_id';

    protected $appends = ['encrypted_org_unit_id'];

    public function getRouteKeyName()
    {
        return 'orgunit_id';
    }

    protected $fillable = [
        'parent_id',
        'name',
        'code',
        'type',
        'level',
        'seq',
        'sort_order',
        'is_active',
        'description',
        'successor_id',
        'auditee_user_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getEncryptedOrgUnitIdAttribute()
    {
        return encryptId($this->orgunit_id);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    public function parent()
    {
        return $this->belongsTo(StrukturOrganisasi::class, 'parent_id', 'orgunit_id');
    }

    public function children()
    {
        return $this->hasMany(StrukturOrganisasi::class, 'parent_id', 'orgunit_id');
    }

    public function activeChildren()
    {
        return $this->hasMany(StrukturOrganisasi::class, 'parent_id', 'orgunit_id')->where('is_active', true);
    }

    public function personils()
    {
        return $this->hasMany(Personil::class, 'org_unit_id', 'orgunit_id');
    }

    public function pegawai()
    {
        // Bridge through RiwayatJabStruktural (hr_riwayat_jabstruktural)
        return $this->hasManyThrough(
            Pegawai::class,
            RiwayatJabStruktural::class,
            'org_unit_id',
            'pegawai_id',
            'orgunit_id',
            'pegawai_id'
        );
    }

    public function successor()
    {
        return $this->belongsTo(StrukturOrganisasi::class, 'successor_id', 'orgunit_id');
    }

    public function predecessor()
    {
        return $this->hasOne(StrukturOrganisasi::class, 'successor_id', 'orgunit_id');
    }

    public function indikators()
    {
        return $this->belongsToMany(Indikator::class, 'pemutu_indikator_orgunit', 'org_unit_id', 'indikator_id')
            ->withPivot('target', 'ed_capaian', 'ed_analisis', 'ed_attachment', 'created_at');
    }

    public function auditee()
    {
        return $this->belongsTo(\App\Models\User::class, 'auditee_user_id', 'id');
    }
}
