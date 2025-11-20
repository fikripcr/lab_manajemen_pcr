<?php

namespace App\Models\Sys;

use Spatie\ServerMonitor\Models\Check as BaseCheck;

class ServerMonitorCheck extends BaseCheck
{
    protected $table = 'sys_checks';
}