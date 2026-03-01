<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\IndikatorRequest;
use App\Http\Requests\Pemutu\StandarAssignmentRequest;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\LabelType;
use App\Models\Pemutu\OrgUnit;
use App\Services\Pemutu\IndikatorService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StandarController extends Controller
{
    public function __construct(protected IndikatorService $indikatorService)
    {}

    public function index()
    {
        $dokumens = Dokumen::whereNull('parent_id')
            ->where('jenis', 'standar')
            ->orderBy('judul')
            ->pluck('judul', 'dok_id')
            ->toArray();

        return view('pages.pemutu.standar.index', compact('dokumens'));
    }

    public function data(Request $request)
    {
        $filters         = $request->all();
        $filters['type'] = 'standar';

        $query = $this->indikatorService->getFilteredQuery($filters)->with(['orgUnits']);

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
                return view('components.tabler.datatables-actions', [
                    'editUrl'       => route('pemutu.indikators.edit', $row->encrypted_indikator_id),
                    'editModal'     => false,
                    'deleteUrl'     => route('pemutu.standar.destroy', $row->encrypted_indikator_id),
                    'customActions' => [
                        [
                            'url'   => route('pemutu.standar.assign', $row->encrypted_indikator_id),
                            'icon'  => 'ti ti-users',
                            'title' => 'Assign Unit',
                            'class' => 'btn-ghost-purple',
                        ],
                    ],
                ])->render();
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
        $data         = $request->validated();
        $data['type'] = 'standar'; // Force type

        // Handle Assignments Parsing (Org Units)
        if ($request->has('assignments')) {
            $syncData = [];
            foreach ($request->assignments as $unitId => $val) {
                if (isset($val['selected']) && $val['selected'] == 1) {
                    $syncData[decryptIdIfEncrypted($unitId)] = ['target' => $val['target'] ?? null];
                }
            }
            $data['org_units'] = $syncData;
        }

        if (isset($data['doksub_ids'])) {
            $data['doksub_ids'] = array_map('decryptIdIfEncrypted', $data['doksub_ids']);
        }
        if (isset($data['labels'])) {
            $data['labels'] = array_map('decryptIdIfEncrypted', $data['labels']);
        }

        $this->indikatorService->createIndikator($data);

        return jsonSuccess('Indikator Standar berhasil dibuat.', route('pemutu.standar.index'));
    }

    public function destroy(Indikator $indikator)
    {
        $this->indikatorService->deleteIndikator($indikator->indikator_id);

        return jsonSuccess('Indikator Standar berhasil dihapus.', route('pemutu.standar.index'));
    }

    public function assign(Indikator $indikator)
    {
        $orgUnits = OrgUnit::with('children')->where('level', 1)->orderBy('seq')->get();

        // Get currently assigned units ids
        $assignedUnitIds = $indikator->orgUnits->pluck('orgunit_id')->toArray();
        $assignments     = $indikator->orgUnits->keyBy('orgunit_id');

        return view('pages.pemutu.standar.assign', compact('indikator', 'orgUnits', 'assignedUnitIds', 'assignments'));
    }

    public function storeAssignment(StandarAssignmentRequest $request, Indikator $indikator)
    {
        $validated = $request->validated();
        $syncData  = [];
        if (isset($validated['assignments'])) {
            foreach ($validated['assignments'] as $unitId => $val) {
                if (isset($val['selected']) && $val['selected'] == 1) {
                    $syncData[decryptIdIfEncrypted($unitId)] = ['target' => $val['target'] ?? null];
                }
            }
        }

        $indikator->orgUnits()->sync($syncData);

        logActivity('pemutu', "Memperbarui penugasan unit kerja untuk indikator: {$indikator->no_indikator}");

        return jsonSuccess('Penugasan Unit Kerja berhasil disimpan.', route('pemutu.standar.index'));
    }
}
