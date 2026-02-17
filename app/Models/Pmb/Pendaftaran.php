<?php
namespace App\Models\Pmb;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pendaftaran extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table = 'pmb_pendaftaran';

    protected $fillable = [
        'no_pendaftaran',
        'user_id',
        'periode_id',
        'jalur_id',
        'status_terkini',
        'nim_final',
        'orgunit_diterima_id',
        'waktu_daftar',
    ];

    /**
     * Accessor to get encrypted ID
     */
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function jalur()
    {
        return $this->belongsTo(Jalur::class, 'jalur_id');
    }

    public function orgUnitDiterima()
    {
        return $this->belongsTo(\App\Models\Shared\StrukturOrganisasi::class, 'orgunit_diterima_id');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatPendaftaran::class, 'pendaftaran_id');
    }

    public function pilihanProdi()
    {
        return $this->hasMany(PilihanProdi::class, 'pendaftaran_id');
    }

    public function dokumenUpload()
    {
        return $this->hasMany(DokumenUpload::class, 'pendaftaran_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'pendaftaran_id');
    }

    public function pesertaUjian()
    {
        return $this->hasOne(PesertaUjian::class, 'pendaftaran_id');
    }
}
