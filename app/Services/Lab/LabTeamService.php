<?php
namespace App\Services\Lab;

use App\Models\Lab\Lab;
use App\Models\Lab\LabTeam;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class LabTeamService
{
    /**
     * Get Query for Lab Teams (Paginated by Controller)
     */
    public function getLabTeamsQuery(string $labId)
    {
        $lab = Lab::findOrFail($labId);
        return $lab->labTeams()->with(['user']);
    }

    /**
     * Get Users for Autocomplete (Excluding already assigned)
     */
    public function getUsersForAutocomplete(string $labId, ?string $search = null, int $limit = 5)
    {
        return User::select('id', 'name', 'email')
            ->whereDoesntHave('labTeams', function ($query) use ($labId) {
                $query->where('lab_id', $labId);
            })
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->limit($limit)
            ->get();
    }

    /**
     * Assign User to Lab Team
     */
    public function assignUserToLab(string $labId, array $data): LabTeam
    {
        return DB::transaction(function () use ($labId, $data) {
            $user = User::findOrFail($data['user_id']); // ID should be decrypted by Controller before passing or handled here if raw
                                                        // Assuming Controller passes Decrypted User ID or Raw ID if from Select2 (Select2 usually sends value as is)

            // Check if user is already assigned
            $existingAssignment = LabTeam::where('user_id', $user->id)
                ->where('lab_id', $labId)
                ->first();

            if ($existingAssignment) {
                throw new Exception('User ini sudah menjadi bagian dari lab ini.');
            }

            $labTeam = LabTeam::create([
                'user_id'       => $user->id,
                'lab_id'        => $labId,
                'jabatan'       => $data['jabatan'] ?? null,
                'tanggal_mulai' => $data['tanggal_mulai'] ?? now(),
                'is_active'     => true,
            ]);

            logActivity('lab_team_management', "Menambahkan user {$user->name} ke Lab ID {$labId}");

            return $labTeam;
        });
    }

    /**
     * Remove/Deactivate User from Lab Team
     */
    public function updateLabTeam(LabTeam $labTeam, array $data): bool
    {
        return DB::transaction(function () use ($labTeam, $data) {
            $labTeam->update($data);

            logActivity('lab_team_management', "Memperbarui data anggota tim ID {$labTeam->lab_team_id}");

            return true;
        });
    }

    /**
     * Remove/Deactivate User from Lab Team
     */
    public function removeUserFromLab(string $labId, LabTeam $labTeam): bool
    {
        return DB::transaction(function () use ($labId, $labTeam) {
            if ($labTeam->lab_id != $labId) {
                throw new Exception('Data tidak ditemukan atau tidak sesuai dengan Lab ini.');
            }

            $labTeam->update([
                'is_active'       => false,
                'tanggal_selesai' => now(),
            ]);

            logActivity('lab_team_management', "Menonaktifkan anggota tim ID {$labTeam->lab_team_id} dari Lab ID {$labId}");

            return true;
        });
    }
}
