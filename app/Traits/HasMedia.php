<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Http\Request;
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
     * Get media by collection name.
     */
    public function getMediaByCollection(string $collectionName)
    {
        return $this->media()->where('collection_name', $collectionName)->get();
    }

    /**
     * Get first media by collection name.
     */
    public function getFirstMediaByCollection(string $collectionName)
    {
        return $this->media()->where('collection_name', $collectionName)->first();
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

        // Create unique filename
        $uniqueFileName = time() . '_' . uniqid() . '.' . $extension;
        $path = $file->storeAs('uploads', $uniqueFileName, 'public');

        return $this->media()->create([
            'file_name' => $originalName,
            'file_path' => $path,
            'mime_type' => $mimeType,
            'file_size' => $size,
            'collection_name' => $collectionName,
        ]);
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
     * Remove all media from collection.
     */
    public function clearMediaCollection(string $collectionName)
    {
        $medias = $this->getMediaByCollection($collectionName);
        foreach ($medias as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }
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
