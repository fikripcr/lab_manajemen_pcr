<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PeriodeKpiRequest;
use App\Models\Pemutu\PeriodeKpi;
use App\Services\Pemutu\PeriodeService;
use Exception;
use Yajra\DataTables\DataTables;

class PeriodeKpiController extends Controller
{
    public function __construct(protected PeriodeService $periodeService)
    {}

    public function index()
    {
        $pageTitle = 'Periode KPI';
        $periodes  = PeriodeKpi::orderBy('tahun', 'desc')->get();
        return view('pages.pemutu.periode_kpis.index', compact('pageTitle', 'periodes'));
    }

    public function data()
    {
        $query = PeriodeKpi::query()->orderBy('tahun', 'desc');
        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        $periodeKpi = new PeriodeKpi();
        return view('pages.pemutu.periode_kpis.create-edit-ajax', compact('periodeKpi'));
    }

    public function store(PeriodeKpiRequest $request)
    {
        try {
            $this->periodeService->store($request->validated());
            return jsonSuccess('Periode KPI berhasil disimpan.', route('pemutu.periode-kpis.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan periode: ' . $e->getMessage());
        }
    }

    public function edit(PeriodeKpi $periodeKpi)
    {
        return view('pages.pemutu.periode_kpis.create-edit-ajax', compact('periodeKpi'));
    }

    public function update(PeriodeKpiRequest $request, PeriodeKpi $periodeKpi)
    {
        try {
            $this->periodeService->update($periodeKpi, $request->validated());
            return jsonSuccess('Periode KPI berhasil diupdate.', route('pemutu.periode-kpis.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui periode: ' . $e->getMessage());
        }
    }

    public function destroy(PeriodeKpi $periodeKpi)
    {
        try {
            $this->periodeService->destroy($periodeKpi);
            return jsonSuccess('Periode KPI berhasil dihapus.', route('pemutu.periode-kpis.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus periode: ' . $e->getMessage());
        }
    }

    public function activate(PeriodeKpi $periodeKpi)
    {
        try {
            $this->periodeService->activate($periodeKpi);
            return jsonSuccess('Periode KPI berhasil diaktifkan.', route('pemutu.periode-kpis.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal mengaktifkan periode: ' . $e->getMessage());
        }
    }
}
