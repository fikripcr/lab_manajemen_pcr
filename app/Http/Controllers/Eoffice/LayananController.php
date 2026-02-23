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
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class LayananController extends Controller
{
    public function __construct(protected LayananService $LayananService)
    {}

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
    /**
     * DataTables for Layanan
     */
    public function data(Request $request)
    {
        // Scope logic: if admin/pic show all or assigned, if user show mine
        $scope = Auth::user()->hasRole('admin') ? 'all' : 'user';

        $query = $this->LayananService->getFilteredQuery($request, $scope);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-';
            })
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
                return view('components.tabler.datatables-actions', [
                    'viewUrl' => route('eoffice.layanan.show', $row->hashid),
                ])->render();
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
        $services  = JenisLayanan::withCount(['layanans', 'isians', 'pics'])
            ->with(['pics.user'])
            ->where('is_active', true)
            ->orderBy('kategori')
            ->orderBy('nama_layanan')
            ->get();

        // Group by category
        $grouped = $services->groupBy('kategori');

        return view('pages.eoffice.layanan.services', compact('pageTitle', 'grouped'));
    }

    /**
     * Show dynamic submission form
     */
    public function create(JenisLayanan $jenisLayanan)
    {
        $pageTitle = 'Pengajuan: ' . $jenisLayanan->nama_layanan;

        return view('pages.eoffice.layanan.create-edit-ajax', compact('pageTitle', 'jenisLayanan'));
    }

    /**
     * Store request
     */
    public function store(LayananStoreRequest $request)
    {
        try {
            $validated    = $request->validated();
            $jenisLayanan = JenisLayanan::with('isians.kategoriIsian')->findOrFail($validated['jenislayanan_id']);

            $data          = $request->only(['jenislayanan_id', 'keterangan']);
            $dynamicFields = [];

            // Handle dynamic fields from the request
            foreach ($jenisLayanan->isians as $item) {
                $field     = $item->kategoriIsian;
                
                // Skip if kategoriIsian is not loaded (defensive programming)
                if (!$field) {
                    \Log::warning("Kategori Isian not found for JL Isian ID: {$item->jlisian_id}");
                    continue;
                }
                
                $fieldName = 'field_' . $field->kategoriisian_id;

                if ($request->has($fieldName)) {
                    $value = $request->input($fieldName);

                    // Handle file upload
                    if ($field->type === 'file' && $request->hasFile($fieldName)) {
                        $file = $request->file($fieldName);
                        
                        // Validate file size (max 2MB)
                        if ($file->getSize() > 2 * 1024 * 1024) {
                            return jsonError('File ' . $field->nama_isian . ' terlalu besar (maks 2MB)');
                        }
                        
                        $path  = $file->store('eoffice/requests/' . date('Y/m'), 'public');
                        $value = $path;
                    }

                    $dynamicFields[$field->nama_isian] = $value;
                } elseif ($item->is_required) {
                    // Required field is missing
                    return jsonError('Field "' . $field->nama_isian . '" wajib diisi.');
                }
            }

            $this->LayananService->createLayanan($data, $dynamicFields);
            return jsonSuccess('Pengajuan berhasil dikirim.', route('eoffice.layanan.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal mengirim pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Show detail
     */
    public function show(Layanan $layanan)
    {
        $layanan->load([
            'jenisLayanan.isians.kategoriIsian',
            'jenisLayanan.disposisis',
            'jenisLayanan.pics.user',
            'statuses.user',
            'isians',
            'latestStatus.user',
            'diskusi.user',
            'keterlibatan.user',
        ]);

        $pageTitle = 'Detail Pengajuan: ' . $layanan->no_layanan;

        // Group isian by fill_by logic
        // FIX: Map definitions to answers because LayananIsian table doesn't have 'fill_by'
        $definitions = $layanan->jenisLayanan->isians->sortBy('seq');
        $answers     = $layanan->isians->keyBy('nama_isian');

        $dataIsian = [
            'Pemohon'     => collect(),
            'Disposisi 1' => collect(),
            'Disposisi 2' => collect(),
            'Sistem'      => collect(),
        ];

        foreach ($definitions as $def) {
            // Safe access to fill_by with default fallback
            $fillBy = $def->fill_by ?? 'Pemohon';
            if (! isset($dataIsian[$fillBy])) {
                $fillBy = 'Pemohon';
            }

            // Safe access to kategoriIsian
            $kategoriIsian = $def->kategoriIsian;
            if (!$kategoriIsian) {
                \Log::warning("Kategori Isian not found for JL Isian ID: {$def->jlisian_id}");
                continue;
            }

            $ans = $answers->get($kategoriIsian->nama_isian);

            // Construct object for view
            $obj = (object) [
                'nama_isian' => $kategoriIsian->nama_isian,
                'isi'        => $ans ? $ans->isi : '-',
                'type'       => $kategoriIsian->type ?? 'text',
            ];

            $dataIsian[$fillBy]->push($obj);
        }

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
        $currentSeq     = $layanan->latestStatus?->seq ?? 0;
        $disposisiChain = $layanan->jenisLayanan->disposisis->sortBy('seq');
        $nextDisposisi  = $disposisiChain->where('seq', '>', $currentSeq)->first();

        return view('pages.eoffice.layanan.show', compact('pageTitle', 'layanan', 'dataIsian', 'canAction', 'nextDisposisi'));
    }

    public function updateStatus(LayananStatusUpdateRequest $request, Layanan $layanan)
    {
        $data = $request->only(['status_layanan', 'keterangan']);

        if ($request->hasFile('file_lampiran')) {
            $data['file_lampiran'] = $request->file('file_lampiran')->store('eoffice/status_attachments', 'public');
        }

        try {
            $this->LayananService->updateStatus($layanan->layanan_id, $data);
            return jsonSuccess('Status berhasil dirubah.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal merubah status: ' . $e->getMessage());
        }
    }

    /**
     * Download PDF with QR Code
     */
    public function downloadPdf(Layanan $layanan)
    {
        $layanan->load([
            'jenisLayanan.isians.kategoriIsian',
            'jenisLayanan.disposisis',
            'statuses.user',
            'isians.isian',
            'latestStatus.user',
        ]);

        // Generate QR Code base64
        $renderer = new GDLibRenderer(400);
        $writer   = new Writer($renderer);
        $pngData  = $writer->writeString(route('eoffice.layanan.show', $layanan->hashid));
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
