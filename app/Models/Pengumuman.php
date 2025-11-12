<?php

namespace App\Models;

use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory, HasMedia;

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'isi',
        'jenis',
        'penulis_id',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'jenis' => 'string',
    ];

    /**
     * Relationship: Announcement belongs to a penulis (user)
     */
    public function penulis()
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }

    /**
     * Relationship: Announcement has many media
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }
    
    /**
     * Get the value of the model's route key.
     */
    public function getRouteKey()
    {
        return encryptId($this->getKey());
    }
    
    /**
     * Get cover image with thumbnail fallback.
     */
    public function getCoverImageAttribute()
    {
        $coverMedia = $this->getFirstMediaByCollection('info_cover');
        if (!$coverMedia) {
            return [
                'original' => null,
                'thumbnail' => null,
                'url' => asset('assets-guest/img/person/person-m-10.webp'), // Default image
            ];
        }
        
        $thumbnailPath = $coverMedia->getThumbnail();
        return [
            'original' => $coverMedia,
            'thumbnail' => $thumbnailPath,
            'url' => asset('storage/' . ($thumbnailPath ?: $coverMedia->file_path)),
        ];
    }

    /**
     * Get first media by collection name.
     */
    public function getFirstMediaByCollection(string $collectionName)
    {
        return $this->media()->where('collection_name', $collectionName)->first();
    }
    
    /**
     * Get media by collection name.
     */
    public function getMediaByCollection(string $collectionName)
    {
        return $this->media()->where('collection_name', $collectionName)->get();
    }
    
    /**
     * Get media by collection with thumbnail info.
     */
    public function getMediaByCollectionWithThumbnails(string $collectionName)
    {
        $mediaItems = $this->media()->where('collection_name', $collectionName)->get();
        
        return $mediaItems->map(function ($media) {
            $customProperties = json_decode($media->custom_properties, true);
            if ($customProperties && isset($customProperties['thumbnail_path'])) {
                $media->thumbnail_url = asset('storage/' . $customProperties['thumbnail_path']);
                $media->medium_url = asset('storage/' . $customProperties['medium_path']);
            }
            return $media;
        });
    }
    
    /**
     * Get all attachment files.
     */
    public function getAttachmentsAttribute()
    {
        return $this->getMediaByCollection('info_attachment');
    }
}