<?php
namespace App\Services\Event;

use App\Models\Event\Event;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function store(array $data): Event
    {
        return DB::transaction(function () use ($data) {
            $event = Event::create($data);
            logActivity('event', "Menambah kegiatan baru: {$event->nama_event}");
            return $event;
        });
    }

    public function update(Event $event, array $data): Event
    {
        return DB::transaction(function () use ($event, $data) {
            $event->update($data);
            logActivity('event', "Memperbarui kegiatan: {$event->nama_event}");
            return $event;
        });
    }

    public function destroy(Event $event): void
    {
        DB::transaction(function () use ($event) {
            $nama = $event->nama_event;
            $event->delete();
            logActivity('event', "Menghapus kegiatan: {$nama}");
        });
    }
}
