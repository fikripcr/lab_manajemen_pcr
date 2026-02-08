<?php
namespace App\Models\Hr;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table = 'hr_pegawai';
    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'pegawai_id';
    }

    protected $fillable = [
        'latest_riwayatdatadiri_id',
        'latest_riwayatpendidikan_id',
        'latest_riwayatstatpegawai_id',
        'latest_riwayatstataktifitas_id',
        'latest_riwayatkelas_id',
        'latest_riwayatjabfungsional_id',
        'latest_riwayatjabstruktural_id',
        'atasan1',
        'atasan2',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relationships to Latest History
    public function latestDataDiri()
    {
        return $this->belongsTo(RiwayatDataDiri::class, 'latest_riwayatdatadiri_id', 'riwayatdatadiri_id');
    }

    public function latestPendidikan()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'latest_riwayatpendidikan_id', 'riwayatpendidikan_id');
    }

    public function latestStatusPegawai()
    {
        return $this->belongsTo(RiwayatStatPegawai::class, 'latest_riwayatstatpegawai_id', 'riwayatstatpegawai_id');
    }

    public function latestStatusAktifitas()
    {
        return $this->belongsTo(RiwayatStatAktifitas::class, 'latest_riwayatstataktifitas_id', 'riwayatstataktifitas_id');
    }

    public function latestKelas()
    {
        return $this->belongsTo(RiwayatKelas::class, 'latest_riwayatkelas_id', 'riwayatkelas_id');
    }

    public function latestJabatanFungsional()
    {
        return $this->belongsTo(RiwayatJabFungsional::class, 'latest_riwayatjabfungsional_id', 'riwayatjabfungsional_id');
    }

    public function latestJabatanStruktural()
    {
        return $this->belongsTo(RiwayatJabStruktural::class, 'latest_riwayatjabstruktural_id', 'riwayatjabstruktural_id');
    }

    public function latestPenugasan()
    {
        return $this->belongsTo(RiwayatPenugasan::class, 'latest_riwayatpenugasan_id', 'riwayatpenugasan_id');
    }

    public function historyPenugasan()
    {
        return $this->hasMany(RiwayatPenugasan::class, 'pegawai_id', 'pegawai_id')->orderBy('tgl_mulai', 'desc');
    }

    public function historyDataDiri()
    {
        return $this->hasMany(RiwayatDataDiri::class, 'pegawai_id', 'pegawai_id')->orderBy('created_at', 'desc');
    }

    public function keluarga()
    {
        return $this->hasMany(Keluarga::class, 'pegawai_id', 'pegawai_id');
    }

    public function riwayatPendidikan()
    {
        return $this->hasMany(RiwayatPendidikan::class, 'pegawai_id', 'pegawai_id')->orderBy('tgl_ijazah', 'desc');
    }

    public function pengembanganDiri()
    {
        return $this->hasMany(PengembanganDiri::class, 'pegawai_id', 'pegawai_id')->orderBy('tgl_mulai', 'desc');
    }

    public function atasanSatu()
    {
        return $this->belongsTo(Pegawai::class, 'atasan1', 'pegawai_id');
    }

    public function atasanDua()
    {
        return $this->belongsTo(Pegawai::class, 'atasan2', 'pegawai_id');
    }

    // History Relations (for lists of all changes)
    public function historyStatPegawai()
    {
        return $this->hasMany(RiwayatStatPegawai::class, 'pegawai_id', 'pegawai_id')->orderBy('tmt', 'desc');
    }

    public function historyStatAktifitas()
    {
        return $this->hasMany(RiwayatStatAktifitas::class, 'pegawai_id', 'pegawai_id')->orderBy('tmt', 'desc');
    }

    public function historyJabFungsional()
    {
        return $this->hasMany(RiwayatJabFungsional::class, 'pegawai_id', 'pegawai_id')->orderBy('tmt', 'desc');
    }

    public function historyJabStruktural()
    {
        return $this->hasMany(RiwayatJabStruktural::class, 'pegawai_id', 'pegawai_id')->orderBy('tgl_awal', 'desc');
    }

    // Direct Accessors (Proxies to latest data diri)
    public function getNamaAttribute()
    {
        return $this->latestDataDiri->nama ?? '-';
    }

    public function getNipAttribute()
    {
        return $this->latestDataDiri->nip ?? '-';
    }

    public function getEmailAttribute()
    {
        return $this->latestDataDiri->email ?? '-';
    }

    public function getInisialAttribute()
    {
        return $this->latestDataDiri->inisial ?? '-';
    }
}
