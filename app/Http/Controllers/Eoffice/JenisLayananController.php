<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\JenisLayananIsianStoreRequest;
use App\Http\Requests\Eoffice\JenisLayananPicStoreRequest;
use App\Http\Requests\Eoffice\JenisLayananRequest;
use App\Models\Eoffice\KategoriIsian;
use App\Models\User;
use App\Services\Eoffice\JenisLayananService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JenisLayananController extends Controller
{
    protected $service;

    public function __construct(JenisLayananService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'Jenis Layanan E-Office';
        return view('pages.eoffice.jenis_layanan.index', compact('pageTitle'));
    }

    public function paginate(Request $request)
    {
        $query = $this->service->getFilteredQuery($request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-green text-green-fg">Aktif</span>'
                    : '<span class="badge bg-red text-red-fg">Non-Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                $editUrl   = route('eoffice.jenis-layanan.edit', $row->jenislayanan_id);
                $deleteUrl = route('eoffice.jenis-layanan.destroy', $row->jenislayanan_id);
                $showUrl   = route('eoffice.jenis-layanan.show', $row->jenislayanan_id);

                return '
                    <div class="btn-group btn-group-sm">
                        <a href="' . $showUrl . '" class="btn btn-icon btn-ghost-info" title="Manage Detail/PIC/Isian">
                            <i class="ti ti-settings"></i>
                        </a>
                        <button type="button" class="btn btn-icon btn-ghost-primary ajax-modal-btn" data-url="' . $editUrl . '" data-modal-title="Edit Jenis Layanan" title="Edit">
                            <i class="ti ti-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-icon btn-ghost-danger ajax-delete" data-url="' . $deleteUrl . '" data-title="Hapus?" data-text="Menghapus jenis layanan ini akan berdampak pada data terkait lainnya.">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
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
            $this->service->createJenisLayanan($request->validated());
            return jsonSuccess('Jenis layanan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function show($id)
    {
        $realId         = decryptId($id);
        $layanan        = $this->service->getById($realId);
        $pageTitle      = 'Manage Layanan: ' . $layanan->nama_layanan;
        $users          = User::orderBy('name')->get();
        $kategoriIsians = KategoriIsian::orderBy('nama_isian')->get();

        return view('pages.eoffice.jenis_layanan.show', compact('layanan', 'pageTitle', 'users', 'kategoriIsians'));
    }

    public function edit($id)
    {
        $realId  = decryptId($id);
        $layanan = $this->service->getById($realId);
        return view('pages.eoffice.jenis_layanan.edit', compact('layanan'));
    }

    public function update(JenisLayananRequest $request, $id)
    {
        try {
            $this->service->updateJenisLayanan($id, $request->validated());
            return jsonSuccess('Jenis layanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Check for transactions first
            $this->service->updateJenisLayanan($id, ['is_active' => false]);
            // For now, just deactivate or hard delete if no data?
            // Usually, soft delete is safer.
            // $this->service->deleteJenisLayanan($id);
            return jsonSuccess('Jenis layanan berhasil dinonaktifkan.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    // PIC & Isian Handlers
    public function storePic(JenisLayananPicStoreRequest $request, $id)
    {
        try {
            $this->service->storePic($id, $request->all());
            return jsonSuccess('PIC berhasil ditambahkan.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroyPic($id)
    {
        try {
            $realId = decryptId($id);
            $this->service->deletePic($realId);
            return jsonSuccess('PIC berhasil dihapus.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function storeIsian(JenisLayananIsianStoreRequest $request, $id)
    {
        try {
            $this->service->storeIsian($id, $request->all());
            return jsonSuccess('Isian berhasil ditambahkan.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroyIsian($id)
    {
        try {
            $realId = decryptId($id);
            $this->service->deleteIsian($realId);
            return jsonSuccess('Isian berhasil dihapus.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function updateIsianField(Request $request, $id)
    {
        try {
            $realId = decryptId($id);
            $this->service->updateIsian($realId, $request->only(['is_required', 'is_show_on_validasi', 'fill_by']));
            return jsonSuccess('Field berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function updateIsianRule(Request $request, $id)
    {
        try {
            $realId = decryptId($id);
            $this->service->updateIsian($realId, $request->only(['rule']));
            return jsonSuccess('Rule berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function updateIsianInfo(Request $request, $id)
    {
        try {
            $realId = decryptId($id);
            $this->service->updateIsian($realId, $request->only(['info_tambahan']));
            return jsonSuccess('Info tambahan berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function updateIsianSeq(Request $request)
    {
        try {
            $this->service->updateIsianSeq($request->get('sequences'));
            return jsonSuccess('Urutan berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
