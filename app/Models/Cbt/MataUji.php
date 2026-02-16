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

    protected $table = 'cbt_mata_uji';

    protected $fillable = [
        'nama_mata_uji',
        'tipe',
        'deskripsi',
    ];

    public function soal()
    {
        return $this->hasMany(Soal::class, 'mata_uji_id');
    }
}
