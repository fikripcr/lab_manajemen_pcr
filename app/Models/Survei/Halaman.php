<?php
namespace App\Models\Survei;

use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Halaman extends Model
{
    use HasFactory, HashidBinding;

    protected $table = 'survei_halaman';
    protected $primaryKey = 'halaman_id';
    protected $appends = ['encrypted_halaman_id'];

    public function getRouteKeyName()
    {
        return 'halaman_id';
    }

    public function getEncryptedHalamanIdAttribute()
    {
        return encryptId($this->halaman_id);
    }

    protected $fillable = [
        'survei_id',
        'judul_halaman',
        'urutan',
        'deskripsi_halaman',
    ];

    public function survei()
    {
        return $this->belongsTo(Survei::class, 'survei_id');
    }

    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'halaman_id')->orderBy('urutan');
    }
}
