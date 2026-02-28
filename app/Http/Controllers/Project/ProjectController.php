<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectRequest;
use App\Models\Project\Project;
use App\Models\User;
use App\Services\Project\ProjectService;
use App\Services\Project\ProjectTaskService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService,
        protected ProjectTaskService $taskService
    )
    {
    }

    /**
     * Display a listing of projects
     */
    public function index(Request $request)
    {
        return view('pages.projects.index');
    }

    /**
     * Display a listing of projects for DataTables
     */
    public function paginate(Request $request)
    {
        $query = $this->projectService->getPaginatedProjects();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('project_name', function ($item) {
                return view('components.project.project-name', [
                    'project' => $item,
                ])->render();
            })
            ->editColumn('status', function ($item) {
                return '<span class="badge ' . $item->status_badge_class . '">' . ucfirst(str_replace('_', ' ', $item->status)) . '</span>';
            })
            ->editColumn('start_date', function ($item) {
                return formatTanggalIndo($item->start_date);
            })
            ->editColumn('end_date', function ($item) {
                return formatTanggalIndo($item->end_date);
            })
            ->addColumn('progress', function ($item) {
                $percentage = $item->progress_percentage;
                return view('components.project.progress-bar', [
                    'percentage' => $percentage,
                ])->render();
            })
            ->addColumn('team_size', function ($item) {
                return $item->members->count();
            })
            ->addColumn('action', function ($item) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('projects.edit', $item),
                    'editModal' => false,
                    'viewUrl'   => route('projects.show', $item),
                    'deleteUrl' => route('projects.destroy', $item),
                ])->render();
            })
            ->rawColumns(['project_name', 'status', 'progress', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        $users = User::all();
        $project = new Project();

        return view('pages.projects.create-edit', compact('project', 'users'));
    }

    /**
     * Store a newly created project
     */
    public function store(ProjectRequest $request)
    {
        $data = $request->validated();
        $project = $this->projectService->createProject($data);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully');
    }

    /**
     * Display the specified project (Dashboard view)
     */
    public function show(Project $project)
    {
        $statistics = $this->projectService->getProjectStatistics($project);
        $tasks = $project->tasks()->with('assignee')->ordered()->get();
        $members = $project->members()->with('user')->get();

        return view('pages.projects.show', compact('project', 'statistics', 'tasks', 'members'));
    }

    /**
     * Show Kanban board for the specified project
     */
    public function kanban(Project $project)
    {
        $tasksByStatus = $this->taskService->getTasksGroupedByStatus($project->project_id);

        return view('pages.projects.kanban', compact('project', 'tasksByStatus'));
    }

    /**
     * Show the form for editing the specified project
     */
    public function edit(Project $project)
    {
        $users = User::all();

        return view('pages.projects.create-edit', compact('project', 'users'));
    }

    /**
     * Update the specified project
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $data = $request->validated();
        $this->projectService->updateProject($project, $data);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified project
     */
    public function destroy(Project $project)
    {
        $projectName = $project->project_name;
        $this->projectService->deleteProject($project);

        return jsonSuccess(
            'Project deleted successfully',
            route('projects.index')
        );
    }
}
