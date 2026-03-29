<?php

namespace App\Services\Event;

use App\Models\Event\Rapat;
use App\Models\Event\RapatEntitas;
use App\Models\Hr\StrukturOrganisasi;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
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

            if ($model === IndikatorOrgUnit::class) {
                $item = IndikatorOrgUnit::with('indikator', 'unitKerja')->find($modelId);
                if ($item) {
                    $rawJson = [
                        'type' => 'Indikator Unit',
                        'no_indikator' => $item->indikator?->no_indikator,
                        'indikator' => $item->indikator?->indikator,
                        'unit_kerja' => $item->unitKerja?->name,
                    ];
                }
            } elseif ($model === StrukturOrganisasi::class) {
                $item = StrukturOrganisasi::find($modelId);
                if ($item) {
                    $rawJson = [
                        'type' => 'Unit Kerja',
                        'name' => $item->name,
                        'code' => $item->code,
                    ];
                }
            } elseif ($model === Indikator::class) {
                $item = Indikator::find($modelId);
                if ($item) {
                    $rawJson = [
                        'type' => 'Indikator Mutu',
                        'no_indikator' => $item->no_indikator,
                        'indikator' => $item->indikator,
                    ];
                }
            }

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

        if ($entitas->model === IndikatorOrgUnit::class) {
            $item = IndikatorOrgUnit::with('indikator')->find($entitas->model_id);
            if ($item && $item->indikator) {
                $selectedEntityText = '[Indikator Unit] '.$item->indikator->no_indikator.' - '.$item->indikator->indikator;
                $selectedEntityId = 'IndikatorOrgUnit:'.$item->indikorgunit_id;
            }
        } elseif ($entitas->model === StrukturOrganisasi::class) {
            $item = StrukturOrganisasi::find($entitas->model_id);
            if ($item) {
                $selectedEntityText = '[Unit Kerja] '.$item->name.($item->code ? " ({$item->code})" : '');
                $selectedEntityId = 'StrukturOrganisasi:'.$item->orgunit_id;
            }
        } elseif ($entitas->model === Indikator::class) {
            $item = Indikator::find($entitas->model_id);
            if ($item) {
                $selectedEntityText = '[Indikator Mutu] '.$item->no_indikator.' - '.$item->indikator;
                $selectedEntityId = 'Indikator:'.$item->indikator_id;
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

        if ($row->model === IndikatorOrgUnit::class) {
            $item = IndikatorOrgUnit::with('indikator')->find($row->model_id);
            if ($item && $item->indikator) {
                return '[Indikator Unit] '.$item->indikator->no_indikator.' - '.\Illuminate\Support\Str::limit($item->indikator->indikator, 30);
            }
        }

        if ($row->model === StrukturOrganisasi::class) {
            $item = StrukturOrganisasi::find($row->model_id);

            return $item ? '[Unit Kerja] '.$item->name : $modelName.' - ID: '.$row->model_id;
        }

        if ($row->model === Indikator::class) {
            $item = Indikator::find($row->model_id);

            return $item ? '[Indikator Mutu] '.$item->no_indikator.' - '.\Illuminate\Support\Str::limit($item->indikator, 30) : $modelName.' - ID: '.$row->model_id;
        }

        return $modelName.' - ID: '.$row->model_id;
    }
}
