<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\PeriodeKpi;
use App\Services\Pemutu\PeriodeService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PeriodeKpiController extends Controller
{
    protected $PeriodeService;

    public function __construct(PeriodeService $PeriodeService)
    {
        $this->PeriodeService = $PeriodeService;
    }

    public function index()
    {
        $pageTitle = 'Periode KPI';
        $periodes  = PeriodeKpi::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();
        return view('pages.pemutu.periode_kpis.index', compact('pageTitle', 'periodes'));
    }

    public function data()
    {
        $query = PeriodeKpi::query();
        return DataTables::of($query)->make(true);
    }

    public function create()
    {
        if (request()->ajax()) {
            return view('pages.pemutu.periode_kpis.form');
        }
        return view('pages.pemutu.periode_kpis.create', ['pageTitle' => 'Tambah Periode KPI']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:100',
            'semester'        => 'required|in:Ganjil,Genap',
            'tahun_akademik'  => 'required|string|max:20',
            'tahun'           => 'required|integer',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        try {
            $this->PeriodeService->store($request->all());
            return jsonSuccess('Periode KPI berhasil disimpan.', route('pemutu.periode-kpis.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(PeriodeKpi $periodeKpi)
    {
        if (request()->ajax()) {
            return view('pages.pemutu.periode_kpis.form', compact('periodeKpi'));
        }
        $pageTitle = 'Edit Periode KPI';
        return view('pages.pemutu.periode_kpis.edit', compact('periodeKpi', 'pageTitle'));
    }

    public function update(Request $request, PeriodeKpi $periodeKpi)
    {
        $request->validate([
            'nama'            => 'required|string|max:100',
            'semester'        => 'required|in:Ganjil,Genap',
            'tahun_akademik'  => 'required|string|max:20',
            'tahun'           => 'required|integer',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        try {
            $this->PeriodeService->update($periodeKpi, $request->all());
            return jsonSuccess('Periode KPI berhasil diupdate.', route('pemutu.periode-kpis.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(PeriodeKpi $periodeKpi)
    {
        try {
            $this->PeriodeService->destroy($periodeKpi);
            return jsonSuccess('Periode KPI berhasil dihapus.', route('pemutu.periode-kpis.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function activate(PeriodeKpi $periodeKpi)
    {
        try {
            $this->PeriodeService->activate($periodeKpi);
            return jsonSuccess('Periode KPI berhasil diaktifkan.', route('pemutu.periode-kpis.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
