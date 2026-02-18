<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\KategoriIsianRequest;
use App\Models\Eoffice\KategoriIsian;
use App\Services\Eoffice\KategoriIsianService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriIsianController extends Controller
{
    protected $KategoriIsianService;

    public function __construct(KategoriIsianService $KategoriIsianService)
    {
        $this->KategoriIsianService = $KategoriIsianService;
    }

    public function index()
    {
        $pageTitle = 'Master Isian Layanan';
        return view('pages.eoffice.kategori_isian.index', compact('pageTitle'));
    }

    public function paginate(Request $request)
    {
        $query = $this->KategoriIsianService->getPaginateData($request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('type_label', function ($row) {
                return ucwords(str_replace('_', ' ', $row->type));
            })
            ->addColumn('action', function ($row) {
                return view('pages.eoffice.kategori_isian._action', compact('row'))->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.eoffice.kategori_isian.create');
    }

    public function store(KategoriIsianRequest $request)
    {
        try {
            $this->KategoriIsianService->createKategori($request->validated());
            return jsonSuccess('Isian berhasil ditambahkan.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit(KategoriIsian $kategori_isian)
    {
        $kategori = $kategori_isian;
        return view('pages.eoffice.kategori_isian.edit', compact('kategori'));
    }

    public function update(KategoriIsianRequest $request, KategoriIsian $kategori_isian)
    {
        try {
            $this->KategoriIsianService->updateKategori($kategori_isian->kategoriisian_id, $request->validated());
            return jsonSuccess('Isian berhasil diperbarui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(KategoriIsian $kategori_isian)
    {
        try {
            $this->KategoriIsianService->deleteKategori($kategori_isian->kategoriisian_id);
            return jsonSuccess('Isian berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
