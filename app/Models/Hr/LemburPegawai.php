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
        'override_nominal',
        'catatan',
    ];

    protected $casts = [
        'override_nominal' => 'decimal:2',
    ];

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
     * Accessor untuk nominal yang digunakan (override atau dari lembur)
     */
    public function getNominalEfektifAttribute(): float
    {
        if ($this->override_nominal) {
            return (float) $this->override_nominal;
        }

        return $this->lembur ? (float) $this->lembur->nominal_per_jam : 0;
    }

    /**
     * Accessor untuk total bayar pegawai ini
     */
    public function getTotalBayarAttribute(): float
    {
        if (! $this->lembur || ! $this->lembur->is_dibayar) {
            return 0;
        }

        $jam = $this->lembur->durasi_menit / 60;
        return round($jam * $this->nominal_efektif, 2);
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
