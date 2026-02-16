<?php
namespace App\Models\Pmb;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PesertaUjian extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table = 'pmb_peserta_ujian';

    protected $fillable = [
        'pendaftaran_id',
        'sesi_id',
        'username_cbt',
        'password_cbt',
        'nilai_akhir',
        'status_kehadiran',
    ];

    /**
     * Accessor to get encrypted ID
     */
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function sesi()
    {
        return $this->belongsTo(SesiUjian::class, 'sesi_id');
    }
}
