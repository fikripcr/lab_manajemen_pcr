<?php

namespace App\Models\Sys;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class Media extends SpatieMedia
{
    use Blameable, HashidBinding, SoftDeletes;

    protected $table = 'sys_media';
}
