<?php
namespace App\Models\Cbt;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataUji extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'cbt_mata_uji';
    protected $primaryKey = 'mata_uji_id';
    protected $appends    = ['encrypted_mata_uji_id'];

    protected $fillable = [
        'nama_mata_uji',
        'tipe',
        'durasi_menit',
        'deskripsi',
    ];

    public function getEncryptedMataUjiIdAttribute()
    {
        return encryptId($this->mata_uji_id);
    }

    public function getRouteKeyName()
    {
        return 'mata_uji_id';
    }

    public function soal()
    {
        return $this->hasMany(Soal::class, 'mata_uji_id');
    }
}
