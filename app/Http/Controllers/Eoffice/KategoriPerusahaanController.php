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
    public function __construct(protected KategoriPerusahaanService $KategoriPerusahaanService)
    {}

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
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('eoffice.kategori-perusahaan.edit', $row->encrypted_kategoriperusahaan_id),
                    'editModal' => true,
                    'deleteUrl' => route('eoffice.kategori-perusahaan.destroy', $row->encrypted_kategoriperusahaan_id),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.eoffice.kategori_perusahaan.create-edit-ajax');
    }

    public function store(KategoriPerusahaanRequest $request)
    {
        try {
            $this->kategoriPerusahaanService->createKategori($request->validated());
            return jsonSuccess('Kategori berhasil ditambahkan.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menambahkan kategori: ' . $e->getMessage());
        }
    }

    public function edit(KategoriPerusahaan $kategori_perusahaan)
    {
        $kategori = $kategori_perusahaan;
        return view('pages.eoffice.kategori_perusahaan.create-edit-ajax', compact('kategori'));
    }

    public function update(KategoriPerusahaanRequest $request, KategoriPerusahaan $kategori_perusahaan)
    {
        try {
            $this->kategoriPerusahaanService->updateKategori($kategori_perusahaan->kategoriperusahaan_id, $request->validated());
            return jsonSuccess('Kategori berhasil diperbarui.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    public function destroy(KategoriPerusahaan $kategori_perusahaan)
    {
        try {
            $this->kategoriPerusahaanService->deleteKategori($kategori_perusahaan->kategoriperusahaan_id);
            return jsonSuccess('Kategori berhasil dihapus.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
}
