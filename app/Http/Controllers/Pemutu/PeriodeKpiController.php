<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\PeriodeKpi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PeriodeKpiController extends Controller
{
    public function index()
    {
        $pageTitle = 'Periode KPI';
        $periodes  = PeriodeKpi::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();
        return view('pages.pemutu.periode_kpis.index', compact('pageTitle', 'periodes'));
    }

    public function data()
    {
        // Keeping this for now if needed, but index uses direct collection
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

    // store method remains same

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
            $periodeKpi->update($request->all());
            return jsonSuccess('Periode KPI berhasil diupdate.', route('pemutu.periode-kpis.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(PeriodeKpi $periodeKpi)
    {
        try {
            if ($periodeKpi->is_active) {
                return jsonError('Tidak dapat menghapus periode yang sedang aktif.', 400);
            }
            $periodeKpi->delete();
            return jsonSuccess('Periode KPI berhasil dihapus.', route('pemutu.periode-kpis.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function activate(PeriodeKpi $periodeKpi)
    {
        try {
            DB::transaction(function () use ($periodeKpi) {
                // Deactivate all other periods
                PeriodeKpi::where('is_active', true)->update(['is_active' => false]);
                // Activate this period
                $periodeKpi->update(['is_active' => true]);
            });

            return jsonSuccess('Periode KPI berhasil diaktifkan.', route('pemutu.periode-kpis.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
