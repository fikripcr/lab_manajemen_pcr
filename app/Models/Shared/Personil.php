<?php
namespace App\Models\Shared;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personil extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'personil';
    protected $primaryKey = 'personil_id';

    protected $appends = ['encrypted_personil_id'];

    public function getRouteKeyName()
    {
        return 'personil_id';
    }

    protected $fillable = [
        'user_id',
        'org_unit_id',
        'nama',
        'email',
        'nip',
        'posisi',
        'tipe',
        'vendor',
        'ttd_digital',
        'status_aktif',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function getEncryptedPersonilIdAttribute()
    {
        return encryptId($this->personil_id);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status_aktif', true);
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orgUnit()
    {
        return $this->belongsTo(StrukturOrganisasi::class, 'org_unit_id', 'orgunit_id');
    }
}
