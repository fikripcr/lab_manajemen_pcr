<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\KategoriPerusahaanRequest;
use App\Services\Eoffice\KategoriPerusahaanService;
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
                $editUrl   = route('eoffice.kategori-perusahaan.edit', $row->hashid);
                $deleteUrl = route('eoffice.kategori-perusahaan.destroy', $row->hashid);

                return '
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-icon btn-ghost-primary ajax-modal-btn" data-url="' . $editUrl . '" data-modal-title="Edit Kategori" title="Edit">
                            <i class="ti ti-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-ghost-danger ajax-delete" data-url="' . $deleteUrl . '" data-title="Hapus?" data-text="Kategori ini akan dihapus permanen.">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
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
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit(\App\Models\Eoffice\KategoriPerusahaan $kategori_perusahaan)
    {
        $kategori = $kategori_perusahaan;
        return view('pages.eoffice.kategori_perusahaan.edit', compact('kategori'));
    }

    public function update(KategoriPerusahaanRequest $request, \App\Models\Eoffice\KategoriPerusahaan $kategori_perusahaan)
    {
        try {
            $this->KategoriPerusahaanService->updateKategori($kategori_perusahaan->kategoriperusahaan_id, $request->validated());
            return jsonSuccess('Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(\App\Models\Eoffice\KategoriPerusahaan $kategori_perusahaan)
    {
        try {
            $this->KategoriPerusahaanService->deleteKategori($kategori_perusahaan->kategoriperusahaan_id);
            return jsonSuccess('Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
