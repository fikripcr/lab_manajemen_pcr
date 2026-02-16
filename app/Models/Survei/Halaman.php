<?php
namespace App\Models\Survei;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Halaman extends Model
{
    use HasFactory;

    protected $table = 'survei_halaman';

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
