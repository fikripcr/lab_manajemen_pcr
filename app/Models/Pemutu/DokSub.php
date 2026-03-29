<?php

namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class DokSub extends Model implements HasMedia
{
    use Blameable, HasFactory, HashidBinding, InteractsWithMedia, SoftDeletes;

    protected $table = 'pemutu_dok_sub';

    protected $primaryKey = 'doksub_id';

    protected $appends = ['encrypted_doksub_id', 'encrypted_dok_id'];

    public function getRouteKeyName()
    {
        return 'doksub_id';
    }

    protected $fillable = [
        'dok_id',
        'jenis', // poin_visi, poin_misi, poin_rjp, etc.
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
        return $this->morphedByMany(Indikator::class, 'source', 'pemutu_indikator_doksub', 'doksub_id', 'source_id')
            ->withPivot('is_hasilkan_indikator')
            ->withTimestamps();
    }

    public function sourceDokumens()
    {
        return $this->morphedByMany(Dokumen::class, 'source', 'pemutu_indikator_doksub', 'doksub_id', 'source_id')
            ->withTimestamps();
    }

    public function childDokumens()
    {
        return $this->hasMany(Dokumen::class, 'parent_doksub_id', 'doksub_id')->orderBy('seq');
    }

    /**
     * Poin-poin yang menjadi tujuan mapping dari poin ini.
     * Misal: M1.mappedTo() → [V1]  (Misi poin M1 dipetakan ke Visi poin V1)
     */
    public function mappedTo()
    {
        return $this->belongsToMany(
            DokSub::class,
            'pemutu_doksub_mapping',
            'doksub_id',
            'mapped_doksub_id'
        )->withTimestamps();
    }

    /**
     * Poin-poin yang memetakan ke poin ini (inverse).
     * Misal: V1.mappedFrom() → [M1, M2]  (Visi poin V1 dipetakan oleh M1 dan M2)
     */
    public function mappedFrom()
    {
        return $this->belongsToMany(
            DokSub::class,
            'pemutu_doksub_mapping',
            'mapped_doksub_id',
            'doksub_id'
        )->withTimestamps();
    }

    /**
     * Spatie Media Library: Register collections.
     * Collection 'dokumen_pendukung' — allows multiple files per point.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('dokumen_pendukung');
    }
}
