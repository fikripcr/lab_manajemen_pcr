<?php
namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DokSub extends Model
{
    use HasFactory, Blameable, HashidBinding, SoftDeletes;

    protected $table      = 'pemutu_dok_sub';
    protected $primaryKey = 'doksub_id';
    protected $appends    = ['encrypted_doksub_id', 'encrypted_dok_id'];

    public function getRouteKeyName()
    {
        return 'doksub_id';
    }
    protected $fillable = [
        'dok_id',
        'judul',
        'kode',
        'isi',
        'seq',
        'is_hasilkan_indikator',
        'created_by',
        'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'is_hasilkan_indikator' => 'boolean',
    ];
    public $timestamps = false;

    public function getEncryptedDoksubIdAttribute()
    {
        return encryptId($this->doksub_id);
    }

    public function getEncryptedDokIdAttribute()
    {
        return encryptId($this->dok_id);
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
