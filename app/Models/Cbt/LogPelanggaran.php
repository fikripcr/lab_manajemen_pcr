<?php
namespace App\Models\Cbt;

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

    public function riwayat()
    {
        return $this->belongsTo(RiwayatUjianSiswa::class, 'riwayat_id');
    }
}
