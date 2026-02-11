<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\KategoriPerusahaanRequest;
use App\Services\Eoffice\KategoriPerusahaanService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriPerusahaanController extends Controller
{
    protected $service;

    public function __construct(KategoriPerusahaanService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'Kategori Perusahaan';
        return view('pages.eoffice.kategori_perusahaan.index', compact('pageTitle'));
    }

    public function paginate(Request $request)
    {
        $query = $this->service->getPaginateData($request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editUrl   = route('eoffice.kategori-perusahaan.edit', $row->kategoriperusahaan_id);
                $deleteUrl = route('eoffice.kategori-perusahaan.destroy', $row->kategoriperusahaan_id);

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
            $this->service->createKategori($request->validated());
            return jsonSuccess('Kategori berhasil ditambahkan.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit($id)
    {
        $kategori = $this->service->getById($id);
        return view('pages.eoffice.kategori_perusahaan.edit', compact('kategori'));
    }

    public function update(KategoriPerusahaanRequest $request, $id)
    {
        try {
            $this->service->updateKategori($id, $request->validated());
            return jsonSuccess('Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->deleteKategori($id);
            return jsonSuccess('Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
