<?php
namespace App\Models\Pemutu;

use App\Models\Event\Rapat;
use App\Models\Event\RapatEntitas;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeriodeSpmi extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pemutu_periode_spmi';
    protected $primaryKey = 'periodespmi_id';
    protected $appends    = ['encrypted_periodespmi_id'];

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
        'penetapan_awal'     => 'date',
        'penetapan_akhir'    => 'date',
        'ed_awal'            => 'date',
        'ed_akhir'           => 'date',
        'ami_awal'           => 'date',
        'ami_akhir'          => 'date',
        'pengendalian_awal'  => 'date',
        'pengendalian_akhir' => 'date',
        'peningkatan_awal'   => 'date',
        'peningkatan_akhir'  => 'date',
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
     * Get the latest (current) RTM rapat for this periode.
     */
    public function getLatestRtmAttribute(): ?Rapat
    {
        $entitas = RapatEntitas::where('model', 'PeriodeSpmi')
            ->where('model_id', $this->periodespmi_id)
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
}
