<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectCostRequest;
use App\Models\Project\Project;
use App\Models\Project\ProjectCost;
use App\Services\Project\ProjectService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectCostController extends Controller
{
    public function __construct(protected ProjectService $projectService)
    {}

    /**
     * Store a newly created cost in storage.
     */
    public function store(ProjectCostRequest $request, Project $project)
    {
        try {
            $data = $request->validated();
            $data['project_id'] = $project->project_id;
            $data['author_id'] = Auth::id();
            
            ProjectCost::create($data);

            return jsonSuccess('Cost recorded successfully');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Failed to record cost: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified cost in storage.
     */
    public function update(ProjectCostRequest $request, Project $project, ProjectCost $cost)
    {
        try {
            $cost->update($request->validated());

            return jsonSuccess('Cost updated successfully');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Failed to update cost: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified cost from storage.
     */
    public function destroy(Project $project, ProjectCost $cost)
    {
        try {
            $cost->delete();

            return jsonSuccess('Cost deleted successfully');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Failed to delete cost: ' . $e->getMessage());
        }
    }

    /**
     * Show modal for adding/editing cost
     */
    public function editModal(Project $project, ?ProjectCost $cost = null)
    {
        $tasks = $project->tasks;
        if (!$cost) {
            $cost = new ProjectCost();
            $cost->project_id = $project->project_id;
            $cost->cost_date = now()->format('Y-m-d');
            $cost->cost_type = 'out_cash';
        }

        return view('pages.projects.costs.edit-modal', compact('project', 'cost', 'tasks'));
    }
}
