<?php

namespace App\Models\Sys;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use SoftDeletes, Blameable, HashidBinding;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_personal_access_tokens';
}