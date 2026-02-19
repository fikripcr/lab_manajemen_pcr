<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\PeriodeSpmi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PeriodeSpmiController extends Controller
{
    public function index()
    {
        $pageTitle = 'Periode SPMI';
        $periodes  = PeriodeSpmi::orderBy('periode', 'desc')->get();
        return view('pages.pemutu.periode_spmis.index', compact('pageTitle', 'periodes'));
    }

    public function paginate(Request $request)
    {
        $query = PeriodeSpmi::query()->orderBy('periode', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('penetapan_awal', function ($row) {
                if (! $row->penetapan_awal) {
                    return '-';
                }

                $start = \Carbon\Carbon::parse($row->penetapan_awal)->format('d M');
                $end   = $row->penetapan_akhir ? \Carbon\Carbon::parse($row->penetapan_akhir)->format('d M Y') : '-';

                return $start . ($row->penetapan_akhir ? ' - ' . $end : ' ' . \Carbon\Carbon::parse($row->penetapan_awal)->format('Y'));
            })
            ->editColumn('ami_awal', function ($row) {
                if (! $row->ami_awal) {
                    return '-';
                }

                $start = \Carbon\Carbon::parse($row->ami_awal)->format('d M');
                $end   = $row->ami_akhir ? \Carbon\Carbon::parse($row->ami_akhir)->format('d M Y') : '-';

                return $start . ($row->ami_akhir ? ' - ' . $end : ' ' . \Carbon\Carbon::parse($row->ami_awal)->format('Y'));
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('pemutu.periode-spmis.edit', $row),
                    'deleteUrl' => route('pemutu.periode-spmis.destroy', $row),
                ])->render();
            })
            ->make(true);
    }

    public function create()
    {
        if (request()->ajax()) {
            return view('pages.pemutu.periode_spmis.create-edit-ajax');
        }
        // Fallback or full page support if needed, but mainly AJAX
        return view('pages.pemutu.periode_spmis.create-edit-ajax');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'periode'            => 'required|integer',
            'jenis_periode'      => 'required|string|max:20',
            'penetapan_awal'     => 'nullable|date',
            'penetapan_akhir'    => 'nullable|date',
            'ed_awal'            => 'nullable|date',
            'ed_akhir'           => 'nullable|date',
            'ami_awal'           => 'nullable|date',
            'ami_akhir'          => 'nullable|date',
            'pengendalian_awal'  => 'nullable|date',
            'pengendalian_akhir' => 'nullable|date',
            'peningkatan_awal'   => 'nullable|date',
            'peningkatan_akhir'  => 'nullable|date',
        ]);

        PeriodeSpmi::create($data);

        return jsonSuccess('Periode SPMI berhasil ditambahkan', route('pemutu.periode-spmis.index'));
    }

    public function edit(PeriodeSpmi $periodeSpmi)
    {
        return view('pages.pemutu.periode_spmis.create-edit-ajax', compact('periodeSpmi'));
    }

    public function update(Request $request, PeriodeSpmi $periodeSpmi)
    {
        $data = $request->validate([
            'periode'            => 'required|integer',
            'jenis_periode'      => 'required|string|max:20',
            'penetapan_awal'     => 'nullable|date',
            'penetapan_akhir'    => 'nullable|date',
            'ed_awal'            => 'nullable|date',
            'ed_akhir'           => 'nullable|date',
            'ami_awal'           => 'nullable|date',
            'ami_akhir'          => 'nullable|date',
            'pengendalian_awal'  => 'nullable|date',
            'pengendalian_akhir' => 'nullable|date',
            'peningkatan_awal'   => 'nullable|date',
            'peningkatan_akhir'  => 'nullable|date',
        ]);

        $periodeSpmi->update($data);

        return jsonSuccess('Periode SPMI berhasil diperbarui', route('pemutu.periode-spmis.index'));
    }

    public function destroy(PeriodeSpmi $periodeSpmi)
    {
        $periodeSpmi->delete();
        return jsonSuccess('Periode SPMI berhasil dihapus', route('pemutu.periode-spmis.index'));
    }
}
