<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\RapatEntitasRequest;
use App\Models\Event\Rapat;
use App\Models\Event\RapatEntitas;
use App\Services\Event\RapatEntitasService;
use App\Models\Hr\StrukturOrganisasi;
// Pemutu models deleted
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RapatEntitasController extends Controller
{
    public function __construct(
        protected RapatEntitasService $service
    ) {}

    public function index(Rapat $rapat)
    {
        $rapat->load(['entitas']);

        return view('pages.event.rapat.entitas.index', compact('rapat'));
    }

    public function data(Rapat $rapat, Request $request)
    {
        $query = $this->service->getFilteredQuery($rapat, $request->all());

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('model_info', function ($row) {
                return $this->service->resolveEntityInfo($row);
            })
            ->addColumn('keterangan', function ($row) {
                return Str::limit($row->keterangan, 50);
            })
            ->addColumn('action', function ($row) use ($rapat) {
                return view('components.tabler.datatables-actions', [
                    'editUrl' => route('Kegiatan.rapat.entitas.edit', [$rapat->encrypted_rapat_id, $row->encrypted_rapatentitas_id]),
                    'deleteUrl' => route('Kegiatan.rapat.entitas.destroy', [$rapat->encrypted_rapat_id, $row->encrypted_rapatentitas_id]),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function search(Rapat $rapat, Request $request)
    {
        $q = $request->input('q');

        $results = collect();

        // Pemutu models deleted - only StrukturOrganisasi remains
        // IndikatorOrgUnit search removed

        // Unit Kerja
        $units = StrukturOrganisasi::query()
            ->where('name', 'like', "%{$q}%")
            ->orWhere('code', 'like', "%{$q}%")
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'StrukturOrganisasi:'.$item->orgunit_id,
                    'text' => '[Unit Kerja] '.$item->name.($item->code ? " ({$item->code})" : ''),
                ];
            });
        $results = $results->concat($units);

        // Indikator search removed (Pemutu model deleted)

        return response()->json(['results' => $results->take(30)]);
    }

    public function create(Rapat $rapat)
    {
        $entitas = new RapatEntitas(['rapat_id' => $rapat->rapat_id]);

        return view('pages.event.rapat.entitas.create-edit-ajax', compact('rapat', 'entitas'));
    }

    public function edit(Rapat $rapat, RapatEntitas $entitas)
    {
        $entityDisplay = $this->service->resolveEntityDisplay($entitas);

        return view('pages.event.rapat.entitas.create-edit-ajax', array_merge(
            compact('rapat', 'entitas'),
            $entityDisplay
        ));
    }

    public function store(RapatEntitasRequest $request, Rapat $rapat)
    {
        $data = $request->validated();
        $data['rapat_id'] = $rapat->rapat_id;
        $this->service->store($data);

        return jsonSuccess('Entitas berhasil ditambahkan');
    }

    public function update(RapatEntitasRequest $request, Rapat $rapat, RapatEntitas $entitas)
    {
        $this->service->update($entitas, $request->validated());

        return jsonSuccess('Entitas berhasil diperbarui');
    }

    public function destroy(Rapat $rapat, RapatEntitas $entitas)
    {
        $this->service->destroy($entitas);

        return jsonSuccess('Entitas berhasil dihapus', route('Kegiatan.rapat.show', $rapat->encrypted_rapat_id).'#section-entitas');
    }
}
