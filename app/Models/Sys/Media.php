<?php

namespace App\Models\Sys;

use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class Media extends SpatieMedia
{
    use SoftDeletes, Blameable, HashidBinding;
    protected $table = 'sys_media';
}
