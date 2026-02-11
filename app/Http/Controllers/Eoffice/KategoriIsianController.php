<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\KategoriIsianRequest;
use App\Services\Eoffice\KategoriIsianService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriIsianController extends Controller
{
    protected $service;

    public function __construct(KategoriIsianService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'Master Isian Layanan';
        return view('pages.eoffice.kategori_isian.index', compact('pageTitle'));
    }

    public function paginate(Request $request)
    {
        $query = $this->service->getPaginateData($request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('type_label', function ($row) {
                return ucwords(str_replace('_', ' ', $row->type));
            })
            ->addColumn('action', function ($row) {
                $editUrl   = route('eoffice.kategori-isian.edit', $row->kategoriisian_id);
                $deleteUrl = route('eoffice.kategori-isian.destroy', $row->kategoriisian_id);

                return '
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-icon btn-ghost-primary ajax-modal-btn" data-url="' . $editUrl . '" data-modal-title="Edit Isian" title="Edit">
                            <i class="ti ti-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-ghost-danger ajax-delete" data-url="' . $deleteUrl . '" data-title="Hapus?" data-text="Isian ini akan dihapus permanen.">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
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
            $this->service->createKategori($request->validated());
            return jsonSuccess('Isian berhasil ditambahkan.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit($id)
    {
        $kategori = $this->service->getById($id);
        return view('pages.eoffice.kategori_isian.edit', compact('kategori'));
    }

    public function update(KategoriIsianRequest $request, $id)
    {
        try {
            $this->service->updateKategori($id, $request->validated());
            return jsonSuccess('Isian berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->deleteKategori($id);
            return jsonSuccess('Isian berhasil dihapus.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
