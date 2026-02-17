<?php
namespace App\Models\Survei;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opsi extends Model
{
    use HasFactory;

    protected $table = 'survei_opsi';

    protected $fillable = [
        'pertanyaan_id',
        'label',
        'nilai_tersimpan',
        'bobot_skor',
        'urutan',
        'next_pertanyaan_id',
    ];

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }

    public function nextPertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'next_pertanyaan_id');
    }
}
