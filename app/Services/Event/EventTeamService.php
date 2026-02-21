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
            $team = EventTeam::create($data);
            logActivity('event', "Menambah panitia baru ke kegiatan: " . ($team->event->nama_event ?? 'Unknown'));
            return $team;
        });
    }

    public function update(EventTeam $team, array $data): EventTeam
    {
        return DB::transaction(function () use ($team, $data) {
            $team->update($data);
            logActivity('event', "Memperbarui data panitia di kegiatan: " . ($team->event->nama_event ?? 'Unknown'));
            return $team;
        });
    }

    public function destroy(EventTeam $team): void
    {
        DB::transaction(function () use ($team) {
            $nama = $team->event->nama_event ?? 'Unknown';
            $team->delete();
            logActivity('event', "Menghapus panitia dari kegiatan: {$nama}");
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
            logActivity('event', "Sinkronisasi panitia untuk kegiatan: {$event->nama_event}");
        });
    }
}
