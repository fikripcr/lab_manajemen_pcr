<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LemburPegawai extends Model
{
    use SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'hr_lembur_pegawai';
    protected $primaryKey = 'lemburpegawai_id';

    protected $fillable = [
        'lembur_id',
        'pegawai_id',
        'catatan',
    ];

    protected $casts = [];

    protected $appends = ['encrypted_lemburpegawai_id'];

    public function getRouteKeyName()
    {
        return 'lemburpegawai_id';
    }

    public function getEncryptedLemburpegawaiIdAttribute()
    {
        return encryptId($this->lemburpegawai_id);
    }

    /**
     * Relasi ke Lembur
     */
    public function lembur(): BelongsTo
    {
        return $this->belongsTo(Lembur::class, 'lembur_id', 'lembur_id');
    }

    /**
     * Relasi ke Pegawai
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }



    /**
     * Scope untuk filter by lembur
     */
    public function scopeByLembur($query, int $lemburId)
    {
        return $query->where('lembur_id', $lemburId);
    }

    /**
     * Scope untuk filter by pegawai
     */
    public function scopeByPegawai($query, int $pegawaiId)
    {
        return $query->where('pegawai_id', $pegawaiId);
    }
}
