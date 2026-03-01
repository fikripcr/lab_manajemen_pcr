<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\KategoriIsianRequest;
use App\Models\Eoffice\KategoriIsian;
use App\Services\Eoffice\KategoriIsianService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriIsianController extends Controller
{
    public function __construct(protected \App\Services\Eoffice\KategoriIsianService $KategoriIsianService)
    {}

    public function index()
    {
        $pageTitle = 'Master Isian Layanan';
        return view('pages.eoffice.kategori_isian.index', compact('pageTitle'));
    }

    public function data(Request $request)
    {
        $query = $this->KategoriIsianService->getPaginateData($request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('type_label', function ($row) {
                return ucwords(str_replace('_', ' ', $row->type));
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('eoffice.kategori-isian.edit', $row->encrypted_kategoriisian_id),
                    'editModal' => true,
                    'deleteUrl' => route('eoffice.kategori-isian.destroy', $row->encrypted_kategoriisian_id),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.eoffice.kategori_isian.create-edit-ajax');
    }

    public function store(KategoriIsianRequest $request)
    {
        $this->KategoriIsianService->createKategori($request->validated());
        return jsonSuccess('Isian berhasil ditambahkan.');
    }

    public function edit(KategoriIsian $kategoriIsian)
    {
        $kategori = $kategoriIsian;
        return view('pages.eoffice.kategori_isian.create-edit-ajax', compact('kategori'));
    }

    public function update(KategoriIsianRequest $request, KategoriIsian $kategoriIsian)
    {
        $this->KategoriIsianService->updateKategori($kategoriIsian->kategoriisian_id, $request->validated());
        return jsonSuccess('Isian berhasil diperbarui.');
    }

    public function destroy(KategoriIsian $kategoriIsian)
    {
        $this->KategoriIsianService->deleteKategori($kategoriIsian->kategoriisian_id);
        return jsonSuccess('Isian berhasil dihapus.');
    }
}
