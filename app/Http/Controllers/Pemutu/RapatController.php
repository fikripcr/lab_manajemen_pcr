<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\RapatRequest;
use App\Models\Pemutu\Rapat;
use App\Services\Pemutu\RapatService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class RapatController extends Controller
{
    public function __construct(
        protected RapatService $service
    ) {}

    public function index()
    {
        $pageTitle = 'Rapat Tinjauan Manajemen';
        return view('pages.pemutu.rapat.index', compact('pageTitle'));
    }

    public function paginate(Request $request)
    {
        $query = Rapat::query()->with(['ketuaUser', 'notulenUser']);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tgl_rapat_formatted', function ($row) {
                return $row->tgl_rapat; // Format if needed
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="btn-group btn-group-sm">
                        <a href="' . route('pemutu.rapat.show', $row->hashid) . '" class="btn btn-icon btn-ghost-primary" title="Detail">
                            <i class="ti ti-eye"></i>
                        </a>
                        <a href="' . route('pemutu.rapat.edit', $row->hashid) . '" class="btn btn-icon btn-ghost-warning" title="Edit">
                            <i class="ti ti-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-icon btn-ghost-danger ajax-delete" data-url="' . route('pemutu.rapat.destroy', $row->hashid) . '" data-title="Hapus?">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $pageTitle = 'Tambah Rapat';
        return view('pages.pemutu.rapat.create', compact('pageTitle'));
    }

    public function store(RapatRequest $request)
    {
        try {
            $this->service->store($request->validated());
            return response()->json([
                'success'  => true,
                'message'  => 'Data berhasil disimpan',
                'redirect' => route('pemutu.rapat.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Rapat $rapat): View
    {
        $rapat->load(['entitas', 'pesertas.user', 'agendas', 'ketuaUser', 'notulenUser', 'authorUser']);
        $pageTitle = 'Detail Rapat';
        return view('pages.pemutu.rapat.show', compact('rapat', 'pageTitle'));
    }

    public function edit(Rapat $rapat)
    {
        $pageTitle = 'Edit Rapat';
        return view('pages.pemutu.rapat.edit', compact('rapat', 'pageTitle'));
    }

    public function update(RapatRequest $request, Rapat $rapat)
    {
        try {
            $this->service->update($rapat, $request->validated());
            return response()->json([
                'success'  => true,
                'message'  => 'Data berhasil diperbarui',
                'redirect' => route('pemutu.rapat.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Rapat $rapat)
    {
        try {
            $this->service->destroy($rapat);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
