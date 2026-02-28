<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\RapatEntitasRequest;
use App\Models\Event\Rapat;
use App\Models\Event\RapatEntitas;
use App\Services\Event\RapatEntitasService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
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
                return class_basename($row->model) . ' - ID: ' . $row->model_id;
            })
            ->addColumn('keterangan', function ($row) {
                return Str::limit($row->keterangan, 50);
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('Kegiatan.rapat.entitas.edit', $row->encrypted_rapatentitas_id),
                    'deleteUrl' => route('Kegiatan.rapat.entitas.destroy', $row->encrypted_rapatentitas_id),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(Rapat $rapat)
    {
        $entitas = new RapatEntitas(['rapat_id' => $rapat->rapat_id]);
        return view('pages.event.rapat.entitas.create-edit-ajax', compact('rapat', 'entitas'));
    }

    public function edit(Rapat $rapat, RapatEntitas $entitas)
    {
        return view('pages.event.rapat.entitas.create-edit-ajax', compact('rapat', 'entitas'));
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
        return jsonSuccess('Entitas berhasil dihapus');
    }
}
