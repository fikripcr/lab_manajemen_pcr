<?php

namespace App\Models\Project;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes, HashidBinding, Blameable;

    /**
     * Table name
     */
    protected $table = 'pr_projects';

    /**
     * Primary key
     */
    protected $primaryKey = 'project_id';

    /**
     * Fillable attributes
     */
    protected $fillable = [
        'project_name',
        'project_desc',
        'is_agile',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'is_agile'   => 'boolean',
        'start_date' => 'date',
        'end_date'   => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the creator of the project
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all members of the project
     */
    public function members(): HasMany
    {
        return $this->hasMany(ProjectMember::class, 'project_id');
    }

    /**
     * Get all phases of the project
     */
    public function phases(): HasMany
    {
        return $this->hasMany(ProjectPhase::class, 'project_id');
    }

    /**
     * Get all sprints of the project
     */
    public function sprints(): HasMany
    {
        return $this->hasMany(ProjectSprint::class, 'project_id');
    }

    /**
     * Get all tasks of the project
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class, 'project_id');
    }

    /**
     * Get all costs of the project
     */
    public function costs(): HasMany
    {
        return $this->hasMany(ProjectCost::class, 'project_id');
    }

    /**
     * Get project leader
     */
    public function leader(): ?ProjectMember
    {
        return $this->members()->where('role', 'leader')->first();
    }

    /**
     * Get total project cost (sum of all costs)
     */
    public function getTotalCostAttribute(): float
    {
        return $this->costs()->sum('amount');
    }

    /**
     * Get task count
     */
    public function getTaskCountAttribute(): int
    {
        return $this->tasks()->count();
    }

    /**
     * Get completed task count
     */
    public function getCompletedTaskCountAttribute(): int
    {
        return $this->tasks()->where('status', 'done')->count();
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute(): float
    {
        $total = $this->task_count;
        if ($total === 0) {
            return 0;
        }

        return round(($this->completed_task_count / $total) * 100, 2);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'planning'  => 'bg-blue-lt',
            'active'    => 'bg-green-lt',
            'completed' => 'bg-teal-lt',
            'on_hold'   => 'bg-yellow-lt',
            default     => 'bg-gray-lt',
        };
    }

    /**
     * Get formatted date range
     */
    public function getDateRangeAttribute(): string
    {
        return formatTanggalIndo($this->start_date) . ' - ' . formatTanggalIndo($this->end_date);
    }

    /**
     * Scope: Active projects
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Completed projects
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Filter by status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
