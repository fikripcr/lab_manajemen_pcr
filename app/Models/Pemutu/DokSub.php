<?php
namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokSub extends Model
{
    use HasFactory, Blameable, HashidBinding;

    protected $table = 'pemutu_dok_sub';
    protected $primaryKey = 'doksub_id';
    protected $appends = ['encrypted_doksub_id'];
    protected $fillable = [
        'dok_id',
        'judul',
        'isi',
        'seq',
        'is_hasilkan_indikator',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_hasilkan_indikator' => 'boolean',
    ];
    public $timestamps = false;

    public function getEncryptedDoksubIdAttribute()
    {
        return encryptId($this->doksub_id);
    }

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
