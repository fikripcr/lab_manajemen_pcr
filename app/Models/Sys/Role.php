<?php

namespace App\Models\Sys;

use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class Role extends SpatieRole implements \Spatie\Searchable\Searchable
{
    use SoftDeletes, Blameable, HashidBinding;
    protected $table = 'sys_roles';

    /**
     * Accessor to get encrypted ID
     */
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('sys.roles.index') . '?search=' . urlencode($this->name);

        return new SearchResult(
            $this,
            $this->name,
            $url
        );
    }
}
