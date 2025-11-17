<?php

namespace App\Models\BaseSystem;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $table = 'sys_permissions';
}
