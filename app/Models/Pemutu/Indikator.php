<?php
namespace App\Models\Pemutu;

use App\Models\Hr\StrukturOrganisasi;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indikator extends Model
{
    use HasFactory, Blameable, HashidBinding, SoftDeletes;

    protected $table      = 'pemutu_indikator';
    protected $primaryKey = 'indikator_id';
    protected $appends    = ['encrypted_indikator_id'];

    public function getRouteKeyName()
    {
        return 'indikator_id';
    }
    protected $fillable = [
        'type',
        'kelompok_indikator',
        'parent_id',
        'renstra_poin_id',
        'prev_indikator_id',
        'no_indikator',
        'indikator',
        'target',
        'unit_ukuran',
        'jenis_data',
        'seq',
        'level_risk',
        'origin_from',
        'hash',
        'peningkat_nonaktif_indik',
        'is_new_indik_after_peningkatan',
        'skala',
        'keterangan',
        'created_by',
        'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'skala' => 'array',
    ];

    public $timestamps = false;

    public function getEncryptedIndikatorIdAttribute()
    {
        return encryptId($this->indikator_id);
    }

    // Relationships
    public function dokSubs()
    {
        return $this->morphToMany(DokSub::class, 'source', 'pemutu_indikator_doksub', 'source_id', 'doksub_id')
            ->withPivot('is_hasilkan_indikator')
            ->withTimestamps();
    }

    // Many-to-Many Relationships (Pivots)
    public function labels()
    {
        return $this->belongsToMany(Label::class, 'pemutu_indikator_label', 'indikator_id', 'label_id');
    }

    public function orgUnits()
    {
        return $this->belongsToMany(StrukturOrganisasi::class, 'pemutu_indikator_orgunit', 'indikator_id', 'org_unit_id')
            ->withPivot(
                'indikorgunit_id', 'target', 'ed_capaian', 'ed_analisis', 'ed_attachment', 'ed_links', 'ed_skala',
                'ami_hasil_akhir', 'ami_hasil_temuan', 'ami_hasil_temuan_sebab', 'ami_hasil_temuan_akibat', 'ami_hasil_temuan_rekom',
                'ami_rtp_isi', 'ami_rtp_tgl_pelaksanaan', 'ami_te_isi',
                'pengend_status', 'pengend_status_atsn', 'pengend_analisis', 'pengend_analisis_atsn', 
                'pengend_important_matrix', 'pengend_important_matrix_atsn', 'pengend_urgent_matrix', 'pengend_urgent_matrix_atsn',
                'created_at'
            );
    }

    public function parent()
    {
        return $this->belongsTo(Indikator::class, 'parent_id', 'indikator_id');
    }

    public function renstraPoin()
    {
        return $this->belongsTo(DokSub::class, 'renstra_poin_id', 'doksub_id');
    }

    public function children()
    {
        return $this->hasMany(Indikator::class, 'parent_id', 'indikator_id')->orderBy('seq');
    }

    /**
     * Indikator dari tahun/periode sebelumnya (cross-year linkage).
     */
    public function prevIndikator()
    {
        return $this->belongsTo(Indikator::class, 'prev_indikator_id', 'indikator_id');
    }

    /**
     * Indikator-indikator yang merupakan duplikat di tahun berikutnya.
     */
    public function nextIndikators()
    {
        return $this->hasMany(Indikator::class, 'prev_indikator_id', 'indikator_id');
    }

    public function pegawai()
    {
        return $this->hasMany(IndikatorPegawai::class, 'indikator_id', 'indikator_id');
    }

    public function indikatorOrgUnits()
    {
        return $this->hasMany(IndikatorOrgUnit::class, 'indikator_id', 'indikator_id');
    }
}
