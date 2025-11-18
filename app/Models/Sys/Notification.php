<?php

namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;

class Notification extends DatabaseNotification
{
    use HasFactory, Notifiable;

    protected $table = 'sys_notifications'; // Use the correct table name

    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'read_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function notifiable()
    {
        return $this->morphTo();
    }
}