<?php
namespace App\Models\Survei;

use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    use HasFactory, HashidBinding;

    protected $table = 'survei_pertanyaan';
    protected $primaryKey = 'pertanyaan_id';
    protected $appends = ['encrypted_pertanyaan_id'];

    public function getRouteKeyName()
    {
        return 'pertanyaan_id';
    }

    public function getEncryptedPertanyaanIdAttribute()
    {
        return encryptId($this->pertanyaan_id);
    }

    protected $fillable = [
        'survei_id',
        'halaman_id',
        'teks_pertanyaan',
        'bantuan_teks',
        'tipe',
        'config_json',
        'wajib_diisi',
        'urutan',
        'next_pertanyaan_id',
    ];

    protected $casts = [
        'config_json' => 'array',
        'wajib_diisi' => 'boolean',
    ];

    public function survei()
    {
        return $this->belongsTo(Survei::class, 'survei_id');
    }

    public function halaman()
    {
        return $this->belongsTo(Halaman::class, 'halaman_id');
    }

    public function opsi()
    {
        return $this->hasMany(Opsi::class, 'pertanyaan_id')->orderBy('urutan');
    }

    public function logika()
    {
        return $this->hasOne(Logika::class, 'pertanyaan_pemicu_id');
    }

    public function nextPertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'next_pertanyaan_id');
    }
}
