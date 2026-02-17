<?php
namespace App\Models\Cbt;

use App\Models\Cbt\PaketUjian;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalUjian extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table = 'cbt_jadwal_ujian';

    protected $fillable = [
        'paket_id',
        'nama_kegiatan',
        'waktu_mulai',
        'waktu_selesai',
        'token_ujian',
        'is_token_aktif',
    ];

    protected $casts = [
        'waktu_mulai'    => 'datetime',
        'waktu_selesai'  => 'datetime',
        'is_token_aktif' => 'boolean',
    ];

    public function paket()
    {
        return $this->belongsTo(PaketUjian::class, 'paket_id');
    }

    public function pesertaBerhak()
    {
        return $this->hasMany(PesertaBerhak::class, 'jadwal_id');
    }

    public function riwayatSiswa()
    {
        return $this->hasMany(RiwayatUjianSiswa::class, 'jadwal_id');
    }
}
