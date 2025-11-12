<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait HasMedia
{
    /**
     * Get all media associated with this model.
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }



    /**
     * Add media to this model.
     */
    public function addMedia($file, string $collectionName, string $fileName = null)
    {
        $originalName = $fileName ?: $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        $modelType = strtolower(class_basename($this));

        // Determine if this is a cover image that needs thumbnail
        $needsThumbnail = in_array($collectionName, ['info_cover', 'cover_image']);

        // Determine folder structure: uploads/{modelType}/{year}/{collectionName}
        $year = date('Y');
        $folderPath = "uploads/{$modelType}/{$year}/{$collectionName}";

        // Create unique filename with type prefix
        $filePrefix = str_replace('_', '-', $collectionName);
        $uniqueFileName = $filePrefix . '_' . time() . '_' . uniqid() . '.' . $extension;
        $path = $file->storeAs($folderPath, $uniqueFileName, 'public');

        // Create media entry
        $media = $this->media()->create([
            'file_name' => $originalName,
            'file_path' => $path,
            'mime_type' => $mimeType,
            'file_size' => $size,
            'collection_name' => $collectionName,
        ]);

        // If this is a cover image, create a thumbnail
        if ($needsThumbnail && in_array(strtolower($mimeType), ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'])) {
            $this->createImageThumbnails($media);
        }

        return $media;
    }

    /**
     * Create thumbnails for an image
     */
    public function createImageThumbnails($media)
    {
        // Only process images
        if (!in_array(strtolower($media->mime_type), ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'])) {
            return null;
        }

        $originalImagePath = storage_path('app/public/' . $media->file_path);
        if (!file_exists($originalImagePath)) {
            return null;
        }

        // Load the original image
        $image = Image::read($originalImagePath);

        // Get original dimensions
        $originalWidth = $image->width();
        $originalHeight = $image->height();

        // Create thumbnail (150x150 max)
        $thumbnail = clone $image;
        $thumbnail->coverDown(150, 150);

        // Create medium size (400x400 max)
        $medium = clone $image;
        $medium->coverDown(400, 400);

        // Create directory for thumbnails
        $thumbnailDir = dirname($media->file_path) . '/thumbnails';
        $fullThumbnailDir = storage_path('app/public/') . $thumbnailDir;
        if (!is_dir($fullThumbnailDir)) {
            mkdir($fullThumbnailDir, 0755, true);
        }

        // Save thumbnails
        $thumbnailFileName = pathinfo($media->file_path, PATHINFO_FILENAME) . '_thumb.' . pathinfo($media->file_path, PATHINFO_EXTENSION);
        $mediumFileName = pathinfo($media->file_path, PATHINFO_FILENAME) . '_med.' . pathinfo($media->file_path, PATHINFO_EXTENSION);

        $thumbnailPath = $thumbnailDir . '/' . $thumbnailFileName;
        $mediumPath = $thumbnailDir . '/' . $mediumFileName;

        $thumbnail->save(storage_path('app/public/') . $thumbnailPath);
        $medium->save(storage_path('app/public/') . $mediumPath);

        // Update media with thumbnail info
        $media->update([
            'custom_properties' => json_encode([
                'original_width' => $originalWidth,
                'original_height' => $originalHeight,
                'thumbnail_path' => $thumbnailPath,
                'medium_path' => $mediumPath
            ])
        ]);
    }

    /**
     * Get media by collection with thumbnail info
     */
    public function getMediaByCollection(string $collectionName)
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
     * Get first media by collection with thumbnail info
     */
    public function getFirstMediaByCollection(string $collectionName)
    {
        $media = $this->media()->where('collection_name', $collectionName)->first();

        if ($media) {
            $customProperties = json_decode($media->custom_properties, true);
            if ($customProperties && isset($customProperties['thumbnail_path'])) {
                $media->thumbnail_url = asset('storage/' . $customProperties['thumbnail_path']);
                $media->medium_url = asset('storage/' . $customProperties['medium_path']);
            }
        }

        return $media;
    }

    /**
     * Clear media collection
     */
    public function clearMediaCollection(string $collectionName)
    {
        $medias = $this->getMediaByCollection($collectionName);
        foreach ($medias as $media) {
            // Delete the physical file
            \Storage::disk('public')->delete($media->file_path);

            // Delete thumbnails if they exist
            $customProperties = json_decode($media->custom_properties, true);
            if ($customProperties) {
                if (isset($customProperties['thumbnail_path'])) {
                    \Storage::disk('public')->delete($customProperties['thumbnail_path']);
                }
                if (isset($customProperties['medium_path'])) {
                    \Storage::disk('public')->delete($customProperties['medium_path']);
                }
            }

            $media->delete();
        }
    }

    /**
     * Create thumbnail for an image.
     */
    public function createThumbnail($media, $basePath)
    {
        if (!in_array($media->mime_type, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'])) {
            return null; // Only process images
        }

        $imagePath = storage_path('app/public/' . $media->file_path);
        if (!file_exists($imagePath)) {
            return null;
        }

        // Load the original image
        $img = Image::read($imagePath);

        // Save original with dimensions
        $originalWidth = $img->width();
        $originalHeight = $img->height();

        // Create thumbnail
        $thumbnail = clone $img;
        $thumbnail->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Create thumbnail filename
        $thumbnailDir = dirname($media->file_path) . '/thumbnails';
        $thumbnailFileName = 'thumb_' . basename($media->file_path);
        $thumbnailPath = $thumbnailDir . '/' . $thumbnailFileName;

        // Create thumbnails directory if it doesn't exist
        $fullThumbnailDir = storage_path('app/public/') . $thumbnailDir;
        if (!is_dir($fullThumbnailDir)) {
            mkdir($fullThumbnailDir, 0755, true);
        }

        // Save thumbnail
        $thumbnail->save(storage_path('app/public/') . $thumbnailPath);

        // Create thumbnail media entry
        $thumbnailMedia = $this->media()->create([
            'file_name' => 'thumb_' . $media->file_name,
            'file_path' => $thumbnailPath,
            'mime_type' => $media->mime_type,
            'file_size' => filesize(storage_path('app/public/') . $thumbnailPath),
            'collection_name' => $media->collection_name . '_thumb', // Different collection for thumbnails
        ]);

        $media->update([
            'custom_properties' => json_encode([
                'original_width' => $img->width(),
                'original_height' => $img->height(),
                'thumbnail_id' => $thumbnailMedia->id
            ])
        ]);

        $thumbnail->destroy(); // Free memory
        $img->destroy(); // Free memory

        return $thumbnailMedia;
    }

    /**
     * Add multiple media files to this model.
     */
    public function addMultipleMedia(array $files, string $collectionName)
    {
        $media = [];
        foreach ($files as $file) {
            $media[] = $this->addMedia($file, $collectionName);
        }
        return collect($media);
    }

    /**
     * Update media.
     */
    public function updateMedia(int $mediaId, $file, string $collectionName)
    {
        $media = $this->media()->where('id', $mediaId)->firstOrFail();

        // Delete old file
        Storage::disk('public')->delete($media->file_path);

        // Upload new file
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        $uniqueFileName = time() . '_' . uniqid() . '.' . $extension;
        $path = $file->storeAs('uploads', $uniqueFileName, 'public');

        $media->update([
            'file_name' => $originalName,
            'file_path' => $path,
            'mime_type' => $mimeType,
            'file_size' => $size,
            'collection_name' => $collectionName,
        ]);

        return $media;
    }

    /**
     * Remove media by ID.
     */
    public function removeMedia(int $mediaId)
    {
        $media = $this->media()->where('id', $mediaId)->firstOrFail();

        // Delete the physical file
        Storage::disk('public')->delete($media->file_path);

        $media->delete();
    }


    /**
     * Attach media to this model.
     */
    public function attachMedia(Media $media, string $collectionName)
    {
        $media->update([
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'collection_name' => $collectionName,
        ]);
    }
}
