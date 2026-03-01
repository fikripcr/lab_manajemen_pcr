<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\JenisLayananIsianStoreRequest;
use App\Http\Requests\Eoffice\JenisLayananPicStoreRequest;
use App\Http\Requests\Eoffice\JenisLayananRequest;
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
    public function __construct(protected JenisLayananService $jenisLayananService)
    {}

    public function index()
    {
        $pageTitle = 'Jenis Layanan E-Office';
        return view('pages.eoffice.jenis_layanan.index', compact('pageTitle'));
    }

    public function data(Request $request)
    {
        $query = $this->jenisLayananService->getFilteredQuery($request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-green text-green-fg">Aktif</span>'
                    : '<span class="badge bg-red text-red-fg">Non-Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('eoffice.jenis-layanan.edit', $row->encrypted_jenislayanan_id),
                    'editModal' => true,
                    'viewUrl'   => route('eoffice.jenis-layanan.show', $row->encrypted_jenislayanan_id),
                    'deleteUrl' => route('eoffice.jenis-layanan.destroy', $row->encrypted_jenislayanan_id),
                ])->render();
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.eoffice.jenis_layanan.create-edit-ajax');
    }

    public function store(JenisLayananRequest $request)
    {
        $this->jenisLayananService->createJenisLayanan($request->validated());
        return jsonSuccess('Jenis layanan berhasil ditambahkan.');
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
        return view('pages.eoffice.jenis_layanan.create-edit-ajax', compact('layanan'));
    }

    public function update(JenisLayananRequest $request, JenisLayanan $jenisLayanan)
    {
        $this->jenisLayananService->updateJenisLayanan($jenisLayanan->jenislayanan_id, $request->validated());
        return jsonSuccess('Jenis layanan berhasil diperbarui.');
    }

    public function destroy(JenisLayanan $jenisLayanan)
    {
        $this->jenisLayananService->updateJenisLayanan($jenisLayanan->jenislayanan_id, ['is_active' => false]);
        return jsonSuccess('Jenis layanan berhasil dinonaktifkan.');
    }

    // --- AJAX Form View Handlers ---

    public function createPic(JenisLayanan $jenisLayanan)
    {
        $layanan = $jenisLayanan;
        $users   = User::orderBy('name')->get();
        return view('pages.eoffice.jenis_layanan.ajax.form-pic', compact('layanan', 'users'));
    }

    public function createIsian(JenisLayanan $jenisLayanan)
    {
        $layanan        = $jenisLayanan;
        $kategoriIsians = KategoriIsian::orderBy('nama_isian')->get();
        return view('pages.eoffice.jenis_layanan.ajax.form-isian', compact('layanan', 'kategoriIsians'));
    }

    public function editIsianRule(JenisLayananIsian $isian)
    {
        return view('pages.eoffice.jenis_layanan.ajax.form-isian-rule', compact('isian'));
    }

    public function editIsianInfo(JenisLayananIsian $isian)
    {
        return view('pages.eoffice.jenis_layanan.ajax.form-isian-info', compact('isian'));
    }

    // PIC & Isian Handlers
    public function storePic(JenisLayananPicStoreRequest $request, JenisLayanan $jenisLayanan)
    {
        $this->jenisLayananService->storePic($jenisLayanan->jenislayanan_id, $request->all());
        return jsonSuccess('PIC berhasil ditambahkan.');
    }

    public function destroyPic(JenisLayananPic $pic)
    {
        $this->jenisLayananService->deletePic($pic->jlpic_id);
        return jsonSuccess('PIC berhasil dihapus.');
    }

    public function storeIsian(JenisLayananIsianStoreRequest $request, JenisLayanan $jenisLayanan)
    {
        $this->jenisLayananService->storeIsian($jenisLayanan->jenislayanan_id, $request->all());
        return jsonSuccess('Isian berhasil ditambahkan.');
    }

    public function destroyIsian(JenisLayananIsian $isian)
    {
        $this->jenisLayananService->deleteIsian($isian->jlisian_id);
        return jsonSuccess('Isian berhasil dihapus.');
    }

    public function updateIsianField(\App\Http\Requests\Eoffice\UpdateIsianFieldRequest $request, JenisLayananIsian $isian)
    {
        $this->jenisLayananService->updateIsian($isian->jlisian_id, $request->validated());
        return jsonSuccess('Field berhasil diperbarui.');
    }

    public function updateIsianRule(\App\Http\Requests\Eoffice\UpdateIsianRuleRequest $request, JenisLayananIsian $isian)
    {
        $this->jenisLayananService->updateIsian($isian->jlisian_id, $request->validated());
        return jsonSuccess('Rule berhasil diperbarui.');
    }

    public function updateIsianInfo(\App\Http\Requests\Eoffice\UpdateIsianInfoRequest $request, JenisLayananIsian $isian)
    {
        $this->jenisLayananService->updateIsian($isian->jlisian_id, $request->validated());
        return jsonSuccess('Info tambahan berhasil diperbarui.');
    }

    public function updateIsianSeq(\App\Http\Requests\Shared\ReorderRequest $request)
    {
        $this->jenisLayananService->updateIsianSeq($request->validated('sequences'));
        return jsonSuccess('Urutan berhasil diperbarui.');
    }
}
