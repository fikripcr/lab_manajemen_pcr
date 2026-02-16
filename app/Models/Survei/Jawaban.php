<?php
namespace App\Models\Survei;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    use HasFactory;

    protected $table = 'survei_jawaban';

    protected $fillable = [
        'pengisian_id',
        'pertanyaan_id',
        'nilai_teks',
        'nilai_angka',
        'nilai_tanggal',
        'nilai_json',
        'opsi_id',
    ];

    protected $casts = [
        'nilai_tanggal' => 'date',
        'nilai_json'    => 'array',
    ];

    public function pengisian()
    {
        return $this->belongsTo(Pengisian::class, 'pengisian_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }

    public function opsi()
    {
        return $this->belongsTo(Opsi::class, 'opsi_id');
    }
}
