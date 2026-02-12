<?php

namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class Notification extends DatabaseNotification
{
    use HasFactory, Notifiable, SoftDeletes, Blameable, HashidBinding;

    protected $table = 'sys_notifications'; // Use the correct table name

    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'created_at',
        'updated_at',        'created_by',        'updated_by',        'deleted_by',
    
    
    
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