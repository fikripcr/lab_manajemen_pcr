<?php
namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Pengumuman extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $table      = 'pengumuman';
    protected $primaryKey = 'pengumuman_id';

    protected $fillable = [
        'judul',
        'isi',
        'jenis',
        'penulis_id',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published'  => 'boolean',
        'jenis'         => 'string',
        'pengumuman_id' => 'string',
    ];

    /**
     * Relationship: Announcement belongs to a penulis (user)
     */
    public function penulis()
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->useFallbackUrl(asset('images/no-image.jpg'))
            ->useFallbackPath(public_path('images/no-image.jpg'))
            ->useDisk('public')
            ->singleFile();

        $this->addMediaCollection('attachments')
            ->useDisk('public')
            ->acceptsMimeTypes([
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/zip',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                // tambah kalau perlu
            ]);
    }

    /**
     * Register the media conversions for this model.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        if ($media?->collection_name == 'cover') {
            $this->addMediaConversion('small')
                ->fit(Fit::Crop, 150, 150)
                ->optimize()
                ->nonQueued();

            $this->addMediaConversion('medium')
                ->fit(Fit::Crop, 400, 400)
                ->optimize()
                ->nonQueued();
        }
    }

    public function getCoverUrlAttribute()
    {
        return $this->getFirstMediaUrl('cover');
    }

    public function getCoverSmallUrlAttribute()
    {
        return $this->getFirstMediaUrl('cover', 'small');
    }

    public function getCoverMediumUrllttribute()
    {
        return $this->getFirstMediaUrl('cover', 'medium');
    }

    public function getAttachmentsUrlAttribute()
    {
        return $this->getFirstMediaUrl('attachments');
    }

    /**
     * Accessor to get encrypted pengumuman_id
     */
    public function getEncryptedPengumumanIdAttribute()
    {
        return encryptId($this->pengumuman_id);
    }
}
