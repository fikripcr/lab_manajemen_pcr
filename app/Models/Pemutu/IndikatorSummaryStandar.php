<?php

namespace App\Models\Pemutu;

use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Model;

class IndikatorSummaryStandar extends Model
{
    use HashidBinding;

    protected $table      = 'vw_pemutu_summary_indikator_standar';
    protected $primaryKey = 'indikorgunit_id';
    public $incrementing  = false;
    public $timestamps    = false;

    protected $appends = ['encrypted_indikorgunit_id'];

    protected $casts = [
        'indikorgunit_id' => 'integer',
        'indikator_id'   => 'integer',
    ];

    /**
     * Get the encrypted ID for hashid binding.
     */
    public function getEncryptedIndikorgunitIdAttribute()
    {
        return encryptId($this->indikorgunit_id);
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
