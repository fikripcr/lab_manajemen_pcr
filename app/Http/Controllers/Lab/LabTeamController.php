<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\LabTeamStoreRequest;
use App\Models\Lab\Lab;
use App\Models\Lab\LabTeam;
use App\Services\Lab\LabTeamService;
use Illuminate\Http\Request;

class LabTeamController extends Controller
{
    public function __construct(protected LabTeamService $labTeamService)
    {}

    public function index(Lab $lab)
    {
        $lab->load(['labTeams.user']);
        $labTeams = $this->labTeamService->getLabTeamsQuery($lab->lab_id)->paginate(10);
        return view('pages.lab.labs.teams.index', compact('lab', 'labTeams'));
    }

    public function create(Lab $lab)
    {
        $labTeam = new LabTeam();
        return view('pages.lab.labs.teams.create-edit-ajax', compact('lab', 'labTeam'));
    }

    public function store(LabTeamStoreRequest $request, Lab $lab)
    {
        $validated = $request->validated();

        $this->labTeamService->assignUserToLab($lab->lab_id, $validated);
        return jsonSuccess('Anggota tim berhasil ditambahkan ke lab.', route('lab.labs.teams.index', $lab->encrypted_lab_id));
    }

    public function edit(Lab $lab, LabTeam $team)
    {
        $labTeam = $team;
        return view('pages.lab.labs.teams.create-edit-ajax', compact('lab', 'labTeam'));
    }

    public function update(LabTeamStoreRequest $request, Lab $lab, LabTeam $team)
    {
        $this->labTeamService->updateLabTeam($team, $request->validated());
        return jsonSuccess('Anggota tim berhasil diperbarui.', route('lab.labs.teams.index', $lab->encrypted_lab_id));
    }

    public function destroy(Lab $lab, LabTeam $team)
    {
        $this->labTeamService->removeUserFromLab($lab->lab_id, $team);
        return jsonSuccess('Anggota tim berhasil dinonaktifkan dari lab.', route('lab.labs.teams.index', $lab->encrypted_lab_id));
    }

    public function getUsers(Request $request, Lab $lab)
    {
        $search = $request->get('search');
        $users  = $this->labTeamService->getUsersForAutocomplete($lab->lab_id, $search);

        $results = $users->map(fn($user) => [
            'id'   => encryptId($user->id),
            'text' => $user->name . ' (' . $user->email . ')',
        ]);

        return jsonSuccess('Data retrieved', null, ['results' => $results]);
    }
}
