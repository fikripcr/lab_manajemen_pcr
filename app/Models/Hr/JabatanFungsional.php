<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JabatanFungsional extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_jabatan_fungsional';
    protected $primaryKey = 'jabfungsional_id';

    protected $appends = ['encrypted_jabfungsional_id'];

    public function getRouteKeyName()
    {
        return 'jabfungsional_id';
    }

    public function getEncryptedJabfungsionalIdAttribute()
    {
        return encryptId($this->jabfungsional_id);
    }

    protected $fillable = [
        'kode_jabatan',
        'jabfungsional',
        'is_active',
        'tunjangan',
        'created_by',
        'updated_by', 'deleted_by',

    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

}
