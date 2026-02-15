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
        return view('pages.pemutu.periode_spmis.index', compact('pageTitle'));
    }

    public function paginate(Request $request)
    {
        $query = PeriodeSpmi::query();
        return DataTables::of($query)
            ->addIndexColumn()
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
        $pageTitle = 'Tambah Periode SPMI';
        return view('pages.pemutu.periode_spmis.create', compact('pageTitle'));
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
        $pageTitle = 'Edit Periode SPMI';
        return view('pages.pemutu.periode_spmis.edit', compact('pageTitle', 'periodeSpmi'));
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
        return response()->json(['success' => true]);
    }
}
