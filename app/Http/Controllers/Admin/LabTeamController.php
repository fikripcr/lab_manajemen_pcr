<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LabTeamRequest;
use App\Models\Lab;
use App\Models\User;
use App\Services\Admin\LabTeamService;
use Illuminate\Http\Request;

class LabTeamController extends Controller
{
    protected $labTeamService;

    public function __construct(LabTeamService $labTeamService)
    {
        $this->labTeamService = $labTeamService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $labId)
    {
        $realLabId = decryptId($labId);
        $lab       = Lab::findOrFail($realLabId);
        $labTeams  = $this->labTeamService->getLabTeamsQuery($realLabId)->paginate(10);

        return view('pages.admin.labs.teams.index', compact('lab', 'labTeams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($labId)
    {
        $realLabId = decryptId($labId);
        $lab       = Lab::findOrFail($realLabId);
        $users     = User::all();

        return view('pages.admin.labs.teams.create', compact('lab', 'users'));
    }

    /**
     * Get users with search for autocomplete
     */
    public function getUsers(Request $request, $labId)
    {
        $search    = $request->get('search');
        $realLabId = decryptId($labId);

        $users = $this->labTeamService->getUsersForAutocomplete($realLabId, $search);

        $results = $users->map(function ($user) {
            return [
                'id'   => encryptId($user->id), // Return Encrypted ID for form submission
                'text' => $user->name . ' (' . $user->email . ')',
            ];
        });

        return response()->json([
            'results' => $results,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LabTeamRequest $request, $labId)
    {
        $realLabId = decryptId($labId);

        try {
            $data            = $request->all();
            $data['user_id'] = decryptId($request->user_id); // Decrypt user_id

            $this->labTeamService->assignUserToLab($realLabId, $data);

            return jsonSuccess('Anggota tim berhasil ditambahkan ke lab.', route('labs.teams.index', $labId));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($labId, $id)
    {
        try {
            $realLabId = decryptId($labId);
            $realId    = decryptId($id); // Assuming ID passed in route is encrypted

            $this->labTeamService->removeUserFromLab($realLabId, $realId);

            return jsonSuccess('Anggota tim berhasil dihapus dari lab.', route('labs.teams.index', $labId));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
