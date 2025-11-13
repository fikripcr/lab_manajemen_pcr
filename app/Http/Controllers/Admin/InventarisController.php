<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\InventarisRequest;
use App\Models\Inventaris;
use App\Models\Lab;
use App\Exports\InventarisExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class InventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.admin.inventories.index');
    }

    /**
     * Process datatables ajax request.
     */
    public function data(Request $request)
    {
        $inventaris = Inventaris::with('lab');

        // Apply filters if provided
        // if ($request->has('search') && !empty($request->search)) {
        //     $searchTerm = $request->search;
        //     $inventaris = $inventaris->where(function ($query) use ($searchTerm) {
        //         $query->where('nama_alat', 'like', "%{$searchTerm}%")
        //               ->orWhere('jenis_alat', 'like', "%{$searchTerm}%")
        //               ->orWhereHas('lab', function ($q) use ($searchTerm) {
        //                   $q->where('name', 'like', "%{$searchTerm}%");
        //               });
        //     });
        // }

        // Apply condition filter if provided
        if ($request->has('condition') && !empty($request->condition)) {
            $inventaris = $inventaris->where('kondisi_terakhir', $request->condition);
        }

        return DataTables::of($inventaris)
            ->addIndexColumn()
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
                return $item->tanggal_pengecekan ? $item->tanggal_pengecekan->format('d M Y') : '-';
            })
            ->addColumn('action', function ($item) {
                $encryptedId = encryptId($item->id);
                return '
                    <div class="d-flex">
                        <a href="' . route('inventories.show', $encryptedId) . '" class="btn btn-info btn-sm me-1" title="View">
                            <i class="bx bx-show"></i>
                        </a>
                        <a href="' . route('inventories.edit', $encryptedId) . '" class="btn btn-primary btn-sm me-1" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="confirmDelete(\'' . route('inventories.destroy', $encryptedId) . '\')">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['kondisi_terakhir', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $labs = Lab::all();
        return view('pages.admin.inventories.create', compact('labs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InventarisRequest $request)
    {
        Inventaris::create($request->validated());

        return redirect()->route('inventories.index')
            ->with('success', 'Inventaris created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $realId = decryptId($id);
        if (!$realId) {
            abort(404);
        }

        $inventory = Inventaris::findOrFail($realId);
        return view('pages.admin.inventories.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId = decryptId($id);
        if (!$realId) {
            abort(404);
        }

        $inventory = Inventaris::findOrFail($realId);
        $labs = Lab::all();
        return view('pages.admin.inventories.edit', compact('inventory', 'labs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventarisRequest $request, $id)
    {
        $realId = decryptId($id);
        if (!$realId) {
            abort(404);
        }

        $inventory = Inventaris::findOrFail($realId);
        $inventory->update($request->validated());

        return redirect()->route('inventories.index')
            ->with('success', 'Inventaris updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $realId = decryptId($id);
        if (!$realId) {
            abort(404);
        }

        $inventory = Inventaris::findOrFail($realId);
        $inventory->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Inventaris deleted successfully.'
            ]);
        }

        return redirect()->route('inventories.index')
            ->with('success', 'Inventaris deleted successfully.');
    }

    /**
     * Export inventories to Excel
     */
    public function export(Request $request)
    {
        // Extract filters from request (matching the DataTables filters)
        $filters = [
            'search' => $request->get('search'),
            'condition' => $request->get('condition'),
            'lab_id' => $request->get('lab_id'),
        ];

        $columns = $request->get('columns', ['id', 'nama_alat', 'jenis_alat', 'kondisi_terakhir', 'tanggal_pengecekan', 'lab_name']);

        $export = new InventarisExport($filters, $columns);

        return Excel::download($export, 'inventory_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}
