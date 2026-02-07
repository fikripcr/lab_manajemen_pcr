<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MataKuliahRequest;
use App\Services\Admin\MataKuliahService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MataKuliahController extends Controller
{
    protected $mataKuliahService;

    public function __construct(MataKuliahService $mataKuliahService)
    {
        $this->mataKuliahService = $mataKuliahService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.admin.mata-kuliah.index');
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
                    'editUrl'   => route('mata-kuliah.edit', $mk->encrypted_mata_kuliah_id),
                    'viewUrl'   => route('mata-kuliah.show', $mk->encrypted_mata_kuliah_id),
                    'deleteUrl' => route('mata-kuliah.destroy', $mk->encrypted_mata_kuliah_id),
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
        return view('pages.admin.mata-kuliah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MataKuliahRequest $request)
    {
        try {
            $this->mataKuliahService->createMataKuliah($request->validated());

            return jsonSuccess('Mata Kuliah berhasil dibuat.', route('mata-kuliah.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $realId     = decryptId($id);
        $mataKuliah = $this->mataKuliahService->getMataKuliahById($realId);

        if (! $mataKuliah) {
            abort(404);
        }

        return view('pages.admin.mata-kuliah.show', compact('mataKuliah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId     = decryptId($id);
        $mataKuliah = $this->mataKuliahService->getMataKuliahById($realId);

        if (! $mataKuliah) {
            abort(404);
        }

        return view('pages.admin.mata-kuliah.edit', compact('mataKuliah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MataKuliahRequest $request, $id)
    {
        $realId = decryptId($id);

        try {
            $this->mataKuliahService->updateMataKuliah($realId, $request->validated());

            return jsonSuccess('Mata Kuliah berhasil diperbarui.', route('mata-kuliah.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $realId = decryptId($id);
            $this->mataKuliahService->deleteMataKuliah($realId);

            return jsonSuccess('Mata Kuliah berhasil dihapus.', route('mata-kuliah.index'));

        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

}
