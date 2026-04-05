<?php

namespace App\Services\Event;

use App\Models\Event\Rapat;
use App\Models\Event\RapatEntitas;
use App\Models\Hr\StrukturOrganisasi;
// Pemutu models deleted
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
        $data = $this->populateRawJson($data);

        return DB::transaction(function () use ($data) {
            return RapatEntitas::create($data);
        });
    }

    public function update(RapatEntitas $rapatEntitas, array $data): RapatEntitas
    {
        $data = $this->populateRawJson($data);

        return DB::transaction(function () use ($rapatEntitas, $data) {
            $rapatEntitas->update($data);

            return $rapatEntitas;
        });
    }

    protected function populateRawJson(array $data): array
    {
        if (isset($data['model']) && isset($data['model_id'])) {
            $model = $data['model'];
            $modelId = $data['model_id'];

            $rawJson = null;

            // Pemutu models deleted - these branches are no longer functional
            // if ($model === IndikatorOrgUnit::class) { ... }
            if ($model === StrukturOrganisasi::class) {
                $item = StrukturOrganisasi::find($modelId);
                if ($item) {
                    $rawJson = [
                        'type' => 'Unit Kerja',
                        'name' => $item->name,
                        'code' => $item->code,
                    ];
                }
            }
            // else if ($model === Indikator::class) { ... }

            $data['raw_json'] = $rawJson;
        }

        return $data;
    }

    public function destroy(RapatEntitas $rapatEntitas): void
    {
        DB::transaction(function () use ($rapatEntitas) {
            $rapatEntitas->delete();
        });
    }

    /**
     * Resolve polymorphic entity menjadi text label untuk tampilan.
     *
     * @return array{id: string, text: string}
     */
    public function resolveEntityDisplay(RapatEntitas $entitas): array
    {
        $selectedEntityId = '';
        $selectedEntityText = '';

        if (! $entitas->exists) {
            return compact('selectedEntityId', 'selectedEntityText');
        }

        // Pemutu models deleted - only StrukturOrganisasi remains
        if ($entitas->model === StrukturOrganisasi::class) {
            $item = StrukturOrganisasi::find($entitas->model_id);
            if ($item) {
                $selectedEntityText = '[Unit Kerja] '.$item->name.($item->code ? " ({$item->code})" : '');
                $selectedEntityId = 'StrukturOrganisasi:'.$item->orgunit_id;
            }
        }

        return compact('selectedEntityId', 'selectedEntityText');
    }

    /**
     * Resolve polymorphic entity menjadi info singkat untuk DataTable.
     */
    public function resolveEntityInfo(RapatEntitas $row): string
    {
        $modelName = class_basename($row->model);

        // Pemutu models deleted - only StrukturOrganisasi remains
        if ($row->model === StrukturOrganisasi::class) {
            $item = StrukturOrganisasi::find($row->model_id);

            return $item ? '[Unit Kerja] '.$item->name : $modelName.' - ID: '.$row->model_id;
        }

        return $modelName.' - ID: '.$row->model_id;
    }
}
