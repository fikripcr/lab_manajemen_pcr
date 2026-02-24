<?php

namespace App\Models\Project;

use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSprint extends Model
{
    use HasFactory, SoftDeletes, HashidBinding, Blameable;

    protected $table = 'pr_project_sprints';
    protected $primaryKey = 'project_sprint_id';

    protected $fillable = [
        'project_id',
        'sprint_name',
        'sprint_desc',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class, 'project_sprint_id');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'bg-yellow-lt',
            'active'    => 'bg-green-lt',
            'completed' => 'bg-blue-lt',
            default     => 'bg-gray-lt',
        };
    }

    public function getTaskCountAttribute(): int
    {
        return $this->tasks()->count();
    }
}
