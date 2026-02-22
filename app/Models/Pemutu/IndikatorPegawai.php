<?php
namespace App\Models\Pemutu;

use App\Models\Shared\Pegawai;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndikatorPegawai extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'pemutu_indikator_pegawai';
    protected $primaryKey = 'indikator_pegawai_id';

    protected $appends = ['encrypted_indikator_pegawai_id'];

    public function getRouteKeyName()
    {
        return 'indikator_pegawai_id';
    }

    public function getEncryptedIndikatorPegawaiIdAttribute()
    {
        return encryptId($this->indikator_pegawai_id);
    }
    protected $fillable = [
        'pegawai_id',
        'indikator_id',
        'periode_kpi_id',
        'year',
        'weight',
        'target_value',
        'realization',
        'score',
        'attachment',
        'status',
        'notes',
        'unit_ukuran',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function periodeKpi()
    {
        return $this->belongsTo(PeriodeKpi::class, 'periode_kpi_id', 'periode_kpi_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'pegawai_id');
    }

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'indikator_id', 'indikator_id');
    }
}
