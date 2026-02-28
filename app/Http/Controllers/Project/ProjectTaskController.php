<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\MoveTaskRequest;
use App\Http\Requests\Project\ProjectTaskRequest;
use App\Models\Project\Project;
use App\Models\Project\ProjectTask;
use App\Models\User;
use App\Services\Project\ProjectService;
use App\Services\Project\ProjectTaskService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProjectTaskController extends Controller
{
    public function __construct(
        protected ProjectTaskService $taskService,
        protected ProjectService $projectService
    ) {
    }

    /**
     * Display a listing of tasks for a project
     */
    public function index(Project $project)
    {
        return view('pages.projects.tasks.index', compact('project'));
    }

    /**
     * Display a listing of tasks for DataTables
     */
    public function paginate(Project $project)
    {
        $query = $this->taskService->getPaginatedTasks($project->project_id);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('task_title', function ($item) {
                return view('components.project.task-title', [
                    'task' => $item,
                ])->render();
            })
            ->editColumn('status', function ($item) {
                return '<span class="badge ' . $item->status_badge_class . '">' . $item->status_label . '</span>';
            })
            ->editColumn('priority', function ($item) {
                return '<span class="badge ' . $item->priority_badge_class . '">' . ucfirst($item->priority) . '</span>';
            })
            ->addColumn('assignee', function ($item) {
                if (!$item->assignee) {
                    return '<span class="text-muted">Unassigned</span>';
                }
                return view('components.project.assignee-avatar', [
                    'user' => $item->assignee,
                ])->render();
            })
            ->editColumn('due_date', function ($item) {
                return $item->due_date ? formatTanggalIndo($item->due_date) : '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function ($item) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('projects.tasks.edit', [$item->project, $item]),
                    'editModal' => true,
                    'deleteUrl' => route('projects.tasks.destroy', [$item->project, $item]),
                ])->render();
            })
            ->rawColumns(['task_title', 'status', 'priority', 'assignee', 'due_date', 'action'])
            ->make(true);
    }

    /**
     * Store a newly created task
     */
    public function store(ProjectTaskRequest $request, Project $project)
    {
        $data = $request->validated();
        $data['project_id'] = $project->project_id;
        $this->taskService->createTask($data);

        return jsonSuccess(
            'Task created successfully'
        );
    }

    /**
     * Update the specified task
     */
    public function update(ProjectTaskRequest $request, Project $project, ProjectTask $task)
    {
        $data = $request->validated();
        $this->taskService->updateTask($task, $data);

        return jsonSuccess(
            'Task updated successfully'
        );
    }

    /**
     * Move task to different status (Kanban)
     */
    public function move(MoveTaskRequest $request, Project $project, ProjectTask $task)
    {
        $data = $request->validated();
        $this->taskService->updateTaskStatus($task, $data['status']);

        return jsonSuccess('Task moved successfully');
    }

    /**
     * Remove the specified task
     */
    public function destroy(Project $project, ProjectTask $task)
    {
        $taskTitle = $task->task_title;
        $this->taskService->deleteTask($task);

        return jsonSuccess(
            'Task deleted successfully'
        );
    }

    /**
     * Get task edit modal content
     */
    public function editModal(Request $request, Project $project, ?ProjectTask $task = null)
    {
        $users = User::all();
        $phases = $project->phases;
        $sprints = $project->sprints;
        
        // Create new instance if not editing
        if (!$task) {
            $task = new ProjectTask();
            $task->project_id = $project->project_id;
            
            // Set default status from query parameter if provided
            if ($request->has('status')) {
                $task->status = $request->query('status');
            }
        }

        return view('pages.projects.tasks.edit-modal', compact('project', 'task', 'users', 'phases', 'sprints'));
    }

    /**
     * Get tasks grouped by status for Kanban (AJAX)
     */
    public function kanbanData(Project $project)
    {
        $tasks = $this->taskService->getTasksGroupedByStatus($project->project_id);
        
        // Reformat tasks for jKanban
        $formattedTasks = [
            'todo' => collect($tasks['todo'] ?? [])->map(fn($t) => $this->formatForKanban($t))->toArray(),
            'in_progress' => collect($tasks['in_progress'] ?? [])->map(fn($t) => $this->formatForKanban($t))->toArray(),
            'done' => collect($tasks['done'] ?? [])->map(fn($t) => $this->formatForKanban($t))->toArray(),
        ];

        return jsonSuccess('Data retrieved', null, $formattedTasks);
    }

    /**
     * Helper to format task for jKanban
     */
    protected function formatForKanban($task)
    {
        $borderColor = match ($task->priority) {
            'low' => 'secondary',
            'medium' => 'blue',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'transparent'
        };

        return [
            'id' => $task->encrypted_project_task_id,
            'title' => view('components.project.kanban-task-card', ['task' => $task])->render(),
            'class' => ['kanban-task-' . $task->encrypted_project_task_id, 'border-start', 'border-start-wide', 'border-' . $borderColor]
        ];
    }
}
