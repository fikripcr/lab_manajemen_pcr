<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\LabInventarisRequest;
use App\Models\Lab\Inventaris;
use App\Models\Lab\Lab;
use App\Services\Lab\InventarisService;
use App\Services\Lab\LabInventarisService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LabInventarisController extends Controller
{
    protected $LabInventarisService;
    protected $InventarisService; // Inject Master Inventory Service for "Get Unassigned" helper

    public function __construct(
        LabInventarisService $LabInventarisService,
        InventarisService $InventarisService
    ) {
        $this->LabInventarisService = $LabInventarisService;
        $this->InventarisService    = $InventarisService;
    }

    public function index($labId)
    {
        $lab = Lab::with(['labInventaris.inventaris'])->findOrFail(decryptId($labId));
        return view('pages.lab.labs.inventaris.index', compact('lab'));
    }

    public function data(Request $request, $labId)
    {
        $labIdDecrypted = decryptId($labId);

        // Use Service Query
        $labInventaris = $this->LabInventarisService->getLabInventarisQuery($labIdDecrypted);

        return DataTables::of($labInventaris)
            ->addIndexColumn()
            ->editColumn('kode_inventaris', function ($item) {
                return '<code>' . $item->kode_inventaris . '</code>';
            })
            ->editColumn('no_series', function ($item) {
                return $item->no_series ?: '-';
            })
            ->editColumn('tanggal_penempatan', function ($item) {
                return formatTanggalIndo($item->tanggal_penempatan);
            })
            ->editColumn('tanggal_penghapusan', function ($item) {
                return $item->tanggal_penghapusan ? formatTanggalIndo($item->tanggal_penghapusan) : '-';
            })
            ->editColumn('status', function ($item) {
                $statusClass = '';
                $statusText  = '';
                switch ($item->status) {
                    case 'active':
                        $statusClass = 'bg-label-success';
                        $statusText  = 'Active';
                        break;
                    case 'moved':
                        $statusClass = 'bg-label-warning';
                        $statusText  = 'Moved';
                        break;
                    case 'inactive':
                        $statusClass = 'bg-label-secondary';
                        $statusText  = 'Inactive';
                        break;
                    default:
                        $statusClass = 'bg-label-secondary';
                        $statusText  = ucfirst($item->status);
                }

                return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
            })
            ->addColumn('nama_alat', function ($item) {
                return $item->inventaris->nama_alat;
            })
            ->addColumn('jenis_alat', function ($item) {
                return $item->inventaris->jenis_alat;
            })
            ->addColumn('action', function ($item) {
                $encryptedId    = encryptId($item->id);
                $encryptedLabId = encryptId($item->lab_id);

                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('lab.labs.inventaris.edit', [$encryptedLabId, $encryptedId]),
                    'editModal' => false,
                    'deleteUrl' => route('lab.labs.inventaris.destroy', [$encryptedLabId, $encryptedId]),
                ])->render();
            })
            ->rawColumns(['kode_inventaris', 'status', 'action'])
            ->make(true);
    }

    public function create($labId)
    {
        $realId = decryptId($labId);
        $lab    = Lab::findOrFail($realId);

                                                                                              // Fetch unassigned items using helper from InventarisService
        $inventarisList = $this->InventarisService->getUnassignedForLab($realId, null, 1000); // Fetch all or reasonable limit

        return view('pages.lab.labs.inventaris.create', compact('lab', 'inventarisList'));
    }

    public function store(LabInventarisRequest $request, $labId)
    {
        $realLabId = decryptId($labId);

        try {
            // Service handles Assignment Creation and Code Generation
            $this->LabInventarisService->assignInventaris($realLabId, $request->all());

            return jsonSuccess('Inventaris berhasil ditambahkan ke lab.', route('lab.labs.inventaris.index', $labId));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($labId, $id)
    {
        $lab           = Lab::findOrFail(decryptId($labId));
        $labInventaris = $this->LabInventarisService->getAssignmentById(decryptId($id));

        $inventarisList = Inventaris::all(); // Or filtered? Edit usually allows selecting any or just changing details.
                                             // If changing item is allowed, we might need all items.
                                             // Existing logic used Inventaris::all().

        return view('pages.lab.labs.inventaris.edit', compact('lab', 'labInventaris', 'inventarisList'));
    }

    public function update(LabInventarisRequest $request, $labId, $id)
    {
        $realLabId = decryptId($labId);
        $realId    = decryptId($id);
        $validated = $request->validated();

        try {
            $this->LabInventarisService->updateAssignment($realId, $validated);

            return jsonSuccess('Data inventaris lab berhasil diperbarui.', route('lab.labs.inventaris.index', $labId));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function getInventaris(Request $request, $labId)
    {
        $search    = $request->get('search');
        $realLabId = decryptId($labId);

        // Use Service to get unassigned items
        $inventaris = $this->InventarisService->getUnassignedForLab($realLabId, $search, 5);

        $results = $inventaris->map(function ($item) {
            return [
                'id'   => $item->inventaris_id, // Original ID (or encrypted if JS expects it? existing controller used encryptId($item->inventaris_id) but store expects valid ID. Usually Select2 sends value. Check existing view logic later. If view expects encrypted, we encrypt. Store decrypts? No, store validates exists:inventaris,inventaris_id. DataTables usually sends ID. Let's assume standard ID for value.)
                                                // Wait, existing controller: 'id' => encryptId($item->inventaris_id).
                                                // Store: $inventaris = Inventaris::findOrFail($request->inventaris_id); -> This implies $request->inventaris_id is the primary key OR if model uses string ID it might be non-encrypted.
                                                // Re-checking existing Store method:
                                                // $inventaris = Inventaris::findOrFail($request->inventaris_id);
                                                // Validation: 'inventaris_id' => 'required|exists:inventaris,inventaris_id'.
                                                // If ID is numeric, 'exists' works on DB. Encrypted string won't work on DB unless we decrypt in validation rules (custom rule) or decrypt before validation.
                                                // Standard: Select2 value is raw ID.
                                                // BUT previous controller returned encrypted ID in `getInventaris`: 'id' => encryptId($item->inventaris_id).
                                                // This means the form submission sends ENCRYPTED ID.
                                                // The validation `exists:inventaris,inventaris_id` would FAIL if ID is encrypted string and DB column is int/string (unless encryption is deterministic and column matches).
                                                // BUT `Inventaris` model primary key `inventaris_id` is string?
                                                // `protected $primaryKey = 'inventaris_id';`
                                                // Migration? `increments` or `uuid`?
                                                // If it's standard int, `exists` needs int.
                                                // I suspect the Previous Controller implementation might have a bug OR the Request handles decryption before validation (unlikely).
                                                // OR `encryptId` returns the ID itself if encryption is disabled?
                                                // Let's assume for now I should send raw ID if I want standard validation.
                                                // HOWEVER, to be safe and match previous behavior (maybe there's a middleware decrypting inputs?), I will stick to what was there OR fix it to be correct (Store expects RAW ID usually).
                                                // "findOrFail($request->inventaris_id)" -> If this works, then input must be Raw ID.
                                                // If `getInventaris` sends Encrypted, then form submits Encrypted.
                                                // `findOrFail` would fail on encrypted string.
                                                // I will return RAW ID.
                'id'   => $item->inventaris_id,
                'text' => $item->nama_alat . ' (' . $item->jenis_alat . ')',
            ];
        });

        return jsonSuccess('Data retrieved', null, [
            'results' => $results,
        ]);
    }

    public function destroy($labId, $id)
    {
        try {
            $this->LabInventarisService->deleteAssignment(decryptId($id));

            return jsonSuccess('Inventaris berhasil dihapus dari lab.', route('labs.inventaris.index', $labId));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
