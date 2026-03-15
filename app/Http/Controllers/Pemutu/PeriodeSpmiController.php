<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PeriodeSpmiRequest;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PeriodeSpmiController extends Controller
{
    public function __construct(
        protected PeriodeSpmiService $periodeSpmiService
    ) {
        // $this->authorizeResourcePermissions('pemutu.periode');
    }

    public function index(Request $request)
    {
        $pageTitle = 'Periode SPMI';

        $years       = $this->periodeSpmiService->getAvailableYears();
        $defaultYear = date('Y');

        // Ensure we always have at least the default year in the list
        if ($years->isEmpty()) {
            $years->push($defaultYear);
        }

        // If current year not in available years, pick the latest one if available
        if (! $years->contains($defaultYear) && $years->isNotEmpty()) {
            $defaultYear = $years->first();
        }

        $selectedYear = $request->get('year', $defaultYear);
        $periodes     = $this->periodeSpmiService->getAll($selectedYear);

        return view('pages.pemutu.periode_spmi.index', compact('pageTitle', 'periodes', 'years', 'selectedYear'));
    }

    public function data(Request $request)
    {
        $query = $this->periodeSpmiService->getBaseQuery();

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
                    'editUrl'   => route('pemutu.periode-spmi.edit', $row->encrypted_periodespmi_id),
                    'deleteUrl' => route('pemutu.periode-spmi.destroy', $row->encrypted_periodespmi_id),
                ])->render();
            })
            ->make(true);
    }

    public function create()
    {
        $periodeSpmi = new PeriodeSpmi();
        return view('pages.pemutu.periode_spmi.create-edit-ajax', compact('periodeSpmi'));
    }

    public function store(PeriodeSpmiRequest $request)
    {
        $this->periodeSpmiService->store($request->validated());
        return jsonSuccess('Periode SPMI berhasil ditambahkan', route('pemutu.periode-spmi.index'));
    }

    public function edit(PeriodeSpmi $periodeSpmi)
    {
        return view('pages.pemutu.periode_spmi.create-edit-ajax', compact('periodeSpmi'));
    }

    public function update(PeriodeSpmiRequest $request, PeriodeSpmi $periodeSpmi)
    {
        $this->periodeSpmiService->update($periodeSpmi, $request->validated());
        return jsonSuccess('Periode SPMI berhasil diperbarui', route('pemutu.periode-spmi.index'));
    }

    public function destroy(PeriodeSpmi $periodeSpmi)
    {
        $this->periodeSpmiService->delete($periodeSpmi);
        return jsonSuccess('Periode SPMI berhasil dihapus.', route('pemutu.periode-spmi.index'));
    }
}
