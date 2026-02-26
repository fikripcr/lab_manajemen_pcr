<?php

namespace App\Models\Pemutu;

use App\Models\Shared\StrukturOrganisasi;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Model;

class IndikatorOrgUnit extends Model
{
    use HashidBinding;

    protected $table      = 'pemutu_indikator_orgunit';
    protected $primaryKey = 'indikorgunit_id';
    protected $appends    = ['encrypted_indorgunit_id'];

    protected $fillable = [
        'indikator_id',
        'org_unit_id',
        'target',
        'ed_capaian',
        'ed_analisis',
        'ed_attachment',
        'ed_links',
        'ed_skala',
        'ami_hasil_akhir',
        'ami_hasil_temuan',
        'ami_hasil_temuan_sebab',
        'ami_hasil_temuan_akibat',
        'ami_hasil_temuan_rekom',
        'pengend_status',
        'pengend_target',
        'pengend_analisis',
        'pengend_penyesuaian',
        'pengend_important_matrix',
        'pengend_urgent_matrix',
    ];

    protected $casts = [
        'ed_links'       => 'array',
        'ed_skala'       => 'integer',
        'ami_hasil_akhir' => 'integer',
    ];

    public function getEncryptedIndorgunitIdAttribute(): string
    {
        return encryptId($this->indikorgunit_id);
    }

    // Labels untuk ami_hasil_akhir
    public static array $hasilAkhirLabels = [
        0 => ['label' => 'KTS', 'color' => 'danger', 'desc' => 'Tidak Terpenuhi'],
        1 => ['label' => 'Terpenuhi', 'color' => 'success', 'desc' => 'Standar Terpenuhi'],
        2 => ['label' => 'Terlampaui', 'color' => 'azure', 'desc' => 'Standar Terlampaui'],
    ];

    // Relationships
    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'indikator_id', 'indikator_id');
    }

    public function orgUnit()
    {
        return $this->belongsTo(StrukturOrganisasi::class, 'org_unit_id', 'orgunit_id');
    }

    public function diskusi()
    {
        return $this->morphMany(Diskusi::class, 'model')->orderBy('created_at', 'asc');
    }
}
