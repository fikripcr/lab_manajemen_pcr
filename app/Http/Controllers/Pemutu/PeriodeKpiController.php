<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PeriodeKpiRequest;
use App\Models\Pemutu\PeriodeKpi;
use App\Services\Pemutu\PeriodeService;
use Yajra\DataTables\DataTables;

class PeriodeKpiController extends Controller
{
    public function __construct(protected PeriodeService $periodeService)
    {}

    public function index()
    {
        $pageTitle = 'Periode KPI';
        $periodes  = $this->periodeService->getAll();
        return view('pages.pemutu.periode_kpis.index', compact('pageTitle', 'periodes'));
    }

    public function data()
    {
        $query = $this->periodeService->getBaseQuery();
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
        $this->periodeService->store($request->validated());
        return jsonSuccess('Periode KPI berhasil disimpan.', route('pemutu.periode-kpis.index'));
    }

    public function edit(PeriodeKpi $periodeKpi)
    {
        return view('pages.pemutu.periode_kpis.create-edit-ajax', compact('periodeKpi'));
    }

    public function update(PeriodeKpiRequest $request, PeriodeKpi $periodeKpi)
    {
        $this->periodeService->update($periodeKpi, $request->validated());
        return jsonSuccess('Periode KPI berhasil diupdate.', route('pemutu.periode-kpis.index'));
    }

    public function destroy(PeriodeKpi $periodeKpi)
    {
        $this->periodeService->destroy($periodeKpi);
        return jsonSuccess('Periode KPI berhasil dihapus.', route('pemutu.periode-kpis.index'));
    }

    public function activate(PeriodeKpi $periodeKpi)
    {
        $this->periodeService->activate($periodeKpi);
        return jsonSuccess('Periode KPI berhasil diaktifkan.', route('pemutu.periode-kpis.index'));
    }
}
