<?php
namespace App\Services\Event;

use App\Models\Event\RapatAgenda;
use Illuminate\Support\Facades\DB;

class RapatAgendaService
{
    public function store(array $data): RapatAgenda
    {
        return DB::transaction(function () use ($data) {
            $agenda = RapatAgenda::create($data);
            logActivity('event', "Menambah agenda rapat baru: {$agenda->judul_agenda}", $agenda);
            return $agenda;
        });
    }

    public function update(RapatAgenda $rapatAgenda, array $data): RapatAgenda
    {
        return DB::transaction(function () use ($rapatAgenda, $data) {
            $rapatAgenda->update($data);
            logActivity('event', "Memperbarui agenda rapat: {$rapatAgenda->judul_agenda}", $rapatAgenda);
            return $rapatAgenda;
        });
    }

    public function destroy(RapatAgenda $rapatAgenda): void
    {
        DB::transaction(function () use ($rapatAgenda) {
            $judul = $rapatAgenda->judul_agenda;
            $rapatAgenda->delete();
            logActivity('event', "Menghapus agenda rapat: {$judul}", $rapatAgenda);
        });
    }
}
