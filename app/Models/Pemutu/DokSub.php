<?php
namespace App\Models\Pemutu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokSub extends Model
{
    use HasFactory;

    protected $table      = 'pemutu_dok_sub';
    protected $primaryKey = 'doksub_id';
    protected $fillable   = [
        'dok_id',
        'judul',
        'isi',
        'seq',
        'is_hasilkan_indikator',
    ];

    protected $casts = [
        'is_hasilkan_indikator' => 'boolean',
    ];
    public $timestamps = false;

    // Relationships
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dok_id', 'dok_id');
    }

    public function indikators()
    {
        return $this->belongsToMany(Indikator::class, 'pemutu_indikator_doksub', 'doksub_id', 'indikator_id')
            ->withPivot('is_hasilkan_indikator')
            ->withTimestamps();
    }

    public function childDokumens()
    {
        return $this->hasMany(Dokumen::class, 'parent_doksub_id', 'doksub_id')->orderBy('seq');
    }
}
