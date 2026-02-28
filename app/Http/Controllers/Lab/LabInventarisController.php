<?php
namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\LabInventarisRequest;
use App\Models\Lab\Inventaris;
use App\Models\Lab\Lab;
use App\Models\Lab\LabInventaris;
use App\Services\Lab\InventarisService;
use App\Services\Lab\LabInventarisService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LabInventarisController extends Controller
{
    public function __construct(
        protected LabInventarisService $labInventarisService,
        protected InventarisService $inventarisService
    ) {}

    public function index(Lab $lab)
    {
        $lab->load(['labInventaris.inventaris']);
        return view('pages.lab.labs.inventaris.index', compact('lab'));
    }

    public function data(Request $request, Lab $lab)
    {
        $labInventaris = $this->labInventarisService->getLabInventarisQuery($lab->lab_id);

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
                $statusClass = match ($item->status) {
                    'active'   => 'bg-label-success',
                    'moved'    => 'bg-label-warning',
                    'inactive' => 'bg-label-secondary',
                    default    => 'bg-label-secondary',
                };
                return '<span class="badge ' . $statusClass . '">' . ucfirst($item->status) . '</span>';
            })
            ->addColumn('nama_alat', function ($item) {
                return $item->inventaris->nama_alat;
            })
            ->addColumn('jenis_alat', function ($item) {
                return $item->inventaris->jenis_alat;
            })
            ->addColumn('action', function ($item) use ($lab) {
                return view('components.tabler.datatables-actions', [
                    'editUrl'   => route('lab.labs.inventaris.edit', [$lab->encrypted_lab_id, $item->encrypted_id]),
                    'editModal' => true,
                    'deleteUrl' => route('lab.labs.inventaris.destroy', [$lab->encrypted_lab_id, $item->encrypted_id]),
                ])->render();
            })
            ->rawColumns(['kode_inventaris', 'status', 'action'])
            ->make(true);
    }

    public function create(Lab $lab)
    {
        $inventarisList = $this->inventarisService->getUnassignedForLab($lab->lab_id);
        $labInventaris  = new LabInventaris();
        return view('pages.lab.labs.inventaris.create-edit-ajax', compact('lab', 'inventarisList', 'labInventaris'));
    }

    public function store(LabInventarisRequest $request, Lab $lab)
    {
        $this->labInventarisService->assignInventaris($lab->lab_id, $request->all());
        return jsonSuccess('Inventaris berhasil ditambahkan ke lab.', route('lab.labs.inventaris.index', $lab->encrypted_lab_id));
    }

    public function edit(Lab $lab, LabInventaris $inventarisLab)
    {
        $labInventaris  = $inventarisLab;
        $inventarisList = Inventaris::all();
        return view('pages.lab.labs.inventaris.create-edit-ajax', compact('lab', 'labInventaris', 'inventarisList'));
    }

    public function update(LabInventarisRequest $request, Lab $lab, LabInventaris $inventarisLab)
    {
        $this->labInventarisService->updateAssignment($inventarisLab, $request->validated());
        return jsonSuccess('Data inventaris lab berhasil diperbarui.', route('lab.labs.inventaris.index', $lab->encrypted_lab_id));
    }

    public function destroy(Lab $lab, LabInventaris $inventarisLab)
    {
        $this->labInventarisService->deleteAssignment($inventarisLab);
        return jsonSuccess('Inventaris berhasil dihapus dari lab.', route('lab.labs.inventaris.index', $lab->encrypted_lab_id));
    }

    public function getInventaris(Request $request, Lab $lab)
    {
        $search     = $request->get('search');
        $inventaris = $this->inventarisService->getUnassignedForLab($lab->lab_id, $search, 10);

        $results = $inventaris->map(fn($item) => [
            'id'   => $item->inventaris_id,
            'text' => $item->nama_alat . ' (' . $item->jenis_alat . ')',
        ]);

        return jsonSuccess('Data retrieved', null, ['results' => $results]);
    }
}
