<?php
namespace App\Models\Pmb;

use App\Models\Pmb\Periode;
use App\Models\Pmb\PesertaUjian;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SesiUjian extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table = 'pmb_sesi_ujian';

    protected $fillable = [
        'periode_id',
        'nama_sesi',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'kuota',
    ];

    /**
     * Accessor to get encrypted ID
     */
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function peserta()
    {
        return $this->hasMany(PesertaUjian::class, 'sesi_id');
    }
}
