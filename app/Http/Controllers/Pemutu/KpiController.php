<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\IndikatorRequest;
use App\Http\Requests\Pemutu\KpiRequest;
use App\Models\Pemutu\Indikator;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\KpiService;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class KpiController extends Controller
{
    public function __construct(
        protected IndikatorService $indikatorService,
        protected KpiService $kpiService
    ) {}

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

        $query = $this->indikatorService->getFilteredQuery($filters)->with(['orgUnits']);

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

                $count = $row->pegawai->count(); // Use relationship standardized earlier
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
                return view('components.tabler.datatables-actions', [
                    'editUrl'       => route('pemutu.kpi.edit', $row->encrypted_indikator_id),
                    'editModal'     => true,
                    'deleteUrl'     => route('pemutu.kpi.destroy', $row->encrypted_indikator_id),
                    'customActions' => [
                        [
                            'url'   => route('pemutu.kpi.assign', $row->encrypted_indikator_id),
                            'label' => 'Assign Personnel',
                            'icon'  => 'ti ti-users',
                            'class' => '',
                        ],
                    ],
                ])->render();
            })
            ->rawColumns(['parent_info', 'target_info', 'indikator', 'action'])
            ->make(true);
    }

    public function create()
    {
        // Data for the form
        $parents = \App\Models\Pemutu\Indikator::where('type', 'standar')
            ->orderBy('no_indikator')
            ->get();

        $orgUnits = \App\Models\Pemutu\OrgUnit::active()->orderBy('name')->pluck('name', 'orgunit_id')->toArray();

        return view('pages.pemutu.kpi.create', compact('parents', 'orgUnits'));
    }

    public function store(KpiRequest $request)
    {
        try {
            $parentId = decryptIdIfEncrypted($request->input('parent_id'));
            $items    = $request->input('items', []);

            $this->kpiService->bulkCreateKpi($parentId, $items);

            return jsonSuccess('Sasaran Kinerja berhasil dibuat.', route('pemutu.kpi.index'));

        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan sasaran kinerja: ' . $e->getMessage());
        }
    }

    public function edit(Indikator $kpi)
    {
        if ($kpi->type !== 'performa') {
            abort(404);
        }

        $parents = \App\Models\Pemutu\Indikator::where('type', 'standar')
            ->where('indikator_id', '!=', $kpi->indikator_id)
            ->orderBy('no_indikator')
            ->get();

        $model = $kpi;

        return view('pages.pemutu.kpi.create-edit-ajax', compact('model', 'parents'));
    }

    public function update(IndikatorRequest $request, Indikator $kpi)
    {
        try {
            $data         = $request->validated();
            $data['type'] = 'performa';

            // Auto-assign doksub_ids from parent if not present
            if (! empty($data['parent_id']) && empty($data['doksub_ids'])) {
                $parent = \App\Models\Pemutu\Indikator::with('dokSubs')->find($data['parent_id']);
                if ($parent && $parent->dokSubs->isNotEmpty()) {
                    $data['doksub_ids'] = $parent->dokSubs->pluck('doksub_id')->toArray();
                }
            }

            $this->indikatorService->updateIndikator($kpi->indikator_id, $data);

            return jsonSuccess('Sasaran Kinerja berhasil diperbarui.', route('pemutu.kpi.index'));

        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui sasaran kinerja: ' . $e->getMessage());
        }
    }

    public function destroy(Indikator $kpi)
    {
        try {
            $this->indikatorService->deleteIndikator($kpi->indikator_id);

            return jsonSuccess('Sasaran Kinerja berhasil dihapus.');
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus sasaran kinerja: ' . $e->getMessage());
        }
    }

    public function assign(\App\Models\Pemutu\Indikator $kpi)
    {
        if ($kpi->type !== 'performa') {
            abort(404);
        }

        $orgUnits = \App\Models\Pemutu\OrgUnit::with('children')->where('level', 1)->orderBy('seq')->get();

        // Fetch all personils with their current assignment to this indicator if any
        $personils = \App\Models\Pemutu\Personil::with(['orgUnit', 'user'])
            ->orderBy('nama')
            ->get();

        $assignedPersonilIds = $kpi->personils->pluck('personil_id')->toArray();
        $assignments         = $kpi->personils->keyBy('personil_id');

        $activePeriode = $this->kpiService->getActivePeriode();

        return view('pages.pemutu.kpi.assign', [
            'indikator'           => $kpi,
            'orgUnits'            => $orgUnits,
            'personils'           => $personils,
            'assignedPersonilIds' => $assignedPersonilIds,
            'assignments'         => $assignments,
            'activePeriode'       => $activePeriode,
        ]);
    }

    public function storeAssignment(KpiRequest $request, Indikator $kpi)
    {
        try {
            $kpiData = [];
            foreach ($request->kpi_assign as $val) {
                if (isset($val['selected']) && $val['selected'] == 1) {
                    unset($val['selected']);
                    $kpiData[] = $val;
                }
            }

            $this->kpiService->storeAssignments($kpi, $kpiData);

            logActivity('pemutu', "Menyimpan penugasan personil untuk KPI: {$kpi->indikator}");

            return jsonSuccess('Penugasan personil berhasil disimpan.', route('pemutu.kpi.index'));
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal menyimpan penugasan: ' . $e->getMessage());
        }
    }
}
