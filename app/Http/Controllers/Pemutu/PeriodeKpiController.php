<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PeriodeKpiRequest;
use App\Models\Pemutu\PeriodeKpi;
use App\Services\Pemutu\PeriodeService;

class PeriodeKpiController extends Controller
{
    public function __construct(protected PeriodeService $periodeService)
    {
        $this->middleware('permission:pemutu.periode-kpi.view')->only(['index']);
        $this->middleware('permission:pemutu.periode-kpi.create')->only(['create', 'store']);
        $this->middleware('permission:pemutu.periode-kpi.update')->only(['edit', 'update']);
        $this->middleware('permission:pemutu.periode-kpi.delete')->only(['destroy']);
        $this->middleware('permission:pemutu.periode-kpi.activate')->only(['activate']);
    }

    public function index()
    {
        $pageTitle = 'Periode KPI';
        $periodes = $this->periodeService->getAll();

        return view('pages.pemutu.periode_kpi.index', compact('pageTitle', 'periodes'));
    }

    public function create()
    {
        $periodeKpi = new PeriodeKpi;

        return view('pages.pemutu.periode_kpi.create-edit-ajax', compact('periodeKpi'));
    }

    public function store(PeriodeKpiRequest $request)
    {
        $this->periodeService->store($request->validated());

        return jsonSuccess('Periode KPI berhasil disimpan.', route('pemutu.periode-kpi.index'));
    }

    public function edit(PeriodeKpi $periodeKpi)
    {
        return view('pages.pemutu.periode_kpi.create-edit-ajax', compact('periodeKpi'));
    }

    public function update(PeriodeKpiRequest $request, PeriodeKpi $periodeKpi)
    {
        $this->periodeService->update($periodeKpi, $request->validated());

        return jsonSuccess('Periode KPI berhasil diupdate.', route('pemutu.periode-kpi.index'));
    }

    public function destroy(PeriodeKpi $periodeKpi)
    {
        $this->periodeService->destroy($periodeKpi);

        return jsonSuccess('Periode KPI berhasil dihapus.', route('pemutu.periode-kpi.index'));
    }

    public function activate(PeriodeKpi $periodeKpi)
    {
        $this->periodeService->activate($periodeKpi);

        return jsonSuccess('Periode KPI berhasil diaktifkan.', route('pemutu.periode-kpi.index'));
    }
}
