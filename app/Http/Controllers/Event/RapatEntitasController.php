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
        try {
            $rapat->load(['entitas']);
            return view('pages.event.rapat.entitas.index', compact('rapat'));
        } catch (\Exception $e) {
            logError($e);
            return redirect()->back()->with('error', 'Gagal memuat entitas: ' . $e->getMessage());
        }
    }

    public function data(Rapat $rapat, Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            logError($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
        try {
            $data = $request->validated();
            $data['rapat_id'] = $rapat->rapat_id;
            $this->service->store($data);
            return response()->json([
                'success' => true,
                'message' => 'Entitas berhasil ditambahkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(RapatEntitasRequest $request, Rapat $rapat, RapatEntitas $entitas)
    {
        try {
            $this->service->update($entitas, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Entitas berhasil diperbarui',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Rapat $rapat, RapatEntitas $entitas)
    {
        try {
            $this->service->destroy($entitas);
            return response()->json([
                'success' => true,
                'message' => 'Entitas berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
