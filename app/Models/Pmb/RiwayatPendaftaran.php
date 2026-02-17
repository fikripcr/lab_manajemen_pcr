<?php
namespace App\Models\Pmb;

use App\Models\Pmb\Pendaftaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pmb_riwayat_pendaftaran';

    protected $fillable = [
        'pendaftaran_id',
        'status_baru',
        'keterangan',
        'user_pelaku_id',
        'waktu_kejadian',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function pelaku()
    {
        return $this->belongsTo(User::class, 'user_pelaku_id');
    }
}
