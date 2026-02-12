<?php

namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Blameable;
use App\Traits\HashidBinding;

class Activity extends SpatieActivity
{
    use HasFactory, SoftDeletes, Blameable, HashidBinding;

    protected $fillable = [
        'log_name',
        'description',
        'event',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'batch_uuid',
        'ip_address',
        'user_agent',        'created_by',        'updated_by',        'deleted_by',
    
    
    
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'properties' => 'array',
    ];
}