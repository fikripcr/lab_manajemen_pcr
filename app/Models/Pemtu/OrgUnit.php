<?php
namespace App\Models\Pemtu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgUnit extends Model
{
    use HasFactory;

    protected $table      = 'org_unit';
    protected $primaryKey = 'orgunit_id';
    protected $fillable   = ['parent_id', 'name', 'type', 'code', 'level', 'seq', 'is_active', 'successor_id', 'auditee_user_id'];
    public $timestamps    = false;

    protected $casts = [
        'is_active' => 'boolean',
    ];

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
