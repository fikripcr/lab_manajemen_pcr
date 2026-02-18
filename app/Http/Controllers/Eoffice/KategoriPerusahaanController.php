<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\KategoriPerusahaanRequest;
use App\Models\Eoffice\KategoriPerusahaan;
use App\Services\Eoffice\KategoriPerusahaanService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriPerusahaanController extends Controller
{
    protected $KategoriPerusahaanService;

    public function __construct(KategoriPerusahaanService $KategoriPerusahaanService)
    {
        $this->KategoriPerusahaanService = $KategoriPerusahaanService;
    }

    public function index()
    {
        $pageTitle = 'Kategori Perusahaan';
        return view('pages.eoffice.kategori_perusahaan.index', compact('pageTitle'));
    }

    public function paginate(Request $request)
    {
        $query = $this->KategoriPerusahaanService->getPaginateData($request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('pages.eoffice.kategori_perusahaan._action', compact('row'))->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.eoffice.kategori_perusahaan.create');
    }

    public function store(KategoriPerusahaanRequest $request)
    {
        try {
            $this->KategoriPerusahaanService->createKategori($request->validated());
            return jsonSuccess('Kategori berhasil ditambahkan.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit(KategoriPerusahaan $kategori_perusahaan)
    {
        $kategori = $kategori_perusahaan;
        return view('pages.eoffice.kategori_perusahaan.edit', compact('kategori'));
    }

    public function update(KategoriPerusahaanRequest $request, KategoriPerusahaan $kategori_perusahaan)
    {
        try {
            $this->KategoriPerusahaanService->updateKategori($kategori_perusahaan->kategoriperusahaan_id, $request->validated());
            return jsonSuccess('Kategori berhasil diperbarui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(KategoriPerusahaan $kategori_perusahaan)
    {
        try {
            $this->KategoriPerusahaanService->deleteKategori($kategori_perusahaan->kategoriperusahaan_id);
            return jsonSuccess('Kategori berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
