<?php
namespace App\Models\Lab;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class Notification extends DatabaseNotification
{
    use SoftDeletes, Blameable, HashidBinding;
    protected $table = 'sys_notifications';

    // Ensure we're using the right connection and table
    public function __construct(array $attributes = [])
    {
        $this->table = config('notifications.table', 'sys_notifications');
        parent::__construct($attributes);
    }

    /**
     * Accessor to get encrypted ID
     */
    public function getEncryptedIdAttribute()
    {
        return encryptId($this->id);
    }
}
