<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\IndikatorRequest;
use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\LabelType;
use App\Models\Pemutu\OrgUnit;
use App\Models\Shared\Pegawai;
use App\Services\Pemutu\DokumenService;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\PelaksanaanService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class IndikatorController extends Controller
{
    public function __construct(
        protected IndikatorService $indikatorService,
        protected PelaksanaanService $PelaksanaanService,
        protected DokumenService $dokumenService
    ) {}

    public function index(Request $request)
    {
        // Allowed types
        $types      = ['standar' => 'Standar', 'renop' => 'Renop', 'performa' => 'Performa'];
        $activeType = $request->query('type', 'standar');
        if (! array_key_exists($activeType, $types)) {
            $activeType = 'standar';
        }

        // Filters data
        $dokumens   = Dokumen::whereNull('parent_id')->orderBy('judul')->pluck('judul', 'dok_id')->toArray();
        $labelTypes = LabelType::orderBy('name')->pluck('name', 'labeltype_id')->toArray();

        // Year filter from Dokumen.periode
        $periodes = Dokumen::select('periode')
            ->distinct()
            ->whereNotNull('periode')
            ->orderBy('periode', 'desc')
            ->pluck('periode', 'periode')
            ->toArray();

        if (empty($periodes)) {
            $periodes[date('Y')] = date('Y');
        }

        return view('pages.pemutu.indikator.index', compact('dokumens', 'labelTypes', 'types', 'periodes', 'activeType'));
    }

    public function data(Request $request)
    {
        $query = $this->indikatorService->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('dokumen_judul', function ($row) {
                return $row->dokSubs->map(function ($ds) {
                    return $ds->dokumen?->judul ?? '-';
                })->unique()->implode(', ') ?: '-';
            })
            ->addColumn('tipe', function ($row) {
                $typeInfo = pemutuIndikatorTypeInfo($row->type);
                $html     = '<span class="badge bg-' . ($typeInfo['color'] ?? 'secondary') . '-lt" title="' . ($typeInfo['label'] ?? '-') . '">' . ($typeInfo['short-label'] ?? 'IND') . '</span>';

                // If Performa, show Parent Code
                if ($row->type === 'performa' && $row->parent) {
                    $html .= '<div class="mt-1"><span class="badge bg-primary-lt" title="Indikator Induk">Ref: ' . e($row->parent?->no_indikator ?? '-') . '</span></div>';
                }

                return $html;
            })
            ->addColumn('doksub_judul', function ($row) {
                return $row->dokSubs->pluck('judul')->implode(', ') ?: '-';
            })
            ->addColumn('labels', function ($row) {
                return '<div class="d-flex flex-wrap gap-1">' . $row->labels->map(function ($label) {
                    $color = $label->type?->color ?? 'blue';
                    return '<span class="badge bg-' . $color . '-lt text-' . $color . '">' . e($label->name) . '</span>';
                })->implode('') . '</div>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'viewUrl'   => route('pemutu.indikator.show', $row->encrypted_indikator_id),
                    'editUrl'   => route('pemutu.indikator.edit', $row->encrypted_indikator_id),
                    'editModal' => false,
                    'deleteUrl' => route('pemutu.indikator.destroy', $row->encrypted_indikator_id),
                ])->render();
            })
            ->rawColumns(['tipe', 'labels', 'action'])
            ->make(true);
    }

    public function create(Request $request)
    {
        // View Dependencies
        $labelTypes = LabelType::with(['labels' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $orgUnits = OrgUnit::with('descendants')->where('level', 1)->orderBy('seq')->get();
        $parents  = Indikator::where('type', 'standar')->orderBy('no_indikator')->get();

        $pegawais = Pegawai::with('latestDataDiri')->get()->sortBy('nama');

        $isRenopContext  = $request->get('is_renop_context') == 1;
        $parentDok       = null;
        $selectedDokSubs = [];

        if ($request->has('parent_dok_id') || $request->has('parent_doksub_id')) {
            $parentDokId = $request->get('parent_dok_id') ? decryptIdIfEncrypted($request->get('parent_dok_id')) : null;
            $parentDok   = $parentDokId ? Dokumen::with('dokSubs')->find($parentDokId) : null;

            if ($request->has('parent_doksub_id')) {
                $doksubId        = decryptIdIfEncrypted($request->get('parent_doksub_id'));
                $selectedDokSubs = DokSub::with('dokumen')->where('doksub_id', $doksubId)->get();

                if (! $parentDok) {
                    $ds        = $selectedDokSubs->first();
                    $parentDok = $ds ? $ds->dokumen : null;
                }
            } elseif ($parentDok) {
                // For standar dokumens, auto-select all their dokSubs.
                // For renop, dokSubs are NOT auto-selected at document level —
                // the user must enter from a specific Poin (which passes parent_doksub_id).
                if (strtolower(trim($parentDok->jenis)) !== 'renop') {
                    $selectedDokSubs = $parentDok->dokSubs;
                }
            }

            if ($parentDok || ! empty($selectedDokSubs)) {
                $suggestedType = ($parentDok && strtolower(trim($parentDok->jenis)) === 'standar') ? 'standar' : 'renop';

                $request->merge([
                    'type'       => $request->get('type', $suggestedType),
                    'doksub_ids' => $request->get('doksub_ids', $selectedDokSubs->pluck('encrypted_doksub_id')->toArray()),
                ]);

                // Ensure is_renop_context is set if the parent is renop
                if ($parentDok && strtolower(trim($parentDok->jenis)) === 'renop') {
                    $isRenopContext = true;
                }
            }
        }

        $indikator = new Indikator(); // Empty for create
        return view('pages.pemutu.indikator.create-edit-ajax', compact(
            'labelTypes', 'orgUnits', 'parents', 'pegawais',
            'parentDok', 'selectedDokSubs', 'indikator', 'isRenopContext'
        ));
    }

    public function store(IndikatorRequest $request)
    {
        $data = $request->validated();

        // Handle Assignments Parsing
        if ($request->has('assignments')) {
            $syncData = [];
            foreach ($request->assignments as $unitId => $val) {
                if (isset($val['selected']) && $val['selected'] == 1) {
                    $syncData[decryptIdIfEncrypted($unitId)] = ['target' => $val['target'] ?? null];
                }
            }
            $data['org_units'] = $syncData;
        }

        // Handle KPI Assignments Parsing
        if ($request->has('kpi_assign')) {
            $kpiData = [];
            foreach ($request->kpi_assign as $val) {
                if (isset($val['selected']) && $val['selected'] == 1 && ! empty($val['pegawai_id'])) {
                    $pegawaiIds = is_array($val['pegawai_id']) ? $val['pegawai_id'] : [$val['pegawai_id']];
                    unset($val['selected']);
                    unset($val['sasaran']);
                    unset($val['keterangan']);

                    foreach ($pegawaiIds as $pId) {
                        $newVal               = $val;
                        $newVal['pegawai_id'] = decryptIdIfEncrypted($pId);
                        $kpiData[]            = $newVal;
                    }
                }
            }
            $data['kpi_assignments'] = $kpiData;
        }

        if (isset($data['doksub_ids'])) {
            $data['doksub_ids'] = array_map('decryptIdIfEncrypted', $data['doksub_ids']);
        }
        if (isset($data['labels'])) {
            $data['labels'] = array_map('decryptIdIfEncrypted', $data['labels']);
        }
        if (isset($data['parent_id'])) {
            $data['parent_id'] = decryptIdIfEncrypted($data['parent_id']);
        }

        $indikator = $this->indikatorService->createIndikator($data);

        logActivity('pemutu', "Menambah indikator baru: {$indikator->no_indikator}");

        $redirectUrl = $request->get('redirect_to', route('pemutu.indikator.index'));
        if (! $request->has('redirect_to') && $request->has('parent_dok_id')) {
            $redirectUrl = route('pemutu.dokumen.show-renop-with-indicators', $request->parent_dok_id);
        }

        return jsonSuccess('Indikator created successfully.', $redirectUrl);
    }

    public function show(Indikator $indikator)
    {
        // Fetch monitorings for related org units
        $monitorings = collect();
        foreach ($indikator->orgUnits as $orgUnit) {
            $indOrg = \App\Models\Pemutu\IndikatorOrgUnit::find($orgUnit->pivot->indikorgunit_id);
            if ($indOrg) {
                $mon         = $this->PelaksanaanService->getMonitoringForIndikator($indOrg);
                $monitorings = $monitorings->merge($mon);
            }
        }
        $monitorings = $monitorings->unique('rapat_id');

        return view('pages.pemutu.indikator.show', compact('indikator', 'monitorings'));
    }

    public function edit(Indikator $indikator)
    {
        $labelTypes = LabelType::with(['labels' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $orgUnits = OrgUnit::with('descendants')->where('level', 1)->orderBy('seq')->get();

        $parents = Indikator::where('type', 'standar')
            ->where('indikator_id', '!=', $indikator->indikator_id)
            ->orderBy('no_indikator')
            ->get();

        $pegawais = Pegawai::with('latestDataDiri')->get()->sortBy('nama');
        // Fetch only and only currently assigned DokSubs to avoid memory heavy eager loading
        $selectedDokSubs = $indikator->dokSubs()->with('dokumen')->get();
        return view('pages.pemutu.indikator.create-edit-ajax', compact('indikator', 'labelTypes', 'orgUnits', 'parents', 'pegawais', 'selectedDokSubs'));
    }

    public function update(IndikatorRequest $request, Indikator $indikator)
    {
        $data = $request->validated();

        // Handle Assignments Parsing
        if ($request->has('assignments')) {
            $syncData = [];
            foreach ($request->assignments as $unitId => $val) {
                if (isset($val['selected']) && $val['selected'] == 1) {
                    $syncData[decryptIdIfEncrypted($unitId)] = ['target' => $val['target'] ?? null];
                }
            }
            $data['org_units'] = $syncData;
        }

        if ($request->has('kpi_assign')) {
            $kpiData = [];
            foreach ($request->kpi_assign as $val) {
                if (isset($val['selected']) && $val['selected'] == 1 && ! empty($val['pegawai_id'])) {
                    $pegawaiIds = is_array($val['pegawai_id']) ? $val['pegawai_id'] : [$val['pegawai_id']];
                    unset($val['selected']);
                    unset($val['sasaran']);
                    unset($val['keterangan']);

                    foreach ($pegawaiIds as $pId) {
                        $newVal               = $val;
                        $newVal['pegawai_id'] = decryptIdIfEncrypted($pId);
                        $kpiData[]            = $newVal;
                    }
                }
            }
            $data['kpi_assignments'] = $kpiData;
        }

        if (isset($data['doksub_ids'])) {
            $data['doksub_ids'] = array_map('decryptIdIfEncrypted', $data['doksub_ids']);
        }
        if (isset($data['labels'])) {
            $data['labels'] = array_map('decryptIdIfEncrypted', $data['labels']);
        }
        if (isset($data['parent_id'])) {
            $data['parent_id'] = decryptIdIfEncrypted($data['parent_id']);
        }

        $this->indikatorService->updateIndikator($indikator->indikator_id, $data);

        logActivity('pemutu', "Memperbarui indikator: {$indikator->no_indikator}");

        $redirectUrl = $request->get('redirect_to', route('pemutu.indikator.show', $indikator->encrypted_indikator_id));

        return jsonSuccess('Indikator updated successfully.', $redirectUrl);
    }

    public function destroy(Indikator $indikator)
    {
        $noIndikator = $indikator->no_indikator;
        $this->indikatorService->deleteIndikator($indikator->indikator_id);

        logActivity('pemutu', "Menghapus indikator: {$noIndikator}");

        return jsonSuccess('Indikator deleted successfully.', route('pemutu.indikator.index'));
    }

    /**
     * Search DokSub for Select2 AJAX
     */
    public function searchDoksub(Request $request)
    {
        $query   = $request->get('q');
        $items   = $this->dokumenService->searchDokSub($query);
        $results = $this->dokumenService->formatForSelect2($items);

        return response()->json($results);
    }
}
