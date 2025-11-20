<?php

namespace App\Models\Sys;

use Spatie\ServerMonitor\Models\Host as BaseHost;

class ServerMonitorHost extends BaseHost
{
    protected $table = 'sys_hosts';
}