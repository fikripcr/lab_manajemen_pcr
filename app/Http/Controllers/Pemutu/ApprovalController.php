<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\Dokumen;
use App\Models\Sys\SysApproval;
use App\Services\Sys\ApprovalService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApprovalController extends Controller
{
    public function __construct(protected ApprovalService $approvalService)
    {
        $this->middleware('permission:pemutu.approval.view')->only(['index', 'show']);
        $this->middleware('permission:pemutu.approval.process')->only(['process']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->approvalService->getApprovalsByTypeQuery(\App\Models\Pemutu\Dokumen::class);

            // Filter: only show approvals assigned to current user or all if admin
            $user = auth()->user();
            if ($user && $user->pegawai && ! $user->hasRole(['Administrator', 'Admin SPMI'])) {
                $query->where('pegawai_id', $user->pegawai->pegawai_id);
            }

            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('status', (string) $request->status);
            }

            return DataTables::of($query)
                ->addColumn('dokumen_judul', function ($row) {
                    if ($row->model == \App\Models\Pemutu\Dokumen::class) {
                        return $row->subject?->judul ?? '-';
                    }

                    return '-';
                })
                ->addColumn('dokumen_kode', function ($row) {
                    if ($row->model == \App\Models\Pemutu\Dokumen::class) {
                        $kode = $row->subject?->kode ?? '-';

                        return '<span class="badge bg-blue-lt">'.$kode.'</span>';
                    }

                    return '-';
                })
                ->addColumn('tipe_approval', function ($row) {
                    return $row->model == \App\Models\Pemutu\Dokumen::class ? 'Dokumen SPMI' : class_basename($row->model);
                })
                ->editColumn('created_at', fn ($row) => $row->created_at?->format('d M Y H:i') ?? '-')
                ->addColumn('status_badge', function ($row) {
                    $color = match ($row->status) {
                        'Approved' => 'green',
                        'Rejected' => 'red',
                        'Pending' => 'orange',
                        default => 'secondary'
                    };

                    return '<span class="badge bg-'.$color.'-lt">'.$row->status.'</span>';
                })
                ->addColumn('action', function ($row) {
                    return view('pages.pemutu.approval._action', compact('row'))->render();
                })
                ->addColumn('oleh', function ($row) {
                    $label = match ($row->status) {
                        'Pending' => '<span class="text-muted small">Menunggu</span>',
                        'Approved' => '<span class="text-success small">Disetujui oleh</span>',
                        'Rejected' => '<span class="text-danger small">Ditolak oleh</span>',
                        default => '',
                    };

                    return $label.'<br><strong>'.$row->pejabat.'</strong>';
                })
                ->rawColumns(['status_badge', 'action', 'dokumen_kode', 'oleh'])
                ->make(true);
        }

        return view('pages.pemutu.approval.index');
    }

    public function show($id)
    {
        $approval = SysApproval::with('subject')
            ->where('sys_approval_id', decryptIdIfEncrypted($id))
            ->firstOrFail();

        $dokumen = $approval->subject;
        $isSah = false;
        $qrCode = null;
        $allApprovals = null;

        if ($dokumen instanceof Dokumen) {
            $allApprovals = $dokumen->sysApprovals;
            [$isSah, $qrCode] = $this->resolveValidationStatus($dokumen, $allApprovals);
        }

        return view('pages.pemutu.approval.show', compact('approval', 'isSah', 'qrCode', 'allApprovals'));
    }

    public function process(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $idDecrypted = (int) decryptIdIfEncrypted($id);
        $approvalRecord = SysApproval::with('subject')->find($idDecrypted);

        if (! $approvalRecord) {
            return jsonError('Approval record tidak ditemukan.');
        }

        // --- GUARD 1: Approval Ownership ---
        $user = auth()->user();
        if ($user && $user->pegawai) {
            if ($approvalRecord->pegawai_id !== $user->pegawai->pegawai_id) {
                // Allow admins to process any approval
                if (! $user->hasRole(['Administrator', 'Admin SPMI'])) {
                    return jsonError('Anda bukan approver yang ditugaskan untuk approval ini.');
                }
            }
        }

        // --- GUARD 2: Already processed ---
        if ($approvalRecord->status !== 'Pending') {
            return jsonError('Approval ini sudah diproses sebelumnya (status: '.$approvalRecord->status.').');
        }

        // --- GUARD 3: Periode Penetapan ---
        if ($approvalRecord->subject instanceof Dokumen) {
            $year = (int) ($approvalRecord->subject->periode ?? session('siklus_spmi_tahun'));
            $kelompok = session('pemutu_active_kelompok', 'akademik');

            if (! pemutu_can_modify($year, $kelompok)) {
                return jsonError('Aksi persetujuan dibatasi. Masa penetapan periode ini belum dibuka atau sudah berakhir.');
            }
        }
        // ---------------------------------

        $approval = $this->approvalService->processApproval(
            $idDecrypted,
            $request->status,
            $request->catatan
        );

        logActivity('pemutu', "Pegawai memproses approval dokumen ID {$approval->model_id} (".$request->status.')');

        $message = 'Persetujuan berhasil '.($request->status == 'Approved' ? 'diterima' : 'ditolak').'.';

        return jsonSuccess($message, request()->header('referer') ?: url()->previous());
    }

    /**
     * Resolve validation status for dokumen
     */
    private function resolveValidationStatus(Dokumen $dokumen, $approvals): array
    {
        if ($approvals->count() === 0 || $approvals->where('status', 'Approved')->count() !== $approvals->count()) {
            return [false, null];
        }

        $verifyUrl = route('pemutu.dokumen.verify', $dokumen->encrypted_dok_id);
        $qrCode = null;

        if (class_exists(\BaconQrCode\Writer::class)) {
            $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(120, 1),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd
            );
            $writer = new \BaconQrCode\Writer($renderer);
            $qrCode = $writer->writeString($verifyUrl);
        }

        return [true, $qrCode];
    }
}
