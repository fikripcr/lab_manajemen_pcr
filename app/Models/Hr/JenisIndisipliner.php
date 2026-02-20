<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisIndisipliner extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_jenis_indisipliner';
    protected $primaryKey = 'jenisindisipliner_id';

    protected $appends = ['encrypted_jenisindisipliner_id'];

    protected $fillable = [
        'jenis_indisipliner',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'jenisindisipliner_id' => 'integer',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'jenisindisipliner_id';
    }

    public function getEncryptedJenisindisiplinerIdAttribute()
    {
        return encryptId($this->jenisindisipliner_id);
    }

    /**
     * Get all indisipliner records of this type.
     */
    public function indisipliner()
    {
        return $this->hasMany(Indisipliner::class, 'jenisindisipliner_id', 'jenisindisipliner_id');
    }
}
