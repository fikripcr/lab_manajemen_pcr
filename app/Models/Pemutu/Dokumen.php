<?php
namespace App\Models\Pemutu;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Dokumen extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding, InteractsWithMedia;

    protected $table      = 'pemutu_dokumen';
    protected $primaryKey = 'dok_id';

    protected $appends = ['encrypted_dok_id'];

    public function getRouteKeyName()
    {
        return 'dok_id';
    }
    protected $fillable = [
        'parent_doksub_id', // For potential future hierarchy if needed, though migration says nullable
        'parent_id',
        'level',
        'seq',
        'jenis',
        'judul',
        'isi',
        'kode',
        'periode',
        // 'tgl_berlaku', // Removed per request
        'std_is_staging',
        'std_amirtn_id',
        'std_jeniskriteria_id',
        'created_by',
        'updated_by', 'deleted_by',

    ];
    public $timestamps = false; // Migration doesn't have timestamps

    public function getEncryptedDokIdAttribute()
    {
        return encryptId($this->dok_id);
    }

    protected $casts = [
        'tgl_berlaku'    => 'date',
        'std_is_staging' => 'boolean',
    ];

    // Relationships
    public function dokSubs()
    {
        return $this->hasMany(DokSub::class, 'dok_id', 'dok_id')->orderBy('seq');
    }

    public function mappedDokSubs()
    {
        return $this->morphToMany(DokSub::class, 'source', 'pemutu_indikator_doksub', 'source_id', 'doksub_id')
            ->withTimestamps();
    }

    public function mappedDokTargets()
    {
        return $this->belongsToMany(Dokumen::class, 'pemutu_dokumen_mapping', 'source_dok_id', 'target_dok_id')
            ->withTimestamps();
    }

    // Hierarchy Relationships
    public function parent()
    {
        return $this->belongsTo(Dokumen::class, 'parent_id', 'dok_id');
    }

    public function parentDokSub()
    {
        return $this->belongsTo(DokSub::class, 'parent_doksub_id', 'doksub_id');
    }

    public function children()
    {
        return $this->hasMany(Dokumen::class, 'parent_id', 'dok_id')->orderBy('seq');
    }

    public function approvals()
    {
        return $this->hasMany(\App\Models\Pemutu\RiwayatApproval::class, 'model_id', 'dok_id')
            ->where('model', self::class)
            ->orderBy('created_at', 'desc');
    }

    public function riwayatApprovals()
    {
        return $this->morphMany(RiwayatApproval::class, 'subject', 'model', 'model_id');
    }

    /**
     * Spatie Media Library: Register collections.
     * Collection 'dokumen_pendukung' — allows multiple files per document.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('dokumen_pendukung');
    }
}
