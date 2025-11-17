<?php

namespace App\Models\BaseSystem;

use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class Media extends SpatieMedia
{
    protected $table = 'sys_media';
}
