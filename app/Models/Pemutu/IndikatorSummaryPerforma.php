<?php

namespace App\Models\Pemutu;

use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Model;

class IndikatorSummaryPerforma extends Model
{
    use HashidBinding;

    protected $table      = 'vw_pemutu_summary_indikator_performa';
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
     * Get KPI scores summary.
     */
    public function getKpiScoreSummaryAttribute(): array
    {
        return [
            'total_pegawai' => $this->total_pegawai_with_kpi ?? 0,
            'avg_score'     => round($this->kpi_avg_score ?? 0, 2),
            'min_score'     => $this->kpi_min_score ?? 0,
            'max_score'     => $this->kpi_max_score ?? 0,
            'draft'         => $this->kpi_draft_count ?? 0,
            'submitted'     => $this->kpi_submitted_count ?? 0,
            'approved'      => $this->kpi_approved_count ?? 0,
            'rejected'      => $this->kpi_rejected_count ?? 0,
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
