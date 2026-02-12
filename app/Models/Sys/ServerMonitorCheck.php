<?php

namespace App\Models\Sys;

use Spatie\ServerMonitor\Models\Check as BaseCheck;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class ServerMonitorCheck extends BaseCheck
{
    use SoftDeletes, Blameable, HashidBinding;
    protected $table = 'sys_checks';
}