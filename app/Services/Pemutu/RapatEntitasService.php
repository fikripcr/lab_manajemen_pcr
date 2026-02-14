<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\RapatEntitas;
use Illuminate\Support\Facades\DB;

class RapatEntitasService
{
    public function store(array $data): RapatEntitas
    {
        return DB::transaction(function () use ($data) {
            return RapatEntitas::create($data);
        });
    }

    public function update(RapatEntitas $rapatEntitas, array $data): RapatEntitas
    {
        return DB::transaction(function () use ($rapatEntitas, $data) {
            $rapatEntitas->update($data);
            return $rapatEntitas;
        });
    }

    public function destroy(RapatEntitas $rapatEntitas): void
    {
        DB::transaction(function () use ($rapatEntitas) {
            $rapatEntitas->delete();
        });
    }
}
