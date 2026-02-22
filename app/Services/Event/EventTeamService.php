<?php
namespace App\Services\Event;

use App\Models\Event\EventTeam;
use Illuminate\Support\Facades\DB;

class EventTeamService
{
    /**
     * Store new team member
     */
    public function store(array $data): EventTeam
    {
        return DB::transaction(function () use ($data) {
            // If this is PIC, unset other PICs
            if (! empty($data['is_pic'])) {
                EventTeam::where('event_id', $data['event_id'])
                    ->update(['is_pic' => false]);
            }

            $team = EventTeam::create([
                'event_id'          => $data['event_id'],
                'pegawai_id'        => $data['pegawai_id'],
                'role'              => $data['role'] ?? null,
                'jabatan_dalam_tim' => $data['jabatan_dalam_tim'] ?? null,
                'is_pic'            => $data['is_pic'] ?? false,
                'created_by'        => auth()->id(),
            ]);

            $memberName = $team->memberable?->nama_pegawai ?? 'N/A';
            logActivity(
                'event_team',
                "Added team member: {$memberName} to event",
                $team
            );

            return $team;
        });
    }

    /**
     * Update team member
     */
    public function update(EventTeam $team, array $data): EventTeam
    {
        return DB::transaction(function () use ($team, $data) {
            // If this is PIC, unset other PICs in same event
            if (! empty($data['is_pic']) && ! $team->is_pic) {
                EventTeam::where('event_id', $team->event_id)
                    ->where('eventteam_id', '!=', $team->eventteam_id)
                    ->update(['is_pic' => false]);
            }

            $team->update([
                'pegawai_id'        => $data['pegawai_id'],
                'role'              => $data['role'] ?? null,
                'jabatan_dalam_tim' => $data['jabatan_dalam_tim'] ?? null,
                'is_pic'            => $data['is_pic'] ?? false,
            ]);

            $memberName = $team->memberable?->nama_pegawai ?? 'N/A';
            logActivity(
                'event_team',
                "Updated team member: {$memberName}",
                $team
            );

            return $team->fresh();
        });
    }

    /**
     * Delete team member
     */
    public function destroy(EventTeam $team): bool
    {
        return DB::transaction(function () use ($team) {
            $memberName = $team->memberable?->nama_pegawai ?? 'N/A';
            logActivity(
                'event_team',
                "Removed team member: {$memberName} from event",
                $team
            );

            return $team->delete();
        });
    }
}
