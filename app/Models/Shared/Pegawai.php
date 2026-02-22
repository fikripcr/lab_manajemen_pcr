<?php
namespace App\Models\Shared;

use App\Models\Hr\FilePegawai;
use App\Models\Hr\Keluarga;
use App\Models\Hr\PengembanganDiri;
use App\Models\Hr\RiwayatApproval;
use App\Models\Hr\RiwayatDataDiri;
use App\Models\Hr\RiwayatInpassing;
use App\Models\Hr\RiwayatJabFungsional;
use App\Models\Hr\RiwayatJabStruktural;
use App\Models\Hr\RiwayatPendidikan;
use App\Models\Hr\RiwayatPenugasan;
use App\Models\Hr\RiwayatStatAktifitas;
use App\Models\Hr\RiwayatStatPegawai;
use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pegawai';
    protected $primaryKey = 'pegawai_id';

    protected $appends = ['encrypted_pegawai_id'];

    public function getRouteKeyName()
    {
        return 'pegawai_id';
    }

    protected $fillable = [
        'latest_riwayatdatadiri_id',
        'latest_riwayatpendidikan_id',
        'latest_riwayatstatpegawai_id',
        'latest_riwayatstataktifitas_id',
        'latest_riwayatjabfungsional_id',
        'latest_riwayatjabstruktural_id',
        'latest_riwayatinpassing_id',
        'latest_riwayatpenugasan_id',
        'atasan1',
        'atasan2',
        'photo',
        'face_encoding',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function getEncryptedPegawaiIdAttribute()
    {
        return encryptId($this->pegawai_id);
    }

    // ----------------------------------------------------------------
    // Relationships to Latest History
    // ----------------------------------------------------------------

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

    public function latestInpassing()
    {
        return $this->belongsTo(RiwayatInpassing::class, 'latest_riwayatinpassing_id', 'riwayatinpassing_id');
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

    public function orgUnit()
    {
        return $this->hasOneThrough(
            \App\Models\Shared\StrukturOrganisasi::class,
            \App\Models\Hr\RiwayatPenugasan::class,
            'riwayatpenugasan_id',
            'orgunit_id',
            'latest_riwayatpenugasan_id',
            'org_unit_id'
        );
    }

    // ----------------------------------------------------------------
    // Core Relationships
    // ----------------------------------------------------------------

    public function user()
    {
        return $this->hasOne(User::class, 'pegawai_id', 'pegawai_id');
    }

    public function atasanSatu()
    {
        return $this->belongsTo(Pegawai::class, 'atasan1', 'pegawai_id');
    }

    public function atasanDua()
    {
        return $this->belongsTo(Pegawai::class, 'atasan2', 'pegawai_id');
    }

    // ----------------------------------------------------------------
    // History Relations
    // ----------------------------------------------------------------

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

    public function approvedPendidikan()
    {
        return $this->hasMany(RiwayatPendidikan::class, 'pegawai_id', 'pegawai_id')
            ->whereHas('approval', function ($q) {
                $q->where('status', 'Approved');
            })->orderBy('tgl_ijazah', 'desc');
    }

    public function approvedKeluarga()
    {
        return $this->hasMany(Keluarga::class, 'pegawai_id', 'pegawai_id')
            ->whereHas('approval', function ($q) {
                $q->where('status', 'Approved');
            });
    }

    public function approvedPengembangan()
    {
        return $this->hasMany(PengembanganDiri::class, 'pegawai_id', 'pegawai_id')
            ->whereHas('approval', function ($q) {
                $q->where('status', 'Approved');
            })->orderBy('tgl_mulai', 'desc');
    }

    public function allApprovals()
    {
        return RiwayatApproval::whereHasMorph('subject', [
            RiwayatDataDiri::class,
            RiwayatPendidikan::class,
            Keluarga::class,
            PengembanganDiri::class,
            RiwayatStatPegawai::class,
            RiwayatStatAktifitas::class,
            RiwayatJabFungsional::class,
            RiwayatJabStruktural::class,
            RiwayatInpassing::class,
        ], function ($q) {
            $q->where('pegawai_id', $this->pegawai_id);
        })->latest();
    }

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

    public function historyInpassing()
    {
        return $this->hasMany(RiwayatInpassing::class, 'pegawai_id', 'pegawai_id')->orderBy('tmt', 'desc');
    }

    public function files()
    {
        return $this->hasMany(FilePegawai::class, 'pegawai_id', 'pegawai_id')->orderBy('created_at', 'desc');
    }

    // ----------------------------------------------------------------
    // Accessors (proxied via latestDataDiri)
    // ----------------------------------------------------------------

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
