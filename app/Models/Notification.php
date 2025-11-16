<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    protected $table = 'sys_notifications';

    // Ensure we're using the right connection and table
    public function __construct(array $attributes = [])
    {
        $this->table = config('notifications.table', 'sys_notifications');
        parent::__construct($attributes);
    }
}