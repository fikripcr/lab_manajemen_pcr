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
    public function __construct(protected ApprovalService $approvalService) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->approvalService->getApprovalsByTypeQuery(\App\Models\Pemutu\Dokumen::class);

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

        $approval = $this->approvalService->processApproval(
            (int) decryptIdIfEncrypted($id),
            $request->status,
            $request->catatan
        );

        logActivity('pemutu', "Pegawai menyetujui dokumen ID {$approval->model_id} (".$request->status.')');

        return jsonSuccess('Persetujuan berhasil '.($request->status == 'Approved' ? 'diterima' : 'ditolak').'.');
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
