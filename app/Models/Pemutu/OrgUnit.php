<?php
namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrgUnit extends Model
{
    use HasFactory, Blameable, HashidBinding, SoftDeletes;

    protected $table = 'pemutu_org_unit';
    protected $primaryKey = 'orgunit_id';
    protected $appends = ['encrypted_org_unit_id'];
    protected $fillable = [
        'parent_id', 
        'name', 
        'type', 
        'code', 
        'level', 
        'seq', 
        'is_active', 
        'successor_id', 
        'auditee_user_id',
        'created_by',
        'updated_by',        'deleted_by',
    
    ];
    public $timestamps = false;

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

    // Relationships
    public function parent()
    {
        return $this->belongsTo(OrgUnit::class, 'parent_id', 'orgunit_id');
    }

    public function children()
    {
        return $this->hasMany(OrgUnit::class, 'parent_id', 'orgunit_id');
    }

    public function activeChildren()
    {
        return $this->hasMany(OrgUnit::class, 'parent_id', 'orgunit_id')->where('is_active', true);
    }

    public function personils()
    {
        return $this->hasMany(Personil::class, 'org_unit_id', 'orgunit_id');
    }

    public function successor()
    {
        return $this->belongsTo(OrgUnit::class, 'successor_id', 'orgunit_id');
    }

    public function predecessor()
    {
        return $this->hasOne(OrgUnit::class, 'successor_id', 'orgunit_id');
    }

    public function auditee()
    {
        return $this->belongsTo(\App\Models\User::class, 'auditee_user_id', 'id');
    }
}
