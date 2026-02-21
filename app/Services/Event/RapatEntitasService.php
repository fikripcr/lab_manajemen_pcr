<?php
namespace App\Services\Event;

use App\Models\Event\RapatEntitas;
use Illuminate\Support\Facades\DB;

class RapatEntitasService
{
    public function getFilteredQuery(Rapat $rapat, array $filters = [])
    {
        $query = RapatEntitas::where('rapat_id', $rapat->rapat_id);

        if (filled($filters['search']['value'] ?? null)) {
            $search = $filters['search']['value'];
            $query->where(function ($q) use ($search) {
                $q->where('model', 'like', "%{$search}%")
                  ->orWhere('model_id', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        return $query;
    }

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
