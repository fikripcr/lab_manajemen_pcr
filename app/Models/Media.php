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
}