<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectMemberRequest;
use App\Models\Project\Project;
use App\Models\Project\ProjectMember;
use App\Models\User;
use App\Services\Project\ProjectService;
use Exception;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    public function __construct(protected ProjectService $projectService)
    {}

    /**
     * Store a newly created member in storage.
     */
    public function store(ProjectMemberRequest $request, Project $project)
    {
        try {
            $this->projectService->addMember($project, $request->validated());

            return jsonSuccess('Member added successfully');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Failed to add member: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy(Project $project, ProjectMember $member)
    {
        try {
            $this->projectService->removeMember($project, $member->project_member_id);

            return jsonSuccess('Member removed successfully');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Failed to remove member: ' . $e->getMessage());
        }
    }

    /**
     * Show modal for adding/editing member
     */
    public function editModal(Project $project, ?ProjectMember $member = null)
    {
        $users = User::all();
        if (!$member) {
            $member = new ProjectMember();
            $member->project_id = $project->project_id;
        }

        return view('pages.projects.members.edit-modal', compact('project', 'member', 'users'));
    }
}
