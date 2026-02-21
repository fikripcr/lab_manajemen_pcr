<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\PerusahaanRequest;
use App\Models\Eoffice\KategoriPerusahaan;
use App\Models\Eoffice\Perusahaan;
use App\Services\Eoffice\PerusahaanService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PerusahaanController extends Controller
{
    public function __construct(protected PerusahaanService $PerusahaanService)
    {}

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
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('eoffice.perusahaan.edit', $row->encrypted_perusahaan_id),
                    'editModal' => true,
                    'viewUrl'   => route('eoffice.perusahaan.show', $row->encrypted_perusahaan_id),
                    'deleteUrl' => route('eoffice.perusahaan.destroy', $row->encrypted_perusahaan_id),
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $categories = KategoriPerusahaan::all();
        return view('pages.eoffice.perusahaan.create-edit-ajax', compact('categories'));
    }

    public function store(PerusahaanRequest $request)
    {
        try {
            $this->PerusahaanService->createPerusahaan($request->validated());
            return jsonSuccess('Perusahaan berhasil ditambahkan.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menambahkan perusahaan: ' . $e->getMessage());
        }
    }

    public function show(Perusahaan $perusahaan)
    {
        $pageTitle = 'Detail Perusahaan: ' . $perusahaan->nama_perusahaan;
        return view('pages.eoffice.perusahaan.show', compact('perusahaan', 'pageTitle'));
    }

    public function edit(Perusahaan $perusahaan)
    {
        $categories = KategoriPerusahaan::all();
        return view('pages.eoffice.perusahaan.create-edit-ajax', compact('perusahaan', 'categories'));
    }

    public function update(PerusahaanRequest $request, Perusahaan $perusahaan)
    {
        try {
            $this->PerusahaanService->updatePerusahaan($perusahaan->perusahaan_id, $request->validated());
            return jsonSuccess('Perusahaan berhasil diperbarui.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui perusahaan: ' . $e->getMessage());
        }
    }

    public function destroy(Perusahaan $perusahaan)
    {
        try {
            $this->PerusahaanService->deletePerusahaan($perusahaan->perusahaan_id);
            return jsonSuccess('Perusahaan berhasil dihapus.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus perusahaan: ' . $e->getMessage());
        }
    }
}
