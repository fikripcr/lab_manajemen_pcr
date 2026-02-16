<?php
namespace App\Models\Survei;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logika extends Model
{
    use HasFactory;

    protected $table = 'survei_logika';

    protected $fillable = [
        'survei_id',
        'pertanyaan_pemicu_id',
        'operator',
        'nilai_pemicu',
        'aksi',
        'target_halaman_id',
        'target_pertanyaan_id',
    ];

    public function survei()
    {
        return $this->belongsTo(Survei::class, 'survei_id');
    }

    public function pertanyaanPemicu()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_pemicu_id');
    }

    public function targetHalaman()
    {
        return $this->belongsTo(Halaman::class, 'target_halaman_id');
    }

    public function targetPertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'target_pertanyaan_id');
    }
}
