<?php

namespace App\Models\Sys;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ServerMonitor\Models\Host as BaseHost;

class ServerMonitorHost extends BaseHost
{
    use Blameable, HashidBinding, SoftDeletes;

    protected $table = 'sys_hosts';
}
