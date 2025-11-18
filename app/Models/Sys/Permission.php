<?php

namespace App\Models\Sys;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Permission extends SpatiePermission implements \Spatie\Searchable\Searchable
{
    protected $table = 'sys_permissions';

    /**
     * Accessor to get encrypted ID
     */
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('permissions.index') . '?search=' . urlencode($this->name);

        return new SearchResult(
            $this,
            $this->name,
            $url
        );
    }
}
