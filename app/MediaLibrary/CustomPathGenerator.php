<?php

namespace App\MediaLibrary;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CustomPathGenerator implements PathGenerator
{
    /**
     * Base folder: model-type (kebab)
     */
    protected function baseFolder(Media $media): string
    {
        return Str::kebab(class_basename($media->model_type));
    }

    /**
     * Extract year from media created_at
     */
    protected function year(Media $media): string
    {
        return optional($media->created_at)->format('Y') ?? now()->format('Y');
    }

    /**
     * Main file path
     */
    public function getPath(Media $media): string
    {
        $folder = $this->baseFolder($media);
        $year = $this->year($media);

        return "uploads/{$folder}/{$year}/{$media->id}/";
    }

    /**
     * Conversions path
     */
    public function getPathForConversions(Media $media): string
    {
        $folder = $this->baseFolder($media);
        $year = $this->year($media);

        return "uploads/{$folder}/{$year}/{$media->id}/conversions/";
    }

    /**
     * Responsive images path
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        $folder = $this->baseFolder($media);
        $year = $this->year($media);

        return "uploads/{$folder}/{$year}/{$media->id}/responsive/";
    }
}
