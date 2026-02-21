<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\LogPenggunaanPcRequest;
use App\Models\Lab\LogPenggunaanPc;
use App\Services\Lab\LogPenggunaanPcService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class LogPenggunaanPcController extends Controller
{
    public function __construct(protected LogPenggunaanPcService $logPenggunaanPcService)
    {}

    /**
     * Display listing (Monitoring)
     */
    public function index()
    {
        return view('pages.lab.log-pc.index');
    }

    public function paginate(Request $request)
    {
        $logs = $this->logPenggunaanPcService->getFilteredQuery($request->all());

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
        $activeJadwal = $this->logPenggunaanPcService->getCurrentActiveJadwal();

        $assignment = null;
        if ($activeJadwal) {
            $assignment = $this->logPenggunaanPcService->getAssignmentForUser(Auth::id(), $activeJadwal->jadwal_kuliah_id);
        }

        $log = new LogPenggunaanPc();
        return view('pages.lab.log-pc.create-edit-ajax', compact('activeJadwal', 'assignment', 'log'));
    }

    /**
     * Store Log
     */
    public function store(LogPenggunaanPcRequest $request)
    {
        try {
            $data              = $request->all();
            $data['user_id']   = Auth::id();
            $data['jadwal_id'] = decryptId($request->jadwal_id);
            $data['lab_id']    = decryptId($request->lab_id);

            $this->logPenggunaanPcService->storeLog($data);

            return jsonSuccess('Log berhasil disimpan.', route('lab.log-pc.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan log: ' . $e->getMessage());
        }
    }
}
