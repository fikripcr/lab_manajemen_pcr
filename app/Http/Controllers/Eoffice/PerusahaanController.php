<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\PerusahaanRequest;
use App\Models\Eoffice\KategoriPerusahaan;
use App\Services\Eoffice\PerusahaanService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PerusahaanController extends Controller
{
    protected $service;

    public function __construct(PerusahaanService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle  = 'Daftar Perusahaan';
        $categories = KategoriPerusahaan::all();
        return view('pages.eoffice.perusahaan.index', compact('pageTitle', 'categories'));
    }

    public function paginate(Request $request)
    {
        $query = $this->service->getPaginateData($request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kategori', function ($row) {
                return $row->kategori->nama_kategori ?? '-';
            })
            ->addColumn('action', function ($row) {
                $editUrl   = route('eoffice.perusahaan.edit', $row->perusahaan_id);
                $deleteUrl = route('eoffice.perusahaan.destroy', $row->perusahaan_id);
                $showUrl   = route('eoffice.perusahaan.show', $row->perusahaan_id);

                return '
                    <div class="btn-group btn-group-sm">
                        <a href="' . $showUrl . '" class="btn btn-icon btn-ghost-info" title="Detail">
                            <i class="ti ti-eye"></i>
                        </a>
                        <button type="button" class="btn btn-icon btn-ghost-primary ajax-modal-btn" data-url="' . $editUrl . '" data-modal-title="Edit Perusahaan" title="Edit">
                            <i class="ti ti-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-ghost-danger ajax-delete" data-url="' . $deleteUrl . '" data-title="Hapus?" data-text="Perusahaan ini akan dihapus permanen.">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $categories = KategoriPerusahaan::all();
        return view('pages.eoffice.perusahaan.create', compact('categories'));
    }

    public function store(PerusahaanRequest $request)
    {
        try {
            $this->service->createPerusahaan($request->validated());
            return jsonSuccess('Perusahaan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function show($id)
    {
        $perusahaan = $this->service->getById($id);
        $pageTitle  = 'Detail Perusahaan: ' . $perusahaan->nama_perusahaan;
        return view('pages.eoffice.perusahaan.show', compact('perusahaan', 'pageTitle'));
    }

    public function edit($id)
    {
        $perusahaan = $this->service->getById($id);
        $categories = KategoriPerusahaan::all();
        return view('pages.eoffice.perusahaan.edit', compact('perusahaan', 'categories'));
    }

    public function update(PerusahaanRequest $request, $id)
    {
        try {
            $this->service->updatePerusahaan($id, $request->validated());
            return jsonSuccess('Perusahaan berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->deletePerusahaan($id);
            return jsonSuccess('Perusahaan berhasil dihapus.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
