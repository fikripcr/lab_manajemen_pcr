<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\LaporanKerusakanRequest;
use App\Models\Lab\Inventaris;
use App\Models\Lab\Lab;
use App\Models\Lab\LabInventaris;
use App\Models\Lab\LaporanKerusakan;
use App\Services\Lab\LaporanKerusakanService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LaporanKerusakanController extends Controller
{
    public function __construct(protected LaporanKerusakanService $laporanKerusakanService)
    {}

    public function index()
    {
        return view('pages.lab.laporan-kerusakan.index');
    }

    public function data(Request $request)
    {
        $query = $this->laporanKerusakanService->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('lab_nama', function ($row) {
                return $row->inventaris && $row->inventaris->lab ? $row->inventaris->lab->name : '-';
            })
            ->addColumn('alat_info', function ($row) {
                return $row->inventaris ? $row->inventaris->nama_alat : 'Umum/Lainnya';
            })
            ->addColumn('pelapor', function ($row) {
                return $row->created_by ?? '-';
            })
            ->addColumn('tanggal', function ($row) {
                return $row->created_at->format('d M Y');
            })
            ->editColumn('status', function ($row) {
                $badges = [
                    'open'        => 'danger',
                    'in_progress' => 'warning',
                    'resolved'    => 'success',
                    'closed'      => 'secondary',
                ];
                $color = $badges[$row->status] ?? 'secondary';
                return "<span class='badge bg-{$color} text-white'>{$row->status}</span>";
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'viewUrl' => route('lab.laporan-kerusakan.show', $row->encrypted_laporan_kerusakan_id),
                ])->render();
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $labs    = Lab::with('labTeams')->get();
        $laporan = new LaporanKerusakan();
        return view('pages.lab.laporan-kerusakan.create-edit-ajax', compact('labs', 'laporan'));
    }

    public function getInventaris(Request $request)
    {
        if (! $request->lab_id) {
            return response()->json(['data' => []]);
        }

        try {
            $labId = decryptIdIfEncrypted($request->lab_id);

            // Get LabAssignments where status is active
            // Note: Relationship structure depends on implementation.
            // Assuming LabInventaris table links Lab to Inventaris.
            // Check LabInventaris model if needed, but assuming standard.
            $assignments = LabInventaris::with('inventaris')
                ->where('lab_id', $labId)
                ->where('is_active', true) // or status 'active'
                ->get();

            $results = $assignments->map(function ($item) {
                // Return only if inventaris exists
                if (! $item->inventaris) {
                    return null;
                }

                return [
                    'id'   => encryptId($item->inventaris_id),
                    'text' => $item->inventaris->nama_alat, // . ' (' . $item->kode_inventaris . ')'
                ];
            })->filter()->values();

            return jsonSuccess('Data retrieved', null, ['data' => $results]);
        } catch (\Exception $e) {
            return response()->json(['data' => []]);
        }
    }

    public function store(LaporanKerusakanRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('bukti_foto')) {
                $data['bukti_foto'] = $request->file('bukti_foto')->store('laporan-kerusakan', 'public');
            }

            $this->laporanKerusakanService->createLaporan($data);
            return jsonSuccess('Laporan berhasil dikirim', route('lab.laporan-kerusakan.index'));
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal mengirim laporan: ' . $e->getMessage());
        }
    }

    public function show(LaporanKerusakan $laporanKerusakan)
    {
        $laporan = $laporanKerusakan->load(['inventaris.lab', 'teknisi']);
        return view('pages.lab.laporan-kerusakan.show', compact('laporan'));
    }
}
