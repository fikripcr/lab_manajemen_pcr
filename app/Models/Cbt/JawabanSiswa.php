<?php
namespace App\Models\Cbt;

use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
    protected $table = 'cbt_jawaban_siswa';

    protected $fillable = [
        'riwayat_id',
        'soal_id',
        'opsi_dipilih_id',
        'jawaban_esai',
        'is_ragu',
        'nilai_didapat',
    ];

    public function riwayat()
    {
        return $this->belongsTo(RiwayatUjianSiswa::class, 'riwayat_id');
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }

    public function opsi()
    {
        return $this->belongsTo(OpsiJawaban::class, 'opsi_dipilih_id');
    }
}
