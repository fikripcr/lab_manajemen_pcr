<?php
namespace App\Models\Cbt;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaketUjian extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table = 'cbt_paket_ujian';

    protected $fillable = [
        'nama_paket',
        'tipe_paket',
        'total_soal',
        'total_durasi_menit',
        'is_acak_soal',
        'is_acak_opsi',
        'kk_nilai_minimal',
        'dibuat_oleh',
    ];

    public function komposisi()
    {
        return $this->hasMany(KomposisiPacket::class, 'paket_id');
    }

    public function jadwal()
    {
        return $this->hasMany(JadwalUjian::class, 'paket_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
