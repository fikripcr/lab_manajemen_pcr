<?php
namespace App\Services\Event;

use App\Models\Event\Rapat;
use App\Models\Event\RapatAgenda;
use App\Models\Event\RapatEntitas;
use App\Models\Event\RapatPeserta;
use Illuminate\Support\Facades\DB;

class RapatService
{
    public function store(array $data): Rapat
    {
        return DB::transaction(function () use ($data) {
            $rapat = Rapat::create($data);
            return $rapat;
        });
    }

    public function update(Rapat $rapat, array $data): Rapat
    {
        return DB::transaction(function () use ($rapat, $data) {
            $rapat->update($data);
            return $rapat;
        });
    }

    public function destroy(Rapat $rapat): void
    {
        DB::transaction(function () use ($rapat) {
            $rapat->delete();
        });
    }

    public function addAgenda(Rapat $rapat, array $data): RapatAgenda
    {
        return $rapat->agendas()->create($data);
    }

    public function addPeserta(Rapat $rapat, array $data): RapatPeserta
    {
        return $rapat->pesertas()->create($data);
    }

    public function addEntitas(Rapat $rapat, array $data): RapatEntitas
    {
        return $rapat->entitas()->create($data);
    }
}
