<?php
namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personil extends Model
{
    use HasFactory, Blameable, HashidBinding;

    protected $table = 'pemutu_personil';
    protected $primaryKey = 'personil_id';
    protected $appends = ['encrypted_personil_id'];
    protected $fillable = [
        'user_id',
        'org_unit_id',
        'nama',
        'email',
        'ttd_digital',
        'jenis',
        'external_source',
        'external_id',
        'created_by',
        'updated_by',
    ];
    public $timestamps = false; // No timestamps in migration

    public function getEncryptedPersonilIdAttribute()
    {
        return encryptId($this->personil_id);
    }

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
