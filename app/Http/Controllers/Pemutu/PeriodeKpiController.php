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
        return view('pages.pemutu.periode_kpis.index', compact('pageTitle'));
    }

    public function data()
    {
        $query = PeriodeKpi::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('periode', function ($row) {
                $badge = $row->is_active ? '<span class="badge bg-success-lt ms-2">Aktif</span>' : '';
                return '<div>' . e($row->nama) . $badge . '</div>
                        <small class="text-muted">' . $row->tanggal_mulai->format('d M Y') . ' - ' . $row->tanggal_selesai->format('d M Y') . '</small>';
            })
            ->addColumn('action', function ($row) {
                $editUrl     = route('pemutu.periode-kpis.edit', $row->encrypted_periode_kpi_id);
                $deleteUrl   = route('pemutu.periode-kpis.destroy', $row->encrypted_periode_kpi_id);
                $activateUrl = route('pemutu.periode-kpis.activate', $row->encrypted_periode_kpi_id);

                $activateBtn = ! $row->is_active
                    ? '<button type="button" class="btn btn-sm btn-icon btn-ghost-success activate-periode" data-url="' . $activateUrl . '" title="Aktifkan"><i class="ti ti-check"></i></button>'
                    : '';

                return '<div class="btn-list flex-nowrap justify-content-end">
                            <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-ghost-primary" title="Edit"><i class="ti ti-pencil"></i></a>
                            ' . $activateBtn . '
                            <button type="button" class="btn btn-sm btn-icon btn-ghost-danger ajax-delete" data-url="' . $deleteUrl . '" data-title="Hapus Periode?" title="Hapus"><i class="ti ti-trash"></i></button>
                        </div>';
            })
            ->rawColumns(['periode', 'action'])
            ->make(true);
    }

    public function create()
    {
        $pageTitle = 'Tambah Periode KPI';
        return view('pages.pemutu.periode_kpis.create', compact('pageTitle'));
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
            PeriodeKpi::create($request->all());
            return jsonSuccess('Periode KPI berhasil ditambahkan.', route('pemutu.periode-kpis.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(PeriodeKpi $periodeKpi)
    {
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
            return jsonSuccess('Periode KPI berhasil dihapus.');
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
