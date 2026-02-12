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
    protected $PerusahaanService;

    public function __construct(PerusahaanService $PerusahaanService)
    {
        $this->PerusahaanService = $PerusahaanService;
    }

    public function index()
    {
        $pageTitle  = 'Daftar Perusahaan';
        $categories = KategoriPerusahaan::all();
        return view('pages.eoffice.perusahaan.index', compact('pageTitle', 'categories'));
    }

    public function paginate(Request $request)
    {
        $query = $this->PerusahaanService->getPaginateData($request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kategori', function ($row) {
                return $row->kategori->nama_kategori ?? '-';
            })
            ->addColumn('action', function ($row) {
                $editUrl   = route('eoffice.perusahaan.edit', $row->hashid);
                $deleteUrl = route('eoffice.perusahaan.destroy', $row->hashid);
                $showUrl   = route('eoffice.perusahaan.show', $row->hashid);

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
            $this->PerusahaanService->createPerusahaan($request->validated());
            return jsonSuccess('Perusahaan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function show(\App\Models\Eoffice\Perusahaan $perusahaan)
    {
        $pageTitle = 'Detail Perusahaan: ' . $perusahaan->nama_perusahaan;
        return view('pages.eoffice.perusahaan.show', compact('perusahaan', 'pageTitle'));
    }

    public function edit(\App\Models\Eoffice\Perusahaan $perusahaan)
    {
        $categories = KategoriPerusahaan::all();
        return view('pages.eoffice.perusahaan.edit', compact('perusahaan', 'categories'));
    }

    public function update(PerusahaanRequest $request, \App\Models\Eoffice\Perusahaan $perusahaan)
    {
        try {
            $this->PerusahaanService->updatePerusahaan($perusahaan->perusahaan_id, $request->validated());
            return jsonSuccess('Perusahaan berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(\App\Models\Eoffice\Perusahaan $perusahaan)
    {
        try {
            $this->PerusahaanService->deletePerusahaan($perusahaan->perusahaan_id);
            return jsonSuccess('Perusahaan berhasil dihapus.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
