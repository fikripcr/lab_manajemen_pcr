<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\MataKuliahRequest;
use App\Models\Lab\MataKuliah;
use App\Services\Lab\MataKuliahService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MataKuliahController extends Controller
{
    public function __construct(protected MataKuliahService $mataKuliahService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.lab.mata-kuliah.index');
    }

    /**
     * Process datatables ajax request.
     */
    public function paginate(Request $request)
    {
        $mataKuliahs = $this->mataKuliahService->getFilteredQuery($request->all());

        return DataTables::of($mataKuliahs)
            ->addIndexColumn()
            ->filter(function ($query) use ($request) {
                // Global search functionality
                if ($request->has('search') && $request->search['value'] != '') {
                    $searchValue = $request->search['value'];
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('kode_mk', 'like', '%' . $searchValue . '%')
                            ->orWhere('nama_mk', 'like', '%' . $searchValue . '%');
                    });
                }
            })
            ->editColumn('nama_mk', function ($mk) {
                return '<span class="fw-medium">' . e($mk->nama_mk) . '</span>';
            })
            ->addColumn('action', function ($mk) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('lab.mata-kuliah.edit', $mk->encrypted_mata_kuliah_id),
                    'editModal' => true,
                    'viewUrl'   => route('lab.mata-kuliah.show', $mk->encrypted_mata_kuliah_id),
                    'deleteUrl' => route('lab.mata-kuliah.destroy', $mk->encrypted_mata_kuliah_id),
                ])->render();
            })
            ->rawColumns(['nama_mk', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mataKuliah = new MataKuliah();
        return view('pages.lab.mata-kuliah.create-edit-ajax', compact('mataKuliah'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MataKuliahRequest $request)
    {
        $this->mataKuliahService->createMataKuliah($request->validated());
        return jsonSuccess('Mata Kuliah berhasil dibuat.', route('lab.mata-kuliah.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(MataKuliah $mataKuliah)
    {
        return view('pages.lab.mata-kuliah.show', compact('mataKuliah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MataKuliah $mataKuliah)
    {
        return view('pages.lab.mata-kuliah.create-edit-ajax', compact('mataKuliah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MataKuliahRequest $request, MataKuliah $mataKuliah)
    {
        $this->mataKuliahService->updateMataKuliah($mataKuliah, $request->validated());
        return jsonSuccess('Mata Kuliah berhasil diperbarui.', route('lab.mata-kuliah.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MataKuliah $mataKuliah)
    {
        $this->mataKuliahService->deleteMataKuliah($mataKuliah);
        return jsonSuccess('Mata Kuliah berhasil dihapus.', route('lab.mata-kuliah.index'));
    }

}
