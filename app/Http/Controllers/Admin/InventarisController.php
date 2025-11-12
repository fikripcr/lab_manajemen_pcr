<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\InventarisRequest;
use App\Models\Inventaris;
use App\Models\Lab;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class InventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $inventories = Inventaris::with('lab')
            ->when($search, function ($query, $search) {
                return $query->where('nama_alat', 'like', "%{$search}%")
                    ->orWhere('jenis_alat', 'like', "%{$search}%")
                    ->orWhereHas('lab', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->paginate(20);

        return view('pages.admin.inventories.index', compact('inventories'));
    }
    
    /**
     * Process datatables ajax request.
     */
    public function dataTable(Request $request)
    {
        $inventaris = Inventaris::with('lab');
        
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
                return '
                    <div class="d-flex">
                        <a href="' . route('inventories.show', $item) . '" class="text-info dropdown-item me-1" title="View">
                            <i class="bx bx-show"></i>
                        </a>
                        <a href="' . route('inventories.edit', $item) . '" class="text-primary dropdown-item me-1" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <form action="' . route('inventories.destroy', $item) . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="text-danger dropdown-item" title="Delete" onclick="return confirm(\'Are you sure?\')">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
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
    public function show(Inventaris $inventory)
    {
        return view('pages.admin.inventories.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventaris $inventory)
    {
        $labs = Lab::all();
        return view('pages.admin.inventories.edit', compact('inventory', 'labs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventarisRequest $request, Inventaris $inventory)
    {
        $inventory->update($request->validated());

        return redirect()->route('inventories.index')
            ->with('success', 'Inventaris updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventaris $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventories.index')
            ->with('success', 'Inventaris deleted successfully.');
    }
}