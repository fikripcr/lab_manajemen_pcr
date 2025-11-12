<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'collection_name',
        'model_type',
        'model_id',
        'custom_properties',
    ];

    protected $casts = [
        'custom_properties' => 'array',
        'file_size' => 'integer',
    ];

    /**
     * Get the model that the media belongs to.
     */
    public function model()
    {
        return $this->morphTo();
    }
    
    /**
     * Get thumbnail path for this image.
     */
    public function getThumbnail()
    {
        if (empty($this->custom_properties)) {
            return null;
        }
        
        $properties = json_decode($this->custom_properties, true);
        if (!isset($properties['thumbnail_path'])) {
            return null;
        }
        
        return $properties['thumbnail_path'];
    }
    
    /**
     * Get medium size path for this image.
     */
    public function getMedium()
    {
        if (empty($this->custom_properties)) {
            return null;
        }
        
        $properties = json_decode($this->custom_properties, true);
        if (!isset($properties['medium_path'])) {
            return null;
        }
        
        return $properties['medium_path'];
    }
    
    /**
     * Check if media is an image.
     */
    public function isImage()
    {
        return strpos($this->mime_type, 'image/') === 0;
    }
}