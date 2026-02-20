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

    protected $table      = 'pmb_sesi_ujian';
    protected $primaryKey = 'sesiujian_id';
    protected $appends    = ['encrypted_sesiujian_id'];

    public function getRouteKeyName()
    {
        return 'sesiujian_id';
    }

    public function getEncryptedSesiujianIdAttribute()
    {
        return encryptId($this->sesiujian_id);
    }

    protected $fillable = [
        'periode_id',
        'nama_sesi',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'kuota',
    ];

    protected $casts = [
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function peserta()
    {
        return $this->hasMany(PesertaUjian::class, 'sesi_id');
    }
}
