<?php
namespace App\Services\Event;

use App\Models\Event\Event;
use App\Models\Event\EventTeam;
use Illuminate\Support\Facades\DB;

class EventTeamService
{
    public function store(array $data): EventTeam
    {
        return DB::transaction(function () use ($data) {
            return EventTeam::create($data);
        });
    }

    public function update(EventTeam $team, array $data): EventTeam
    {
        return DB::transaction(function () use ($team, $data) {
            $team->update($data);
            return $team;
        });
    }

    public function destroy(EventTeam $team): void
    {
        DB::transaction(function () use ($team) {
            $team->delete();
        });
    }

    /**
     * Bulk sync team members for an event
     */
    public function sync(Event $event, array $members): void
    {
        DB::transaction(function () use ($event, $members) {
            $event->teams()->delete();
            foreach ($members as $member) {
                $event->teams()->create($member);
            }
        });
    }
}
