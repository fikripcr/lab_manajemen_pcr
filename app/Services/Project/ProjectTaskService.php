<?php

namespace App\Services\Project;

use App\Models\Project\Project;
use App\Models\Project\ProjectTask;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectTaskService
{
    /**
     * Get all tasks for a project
     */
    public function getTasksByProject(int $projectId)
    {
        return ProjectTask::with(['assignee', 'phase', 'sprint', 'parent'])
            ->byProject($projectId)
            ->ordered()
            ->get();
    }

    /**
     * Get paginated tasks for DataTables
     */
    public function getPaginatedTasks(int $projectId)
    {
        return ProjectTask::with(['assignee', 'phase', 'sprint'])
            ->byProject($projectId)
            ->orderBy('seq')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get task by ID
     */
    public function getTaskById(string $id): ?ProjectTask
    {
        return ProjectTask::with([
            'project',
            'assignee',
            'phase',
            'sprint',
            'parent',
            'subtasks',
            'costs',
        ])->find($id);
    }

    /**
     * Create a new task
     */
    public function createTask(array $data): ProjectTask
    {
        return DB::transaction(function () use ($data) {
            $task = ProjectTask::create([
                'project_id'       => $data['project_id'],
                'project_phase_id' => $data['project_phase_id'] ?? null,
                'project_sprint_id' => $data['project_sprint_id'] ?? null,
                'assignee_id'      => $data['assignee_id'] ?? null,
                'parent_id'        => $data['parent_id'] ?? null,
                'task_title'       => $data['task_title'],
                'task_desc'        => $data['task_desc'] ?? null,
                'status'           => $data['status'] ?? 'todo',
                'weight'           => $data['weight'] ?? 1,
                'hours_worked'     => $data['hours_worked'] ?? 0,
                'seq'              => $data['seq'] ?? $this->getNextSequence($data['project_id']),
                'priority'         => $data['priority'] ?? 'medium',
                'due_date'         => $data['due_date'] ?? null,
            ]);

            logActivity(
                'project_task_management',
                "Created task: {$task->task_title}",
                $task
            );

            return $task;
        });
    }

    /**
     * Update an existing task
     */
    public function updateTask(ProjectTask $task, array $data): bool
    {
        return DB::transaction(function () use ($task, $data) {
            $oldTitle = $task->task_title;

            $task->update([
                'task_title'        => $data['task_title'] ?? $task->task_title,
                'task_desc'         => $data['task_desc'] ?? $task->task_desc,
                'status'            => $data['status'] ?? $task->status,
                'weight'            => $data['weight'] ?? $task->weight,
                'hours_worked'      => $data['hours_worked'] ?? $task->hours_worked,
                'priority'          => $data['priority'] ?? $task->priority,
                'due_date'          => $data['due_date'] ?? $task->due_date,
                'project_phase_id'  => $data['project_phase_id'] ?? $task->project_phase_id,
                'project_sprint_id' => $data['project_sprint_id'] ?? $task->project_sprint_id,
                'assignee_id'       => $data['assignee_id'] ?? $task->assignee_id,
                'parent_id'         => $data['parent_id'] ?? $task->parent_id,
            ]);

            logActivity(
                'project_task_management',
                "Updated task: {$oldTitle} → {$task->task_title}",
                $task
            );

            return true;
        });
    }

    /**
     * Update task status (for Kanban drag & drop)
     */
    public function updateTaskStatus(ProjectTask $task, string $newStatus): bool
    {
        $validStatuses = ['todo', 'in_progress', 'review', 'done'];

        if (!in_array($newStatus, $validStatuses)) {
            throw new Exception('Invalid status. Must be one of: ' . implode(', ', $validStatuses));
        }

        return DB::transaction(function () use ($task, $newStatus) {
            $oldStatus = $task->status;
            $task->status = $newStatus;
            $task->save();

            logActivity(
                'project_task_management',
                "Moved task '{$task->task_title}' from {$oldStatus} to {$newStatus}",
                $task,
                ['old_status' => $oldStatus, 'new_status' => $newStatus]
            );

            return true;
        });
    }

    /**
     * Delete a task
     */
    public function deleteTask(ProjectTask $task): bool
    {
        return DB::transaction(function () use ($task) {
            $taskTitle = $task->task_title;

            $task->delete();

            logActivity(
                'project_task_management',
                "Deleted task: {$taskTitle}",
                $task
            );

            return true;
        });
    }

    /**
     * Get tasks grouped by status (for Kanban)
     */
    public function getTasksGroupedByStatus(int $projectId): array
    {
        $tasks = $this->getTasksByProject($projectId);

        return [
            'todo'        => $tasks->where('status', 'todo')->values()->all(),
            'in_progress' => $tasks->where('status', 'in_progress')->values()->all(),
            'review'      => $tasks->where('status', 'review')->values()->all(),
            'done'        => $tasks->where('status', 'done')->values()->all(),
        ];
    }

    /**
     * Get next sequence number for ordering
     */
    protected function getNextSequence(int $projectId): int
    {
        $maxSeq = ProjectTask::where('project_id', $projectId)->max('seq');
        return ($maxSeq ?? 0) + 1;
    }

    /**
     * Reorder tasks
     */
    public function reorderTasks(array $taskIds): bool
    {
        return DB::transaction(function () use ($taskIds) {
            foreach ($taskIds as $index => $taskId) {
                ProjectTask::where('project_task_id', $taskId)->update(['seq' => $index + 1]);
            }

            return true;
        });
    }

    /**
     * Find task or fail
     */
    protected function findOrFail(string $id): ProjectTask
    {
        $task = ProjectTask::find($id);
        if (!$task) {
            throw new Exception('Task not found');
        }

        return $task;
    }
}
