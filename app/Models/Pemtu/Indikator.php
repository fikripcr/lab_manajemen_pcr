<?php
namespace App\Models\Pemtu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    use HasFactory;

    protected $table      = 'indikator';
    protected $primaryKey = 'indikator_id';
    protected $fillable   = [
        'doksub_id',
        'no_indikator',
        'indikator',
        'target',
        'jenis_indikator', // e.g., Kualitatif, Kuantitatif
        'jenis_data',
        'periode_jenis', // e.g., Tahunan, Semester
        'periode_mulai',
        'periode_selesai',
        'keterangan',
        'seq',
        'level_risk',
        'origin_from',
        'hash',
        'peningkat_nonaktif_indik',
        'is_new_indik_after_peningkatan',
    ];
    public $timestamps = false;

    protected $casts = [
        'periode_mulai'   => 'datetime',
        'periode_selesai' => 'datetime',
    ];

    // Relationships
    public function dokSub()
    {
        return $this->belongsTo(DokSub::class, 'doksub_id', 'doksub_id');
    }

    // Many-to-Many Relationships (Pivots)
    public function labels()
    {
        return $this->belongsToMany(Label::class, 'indikator_label', 'indikator_id', 'label_id');
    }

    public function orgUnits()
    {
        return $this->belongsToMany(OrgUnit::class, 'indikator_orgunit', 'indikator_id', 'org_unit_id')
            ->withPivot('created_at');
    }

    public function relatedDokSubs()
    {
        return $this->belongsToMany(DokSub::class, 'indikator_doksub', 'indikator_id', 'doksub_id')
            ->withPivot('is_hasilkan_indikator');
    }
}
