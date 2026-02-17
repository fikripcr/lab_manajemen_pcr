<?php
namespace App\Services\Event;

use App\Models\Event\Event;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function store(array $data): Event
    {
        return DB::transaction(function () use ($data) {
            return Event::create($data);
        });
    }

    public function update(Event $event, array $data): Event
    {
        return DB::transaction(function () use ($event, $data) {
            $event->update($data);
            return $event;
        });
    }

    public function destroy(Event $event): void
    {
        DB::transaction(function () use ($event) {
            $event->delete();
        });
    }
}
