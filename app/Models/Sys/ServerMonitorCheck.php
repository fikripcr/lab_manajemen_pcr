<?php

namespace App\Models\Sys;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ServerMonitor\Models\Check as BaseCheck;

class ServerMonitorCheck extends BaseCheck
{
    use Blameable, HashidBinding, SoftDeletes;

    protected $table = 'sys_checks';
}
