<?php
namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pmb\UpdateStatusRequest;
use App\Http\Requests\Pmb\VerifyDocRequest;
use App\Models\Pmb\DokumenUpload;
use App\Models\Pmb\Pendaftaran;
use App\Services\Pmb\PendaftaranService;
use App\Services\Pmb\PeriodeService;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function __construct(
        protected PendaftaranService $pendaftaranService,
        protected PeriodeService $periodeService,
        protected \App\Services\Pmb\VerificationService $verificationService
    ) {}

    /**
     * Unified Dashboard (Admin & Camaba)
     */
    public function dashboard()
    {
        $periodeAktif = $this->periodeService->getActivePeriode();
        return \view('pages.pmb.dashboard.index', compact('periodeAktif'));
    }

    /**
     * Display a listing of registrations (Admin)
     */
    public function index()
    {
        return \view('pages.pmb.pendaftaran.index');
    }

    /**
     * DataTables pagination for registrations
     */
    public function data(Request $request)
    {
        return \datatables()->of($this->pendaftaranService->getFilteredQuery($request->all()))
            ->addIndexColumn()
            ->editColumn('no_pendaftaran', function ($pendaftaran) {
                return '<span class="badge bg-blue-lt">' . $pendaftaran->no_pendaftaran . '</span>';
            })
            ->editColumn('status_terkini', function ($pendaftaran) {
                $class = match ($pendaftaran->status_terkini) {
                    'Draft'       => 'bg-secondary',
                    'Menunggu_Verifikasi_Bayar', 'Menunggu_Verifikasi_Berkas' => 'bg-warning',
                    'Siap_Ujian'  => 'bg-info',
                    'Lulus'       => 'bg-success',
                    'Tidak_Lulus' => 'bg-danger',
                    default       => 'bg-primary'
                };
                return '<span class="badge ' . $class . ' text-white">' . str_replace('_', ' ', $pendaftaran->status_terkini) . '</span>';
            })
            ->editColumn('waktu_daftar', function ($pendaftaran) {
                return formatTanggalIndo($pendaftaran->waktu_daftar);
            })
            ->addColumn('action', function ($pendaftaran) {
                $customActions = [];
                if (in_array($pendaftaran->status_terkini, ['Menunggu_Verifikasi_Bayar', 'Menunggu_Verifikasi_Berkas'])) {
                    $customActions[] = [
                        'url'   => 'javascript:void(0)',
                        'label' => 'Verifikasi',
                        'icon'  => 'check',
                        'class' => 'btn-verify',
                        'attr'  => 'data-id="' . $pendaftaran->encrypted_pendaftaran_id . '"',
                    ];
                }

                return \view('components.tabler.datatables-actions', [
                    'viewUrl'       => \route('pmb.pendaftaran.show', $pendaftaran->encrypted_pendaftaran_id),
                    'customActions' => $customActions,
                ])->render();
            })
            ->rawColumns(['no_pendaftaran', 'status_terkini', 'action'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load(['user', 'profil', 'dokumenUpload.jenisDokumen', 'riwayat.pelaku', 'approvals' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }]);
        return \view('pages.pmb.pendaftaran.show', compact('pendaftaran'));
    }

    /**
     * Show update status form
     */
    public function updateStatusForm(Pendaftaran $pendaftaran)
    {
        return \view('pages.pmb.pendaftaran.update_status_form', compact('pendaftaran'));
    }

    /**
     * Update pendaftaran status (Admin)
     */
    public function updateStatus(UpdateStatusRequest $request, Pendaftaran $pendaftaran)
    {
        $this->verificationService->updatePendaftaranStatus($pendaftaran, $request->validated('status'), $request->validated('keterangan'));
        return jsonSuccess('Status pendaftaran berhasil diperbarui.');
    }

    /**
     * Show verification form for single document
     */
    public function verifyDocumentForm(DokumenUpload $document)
    {
        return \view('pages.pmb.pendaftaran.verify_doc_form', compact('document'));
    }

    /**
     * Verify single document
     */
    public function verifyDocument(VerifyDocRequest $request, DokumenUpload $document)
    {
        $this->verificationService->verifyDocument($document->pendaftaran, [
            'status'     => $request->validated('status'),
            'keterangan' => $request->validated('keterangan'),
        ]);
        return jsonSuccess('Status dokumen berhasil diperbarui!');
    }
}
