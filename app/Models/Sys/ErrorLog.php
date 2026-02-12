<?php
namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class ErrorLog extends Model
{
    use SoftDeletes, Blameable, HashidBinding;

    protected $table = 'sys_error_log';

    protected $fillable = [
        'level',
        'message',
        'exception_class',
        'file',
        'line',
        'trace',
        'context',
        'url',
        'method',
        'ip_address',
        'user_agent',
        'user_id',        'created_by',        'updated_by',        'deleted_by',
    
    
    
    ];

    protected $casts = [
        'context'    => 'array',
        'trace'      => 'json', // Store as JSON for better handling
        'user_id'    => 'integer',
        'line'       => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Relationship: Error log belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Scope to filter by error level
     */
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Format the full trace for display
     */
    public function getFormattedTraceAttribute()
    {
        if (! $this->trace) {
            return 'No stack trace available';
        }

        if (is_array($this->trace)) {
            return implode("\n", array_map(function ($item) {
                return is_scalar($item) ? strval($item) : print_r($item, true);
            }, $this->trace));
        }

        return $this->trace;
    }
}
