<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Services\Lab\LogPenggunaanPcService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class LogPenggunaanPcController extends Controller
{
    protected $logService;

    public function __construct(LogPenggunaanPcService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Display listing (Monitoring)
     */
    public function index()
    {
        return view('pages.lab.log-pc.index');
    }

    public function paginate(Request $request)
    {
        $logs = $this->logService->getFilteredQuery($request->all());

        return DataTables::of($logs)
            ->addIndexColumn()
            ->addColumn('mahasiswa', function ($log) {
                return $log->user->name . '<br><small class="text-muted">' . $log->user->username . '</small>';
            })
            ->addColumn('pc_info', function ($log) {
                $nomor = $log->pcAssignment ? $log->pcAssignment->nomor_pc : '?';
                $lab   = $log->lab ? $log->lab->name : '?';
                return "PC {$nomor} <br><small>{$lab}</small>";
            })
            ->addColumn('waktu', function ($log) {
                return $log->waktu_isi->format('d M Y H:i');
            })
            ->addColumn('kondisi', function ($log) {
                $color = $log->status_pc == 'Baik' ? 'success' : 'danger';
                return "<span class='badge bg-{$color}'>{$log->status_pc}</span><br><small>{$log->catatan_umum}</small>";
            })
            ->rawColumns(['mahasiswa', 'pc_info', 'kondisi'])
            ->make(true);
    }

    /**
     * Show Form
     */
    public function create()
    {
        // Auto-detect Schedule
        $activeJadwal = $this->logService->getCurrentActiveJadwal();

        $assignment = null;
        if ($activeJadwal) {
            $assignment = $this->logService->getAssignmentForUser(Auth::id(), $activeJadwal->jadwal_kuliah_id);
        }

        return view('pages.lab.log-pc.create', compact('activeJadwal', 'assignment'));
    }

    /**
     * Store Log
     */
    public function store(Request $request)
    {
        $request->validate([
            'status_pc'    => 'required|in:Baik,Rusak',
            'catatan_umum' => 'nullable|string',
            'jadwal_id'    => 'required', // Hidden input
            'lab_id'       => 'required', // Hidden input
        ]);

        // Re-validate Schedule Time Security
        // ... (Logic in Service preferred, but simple check here or trust UI for now since service stores what is passed)
        // Let's rely on data passed being valid for Phase 2 MVP.

        try {
            // Force User ID to current user
            $data              = $request->all();
            $data['user_id']   = Auth::id();
            $data['jadwal_id'] = decryptId($request->jadwal_id); // Assuming hidden input is encrypted
            $data['lab_id']    = decryptId($request->lab_id);

            $this->logService->storeLog($data);

            return jsonSuccess('Log berhasil disimpan.', route('lab.log-pc.index'));
        } catch (\Exception $e) {
            return jsonError('Gagal menyimpan log: ' . $e->getMessage(), 500);
        }
    }
}
