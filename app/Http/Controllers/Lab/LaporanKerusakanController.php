<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab\Inventaris;
use App\Models\Lab\Lab;
use App\Models\Lab\LabInventaris;
use App\Services\Lab\LaporanKerusakanService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LaporanKerusakanController extends Controller
{
    protected $service;

    public function __construct(LaporanKerusakanService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('pages.lab.laporan-kerusakan.index');
    }

    public function data(Request $request)
    {
        $query = $this->service->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('lab_nama', function ($row) {
                return $row->inventaris && $row->inventaris->lab ? $row->inventaris->lab->name : '-';
            })
            ->addColumn('alat_info', function ($row) {
                return $row->inventaris ? $row->inventaris->nama_alat : 'Umum/Lainnya';
            })
            ->addColumn('pelapor', function ($row) {
                return $row->createdBy ? $row->createdBy->name : '-';
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
                    'viewUrl' => route('lab.laporan-kerusakan.show', encryptId($row->laporan_kerusakan_id)),
                ])->render();
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $labs = Lab::all();
        return view('pages.lab.laporan-kerusakan.create', compact('labs'));
    }

    public function getInventaris(Request $request)
    {
        if (! $request->lab_id) {
            return response()->json(['data' => []]);
        }

        try {
            $labId = decryptId($request->lab_id);

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

            return response()->json(['data' => $results]);
        } catch (\Exception $e) {
            return response()->json(['data' => []]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'lab_id'              => 'required',
            'inventaris_id'       => 'required',
            'deskripsi_kerusakan' => 'required|string',
            'bukti_foto'          => 'nullable|image|max:2048',
        ]);

        try {
            $data = $request->except('bukti_foto');
            // We don't pass lab_id to service as it is not in LaporanKerusakan model,
            // but we needed it for the dropdown.
            // $data['lab_id'] = decryptId($request->lab_id);

            $data['inventaris_id'] = decryptId($request->inventaris_id);

            if ($request->hasFile('bukti_foto')) {
                $path               = $request->file('bukti_foto')->store('laporan-kerusakan', 'public');
                $data['bukti_foto'] = $path;
            }

            $this->service->createLaporan($data);
            return jsonSuccess('Laporan berhasil dikirim', route('lab.laporan-kerusakan.index'));
        } catch (\Exception $e) {
            return jsonError('Gagal mengirim laporan: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $laporan = $this->service->getLaporanById(decryptId($id));
        if (! $laporan) {
            abort(404);
        }

        return view('pages.lab.laporan-kerusakan.show', compact('laporan'));
    }
}
