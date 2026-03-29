<?php

namespace App\Models\Sys;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model SysPeriode - Global Period/Milestone Management
 * 
 * Digunakan untuk mengelola periode/milestone di berbagai modul:
 * - Pemutu (SPMI, KPI)
 * - PMB (Pendaftaran)
 * - Eoffice (Layanan)
 * - Event (Event periods)
 * - Dan modul lainnya
 * 
 * ## Usage Example:
 * 
 * ```php
 * // SPMI Period
 * SysPeriode::create([
 *     'name' => 'SPMI 2026 - Akademik',
 *     'type' => 'spmi',
 *     'year' => 2026,
 *     'metadata' => [
 *         'jenis_periode' => 'Akademik',
 *         'ed_awal' => '2026-01-01',
 *         'ed_akhir' => '2026-03-31',
 *     ]
 * ]);
 * 
 * // KPI Period
 * SysPeriode::create([
 *     'name' => 'KPI Q1 2026',
 *     'type' => 'kpi',
 *     'year' => 2026,
 *     'start_date' => '2026-01-01',
 *     'end_date' => '2026-03-31',
 *     'is_active' => true
 * ]);
 * ```
 * 
 * @package App\Models\Sys
 */
class SysPeriode extends Model
{
    use Blameable, HasFactory, HashidBinding, SoftDeletes;

    protected $table = 'sys_periodes';

    protected $primaryKey = 'sys_periode_id';

    protected $appends = ['encrypted_sys_periode_id'];

    public function getRouteKeyName()
    {
        return 'sys_periode_id';
    }

    public function getEncryptedSysPeriodeIdAttribute()
    {
        return encryptId($this->sys_periode_id);
    }

    /**
     * The attributes that are mass assignable.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'year',
        'start_date',
        'end_date',
        'is_active',
        'metadata',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be cast.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Scope untuk mendapatkan periode yang aktif.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mendapatkan periode berdasarkan type.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope untuk mendapatkan periode berdasarkan year.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $year
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope untuk mendapatkan periode yang sedang berjalan (tanggal sekarang di antara start dan end).
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrent($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('is_active', true);
    }

    /**
     * Check apakah periode ini sedang aktif (tanggal sekarang di antara start dan end).
     * 
     * @return bool
     */
    public function isCurrent(): bool
    {
        if (!$this->start_date || !$this->end_date) {
            return false;
        }

        return now()->between($this->start_date, $this->end_date);
    }

    /**
     * Get metadata value by key.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getMeta(string $key, $default = null)
    {
        return $this->metadata[$key] ?? $default;
    }

    /**
     * Set metadata value by key.
     * 
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setMeta(string $key, $value)
    {
        $this->metadata = array_merge($this->metadata ?? [], [$key => $value]);
        return $this;
    }

    /**
     * Get all models that belong to this period (polymorphic).
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function periodable()
    {
        return $this->morphedByMany(Model::class, 'periodable', 'sys_periodeables');
    }
}
