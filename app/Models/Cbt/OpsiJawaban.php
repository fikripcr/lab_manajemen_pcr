<?php
namespace App\Models\Cbt;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsiJawaban extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table = 'cbt_opsi_jawaban';

    protected $fillable = [
        'soal_id',
        'label',
        'teks_jawaban',
        'media_url',
        'is_kunci_jawaban',
        'bobot_nilai',
    ];

    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }
}
