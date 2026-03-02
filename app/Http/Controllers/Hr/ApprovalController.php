<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Keluarga;
use App\Models\Hr\PengembanganDiri;
use App\Models\Hr\RiwayatApproval;
use App\Models\Hr\RiwayatDataDiri;
use App\Models\Hr\RiwayatInpassing;
use App\Models\Hr\RiwayatJabFungsional;
use App\Models\Hr\RiwayatJabStruktural;
use App\Models\Hr\RiwayatPendidikan;
use App\Models\Hr\RiwayatStatAktifitas;
use App\Models\Hr\RiwayatStatPegawai;
use App\Services\Hr\ApprovalService;
use App\Services\Hr\RiwayatDataDiriService;
use App\Services\Hr\RiwayatJabFungsionalService;
use App\Services\Hr\RiwayatPendidikanService;
use App\Services\Hr\RiwayatStatAktifitasService;
use App\Services\Hr\RiwayatStatPegawaiService;
use App\Services\Hr\RiwayatStrukturalService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApprovalController extends Controller
{
    public function __construct(protected ApprovalService $approvalService)
    {}

    /**
     * Map model class → service yang punya processApproval().
     * Model tanpa FK header di pegawai (Keluarga, PengembanganDiri) pakai fallback.
     */
    protected function resolveService(string $modelClass): ?object
    {
        $map = [
            RiwayatDataDiri::class          => app(RiwayatDataDiriService::class),
            RiwayatStatPegawai::class       => app(RiwayatStatPegawaiService::class),
            RiwayatStatAktifitas::class     => app(RiwayatStatAktifitasService::class),
            RiwayatJabFungsional::class     => app(RiwayatJabFungsionalService::class),
            RiwayatPendidikan::class        => app(RiwayatPendidikanService::class),
            RiwayatJabStruktural::class     => app(RiwayatStrukturalService::class),
            \App\Models\Hr\Perizinan::class => app(\App\Services\Hr\PerizinanService::class),
            \App\Models\Hr\Lembur::class    => app(\App\Services\Hr\LemburService::class),
        ];

        return $map[$modelClass] ?? null;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $status = $request->input('status', ''); // kosong = semua data

            $pegawaiModels = [
                RiwayatDataDiri::class,
                RiwayatPendidikan::class,
                RiwayatStatPegawai::class,
                RiwayatStatAktifitas::class,
                RiwayatJabFungsional::class,
                RiwayatJabStruktural::class,
                RiwayatInpassing::class,
                Keluarga::class,
                PengembanganDiri::class,
                \App\Models\Hr\Perizinan::class,
                \App\Models\Hr\Lembur::class,
            ];

            $query = RiwayatApproval::with('subject')
                ->whereIn('model', $pegawaiModels)
                ->when(! empty($status), fn($q) => $q->where('status', $status))
                ->latest();

            return DataTables::of($query)
                ->addColumn('pegawai_nama', fn($row) => $row->pegawai?->nama ?? '-')
                ->addColumn('tipe_request', fn($row) => hrModelLabel($row->model))
                ->editColumn('created_at', fn($row) => $row->created_at?->format('d M Y H:i') ?? '-')
                ->addColumn('status', fn($row) => getApprovalStatus($row->status))
                ->addColumn('action', fn($row) => view('pages.hr.approval._action', compact('row'))->render())
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('pages.hr.approval.index');
    }

    public function show($id)
    {
        $approval = RiwayatApproval::with('subject')->findOrFail($id);
        $subject  = $approval->subject;
        $pegawai  = $approval->pegawai;

        $before = null;
        if ($subject && isset($subject->before_id) && $subject->before_id) {
            $before = $subject::find($subject->before_id);
        }

        $diffs = hrDiffFields($before, $subject);

        return view('pages.hr.approval.show', compact('approval', 'subject', 'before', 'pegawai', 'diffs'));
    }

    /**
     * Proses approval — dispatch ke service spesifik jika ada, fallback ke ApprovalService.
     * Status dikirim dari request (Approved, Rejected, Tangguhkan, dll).
     */
    public function process(Request $request, $id)
    {
        $status = $request->input('status', 'Approved');
        $reason = $request->input('reason');

        $approval = RiwayatApproval::findOrFail($id);
        $service  = $this->resolveService($approval->model);

        if ($service && method_exists($service, 'processApproval')) {
            $service->processApproval($id, $status, $reason);
        } else {
            // Fallback: model tidak punya FK header (Keluarga, PengembanganDiri, dll)
            $this->approvalService->processApproval($id, $status, $reason);
        }

        $messages = [
            'Approved'   => 'Pengajuan berhasil disetujui.',
            'Rejected'   => 'Pengajuan berhasil ditolak.',
            'Tangguhkan' => 'Pengajuan berhasil ditangguhkan.',
        ];

        return jsonSuccess($messages[$status] ?? 'Pengajuan berhasil diproses.');
    }
}
