<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\InventarisRequest;
use App\Models\Lab\Inventaris;
use App\Models\Lab\Lab;
use App\Services\Lab\InventarisService;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class InventarisController extends Controller
{
    public function __construct(protected InventarisService $inventarisService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.lab.inventaris.index');
    }

    /**
     * Process datatables ajax request.
     */
    public function paginate(Request $request)
    {
        // Use Service Query
        $inventaris = $this->inventarisService->getFilteredQuery($request->all());

        return DataTables::of($inventaris)
            ->addIndexColumn()
            ->editColumn('nama_alat', function ($item) {
                return '<span class="fw-medium">' . $item->nama_alat . '</span>';
            })
            ->editColumn('jenis_alat', function ($item) {
                return '<span class="badge bg-label-info me-1">' . $item->jenis_alat . '</span>';
            })
            ->editColumn('kondisi_terakhir', function ($item) {
                $badgeClass = '';
                switch ($item->kondisi_terakhir) {
                    case 'Baik':
                        $badgeClass = 'bg-label-success';
                        break;
                    case 'Rusak Ringan':
                        $badgeClass = 'bg-label-warning';
                        break;
                    case 'Rusak Berat':
                        $badgeClass = 'bg-label-danger';
                        break;
                    case 'Tidak Dapat Digunakan':
                        $badgeClass = 'bg-label-dark';
                        break;
                    default:
                        $badgeClass = 'bg-label-secondary';
                }
                return '<span class="badge ' . $badgeClass . '">' . $item->kondisi_terakhir . '</span>';
            })
            ->editColumn('tanggal_pengecekan', function ($item) {
                return formatTanggalIndo($item->tanggal_pengecekan);
            })
            ->addColumn('action', function ($item) {
                $encryptedId = encryptId($item->inventaris_id);
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('lab.inventaris.edit', $encryptedId),
                    'viewUrl'   => route('lab.inventaris.show', $encryptedId),
                    'deleteUrl' => route('lab.inventaris.destroy', $encryptedId),
                ])->render();
            })
            ->rawColumns(['nama_alat', 'jenis_alat', 'kondisi_terakhir', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $labs       = Lab::with('labTeams')->get();
        $inventaris = new Inventaris();
        return view('pages.lab.inventaris.create-edit-ajax', compact('labs', 'inventaris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InventarisRequest $request)
    {
        try {
            $this->inventarisService->createInventaris($request->validated());
            return jsonSuccess('Inventaris berhasil dibuat.', route('lab.inventaris.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan inventaris: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventaris $inventaris)
    {
        return view('pages.lab.inventaris.show', compact('inventaris'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventaris $inventaris)
    {
        $labs = Lab::with('labTeams')->get();
        return view('pages.lab.inventaris.create-edit-ajax', compact('inventaris', 'labs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventarisRequest $request, Inventaris $inventaris)
    {
        try {
            $this->inventarisService->updateInventaris($inventaris, $request->validated());
            return jsonSuccess('Inventaris berhasil diperbarui.', route('lab.inventaris.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui inventaris: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventaris $inventaris)
    {
        try {
            $this->inventarisService->deleteInventaris($inventaris);
            return jsonSuccess('Inventaris berhasil dihapus.', route('lab.inventaris.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus inventaris: ' . $e->getMessage());
        }
    }

    /**
     * Export inventaris to Excel
     */
    public function export(Request $request)
    {
        // Extract filters from request (matching the DataTables filters)
        $filters = [
            'search'    => $request->get('search'),
            'condition' => $request->get('condition'),
            'lab_id'    => $request->get('lab_id'),
        ];
        $columns = $request->get('columns', ['id', 'nama_alat', 'jenis_alat', 'kondisi_terakhir', 'tanggal_pengecekan', 'lab_name']);

        $export = $this->inventarisService->exportInventaris($filters, $columns);

        return Excel::download($export, 'inventory_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}
