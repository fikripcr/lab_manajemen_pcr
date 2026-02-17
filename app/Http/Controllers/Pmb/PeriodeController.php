<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\StorePeriodeRequest;
use App\Models\Pmb\Periode;
use App\Services\Pmb\PeriodeService;
use Exception;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    protected $PeriodeService;

    public function __construct(PeriodeService $PeriodeService)
    {
        $this->PeriodeService = $PeriodeService;
    }

    public function index()
    {
        return view('pages.pmb.periode.index');
    }

    public function paginate(Request $request)
    {
        return datatables()->of($this->PeriodeService->getPaginateData($request))
            ->addIndexColumn()
            ->editColumn('tanggal_mulai', fn($p) => formatTanggalIndo($p->tanggal_mulai))
            ->editColumn('tanggal_selesai', fn($p) => formatTanggalIndo($p->tanggal_selesai))
            ->editColumn('is_aktif', function ($p) {
                return $p->is_aktif
                    ? '<span class="badge bg-success text-white">Aktif</span>'
                    : '<span class="badge bg-danger text-white">Non-Aktif</span>';
            })
            ->addColumn('action', function ($p) {
                return view('pages.pmb.periode._actions', compact('p'));
            })
            ->rawColumns(['is_aktif', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('pages.pmb.periode.create');
    }

    public function store(StorePeriodeRequest $request)
    {
        try {
            $this->PeriodeService->createPeriode($request->validated());
            return jsonSuccess('Periode berhasil ditambahkan.', route('pmb.periode.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit(Periode $periode)
    {
        return view('pages.pmb.periode.edit', compact('periode'));
    }

    public function update(StorePeriodeRequest $request, Periode $periode)
    {
        try {
            $this->PeriodeService->updatePeriode($periode->id, $request->validated());
            return jsonSuccess('Periode berhasil diperbarui.', route('pmb.periode.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(Periode $periode)
    {
        try {
            $this->PeriodeService->deletePeriode($periode->id);
            return jsonSuccess('Periode berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
