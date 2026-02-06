<?php
namespace App\Http\Controllers\Admin;

use App\Exports\InventarisExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InventarisRequest;
use App\Models\Inventaris;
use App\Models\Lab;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class InventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.admin.inventaris.index');
    }

    /**
     * Process datatables ajax request.
     */
    public function paginate(Request $request)
    {
        $inventaris = Inventaris::select([
            'inventaris_id',
            'nama_alat',
            'jenis_alat',
            'kondisi_terakhir',
            'tanggal_pengecekan',
        ])
            ->whereNull('deleted_at');

        // Apply condition filter if provided
        if ($request->has('condition') && ! empty($request->condition)) {
            $inventaris = $inventaris->where('kondisi_terakhir', $request->condition);
        }

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
                    'editUrl'   => route('inventaris.edit', $encryptedId),
                    'viewUrl'   => route('inventaris.show', $encryptedId),
                    'deleteUrl' => route('inventaris.destroy', $encryptedId),
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
        $labs = Lab::all();
        return view('pages.admin.inventaris.create', compact('labs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InventarisRequest $request)
    {
        \DB::beginTransaction();
        try {
            Inventaris::create($request->validated());

            \DB::commit();

            return redirect()->route('inventaris.index')
                ->with('success', 'Inventaris berhasil dibuat.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat inventaris: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $realId = decryptId($id);

        $inventory = Inventaris::findOrFail($realId);
        return view('pages.admin.inventaris.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId    = decryptId($id);
        $inventory = Inventaris::findOrFail($realId);
        $labs      = Lab::all();
        return view('pages.admin.inventaris.edit', compact('inventory', 'labs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventarisRequest $request, $id)
    {
        $realId = decryptId($id);

        $inventory = Inventaris::findOrFail($realId);

        \DB::beginTransaction();
        try {
            $inventory->update($request->validated());

            \DB::commit();

            return redirect()->route('inventaris.index')
                ->with('success', 'Inventaris berhasil diperbarui.');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui inventaris: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $realId = decryptId($id);

        $inventory = Inventaris::findOrFail($realId);
        $inventory->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Inventaris berhasil dihapus.',
            ]);
        }

        return redirect()->route('inventaris.index')
            ->with('success', 'Inventaris berhasil dihapus.');
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

        $export = new InventarisExport($filters, $columns);

        return Excel::download($export, 'inventory_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}
