<?php
namespace App\Services\Event;

use App\Models\Event\Rapat;
use App\Models\Event\RapatEntitas;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Hr\StrukturOrganisasi;
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
            $model   = $data['model'];
            $modelId = $data['model_id'];

            $rawJson = null;

            if ($model === IndikatorOrgUnit::class) {
                $item = IndikatorOrgUnit::with('indikator', 'unitKerja')->find($modelId);
                if ($item) {
                    $rawJson = [
                        'type'         => 'Indikator Unit',
                        'no_indikator' => $item->indikator?->no_indikator,
                        'indikator'    => $item->indikator?->indikator,
                        'unit_kerja'   => $item->unitKerja?->name,
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
                        'type'         => 'Indikator Mutu',
                        'no_indikator' => $item->no_indikator,
                        'indikator'    => $item->indikator,
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
}
