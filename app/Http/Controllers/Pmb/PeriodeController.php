<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\StorePeriodeRequest;
use App\Models\Pmb\Periode;
use App\Services\Pmb\PeriodeService;
use Exception;

class PeriodeController extends Controller
{
    public function __construct(protected PeriodeService $periodeService)
    {}

    public function index()
    {
        return view('pages.pmb.periode.index');
    }

    public function paginate(\Illuminate\Http\Request $request)
    {
        return datatables()->of($this->periodeService->getPaginateData($request->all()))
            ->addIndexColumn()
            ->editColumn('tanggal_mulai', fn($p) => formatTanggalIndo($p->tanggal_mulai))
            ->editColumn('tanggal_selesai', fn($p) => formatTanggalIndo($p->tanggal_selesai))
            ->editColumn('is_aktif', function ($p) {
                return $p->is_aktif
                    ? '<span class="badge bg-success text-white">Aktif</span>'
                    : '<span class="badge bg-danger text-white">Non-Aktif</span>';
            })
            ->addColumn('action', function ($p) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('pmb.periode.edit', $p->encrypted_periode_id),
                    'editModal' => true,
                    'deleteUrl' => route('pmb.periode.destroy', $p->encrypted_periode_id),
                ])->render();
            })
            ->rawColumns(['is_aktif', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.pmb.periode.create-edit-ajax', ['periode' => new Periode()]);
    }

    public function store(StorePeriodeRequest $request)
    {
        try {
            $this->periodeService->createPeriode($request->validated());
            return jsonSuccess('Periode berhasil ditambahkan.', route('pmb.periode.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menambahkan periode: ' . $e->getMessage());
        }
    }

    public function edit(Periode $periode)
    {
        return view('pages.pmb.periode.create-edit-ajax', compact('periode'));
    }

    public function update(StorePeriodeRequest $request, Periode $periode)
    {
        try {
            $this->periodeService->updatePeriode($periode, $request->validated());
            return jsonSuccess('Periode berhasil diperbarui.', route('pmb.periode.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui periode: ' . $e->getMessage());
        }
    }

    public function destroy(Periode $periode)
    {
        try {
            $this->periodeService->deletePeriode($periode);
            return jsonSuccess('Periode berhasil dihapus.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus periode: ' . $e->getMessage());
        }
    }
}
