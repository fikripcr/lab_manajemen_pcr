<?php
namespace App\Models\Pmb;

use App\Models\Pmb\Pendaftaran;
use App\Models\Pmb\SesiUjian;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PesertaUjian extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pmb_peserta_ujian';
    protected $primaryKey = 'pesertaujian_id';
    protected $appends    = ['encrypted_pesertaujian_id'];

    public function getRouteKeyName()
    {
        return 'pesertaujian_id';
    }

    public function getEncryptedPesertaujianIdAttribute()
    {
        return encryptId($this->pesertaujian_id);
    }

    protected $fillable = [
        'pendaftaran_id',
        'sesi_id',
        'username_cbt',
        'password_cbt',
        'nilai_akhir',
        'status_kehadiran',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function sesi()
    {
        return $this->belongsTo(SesiUjian::class, 'sesi_id');
    }
}
