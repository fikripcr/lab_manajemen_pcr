<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\User;
use App\Models\LabTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $labId)
    {
        $lab = Lab::findOrFail($labId);
        $labTeams = $lab->labTeams()->with(['user'])->paginate(10);

        return view('pages.admin.labs.teams.index', compact('lab', 'labTeams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($labId)
    {
        $lab = Lab::findOrFail($labId);
        $users = User::all();

        return view('pages.admin.labs.teams.create', compact('lab', 'users'));
    }

    /**
     * Get users with search for autocomplete
     */
    public function getUsers(Request $request, $labId)
    {
        $search = $request->get('search');
        $realLabId = decryptId($labId);

        // Exclude users already assigned to this lab
        $users = User::select('id', 'name', 'email')
            ->whereDoesntHave('labTeams', function($query) use ($realLabId) {
                $query->where('lab_id', $realLabId);
            })
            ->when($search, function($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->limit(5)
            ->get()
            ->map(function($user) {
                return [
                    'id' => encryptId($user->id),
                    'text' => $user->name . ' (' . $user->email . ')'
                ];
            });

        return response()->json([
            'results' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $labId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jabatan' => 'nullable|string|max:255',
            'tanggal_mulai' => 'nullable|date',
        ]);

        $realId = decryptId($request->user_id);
        $lab = Lab::findOrFail($labId);
        $user = User::findOrFail($realId);

        DB::beginTransaction();
        try {
            // Check if this user is already assigned to this lab
            $existingAssignment = LabTeam::where('user_id', $user->id)
                                         ->where('lab_id', $lab->lab_id)
                                         ->first();

            if ($existingAssignment) {
                return redirect()->back()
                    ->with('error', 'User ini sudah menjadi bagian dari lab ini.')
                    ->withInput();
            }

            LabTeam::create([
                'user_id' => $user->id,
                'lab_id' => $lab->lab_id,
                'jabatan' => $request->jabatan,
                'tanggal_mulai' => $request->tanggal_mulai ?? now(),
                'is_active' => true,
            ]);

            DB::commit();

            return redirect()->route('labs.teams.index', $lab->lab_id)
                ->with('success', 'Anggota tim berhasil ditambahkan ke lab.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan anggota tim: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($labId, $id)
    {
        $labTeam = LabTeam::findOrFail($id);

        if ($labTeam->lab_id != $labId) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            // Update status to inactive instead of deleting
            $labTeam->update([
                'is_active' => false,
                'tanggal_selesai' => now()
            ]);

            DB::commit();

            return redirect()->route('labs.teams.index', $labId)
                ->with('success', 'Anggota tim berhasil dihapus dari lab.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus anggota tim: ' . $e->getMessage());
        }
    }
}
