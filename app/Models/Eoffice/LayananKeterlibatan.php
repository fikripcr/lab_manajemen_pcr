<?php
namespace App\Models\Eoffice;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LayananKeterlibatan extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_layanan_keterlibatan';
    protected $primaryKey = 'layananketerlibatan_id';

    protected $appends = ['encrypted_layananketerlibatan_id'];

    public function getRouteKeyName()
    {
        return 'layananketerlibatan_id';
    }

    public function getEncryptedLayananketerlibatanIdAttribute()
    {
        return encryptId($this->layananketerlibatan_id);
    }

    protected $fillable = [
        'layanan_id',
        'user_id',
        'peran', 'created_by', 'updated_by', 'deleted_by',

    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id', 'layanan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
