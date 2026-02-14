<?php
namespace App\Models\Sys;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Permission extends SpatiePermission implements Searchable
{
    use SoftDeletes, Blameable, HashidBinding;
    protected $table = 'sys_permissions';

    protected $fillable = [
        'name',
        'guard_name',
        'category',
        'sub_category',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    /**
     * Accessor to get encrypted ID
     */
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('sys.permissions.index') . '?search=' . urlencode($this->name);

        return new SearchResult(
            $this,
            $this->name,
            $url
        );
    }
}
