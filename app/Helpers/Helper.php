<?php

if (!function_exists('encryptId')) {
    /**
     * Encrypt an ID using Hashids
     *
     * @param int $id
     * @return string
     */
    function encryptId($id)
    {
        return app('hashids')->encode($id);
    }
}

if (!function_exists('decryptId')) {
    /**
     * Decrypt a Hashid to get the original ID
     *
     * @param string $hash
     * @return int|null
     */
    function decryptId($hash)
    {
        $decoded = app('hashids')->decode($hash);
        return !empty($decoded) ? $decoded[0] : null;
    }
}

if (!function_exists('uploadMedia')) {
    /**
     * Upload a file and store it in the media table
     *
     * @param mixed $file The uploaded file
     * @param string $collectionName The media collection name (e.g., 'cover_image', 'attachments')
     * @param string $disk The storage disk to use (default: 'public')
     * @param string $directory The directory to store the file in (default: 'uploads')
     * @return \App\Models\Media|null
     */
    function uploadMedia($file, string $collectionName, string $disk = 'public', string $directory = 'uploads')
    {
        if (!$file) {
            return null;
        }

        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        // Create unique filename
        $uniqueFileName = time() . '_' . uniqid() . '.' . $extension;
        $path = $file->storeAs($directory, $uniqueFileName, $disk);

        return \App\Models\Media::create([
            'file_name' => $originalName,
            'file_path' => $path,
            'mime_type' => $mimeType,
            'file_size' => $size,
            'collection_name' => $collectionName,
            'model_type' => null, // Will be attached later to a specific model if needed
            'model_id' => null,
        ]);
    }
}

if (!function_exists('updateMedia')) {
    /**
     * Update an existing media file
     *
     * @param int $mediaId The ID of the existing media
     * @param mixed $file The new uploaded file
     * @param string $collectionName The media collection name
     * @param string $disk The storage disk to use (default: 'public')
     * @param string $directory The directory to store the file in (default: 'uploads')
     * @return \App\Models\Media|null
     */
    function updateMedia(int $mediaId, $file, string $collectionName, string $disk = 'public', string $directory = 'uploads')
    {
        if (!$file) {
            return null;
        }

        $media = \App\Models\Media::find($mediaId);
        if (!$media) {
            return null;
        }

        // Delete old file
        \Illuminate\Support\Facades\Storage::disk($disk)->delete($media->file_path);

        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        // Create unique filename
        $uniqueFileName = time() . '_' . uniqid() . '.' . $extension;
        $path = $file->storeAs($directory, $uniqueFileName, $disk);

        $media->update([
            'file_name' => $originalName,
            'file_path' => $path,
            'mime_type' => $mimeType,
            'file_size' => $size,
            'collection_name' => $collectionName,
        ]);

        return $media;
    }
}

if (!function_exists('deleteMedia')) {
    /**
     * Delete a media file and remove it from the database
     *
     * @param int $mediaId The ID of the media to delete
     * @param string $disk The storage disk (default: 'public')
     * @return bool
     */
    function deleteMedia(int $mediaId, string $disk = 'public')
    {
        $media = \App\Models\Media::find($mediaId);
        if (!$media) {
            return false;
        }

        // Delete the physical file
        \Illuminate\Support\Facades\Storage::disk($disk)->delete($media->file_path);
        
        $media->delete();
        return true;
    }
}