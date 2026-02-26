<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PeriodeSpmiRequest;
use App\Models\Pemutu\PeriodeSpmi;
use App\Services\Pemutu\PeriodeSpmiService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PeriodeSpmiController extends Controller
{
    public function __construct(
        protected PeriodeSpmiService $periodeSpmiService
    ) {}

    public function index()
    {
        $pageTitle = 'Periode SPMI';
        $periodes  = $this->periodeSpmiService->getAll();
        return view('pages.pemutu.periode_spmis.index', compact('pageTitle', 'periodes'));
    }

    public function paginate(Request $request)
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
                    'editUrl'   => route('pemutu.periode-spmis.edit', $row->encrypted_periodespmi_id),
                    'deleteUrl' => route('pemutu.periode-spmis.destroy', $row->encrypted_periodespmi_id),
                ])->render();
            })
            ->make(true);
    }

    public function create()
    {
        $periodeSpmi = new PeriodeSpmi();
        return view('pages.pemutu.periode_spmis.create-edit-ajax', compact('periodeSpmi'));
    }

    public function store(PeriodeSpmiRequest $request)
    {
        try {
            $this->periodeSpmiService->store($request->validated());
            return jsonSuccess('Periode SPMI berhasil ditambahkan', route('pemutu.periode-spmis.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menambah periode: ' . $e->getMessage());
        }
    }

    public function edit(PeriodeSpmi $periodeSpmi)
    {
        return view('pages.pemutu.periode_spmis.create-edit-ajax', compact('periodeSpmi'));
    }

    public function update(PeriodeSpmiRequest $request, PeriodeSpmi $periodeSpmi)
    {
        try {
            $this->periodeSpmiService->update($periodeSpmi, $request->validated());
            return jsonSuccess('Periode SPMI berhasil diperbarui', route('pemutu.periode-spmis.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui periode: ' . $e->getMessage());
        }
    }

    public function destroy(PeriodeSpmi $periodeSpmi)
    {
        try {
            $this->periodeSpmiService->delete($periodeSpmi);
            return jsonSuccess('Periode SPMI berhasil dihapus.', route('pemutu.periode-spmis.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus periode: ' . $e->getMessage());
        }
    }
}
