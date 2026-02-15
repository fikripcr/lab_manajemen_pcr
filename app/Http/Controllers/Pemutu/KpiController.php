<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\IndikatorRequest;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\OrgUnit;
use App\Models\Pemutu\PeriodeKpi;
use App\Models\Pemutu\Personil;
use App\Services\Pemutu\IndikatorService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KpiController extends Controller
{
    protected $IndikatorService;

    public function __construct(IndikatorService $IndikatorService)
    {
        $this->IndikatorService = $IndikatorService;
    }

    public function index()
    {
        // Filters for the view
        $parents  = Indikator::where('type', 'standar')->orderBy('no_indikator')->pluck('indikator', 'indikator_id')->toArray();
        $dokumens = Dokumen::whereNull('parent_id')
            ->where('jenis', 'standar')
            ->orderBy('judul')
            ->pluck('judul', 'dok_id')
            ->toArray();

        return view('pages.pemutu.kpi.index', compact('parents', 'dokumens'));
    }

    public function paginate(Request $request)
    {
        // Force type 'performa' for this controller
        $filters         = $request->all();
        $filters['type'] = 'performa';

        $query = $this->IndikatorService->getFilteredQuery($filters)->with(['orgUnits']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('parent_info', function ($row) {
                if ($row->parent) {
                    return '<div class="text-muted small">Induk: ' . $row->parent->no_indikator . '</div>
                            <div class="text-truncate" style="max-width: 300px;" title="' . e($row->parent->indikator) . '">' . e($row->parent->indikator) . '</div>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('target_info', function ($row) {
                $html = '';
                if ($row->target) {
                    $html .= '<div class="font-weight-bold">' . e($row->target) . ' ' . e($row->unit_ukuran) . '</div>';
                }

                $count = $row->personils->count();
                if ($count > 0) {
                    $html .= '<div class="mt-1"><span class="badge bg-purple-lt cursor-help" title="Ditugaskan ke ' . $count . ' orang">' . $count . ' Pegawai</span></div>';
                }

                return $html ?: '<span class="text-muted">-</span>';
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
            ->addColumn('action', function ($row) {
                $editUrl   = route('pemutu.kpi.edit', $row->indikator_id);
                $assignUrl = route('pemutu.kpi.assign', $row->indikator_id);
                $deleteUrl = route('pemutu.kpi.destroy', $row->indikator_id);

                return '
                    <div class="btn-list flex-nowrap justify-content-end">
                        <a href="' . $assignUrl . '" class="btn btn-sm btn-icon btn-ghost-purple" title="Assign Personnel">
                            <i class="ti ti-users"></i>
                        </a>
                        <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-ghost-primary" title="Edit">
                            <i class="ti ti-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-ghost-danger ajax-delete" data-url="' . $deleteUrl . '" data-title="Hapus Sasaran Kinerja?" data-text="Data ini akan dihapus permanen.">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['parent_info', 'target_info', 'indikator', 'action'])
            ->make(true);
    }

    public function create()
    {
        // Data for the form
        $parents = Indikator::where('type', 'standar')
            ->orderBy('no_indikator')
            ->get();

        $orgUnits = OrgUnit::active()->orderBy('name')->pluck('name', 'orgunit_id')->toArray();

        return view('pages.pemutu.kpi.create', compact('parents', 'orgUnits'));
    }

    public function store(Request $request)
    {
        try {
            $type     = 'performa';
            $parentId = $request->input('parent_id');

            if (! $parentId) {
                return jsonError('Parent Indikator wajib dipilih.', 422);
            }

            $items = $request->input('items', []);
            if (empty($items)) {
                return jsonError('Minimal satu sasaran kinerja harus diisi.', 422);
            }

            DB::beginTransaction();

            foreach ($items as $item) {
                if (empty($item['indikator'])) {
                    continue;
                }

                $data = [
                    'type'         => $type,
                    'parent_id'    => $parentId,
                    'indikator'    => $item['indikator'],
                    'target'       => $item['target'] ?? null,
                    'unit_ukuran'  => $item['unit_ukuran'] ?? null,
                    'keterangan'   => $item['keterangan'] ?? null,
                    'no_indikator' => $item['no_indikator'] ?? $this->generateNoIndikator($parentId),
                    'org_units'    => $item['org_unit_ids'] ?? [],
                ];

                // Inherit doksub_ids from parent
                $parent = Indikator::with('dokSubs')->find($parentId);
                if ($parent && $parent->dokSubs->isNotEmpty()) {
                    $data['doksub_ids'] = $parent->dokSubs->pluck('doksub_id')->toArray();
                }

                $this->IndikatorService->createIndikator($data);
            }

            DB::commit();

            return jsonSuccess('Sasaran Kinerja berhasil dibuat.', route('pemutu.kpi.index'));

        } catch (Exception $e) {
            DB::rollBack();
            return jsonError($e->getMessage(), 500);
        }
    }

    private function generateNoIndikator($parentId)
    {
        $parent = Indikator::find($parentId);
        if (! $parent) {
            return null;
        }

        $prefix = $parent->no_indikator;
        $count  = Indikator::where('parent_id', $parentId)->count();

        return $prefix . '.' . ($count + 1);
    }

    public function edit($id)
    {
        $indikator = $this->IndikatorService->getIndikatorById($id);
        if (! $indikator || $indikator->type !== 'performa') {
            abort(404);
        }

        $parents = Indikator::where('type', 'standar')
            ->where('indikator_id', '!=', $id)
            ->orderBy('no_indikator')
            ->get();

        return view('pages.pemutu.kpi.edit', compact('indikator', 'parents'));
    }

    public function update(IndikatorRequest $request, $id)
    {
        try {
            $data         = $request->validated();
            $data['type'] = 'performa';

            // Auto-assign doksub_ids from parent if not present
            if (! empty($data['parent_id']) && empty($data['doksub_ids'])) {
                $parent = Indikator::with('dokSubs')->find($data['parent_id']);
                if ($parent && $parent->dokSubs->isNotEmpty()) {
                    $data['doksub_ids'] = $parent->dokSubs->pluck('doksub_id')->toArray();
                }
            }

            $this->IndikatorService->updateIndikator($id, $data);

            return jsonSuccess('Sasaran Kinerja berhasil diperbarui.', route('pemutu.kpi.index'));

        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->IndikatorService->deleteIndikator($id);
            return jsonSuccess('Sasaran Kinerja berhasil dihapus.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function assign($id)
    {
        $indikator = $this->IndikatorService->getIndikatorById($id);
        if (! $indikator || $indikator->type !== 'performa') {
            abort(404);
        }

        $orgUnits = OrgUnit::with('children')->where('level', 1)->orderBy('seq')->get();

        // Fetch all personils with their current assignment to this indicator if any
        $personils = Personil::with(['orgUnit', 'user'])
            ->orderBy('nama')
            ->get();

        $assignedPersonilIds = $indikator->personils->pluck('personil_id')->toArray();
        $assignments         = $indikator->personils->keyBy('personil_id');

        $activePeriode = PeriodeKpi::where('is_active', true)->first();

        return view('pages.pemutu.kpi.assign', compact('indikator', 'orgUnits', 'personils', 'assignedPersonilIds', 'assignments', 'activePeriode'));
    }

    public function storeAssignment(Request $request, $id)
    {
        try {
            $indikator = Indikator::findOrFail($id);

            $kpiData = [];
            if ($request->has('kpi_assign')) {
                foreach ($request->kpi_assign as $val) {
                    if (isset($val['selected']) && $val['selected'] == 1) {
                        unset($val['selected']);
                        $kpiData[] = $val;
                    }
                }
            }

            \DB::transaction(function () use ($indikator, $kpiData) {
                $indikator->personils()->delete();
                foreach ($kpiData as $assign) {
                    $indikator->personils()->create($assign);
                }
            });

            return jsonSuccess('Penugasan personil berhasil disimpan.', route('pemutu.kpi.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
