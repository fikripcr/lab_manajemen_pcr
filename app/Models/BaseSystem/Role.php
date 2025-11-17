<?php

namespace App\Models\BaseSystem;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $table = 'sys_roles';
}
