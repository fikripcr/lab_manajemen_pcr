<?php
namespace App\Models\Eoffice;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisLayananPic extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'eoffice_jenis_layanan_pic';
    protected $primaryKey = 'jlpic_id';

    protected $appends = ['encrypted_jlpic_id'];

    public function getEncryptedJlpicIdAttribute()
    {
        return encryptId($this->jlpic_id);
    }
    protected $fillable = [
        'jenislayanan_id',
        'user_id',
        'expired',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function getRouteKeyName()
    {
        return 'jlpic_id';
    }

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'jenislayanan_id', 'jenislayanan_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}
