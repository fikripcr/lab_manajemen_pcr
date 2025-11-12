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
     * @param string $modelType The model type for folder structure (e.g., 'pengumuman', 'user')
     * @param string $disk The storage disk to use (default: 'public')
     * @return \App\Models\Media|null
     */
    function uploadMedia($file, string $collectionName, string $modelType = 'general', string $disk = 'public')
    {
        if (!$file) {
            return null;
        }

        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        
        // Create folder structure: uploads/{modelType}/{year}/{collectionName}
        $year = date('Y');
        $folderPath = "uploads/{$modelType}/{$year}/{$collectionName}";
        
        // Create unique filename with type prefix
        $filePrefix = str_replace('_', '-', $collectionName);
        $uniqueFileName = $filePrefix . '_' . time() . '_' . uniqid() . '.' . $extension;
        $path = $file->storeAs($folderPath, $uniqueFileName, $disk);

        return \App\Models\Media::create([
            'file_name' => $originalName,
            'file_path' => $path,
            'mime_type' => $mimeType,
            'file_size' => $size,
            'collection_name' => $collectionName,
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

if (!function_exists('displayMedia')) {
    /**
     * Display media file with appropriate action based on file type
     *
     * @param mixed $media The media object or file path
     * @param string $size Size variant: 'thumb', 'medium', 'original', or 'auto'
     * @param string $altText Alternative text for images
     * @return string HTML string for displaying media
     */
    function displayMedia($media, string $size = 'auto', string $altText = 'Media file')
    {
        // Handle different input types
        if (is_string($media)) {
            $filePath = $media;
            $mimeType = mime_content_type(storage_path('app/public/' . $filePath));
            $fileName = basename($filePath);
        } elseif (is_object($media) && isset($media->file_path, $media->mime_type)) {
            $filePath = $media->file_path;
            $mimeType = $media->mime_type;
            $fileName = $media->file_name ?? basename($filePath);

            // Check for thumbnail/medium variants if requested
            if ($size !== 'original' && isset($media->custom_properties)) {
                $properties = json_decode($media->custom_properties, true);
                if ($properties) {
                    if ($size === 'thumb' && isset($properties['thumbnail_path'])) {
                        $filePath = $properties['thumbnail_path'];
                        $mimeType = mime_content_type(storage_path('app/public/' . $filePath));
                    } elseif ($size === 'medium' && isset($properties['medium_path'])) {
                        $filePath = $properties['medium_path'];
                        $mimeType = mime_content_type(storage_path('app/public/' . $filePath));
                    }
                }
            }
        } else {
            // Return default image if media not valid
            return '<img src="' . asset('assets-guest/img/person/person-m-10.webp') . '" alt="No media" class="img-fluid" style="max-width: 100px; max-height: 100px; object-fit: cover;">';
        }

        $fullPath = asset('storage/' . $filePath);
        $isImage = strpos($mimeType, 'image/') === 0;
        $isPdf = $mimeType === 'application/pdf';
        $isExcel = in_array($mimeType, [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);

        if ($isImage) {
            // For images, show the image with a link to open in new tab
            return '<a href="' . $fullPath . '" target="_blank" rel="noopener noreferrer" title="' . e($fileName) . '">
                        <img src="' . $fullPath . '" alt="' . e($altText) . '" class="img-fluid" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                    </a>';
        } elseif ($isPdf || $isExcel) {
            // For PDFs and Excel files, show icon with link to open in new tab
            $icon = $isPdf ? 'bx bxs-file-pdf text-danger' : 'bx bxs-file-xls text-success';
            $type = $isPdf ? 'PDF' : 'Excel';
            return '<a href="' . $fullPath . '" target="_blank" rel="noopener noreferrer" class="text-decoration-none" title="' . e($fileName) . '">
                        <i class="' . $icon . '" style="font-size: 2rem;"></i>
                        <small class="d-block text-muted">' . e($type) . ' file</small>
                    </a>';
        } else {
            // For other file types, show download button
            return '<a href="' . $fullPath . '" download="' . e($fileName) . '" class="btn btn-sm btn-outline-primary" title="Download ' . e($fileName) . '">
                        <i class="bx bx-download"></i> Download
                    </a>';
        }
    }
}

if (!function_exists('displayMediaInTable')) {
    /**
     * Display media in table context - shows image if it's an image, or download button if not
     *
     * @param mixed $media The media object or file path
     * @param string $size Size variant for images: 'thumb', 'medium', or 'table'
     * @return string HTML string for displaying media in tables
     */
    function displayMediaInTable($media, string $size = 'thumb')
    {
        // Handle different input types
        if (is_string($media)) {
            $filePath = $media;
            $mimeType = mime_content_type(storage_path('app/public/' . $filePath));
        } elseif (is_object($media) && isset($media->file_path, $media->mime_type)) {
            $filePath = $media->file_path;
            $mimeType = $media->mime_type;

            // Use thumbnail if available and requested
            if ($size !== 'original' && isset($media->custom_properties)) {
                $properties = json_decode($media->custom_properties, true);
                if ($properties) {
                    if (isset($properties['thumbnail_path'])) {
                        $filePath = $properties['thumbnail_path'];
                    } elseif (isset($properties['medium_path']) && $size === 'medium') {
                        $filePath = $properties['medium_path'];
                    }
                }
            }
        } else {
            return '<div class="text-center">
                        <i class="bx bx-image-alt text-muted" style="font-size: 1.5rem;"></i>
                        <small class="text-muted d-block">No media</small>
                    </div>';
        }

        $fullPath = asset('storage/' . $filePath);
        $isImage = strpos($mimeType, 'image/') === 0;
        $fileName = is_object($media) ? ($media->file_name ?? basename($filePath)) : basename($filePath);

        if ($isImage) {
            // For images in table, show thumbnail with link to full size
            return '<div class="text-center">
                        <a href="' . $fullPath . '" target="_blank" rel="noopener noreferrer" title="' . e($fileName) . '">
                            <img src="' . $fullPath . '" alt="Preview" class="img-thumbnail" style="max-width: 80px; max-height: 60px; object-fit: cover;">
                        </a>
                    </div>';
        } else {
            // For non-images in table, show download button
            return '<div class="text-center">
                        <a href="' . $fullPath . '" download="' . e($fileName) . '" class="btn btn-sm btn-outline-primary" title="Download ' . e($fileName) . '">
                            <i class="bx bx-download"></i>
                        </a>
                    </div>';
        }
    }
}