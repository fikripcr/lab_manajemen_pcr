<?php

namespace App\Models\Pemutu;

use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Model;

class IndikatorSummaryPerforma extends Model
{
    use HashidBinding;

    protected $table      = 'vw_pemutu_summary_indikator_performa';
    protected $primaryKey = 'indikator_pegawai_id';
    public $incrementing  = false;
    public $timestamps    = false;

    protected $appends = ['encrypted_indikator_pegawai_id', 'encrypted_indikator_id'];

    protected $casts = [
        'indikator_pegawai_id' => 'integer',
        'indikator_id' => 'integer',
        'pegawai_id' => 'integer',
        'unit_id' => 'integer',
    ];

    /**
     * Get the encrypted indikator pegawai ID for hashid binding.
     */
    public function getEncryptedIndikatorPegawaiIdAttribute()
    {
        return encryptId($this->indikator_pegawai_id);
    }
    
    public function getEncryptedIndikatorIdAttribute()
    {
        return encryptId($this->indikator_id);
    }

    /**
     * Get labels as array from aggregated string.
     */
    public function getLabelsArrayAttribute(): array
    {
        if (empty($this->all_labels)) {
            return [];
        }

        $names  = explode(', ', $this->all_labels ?? '');
        $colors = explode(', ', $this->all_label_colors ?? '');

        return array_map(function ($name, $color) {
            return ['name' => $name, 'color' => $color];
        }, $names, $colors);
    }

    /**
     * Scope untuk filter berdasarkan pegawai.
     */
    public function scopeOfPegawai($query, $pegawaiId)
    {
        return $query->where('pegawai_id', $pegawaiId);
    }

    /**
     * Scope untuk filter berdasarkan unit.
     */
    public function scopeOfUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    /**
     * Scope untuk filter berdasarkan kelompok indikator.
     */
    public function scopeOfKelompok($query, string $kelompok)
    {
        return $query->where('kelompok_indikator', $kelompok);
    }

    /**
     * Scope untuk filter berdasarkan tahun dari periode_mulai.
     */
    public function scopeOfYear($query, int $year)
    {
        return $query->whereYear('periode_mulai', $year);
    }

    /**
     * Scope untuk search berdasarkan no_indikator, indikator text, atau nama pegawai.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('no_indikator', 'LIKE', "%{$search}%")
                ->orWhere('indikator', 'LIKE', "%{$search}%")
                ->orWhere('pegawai_name', 'LIKE', "%{$search}%")
                ->orWhere('pegawai_nip', 'LIKE', "%{$search}%")
                ->orWhere('all_labels', 'LIKE', "%{$search}%");
        });
    }
}
