<?php

namespace App\Models\Project;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectCost extends Model
{
    use HasFactory, SoftDeletes, HashidBinding, Blameable;

    protected $table = 'pr_project_costs';
    protected $primaryKey = 'project_cost_id';

    protected $fillable = [
        'project_id',
        'project_task_id',
        'author_id',
        'cost_desc',
        'cost_type',
        'amount',
        'cost_date',
        'approval_status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'cost_date'  => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(ProjectTask::class, 'project_task_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getApprovalStatusBadgeClassAttribute(): string
    {
        return match ($this->approval_status) {
            'pending'  => 'bg-yellow-lt',
            'approved' => 'bg-green-lt',
            'rejected' => 'bg-red-lt',
            default    => 'bg-gray-lt',
        };
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}
