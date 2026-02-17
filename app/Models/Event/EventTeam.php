<?php
namespace App\Models\Event;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventTeam extends Model
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $table      = 'event_teams';
    protected $primaryKey = 'eventteam_id';

    protected $fillable = [
        'event_id',
        'memberable_type',
        'memberable_id',
        'name',
        'role',
        'is_pic',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Polymorphic relationship for team members
     */
    public function memberable()
    {
        return $this->morphTo();
    }

    /**
     * Get display name (from model or name column)
     */
    public function getDisplayNameAttribute()
    {
        if ($this->memberable) {
            return $this->memberable->name ?? $this->memberable->nama ?? $this->name;
        }
        return $this->name;
    }
}
