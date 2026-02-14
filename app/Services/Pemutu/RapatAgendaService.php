<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\RapatAgenda;
use Illuminate\Support\Facades\DB;

class RapatAgendaService
{
    public function store(array $data): RapatAgenda
    {
        return DB::transaction(function () use ($data) {
            return RapatAgenda::create($data);
        });
    }

    public function update(RapatAgenda $rapatAgenda, array $data): RapatAgenda
    {
        return DB::transaction(function () use ($rapatAgenda, $data) {
            $rapatAgenda->update($data);
            return $rapatAgenda;
        });
    }

    public function destroy(RapatAgenda $rapatAgenda): void
    {
        DB::transaction(function () use ($rapatAgenda) {
            $rapatAgenda->delete();
        });
    }
}
