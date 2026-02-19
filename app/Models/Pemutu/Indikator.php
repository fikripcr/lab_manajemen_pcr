<?php
namespace App\Models\Pemutu;

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
    protected $fillable   = [
        'type',
        'parent_id',
        'no_indikator',
        'indikator',
        'target',
        'unit_ukuran',
        'jenis_indikator',
        'jenis_data',
        'periode_jenis',
        'periode_mulai',
        'periode_selesai',
        'keterangan',
        'seq',
        'level_risk',
        'origin_from',
        'hash',
        'peningkat_nonaktif_indik',
        'is_new_indik_after_peningkatan',
        'created_by',
        'updated_by', 'deleted_by',

    ];
    public $timestamps = false;

    public function getEncryptedIndikatorIdAttribute()
    {
        return encryptId($this->indikator_id);
    }

    // Relationships
    public function dokSubs()
    {
        return $this->belongsToMany(DokSub::class, 'pemutu_indikator_doksub', 'indikator_id', 'doksub_id')
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
        return $this->belongsToMany(\App\Models\Shared\StrukturOrganisasi::class, 'pemutu_indikator_orgunit', 'indikator_id', 'org_unit_id')
            ->withPivot('target', 'ed_capaian', 'ed_analisis', 'ed_attachment', 'created_at');
    }

    public function parent()
    {
        return $this->belongsTo(Indikator::class, 'parent_id', 'indikator_id');
    }

    public function children()
    {
        return $this->hasMany(Indikator::class, 'parent_id', 'indikator_id')->orderBy('seq');
    }

    public function personils()
    {
        return $this->hasMany(IndikatorPersonil::class, 'indikator_id', 'indikator_id');
    }
}
