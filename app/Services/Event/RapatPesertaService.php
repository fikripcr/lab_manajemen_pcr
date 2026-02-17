<?php
namespace App\Services\Event;

use App\Models\Event\RapatPeserta;
use Illuminate\Support\Facades\DB;

class RapatPesertaService
{
    public function store(array $data): RapatPeserta
    {
        return DB::transaction(function () use ($data) {
            return RapatPeserta::create($data);
        });
    }

    public function update(RapatPeserta $rapatPeserta, array $data): RapatPeserta
    {
        return DB::transaction(function () use ($rapatPeserta, $data) {
            $rapatPeserta->update($data);
            return $rapatPeserta;
        });
    }

    public function destroy(RapatPeserta $rapatPeserta): void
    {
        DB::transaction(function () use ($rapatPeserta) {
            $rapatPeserta->delete();
        });
    }
}
