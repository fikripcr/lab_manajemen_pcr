<?php

namespace App\Models\Pemutu;

use App\Models\Event\Rapat;
use App\Models\Event\RapatEntitas;
use App\Models\Sys\SysPeriode;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * PeriodeSpmi Model - Backward Compatibility Wrapper
 * 
 * DEPRECATED: Use SysPeriode instead for new development
 * This wrapper maintains backward compatibility while migrating to SysPeriode
 * 
 * @deprecated Use SysPeriode with type='spmi' instead
 */
class PeriodeSpmi extends Model
{
    use Blameable, HasFactory, HashidBinding, SoftDeletes;

    protected $table = 'pemutu_periode_spmi';

    protected $primaryKey = 'periodespmi_id';

    protected $appends = ['encrypted_periodespmi_id'];

    public function getRouteKeyName()
    {
        return 'periodespmi_id';
    }

    public function getEncryptedPeriodespmiIdAttribute()
    {
        return encryptId($this->periodespmi_id);
    }

    protected $fillable = [
        'periode',
        'jenis_periode',
        'penetapan_awal',
        'penetapan_akhir',
        'ed_awal',
        'ed_akhir',
        'ami_awal',
        'ami_akhir',
        'pengendalian_awal',
        'pengendalian_akhir',
        'peningkatan_awal',
        'peningkatan_akhir',
    ];

    protected $casts = [
        'penetapan_awal' => 'date',
        'penetapan_akhir' => 'date',
        'ed_awal' => 'date',
        'ed_akhir' => 'date',
        'ami_awal' => 'date',
        'ami_akhir' => 'date',
        'pengendalian_awal' => 'date',
        'pengendalian_akhir' => 'date',
        'peningkatan_awal' => 'date',
        'peningkatan_akhir' => 'date',
    ];

    /**
     * Get all RTM rapats linked to this periode via event_rapat_entitas.
     */
    public function rapatEntitas()
    {
        return RapatEntitas::where('model', 'PeriodeSpmi')
            ->where('model_id', $this->periodespmi_id);
    }

    /**
     * Get the latest RTM Pengendalian rapat for this periode.
     */
    public function getLatestRtmPengendalianAttribute(): ?Rapat
    {
        $entitas = RapatEntitas::where('model', 'PeriodeSpmi')
            ->where('model_id', $this->periodespmi_id)
            ->where('keterangan', 'like', 'RTM Pengendalian%')
            ->latest('created_at')
            ->first();

        return $entitas ? Rapat::find($entitas->rapat_id) : null;
    }

    /**
     * Get the latest RTM Peningkatan rapat for this periode.
     */
    public function getLatestRtmPeningkatanAttribute(): ?Rapat
    {
        $entitas = RapatEntitas::where('model', 'PeriodeSpmi')
            ->where('model_id', $this->periodespmi_id)
            ->where('keterangan', 'like', 'RTM Peningkatan%')
            ->latest('created_at')
            ->first();

        return $entitas ? Rapat::find($entitas->rapat_id) : null;
    }

    /**
     * Get all RTM rapats for this periode.
     */
    public function getRtmRapatsAttribute()
    {
        $rapatIds = RapatEntitas::where('model', 'PeriodeSpmi')
            ->where('model_id', $this->periodespmi_id)
            ->pluck('rapat_id');

        return Rapat::whereIn('rapat_id', $rapatIds)->orderByDesc('tgl_rapat')->get();
    }

    /**
     * Convert to SysPeriode format
     * 
     * @return array
     */
    public function toSysPeriodeData(): array
    {
        return [
            'name' => "SPMI {$this->periode} - {$this->jenis_periode}",
            'type' => 'spmi',
            'year' => $this->periode,
            'start_date' => $this->penetapan_awal,
            'end_date' => $this->peningkatan_akhir,
            'is_active' => true, // You may want to determine this based on dates
            'metadata' => [
                'jenis_periode' => $this->jenis_periode,
                'penetapan_awal' => $this->penetapan_awal?->toDateString(),
                'penetapan_akhir' => $this->penetapan_akhir?->toDateString(),
                'ed_awal' => $this->ed_awal?->toDateString(),
                'ed_akhir' => $this->ed_akhir?->toDateString(),
                'ami_awal' => $this->ami_awal?->toDateString(),
                'ami_akhir' => $this->ami_akhir?->toDateString(),
                'pengendalian_awal' => $this->pengendalian_awal?->toDateString(),
                'pengendalian_akhir' => $this->pengendalian_akhir?->toDateString(),
                'peningkatan_awal' => $this->peningkatan_awal?->toDateString(),
                'peningkatan_akhir' => $this->peningkatan_akhir?->toDateString(),
                'legacy_id' => $this->periodespmi_id,
                'legacy_table' => 'pemutu_periode_spmi',
            ]
        ];
    }

    /**
     * Get related SysPeriode if exists
     * 
     * @return SysPeriode|null
     */
    public function getSysPeriode(): ?SysPeriode
    {
        return SysPeriode::where('type', 'spmi')
            ->where('year', $this->periode)
            ->whereJsonContains('metadata->legacy_id', $this->periodespmi_id)
            ->first();
    }
}
