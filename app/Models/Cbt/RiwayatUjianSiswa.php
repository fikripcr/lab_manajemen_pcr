<?php
namespace App\Models\Cbt;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatUjianSiswa extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table = 'cbt_riwayat_ujian_siswa';

    protected $fillable = [
        'jadwal_id',
        'user_id',
        'waktu_mulai',
        'waktu_selesai',
        'sisa_waktu_terakhir',
        'nilai_akhir',
        'status',
        'ip_address',
        'browser_info',
    ];

    protected $casts = [
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalUjian::class, 'jadwal_id');
    }

    public function jawaban()
    {
        return $this->hasMany(JawabanSiswa::class, 'riwayat_id');
    }

    public function logPelanggaran()
    {
        return $this->hasMany(LogPelanggaran::class, 'riwayat_id');
    }
}
