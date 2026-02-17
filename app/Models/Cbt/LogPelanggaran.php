<?php
namespace App\Models\Cbt;

use App\Models\Cbt\RiwayatUjianSiswa;
use Illuminate\Database\Eloquent\Model;

class LogPelanggaran extends Model
{
    protected $table = 'cbt_log_pelanggaran';

    protected $fillable = [
        'riwayat_id',
        'jenis_pelanggaran',
        'waktu_kejadian',
        'keterangan',
    ];

    protected $casts = [
        'waktu_kejadian' => 'datetime',
    ];

    public function riwayatUjianSiswa()
    {
        return $this->belongsTo(RiwayatUjianSiswa::class, 'riwayat_id');
    }
}
