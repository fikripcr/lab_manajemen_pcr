<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\JenisLayananIsianStoreRequest;
use App\Http\Requests\Eoffice\JenisLayananPicStoreRequest;
use App\Models\Eoffice\JenisLayanan;
use App\Models\Eoffice\JenisLayananIsian;
use App\Models\Eoffice\JenisLayananPic;
use App\Models\Eoffice\KategoriIsian;
use App\Models\User;
use App\Services\Eoffice\JenisLayananService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JenisLayananController extends Controller
{
    protected $JenisLayananService;

    public function __construct(JenisLayananService $JenisLayananService)
    {
        $this->JenisLayananService = $JenisLayananService;
    }

    public function index()
    {
        $pageTitle = 'Jenis Layanan E-Office';
        return view('pages.eoffice.jenis_layanan.index', compact('pageTitle'));
    }

    public function paginate(Request $request)
    {
        $query = $this->JenisLayananService->getFilteredQuery($request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-green text-green-fg">Aktif</span>'
                    : '<span class="badge bg-red text-red-fg">Non-Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                return view('pages.eoffice.jenis_layanan._action', compact('row'))->render();
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.eoffice.jenis_layanan.create');
    }

    public function store(JenisLayananRequest $request)
    {
        try {
            $this->JenisLayananService->createJenisLayanan($request->validated());
            return jsonSuccess('Jenis layanan berhasil ditambahkan.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function show(JenisLayanan $jenisLayanan)
    {
        $layanan        = $jenisLayanan;
        $pageTitle      = 'Manage Layanan: ' . $layanan->nama_layanan;
        $users          = User::orderBy('name')->get();
        $kategoriIsians = KategoriIsian::orderBy('nama_isian')->get();

        return view('pages.eoffice.jenis_layanan.show', compact('layanan', 'pageTitle', 'users', 'kategoriIsians'));
    }

    public function edit(JenisLayanan $jenisLayanan)
    {
        $layanan = $jenisLayanan;
        return view('pages.eoffice.jenis_layanan.edit', compact('layanan'));
    }

    public function update(JenisLayananRequest $request, JenisLayanan $jenisLayanan)
    {
        try {
            $this->JenisLayananService->updateJenisLayanan($jenisLayanan->jenislayanan_id, $request->validated());
            return jsonSuccess('Jenis layanan berhasil diperbarui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(JenisLayanan $jenisLayanan)
    {
        try {
            // Check for transactions first
            $this->JenisLayananService->updateJenisLayanan($jenisLayanan->jenislayanan_id, ['is_active' => false]);
            // For now, just deactivate or hard delete if no data?
            // Usually, soft delete is safer.
            // $this->JenisLayananService->deleteJenisLayanan($id);
            return jsonSuccess('Jenis layanan berhasil dinonaktifkan.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    // PIC & Isian Handlers
    public function storePic(JenisLayananPicStoreRequest $request, JenisLayanan $jenis_layanan)
    {
        try {
            $this->JenisLayananService->storePic($jenis_layanan->jenislayanan_id, $request->all());
            return jsonSuccess('PIC berhasil ditambahkan.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroyPic(JenisLayananPic $pic)
    {
        try {
            $this->JenisLayananService->deletePic($pic->jlpic_id);
            return jsonSuccess('PIC berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function storeIsian(JenisLayananIsianStoreRequest $request, JenisLayanan $jenis_layanan)
    {
        try {
            $this->JenisLayananService->storeIsian($jenis_layanan->jenislayanan_id, $request->all());
            return jsonSuccess('Isian berhasil ditambahkan.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroyIsian(JenisLayananIsian $isian)
    {
        try {
            $this->JenisLayananService->deleteIsian($isian->jlisian_id);
            return jsonSuccess('Isian berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function updateIsianField(Request $request, JenisLayananIsian $isian)
    {
        try {
            $this->JenisLayananService->updateIsian($isian->jlisian_id, $request->only(['is_required', 'is_show_on_validasi', 'fill_by']));
            return jsonSuccess('Field berhasil diperbarui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function updateIsianRule(Request $request, JenisLayananIsian $isian)
    {
        try {
            $this->JenisLayananService->updateIsian($isian->jlisian_id, $request->only(['rule']));
            return jsonSuccess('Rule berhasil diperbarui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function updateIsianInfo(Request $request, JenisLayananIsian $isian)
    {
        try {
            $this->JenisLayananService->updateIsian($isian->jlisian_id, $request->only(['info_tambahan']));
            return jsonSuccess('Info tambahan berhasil diperbarui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function updateIsianSeq(Request $request)
    {
        try {
            $this->JenisLayananService->updateIsianSeq($request->get('sequences'));
            return jsonSuccess('Urutan berhasil diperbarui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
