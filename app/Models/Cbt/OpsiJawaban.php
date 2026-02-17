<?php
namespace App\Models\Cbt;

use App\Models\Cbt\Soal;
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

    protected $casts = [
        'is_kunci_jawaban' => 'boolean',
    ];

    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }
}
