<?php
namespace App\Http\Controllers\Eoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Eoffice\LayananStatusUpdateRequest;
use App\Http\Requests\Eoffice\LayananStoreRequest;
use App\Models\Eoffice\JenisLayanan;
use App\Models\Eoffice\Layanan;
use App\Services\Eoffice\LayananService;
use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class LayananController extends Controller
{
    protected $layananService;

    public function __construct(LayananService $layananService)
    {
        $this->layananService = $layananService;
    }

    /**
     * List of requests (For User or Admin/PIC)
     */
    public function index()
    {
        $pageTitle     = 'Daftar Pengajuan Layanan';
        $jenisLayanans = JenisLayanan::where('is_active', true)->orderBy('nama_layanan')->get();
        return view('pages.eoffice.layanan.index', compact('pageTitle', 'jenisLayanans'));
    }

    /**
     * DataTables for Layanan
     */
    public function data(Request $request)
    {
        // Scope logic: if admin/pic show all or assigned, if user show mine
        $scope = Auth::user()->hasRole('admin') ? 'all' : 'user';

        $query = $this->layananService->getFilteredQuery($request, $scope);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status_label', function ($row) {
                $status = $row->latestStatus->status_layanan ?? 'Menunggu';
                $class  = match ($status) {
                    'Diajukan' => 'bg-blue-lt',
                    'Diproses' => 'bg-yellow-lt',
                    'Selesai'  => 'bg-green-lt',
                    'Ditolak'  => 'bg-red-lt',
                    'Direvisi' => 'bg-orange-lt',
                    default    => 'bg-secondary-lt'
                };
                return '<span class="badge ' . $class . '">' . $status . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<a href="' . route('eoffice.layanan.show', $row->layanan_id) . '" class="btn btn-sm btn-outline-primary">
                            <i class="ti ti-eye"></i> Detail
                        </a>';
            })
            ->rawColumns(['status_label', 'action'])
            ->make(true);
    }

    /**
     * Show list of available services
     */
    public function services()
    {
        $pageTitle = 'Pilih Jenis Layanan';
        $services  = JenisLayanan::where('is_active', true)->get();

        // Group by category
        $grouped = $services->groupBy('kategori');

        return view('pages.eoffice.layanan.services', compact('pageTitle', 'grouped'));
    }

    /**
     * Show dynamic submission form
     */
    public function create($id)
    {
        $realId       = decryptId($id);
        $jenisLayanan = JenisLayanan::with('isians.kategoriIsian')->findOrFail($realId);
        $pageTitle    = 'Pengajuan: ' . $jenisLayanan->nama_layanan;

        return view('pages.eoffice.layanan.create', compact('pageTitle', 'jenisLayanan'));
    }

    /**
     * Store request
     */
    public function store(LayananStoreRequest $request)
    {
        $validated = $request->validated();
        $jenisLayanan = JenisLayanan::with('isians.kategoriIsian')->findOrFail($validated['jenislayanan_id']);

        $data = $request->only(['jenislayanan_id', 'keterangan']);
        $dynamicFields = [];

        // Handle dynamic fields from the request
        foreach ($jenisLayanan->isians as $item) {
            $field = $item->kategoriIsian;
            $fieldName = 'field_' . $field->kategoriisian_id;

            if ($request->has($fieldName)) {
                $value = $request->input($fieldName);

                // Handle file upload
                if ($field->type === 'file' && $request->hasFile($fieldName)) {
                    $file = $request->file($fieldName);
                    $path = $file->store('eoffice/requests/' . date('Y/m'), 'public');
                    $value = $path;
                }

                $dynamicFields[$field->nama_isian] = $value;
            }
        }

        try {
            $this->layananService->createLayanan($data, $dynamicFields);
            return jsonSuccess('Pengajuan berhasil dikirim.', route('eoffice.layanan.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Show detail
     */
    public function show($id)
    {
        $realId  = decryptId($id);
        $layanan = Layanan::with([
            'jenisLayanan.isians.kategoriIsian',
            'jenisLayanan.disposisis',
            'jenisLayanan.pics.user',
            'statuses.user',
            'isians',
            'latestStatus.user',
            'diskusi.user',
            'keterlibatan.user',
        ])->findOrFail($realId);

        $pageTitle = 'Detail Pengajuan: ' . $layanan->no_layanan;

        // Group isian by fill_by logic
        $dataIsian = [
            'Pemohon'     => $layanan->isians->where('fill_by', 'Pemohon'),
            'Disposisi 1' => $layanan->isians->where('fill_by', 'Disposisi 1'),
            'Disposisi 2' => $layanan->isians->where('fill_by', 'Disposisi 2'),
            'Sistem'      => $layanan->isians->where('fill_by', 'Sistem'),
        ];

        // Determine if current user can take action
        $user      = Auth::user();
        $canAction = false;
        $nextStep  = null;

        // Basic permission check (simplified for now)
        if ($user->hasRole('admin')) {
            $canAction = true;
        } else {
            // Check if user is PIC of this service
            $isPic = $layanan->jenisLayanan->pics->where('user_id', $user->id)->count() > 0;
            if ($isPic) {
                $canAction = true;
            }
        }

        // Determine next disposition from the chain
        $currentSeq     = $layanan->latestStatus->seq ?? 0;
        $disposisiChain = $layanan->jenisLayanan->disposisis->sortBy('seq');
        $nextDisposisi  = $disposisiChain->where('seq', '>', $currentSeq)->first();

        // Check if current user is the target of current disposition
        // (This would require more complex logic matching position/jabatan)

        return view('pages.eoffice.layanan.show', compact('pageTitle', 'layanan', 'dataIsian', 'canAction', 'nextDisposisi'));
    }

    /**
     * Update status (Disposition)
     */
    public function updateStatus(LayananStatusUpdateRequest $request, $id)
    {
        $data = $request->only(['status_layanan', 'keterangan']);

        if ($request->hasFile('file_lampiran')) {
            $data['file_lampiran'] = $request->file('file_lampiran')->store('eoffice/status_attachments', 'public');
        }

        try {
            $realId = decryptId($id);
            $this->layananService->updateStatus($realId, $data);
            return jsonSuccess('Status berhasil dirubah.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Download PDF with QR Code
     */
    public function downloadPdf($id)
    {
        $realId  = decryptId($id);
        $layanan = Layanan::with([
            'jenisLayanan.isians.kategoriIsian',
            'jenisLayanan.disposisis',
            'statuses.user',
            'isians.isian',
            'latestStatus.user',
        ])->findOrFail($realId);

        // Generate QR Code base64
        $renderer = new GDLibRenderer(400);
        $writer   = new Writer($renderer);
        $pngData  = $writer->writeString(route('eoffice.layanan.show', $id));
        $qrcode   = base64_encode($pngData);

        $data = [
            'layanan' => $layanan,
            'qrcode'  => $qrcode,
            'title'   => 'Bukti Pengajuan Layanan - ' . $layanan->no_layanan,
        ];

        $pdf = Pdf::loadView('pages.eoffice.layanan.pdf', $data);

        return $pdf->download('E-Office-' . $layanan->no_layanan . '.pdf');
    }
}
