<?php
namespace App\Models\Pemtu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgUnit extends Model
{
    use HasFactory;

    protected $table      = 'org_unit';
    protected $primaryKey = 'orgunit_id';
    protected $fillable   = ['parent_id', 'name', 'type', 'code', 'level', 'seq'];
    public $timestamps    = false; // No timestamps in migration

    // Relationships
    public function parent()
    {
        return $this->belongsTo(OrgUnit::class, 'parent_id', 'orgunit_id');
    }

    public function children()
    {
        return $this->hasMany(OrgUnit::class, 'parent_id', 'orgunit_id');
    }

    public function personils()
    {
        return $this->hasMany(Personil::class, 'org_unit_id', 'orgunit_id');
    }
}
