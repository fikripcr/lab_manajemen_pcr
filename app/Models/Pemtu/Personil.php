<?php
namespace App\Models\Pemtu;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personil extends Model
{
    use HasFactory;

    protected $table      = 'personil';
    protected $primaryKey = 'personil_id';
    protected $fillable   = [
        'user_id',
        'org_unit_id',
        'nama',
        'email',
        'ttd_digital',
        'jenis',
        'external_source',
        'external_id',
    ];
    public $timestamps = false; // No timestamps in migration

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orgUnit()
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id', 'orgunit_id');
    }
}
