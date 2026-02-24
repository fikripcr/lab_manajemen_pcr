<?php

namespace App\Models\Project;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTask extends Model
{
    use HasFactory, SoftDeletes, HashidBinding, Blameable;

    /**
     * Table name
     */
    protected $table = 'pr_project_tasks';

    /**
     * Primary key
     */
    protected $primaryKey = 'project_task_id';

    /**
     * Fillable attributes
     */
    protected $fillable = [
        'project_id',
        'project_phase_id',
        'project_sprint_id',
        'assignee_id',
        'parent_id',
        'task_title',
        'task_desc',
        'status',
        'weight',
        'hours_worked',
        'seq',
        'priority',
        'due_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Appends
     */
    protected $appends = [
        'due_date_formatted',
        'encrypted_project_task_id',
        'status_label',
        'status_badge_class',
        'priority_badge_class',
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'weight'       => 'integer',
        'hours_worked' => 'integer',
        'seq'          => 'integer',
        'due_date'     => 'date',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'deleted_at'   => 'datetime',
    ];

    /**
     * Get the project that owns the task
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get the phase that owns the task
     */
    public function phase(): BelongsTo
    {
        return $this->belongsTo(ProjectPhase::class, 'project_phase_id');
    }

    /**
     * Get the sprint that owns the task
     */
    public function sprint(): BelongsTo
    {
        return $this->belongsTo(ProjectSprint::class, 'project_sprint_id');
    }

    /**
     * Get the assignee of the task
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    /**
     * Get the parent task
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProjectTask::class, 'parent_id');
    }

    /**
     * Get subtasks
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class, 'parent_id');
    }

    /**
     * Get costs related to this task
     */
    public function costs(): HasMany
    {
        return $this->hasMany(ProjectCost::class, 'project_task_id');
    }

    /**
     * Get task cost (sum of task-specific costs)
     */
    public function getTotalCostAttribute(): float
    {
        return $this->costs()->sum('amount');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'todo'        => 'bg-blue-lt',
            'in_progress' => 'bg-yellow-lt',
            'review'      => 'bg-orange-lt',
            'done'        => 'bg-green-lt',
            default       => 'bg-gray-lt',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'todo'        => 'To Do',
            'in_progress' => 'In Progress',
            'review'      => 'Review',
            'done'        => 'Done',
            default       => ucfirst($this->status),
        };
    }

    /**
     * Get priority badge class
     */
    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'low'      => 'bg-secondary-lt',
            'medium'   => 'bg-blue-lt',
            'high'     => 'bg-orange-lt',
            'urgent'   => 'bg-red-lt',
            default    => 'bg-gray-lt',
        };
    }

    /**
     * Scope: Filter by project
     */
    public function scopeByProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter by assignee
     */
    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assignee_id', $userId);
    }

    /**
     * Scope: Order by sequence
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('seq')->orderBy('created_at');
    }

    /**
     * Scope: Filter by parent task (get subtasks only)
     */
    public function scopeSubtasks($query, ?int $parentId = null)
    {
        return $query->whereNotNull('parent_id')
            ->when($parentId, fn ($q) => $q->where('parent_id', $parentId));
    }

    /**
     * Scope: Filter by phase
     */
    public function scopeByPhase($query, int $phaseId)
    {
        return $query->where('project_phase_id', $phaseId);
    }

    /**
     * Scope: Filter by sprint
     */
    public function scopeBySprint($query, int $sprintId)
    {
        return $query->where('project_sprint_id', $sprintId);
    }

    /**
     * Get formatted due date
     */
    public function getDueDateFormattedAttribute(): ?string
    {
        return $this->due_date ? formatTanggalIndo($this->due_date) : null;
    }

    /**
     * Get encrypted task ID
     */
    public function getEncryptedProjectTaskIdAttribute(): string
    {
        return encryptId($this->project_task_id);
    }
}
