<?php

namespace App\Models\Pemutu;

use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Model;

class IndikatorSummaryStandar extends Model
{
    use HashidBinding;

    protected $table      = 'vw_pemutu_summary_indikator_standar';
    protected $primaryKey = 'indikator_id';
    public $incrementing  = false;
    public $timestamps    = false;

    protected $appends = ['encrypted_indikator_id'];

    protected $casts = [
        'indikator_id' => 'integer',
    ];

    /**
     * Get the encrypted indikator ID for hashid binding.
     */
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
     * Get ED capaian per unit as array.
     */
    public function getEdCapaianPerUnitAttribute(): array
    {
        if (empty($this->ed_capaian_all_units)) {
            return [];
        }

        $result = [];
        $items  = explode(' || ', $this->ed_capaian_all_units);

        foreach ($items as $item) {
            if (strpos($item, ': ') !== false) {
                [$unit, $capaian] = explode(': ', $item, 2);
                $result[$unit]    = $capaian;
            }
        }

        return $result;
    }

    /**
     * Get AMI hasil akhir summary.
     */
    public function getAmiHasilSummaryAttribute(): array
    {
        return [
            'total_assessed' => $this->ami_assessed_units ?? 0,
            'kts'            => $this->ami_kts_units ?? 0,
            'terpenuhi'      => $this->ami_terpenuhi_units ?? 0,
            'terlampaui'     => $this->ami_terlampaui_units ?? 0,
        ];
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
     * Scope untuk search berdasarkan no_indikator atau indikator text.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('no_indikator', 'LIKE', "%{$search}%")
                ->orWhere('indikator', 'LIKE', "%{$search}%")
                ->orWhere('all_labels', 'LIKE', "%{$search}%");
        });
    }
}
