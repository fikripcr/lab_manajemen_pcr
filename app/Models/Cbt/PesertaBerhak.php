<?php
namespace App\Models\Cbt;

use App\Models\Cbt\JadwalUjian;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PesertaBerhak extends Model
{
    protected $table = 'cbt_peserta_berhak';

    protected $fillable = [
        'jadwal_id',
        'user_id',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalUjian::class, 'jadwal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
