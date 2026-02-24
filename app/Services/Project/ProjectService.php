<?php

namespace App\Services\Project;

use App\Models\Project\Project;
use App\Models\Project\ProjectCost;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    /**
     * Get all projects with relationships
     */
    public function getAllProjects()
    {
        return Project::with(['creator', 'members', 'tasks'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get paginated projects for DataTables
     */
    public function getPaginatedProjects()
    {
        return Project::with(['creator', 'members'])
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get project by ID with all relationships
     */
    public function getProjectById(string $id): ?Project
    {
        return Project::with([
            'members.user',
            'phases',
            'sprints',
            'tasks.assignee',
            'tasks.phase',
            'tasks.sprint',
            'costs.author',
        ])->find($id);
    }

    /**
     * Create a new project
     */
    public function createProject(array $data): Project
    {
        return DB::transaction(function () use ($data) {
            $project = Project::create([
                'project_name' => $data['project_name'],
                'project_desc' => $data['project_desc'] ?? null,
                'is_agile'     => $data['is_agile'] ?? false,
                'start_date'   => $data['start_date'],
                'end_date'     => $data['end_date'],
                'status'       => $data['status'] ?? 'planning',
            ]);

            // Add creator as project leader
            if (Auth::check()) {
                $project->members()->create([
                    'user_id'        => Auth::id(),
                    'role'           => 'leader',
                    'alias_position' => 'Project Manager',
                ]);
            }

            logActivity(
                'project_management',
                "Created project: {$project->project_name}",
                $project
            );

            return $project;
        });
    }

    /**
     * Update an existing project
     */
    public function updateProject(Project $project, array $data): bool
    {
        return DB::transaction(function () use ($project, $data) {
            $oldName = $project->project_name;

            $project->update([
                'project_name' => $data['project_name'],
                'project_desc' => $data['project_desc'] ?? null,
                'is_agile'     => $data['is_agile'] ?? false,
                'start_date'   => $data['start_date'],
                'end_date'     => $data['end_date'],
                'status'       => $data['status'] ?? $project->status,
            ]);

            logActivity(
                'project_management',
                "Updated project: {$oldName} → {$project->project_name}",
                $project
            );

            return true;
        });
    }

    /**
     * Delete a project
     */
    public function deleteProject(Project $project): bool
    {
        return DB::transaction(function () use ($project) {
            $projectName = $project->project_name;

            $project->delete();

            logActivity(
                'project_management',
                "Deleted project: {$projectName}",
                $project
            );

            return true;
        });
    }

    /**
     * Calculate total cost for a project (bottom-up from costs table)
     */
    public function calculateTotalCost(Project $project): float
    {
        return $project->costs()->sum('amount');
    }

    /**
     * Get project statistics
     */
    public function getProjectStatistics(Project $project): array
    {
        return [
            'total_tasks'        => $project->tasks()->count(),
            'completed_tasks'    => $project->tasks()->where('status', 'done')->count(),
            'in_progress_tasks'  => $project->tasks()->where('status', 'in_progress')->count(),
            'todo_tasks'         => $project->tasks()->where('status', 'todo')->count(),
            'review_tasks'       => $project->tasks()->where('status', 'review')->count(),
            'total_cost'         => $this->calculateTotalCost($project),
            'total_members'      => $project->members()->count(),
            'total_phases'       => $project->phases()->count(),
            'total_sprints'      => $project->sprints()->count(),
            'progress_percentage' => $project->progress_percentage,
        ];
    }

    /**
     * Add member to project
     */
    public function addMember(Project $project, array $data)
    {
        return DB::transaction(function () use ($project, $data) {
            $member = $project->members()->create([
                'user_id'        => $data['user_id'],
                'role'           => $data['role'] ?? 'member',
                'alias_position' => $data['alias_position'] ?? null,
                'rate_per_hour'  => $data['rate_per_hour'] ?? null,
            ]);

            logActivity(
                'project_management',
                "Added member to project: {$project->project_name}",
                $project,
                ['member_id' => $member->project_member_id]
            );

            return $member;
        });
    }

    /**
     * Remove member from project
     */
    public function removeMember(Project $project, int $memberId): bool
    {
        return DB::transaction(function () use ($project, $memberId) {
            $member = $project->members()->findOrFail($memberId);
            $member->delete();

            logActivity(
                'project_management',
                "Removed member from project: {$project->project_name}",
                $project
            );

            return true;
        });
    }

    /**
     * Find project or fail
     */
    protected function findOrFail(string $id): Project
    {
        $project = Project::find($id);
        if (!$project) {
            throw new Exception('Project not found');
        }

        return $project;
    }
}
