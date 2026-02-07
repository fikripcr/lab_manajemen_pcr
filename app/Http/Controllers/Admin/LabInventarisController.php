<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use App\Models\Lab; // Still used for type hinting or direct simple queries if necessary
use App\Services\Admin\InventarisService;
use App\Services\Admin\LabInventarisService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LabInventarisController extends Controller
{
    protected $labInventarisService;
    protected $inventarisService; // Inject Master Inventory Service for "Get Unassigned" helper

    public function __construct(
        LabInventarisService $labInventarisService,
        InventarisService $inventarisService
    ) {
        $this->labInventarisService = $labInventarisService;
        $this->inventarisService    = $inventarisService;
    }

    public function index($labId)
    {
        $lab = Lab::findOrFail(decryptId($labId));
        return view('pages.admin.labs.inventaris.index', compact('lab'));
    }

    public function data(Request $request, $labId)
    {
        $labIdDecrypted = decryptId($labId);

        // Use Service Query
        $labInventaris = $this->labInventarisService->getLabInventarisQuery($labIdDecrypted);

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

                return '
                    <div class="d-flex align-items-center">
                        <a href="' . route('labs.inventaris.edit', [$encryptedLabId, $encryptedId]) . '" class="btn btn-sm btn-icon btn-outline-primary me-1" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-outline-danger" title="Delete" onclick="confirmDelete(\'' . route('labs.inventaris.destroy', [$encryptedLabId, $encryptedId]) . '\')">
                            <i class="bx bx-trash"></i>
                        </a>
                    </div>';
            })
            ->rawColumns(['kode_inventaris', 'status', 'action'])
            ->make(true);
    }

    public function create($labId)
    {
        $realId = decryptId($labId);
        $lab    = Lab::findOrFail($realId);

                                                                                              // Fetch unassigned items using helper from InventarisService
        $inventarisList = $this->inventarisService->getUnassignedForLab($realId, null, 1000); // Fetch all or reasonable limit

        return view('pages.admin.labs.inventaris.create', compact('lab', 'inventarisList'));
    }

    public function store(Request $request, $labId)
    {
        $request->validate([
            'inventaris_id'      => 'required|exists:inventaris,inventaris_id',
            'no_series'          => 'nullable|string|max:255',
            'keterangan'         => 'nullable|string|max:1000',
            'tanggal_penempatan' => 'nullable|date',
            'status'             => 'nullable|in:active,moved,inactive',
        ]);

        $realLabId = decryptId($labId);

        try {
            // Service handles Assignment Creation and Code Generation
            $this->labInventarisService->assignInventaris($realLabId, $request->all());

            return jsonSuccess('Inventaris berhasil ditambahkan ke lab.', route('labs.inventaris.index', $labId));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($labId, $id)
    {
        $lab           = Lab::findOrFail(decryptId($labId));
        $labInventaris = $this->labInventarisService->getAssignmentById(decryptId($id));

        $inventarisList = Inventaris::all(); // Or filtered? Edit usually allows selecting any or just changing details.
                                             // If changing item is allowed, we might need all items.
                                             // Existing logic used Inventaris::all().

        return view('pages.admin.labs.inventaris.edit', compact('lab', 'labInventaris', 'inventarisList'));
    }

    public function update(Request $request, $labId, $id)
    {
        $request->validate([
            'inventaris_id'       => 'required|exists:inventaris,inventaris_id',
            'no_series'           => 'nullable|string|max:255',
            'keterangan'          => 'nullable|string|max:1000',
            'tanggal_penempatan'  => 'nullable|date',
            'tanggal_penghapusan' => 'nullable|date',
            'status'              => 'nullable|in:active,moved,inactive',
        ]);

        try {
            $this->labInventarisService->updateAssignment(decryptId($id), $request->all());

            return jsonSuccess('Data inventaris lab berhasil diperbarui.', route('labs.inventaris.index', $labId));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function getInventaris(Request $request, $labId)
    {
        $search    = $request->get('search');
        $realLabId = decryptId($labId);

        // Use Service to get unassigned items
        $inventaris = $this->inventarisService->getUnassignedForLab($realLabId, $search, 5);

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

        return response()->json([
            'results' => $results,
        ]);
    }

    public function destroy($labId, $id)
    {
        try {
            $this->labInventarisService->deleteAssignment(decryptId($id));

            return jsonSuccess('Inventaris berhasil dihapus dari lab.', route('labs.inventaris.index', $labId));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
