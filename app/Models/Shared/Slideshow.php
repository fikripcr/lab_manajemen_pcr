<?php
namespace App\Models\Shared;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slideshow extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding, InteractsWithMedia;

    protected $appends = ['encrypted_slideshow_id'];

    public function getEncryptedSlideshowIdAttribute()
    {
        return encryptId($this->id);
    }

    protected $fillable = [
        'image_url',
        'title',
        'caption',
        'link',
        'seq',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('slideshow_image')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 150, 150)
            ->nonQueued();

        $this->addMediaConversion('large')
            ->fit(Fit::Max, 1200, 600)
            ->nonQueued();
    }

    public function getImageUrlAttribute()
    {
        $media = $this->getFirstMedia('slideshow_image');
        return $media ? $media->getUrl() : asset('static/img/default-slideshow.jpg');
    }
}
