<?php
namespace App\Models\Pmb;

use App\Models\Pmb\Pendaftaran;
use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pmb_pembayaran';
    protected $primaryKey = 'pembayaran_id';
    protected $appends    = ['encrypted_pembayaran_id'];

    public function getRouteKeyName()
    {
        return 'pembayaran_id';
    }

    public function getEncryptedPembayaranIdAttribute()
    {
        return encryptId($this->pembayaran_id);
    }

    protected $fillable = [
        'pendaftaran_id',
        'jenis_bayar',
        'jumlah_bayar',
        'bukti_bayar_path',
        'status_verifikasi',
        'verifikator_id',
        'waktu_bayar',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }
}
