<?php
namespace App\Models\Cbt;

use App\Models\Cbt\RiwayatUjianSiswa;
use Illuminate\Database\Eloquent\Model;

class LogPelanggaran extends Model
{
    protected $table      = 'cbt_log_pelanggaran';
    protected $primaryKey = 'log_pelanggaran_id';
    protected $appends    = ['encrypted_log_pelanggaran_id'];

    protected $fillable = [
        'riwayat_id',
        'jenis_pelanggaran',
        'waktu_kejadian',
        'keterangan',
    ];

    public function getEncryptedLogPelanggaranIdAttribute()
    {
        return encryptId($this->log_pelanggaran_id);
    }

    public function getRouteKeyName()
    {
        return 'log_pelanggaran_id';
    }

    protected $casts = [
        'waktu_kejadian' => 'datetime',
    ];

    public function riwayatUjianSiswa()
    {
        return $this->belongsTo(RiwayatUjianSiswa::class, 'riwayat_id');
    }
}
