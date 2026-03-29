<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\IndikatorRequest;
use App\Http\Requests\Pemutu\StandarAssignmentRequest;
use App\Models\Hr\StrukturOrganisasi;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\LabelType;
use App\Services\Pemutu\IndikatorService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StandarController extends Controller
{
    public function __construct(
        protected IndikatorService $indikatorService,
        protected \App\Services\Hr\StrukturOrganisasiService $StrukturOrganisasiService
    ) {}

    public function index()
    {
        $dokumens = Dokumen::whereNull('parent_id')
            ->where('jenis', 'standar')
            ->orderBy('judul')
            ->pluck('judul', 'dok_id')
            ->toArray();

        $units = $this->StrukturOrganisasiService->getHierarchicalList();

        return view('pages.pemutu.standar.index', compact('dokumens', 'units'));
    }

    public function data(Request $request)
    {
        // SIMPLE LOGIC: If not 'all', add to filters
        $filters = [];
        foreach ($request->only(['dokumen_id', 'kelompok_indikator', 'label_ids']) as $key => $value) {
            if (!empty($value) && $value !== 'all') {
                $filters[$key] = $value;
            }
        }
        $filters['type'] = 'standar';

        $query = $this->indikatorService->getFilteredQuery($filters)->with(['orgUnits']);

        return DataTables::of($query)
            ->addColumn('no', function ($row) {
                return pemutuDtColNo($row);
            })
            ->addColumn('indikator', function ($row) {
                return pemutuDtColIndikator($row);
            })
            ->addColumn('doksub_judul', function ($row) {
                return $row->dokSubs->map(function ($ds) {
                    return $ds->judul;
                })->implode(', ') ?: '-';
            })
            ->addColumn('target_info', function ($row) {
                return pemutuDtColTarget($row);
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl' => route('pemutu.indikator.edit', $row->encrypted_indikator_id),
                    'editModal' => false,
                    'deleteUrl' => route('pemutu.standar.destroy', $row->encrypted_indikator_id),
                    'customActions' => [
                        [
                            'url' => route('pemutu.standar.assign', $row->encrypted_indikator_id),
                            'icon' => 'ti ti-users',
                            'title' => 'Assign Unit',
                            'class' => 'btn-ghost-purple',
                        ],
                    ],
                ])->render();
            })
            ->rawColumns(['indikator', 'target_info', 'action'])
            ->make(true);
    }

    public function create()
    {
        // View Dependencies
        $labelTypes = LabelType::with(['labels' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $orgUnits = StrukturOrganisasi::with('children')->where('level', 1)->orderBy('seq')->get();

        $dokumens = Dokumen::with('dokSubs')
            ->whereIn('jenis', ['standar'])
            ->orderBy('judul')
            ->get();

        // Pass 'isStandarMode' to hide/show specific fields in the generic view if we reuse it
        // Or create a dedicated view.
        return view('pages.pemutu.standar.create-edit-ajax', [
            'labelTypes' => $labelTypes,
            'orgUnits' => $orgUnits,
            'dokumens' => $dokumens,
            'parents' => [], // Standar usually doesn't have parent standar, but self-reference is possible (ignored for now)
            'personils' => [],
            'selectedDokSubs' => [],
            'parentDok' => null,
            'indikator' => new Indikator,
        ])->with('type', 'standar');
    }

    public function store(IndikatorRequest $request)
    {
        $data = $request->validated();
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
        $orgUnits = StrukturOrganisasi::with('children')->where('level', 1)->orderBy('seq')->get();

        // Get currently assigned units ids
        $assignedUnitIds = $indikator->orgUnits->pluck('orgunit_id')->toArray();
        $assignments = $indikator->orgUnits->keyBy('orgunit_id');

        return view('pages.pemutu.standar.assign', compact('indikator', 'orgUnits', 'assignedUnitIds', 'assignments'));
    }

    public function storeAssignment(StandarAssignmentRequest $request, Indikator $indikator)
    {
        $validated = $request->validated();
        $syncData = [];
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
