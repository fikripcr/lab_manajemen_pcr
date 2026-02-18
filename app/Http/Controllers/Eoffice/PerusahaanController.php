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
                return view('pages.eoffice.perusahaan._action', compact('row'))->render();
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
        } catch (Exception $e) {
            return jsonError($e->getMessage());
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
        return view('pages.eoffice.perusahaan.edit', compact('perusahaan', 'categories'));
    }

    public function update(PerusahaanRequest $request, Perusahaan $perusahaan)
    {
        try {
            $this->PerusahaanService->updatePerusahaan($perusahaan->perusahaan_id, $request->validated());
            return jsonSuccess('Perusahaan berhasil diperbarui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(Perusahaan $perusahaan)
    {
        try {
            $this->PerusahaanService->deletePerusahaan($perusahaan->perusahaan_id);
            return jsonSuccess('Perusahaan berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
