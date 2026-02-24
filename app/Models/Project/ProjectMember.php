<?php

namespace App\Models\Project;

use App\Models\User;
use App\Traits\Blameable;
use App\Traits\HashidBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectMember extends Model
{
    use HasFactory, SoftDeletes, HashidBinding, Blameable;

    protected $table = 'pr_project_members';
    protected $primaryKey = 'project_member_id';

    protected $fillable = [
        'project_id',
        'user_id',
        'role',
        'alias_position',
        'rate_per_hour',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'rate_per_hour' => 'decimal:2',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return $this->role === 'leader' ? 'bg-purple-lt' : 'bg-blue-lt';
    }
}
