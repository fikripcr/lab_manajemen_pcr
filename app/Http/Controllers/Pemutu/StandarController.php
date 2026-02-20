<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\IndikatorRequest;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\LabelType;
use App\Models\Pemutu\OrgUnit;
use App\Services\Pemutu\IndikatorService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StandarController extends Controller
{
    protected $IndikatorService;

    public function __construct(IndikatorService $IndikatorService)
    {
        $this->IndikatorService = $IndikatorService;
    }

    public function index()
    {
        $dokumens = Dokumen::whereNull('parent_id')
            ->where('jenis', 'standar')
            ->orderBy('judul')
            ->pluck('judul', 'dok_id')
            ->toArray();

        return view('pages.pemutu.standar.index', compact('dokumens'));
    }

    public function paginate(Request $request)
    {
        $filters         = $request->all();
        $filters['type'] = 'standar';

        $query = $this->IndikatorService->getFilteredQuery($filters)->with(['orgUnits']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('doksub_judul', function ($row) {
                return $row->dokSubs->map(function ($ds) {
                    return $ds->judul;
                })->implode(', ') ?: '-';
            })
            ->editColumn('indikator', function ($row) {
                $html = '<div class="font-weight-medium">' . e($row->indikator) . '</div>';

                if ($row->orgUnits->isNotEmpty()) {
                    $html .= '<div class="mt-1">';
                    foreach ($row->orgUnits as $unit) {
                        $html .= '<span class="badge bg-blue-lt me-1 mb-1">' . e($unit->name) . '</span>';
                    }
                    $html .= '</div>';
                }
                return $html;
            })
            ->addColumn('target_info', function ($row) {
                if ($row->target) {
                    return '<div class="font-weight-bold">' . e($row->target) . ' ' . e($row->unit_ukuran) . '</div>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function ($row) {
                $editUrl   = route('pemutu.indikators.edit', $row->indikator_id); // Reuse generic edit for now or create specific
                $assignUrl = route('pemutu.standar.assign', $row->indikator_id);
                $deleteUrl = route('pemutu.standar.destroy', $row->indikator_id);

                return '
                    <div class="btn-list flex-nowrap justify-content-end">
                        <a href="' . $assignUrl . '" class="btn btn-sm btn-icon btn-ghost-purple" title="Assign Unit">
                            <i class="ti ti-users"></i>
                        </a>
                        <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-ghost-primary" title="Edit">
                            <i class="ti ti-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-ghost-danger ajax-delete" data-url="' . $deleteUrl . '" data-title="Hapus Indikator?" data-text="Data ini akan dihapus permanen.">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['doksub_judul', 'indikator', 'target_info', 'action'])
            ->make(true);
    }

    public function create()
    {
        // View Dependencies
        $labelTypes = LabelType::with(['labels' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $orgUnits = OrgUnit::with('children')->where('level', 1)->orderBy('seq')->get();

        $dokumens = Dokumen::with('dokSubs')
            ->whereIn('jenis', ['standar'])
            ->orderBy('judul')
            ->get();

        // Pass 'isStandarMode' to hide/show specific fields in the generic view if we reuse it
        // Or create a dedicated view.
        return view('pages.pemutu.standar.create-edit-ajax', [
            'labelTypes'      => $labelTypes,
            'orgUnits'        => $orgUnits,
            'dokumens'        => $dokumens,
            'parents'         => [], // Standar usually doesn't have parent standar, but self-reference is possible (ignored for now)
            'personils'       => [],
            'selectedDokSubs' => [],
            'parentDok'       => null,
            'indikator'       => new Indikator(),
        ])->with('type', 'standar');
    }

    public function store(IndikatorRequest $request)
    {
        // For store, we can mostly reuse IndikatorController store or implement specific logic here.
        // To keep it properly separated as per plan, let's look at IndikatorController::store.
        // It delegates to IndikatorService.

        try {
            $data         = $request->validated();
            $data['type'] = 'standar'; // Force type

            // Handle Assignments Parsing (Org Units)
            if ($request->has('assignments')) {
                $syncData = [];
                foreach ($request->assignments as $unitId => $val) {
                    if (isset($val['selected']) && $val['selected'] == 1) {
                        $syncData[$unitId] = ['target' => $val['target'] ?? null];
                    }
                }
                $data['org_units'] = $syncData;
            }

            $this->IndikatorService->createIndikator($data);

            return jsonSuccess('Indikator Standar berhasil dibuat.', route('pemutu.standar.index'));

        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->IndikatorService->deleteIndikator($id);
            return jsonSuccess('Indikator Standar berhasil dihapus.', route('pemutu.standar.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function assign($id)
    {
        $indikator = $this->IndikatorService->getIndikatorById($id);
        if (! $indikator || $indikator->type !== 'standar') {
            abort(404);
        }

        $orgUnits = OrgUnit::with('children')->where('level', 1)->orderBy('seq')->get();

        // Get currently assigned units ids
        $assignedUnitIds = $indikator->orgUnits->pluck('orgunit_id')->toArray();
        $assignments     = $indikator->orgUnits->keyBy('orgunit_id');

        return view('pages.pemutu.standar.assign', compact('indikator', 'orgUnits', 'assignedUnitIds', 'assignments'));
    }

    public function storeAssignment(Request $request, $id)
    {
        try {
            $indikator = Indikator::findOrFail($id);

            $syncData = [];
            if ($request->has('assignments')) {
                foreach ($request->assignments as $unitId => $val) {
                    if (isset($val['selected']) && $val['selected'] == 1) {
                        $syncData[$unitId] = ['target' => $val['target'] ?? null];
                    }
                }
            }

            $indikator->orgUnits()->sync($syncData);

            return jsonSuccess('Penugasan Unit Kerja berhasil disimpan.', route('pemutu.standar.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
