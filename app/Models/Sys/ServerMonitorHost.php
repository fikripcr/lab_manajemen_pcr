<?php

namespace App\Models\Sys;

use Spatie\ServerMonitor\Models\Host as BaseHost;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class ServerMonitorHost extends BaseHost
{
    use SoftDeletes, Blameable, HashidBinding;
    protected $table = 'sys_hosts';
}