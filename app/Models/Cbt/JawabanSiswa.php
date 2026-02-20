<?php
namespace App\Models\Cbt;

use App\Models\Cbt\OpsiJawaban;
use App\Models\Cbt\RiwayatUjianSiswa;
use App\Models\Cbt\Soal;
use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
    protected $table      = 'cbt_jawaban_siswa';
    protected $primaryKey = 'jawaban_siswa_id';
    protected $appends    = ['encrypted_jawaban_siswa_id'];

    protected $fillable = [
        'riwayat_id',
        'soal_id',
        'opsi_dipilih_id',
        'jawaban_esai',
        'is_ragu',
        'nilai_didapat',
    ];

    protected $casts = [
        'is_ragu'       => 'boolean',
        'nilai_didapat' => 'decimal:2',
    ];

    public function getEncryptedJawabanSiswaIdAttribute()
    {
        return encryptId($this->jawaban_siswa_id);
    }

    public function getRouteKeyName()
    {
        return 'jawaban_siswa_id';
    }

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
