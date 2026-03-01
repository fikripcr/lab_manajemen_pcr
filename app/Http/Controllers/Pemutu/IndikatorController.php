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
use App\Services\Pemutu\IndikatorService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class IndikatorController extends Controller
{
    public function __construct(protected IndikatorService $indikatorService)
    {}

    public function index()
    {
        // Filters data
        $dokumens   = Dokumen::whereNull('parent_id')->orderBy('judul')->pluck('judul', 'dok_id')->toArray();
        $labelTypes = LabelType::orderBy('name')->pluck('name', 'labeltype_id')->toArray();
        $types      = ['standar' => 'Standar', 'renop' => 'Renop', 'performa' => 'Performa'];

        return view('pages.pemutu.indikators.index', compact('dokumens', 'labelTypes', 'types'));
    }

    public function data(Request $request)
    {
        $query = $this->indikatorService->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('dokumen_judul', function ($row) {
                return $row->dokSubs->map(function ($ds) {
                    return $ds->dokumen->judul ?? '-';
                })->unique()->implode(', ') ?: '-';
            })
            ->addColumn('tipe', function ($row) {
                $typeInfo = pemutuIndikatorTypeInfo($row->type);
                $html     = '<span class="badge bg-' . $typeInfo['color'] . '-lt" title="' . $typeInfo['label'] . '">' . $typeInfo['short-label'] . '</span>';

                // If Performa, show Parent Code
                if ($row->type === 'performa' && $row->parent) {
                    $html .= '<div class="mt-1"><span class="badge bg-primary-lt" title="Indikator Induk">Ref: ' . e($row->parent->kode ?? '-') . '</span></div>';
                }

                return $html;
            })
            ->addColumn('doksub_judul', function ($row) {
                return $row->dokSubs->pluck('judul')->implode(', ') ?: '-';
            })
            ->addColumn('labels', function ($row) {
                return '<div class="d-flex flex-wrap gap-1">' . $row->labels->map(function ($label) {
                    $color = $label->type->color ?? 'blue';
                    return '<span class="badge bg-' . $color . '-lt text-' . $color . '">' . e($label->name) . '</span>';
                })->implode('') . '</div>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'viewUrl'   => route('pemutu.indikators.show', $row->encrypted_indikator_id),
                    'editUrl'   => route('pemutu.indikators.edit', $row->encrypted_indikator_id),
                    'editModal' => false,
                    'deleteUrl' => route('pemutu.indikators.destroy', $row->encrypted_indikator_id),
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

        $orgUnits = OrgUnit::with('children')->where('level', 1)->orderBy('seq')->get();

        $dokumens = Dokumen::with('dokSubs')
            ->whereIn('jenis', ['renstra', 'renop', 'standar'])
            ->orderBy('judul')
            ->get();

        $parents = Indikator::where('type', 'standar')->orderBy('no_indikator')->get();

        $pegawais = Pegawai::with('latestDataDiri')->get()->sortBy('nama');

        $isRenopContext  = $request->get('is_renop_context') == 1;
        $parentDok       = null;
        $selectedDokSubs = [];

        if ($request->has('parent_dok_id') || $request->has('parent_doksub_id')) {
            $parentDokId = $request->get('parent_dok_id') ? decryptIdIfEncrypted($request->get('parent_dok_id')) : null;
            $parentDok   = $parentDokId ? Dokumen::with('dokSubs')->find($parentDokId) : null;

            if ($request->has('parent_doksub_id')) {
                $selectedDokSubs = [$request->get('parent_doksub_id')];
                $doksubId        = decryptIdIfEncrypted($request->get('parent_doksub_id'));

                if (! $parentDok) {
                    $ds        = DokSub::find($doksubId);
                    $parentDok = $ds ? $ds->dokumen : null;
                }
            } elseif ($parentDok) {
                // For standar dokumens, auto-select all their dokSubs.
                // For renop, dokSubs are NOT auto-selected at document level —
                // the user must enter from a specific Poin (which passes parent_doksub_id).
                if (strtolower(trim($parentDok->jenis)) !== 'renop') {
                    $selectedDokSubs = $parentDok->dokSubs->map(fn($ds) => $ds->encrypted_doksub_id)->toArray();
                }
            }

            if ($parentDok || ! empty($selectedDokSubs)) {
                $suggestedType = ($parentDok && strtolower(trim($parentDok->jenis)) === 'standar') ? 'standar' : 'renop';

                $request->merge([
                    'type'       => $request->get('type', $suggestedType),
                    'doksub_ids' => $request->get('doksub_ids', $selectedDokSubs),
                ]);

                // Ensure is_renop_context is set if the parent is renop
                if ($parentDok && strtolower(trim($parentDok->jenis)) === 'renop') {
                    $isRenopContext = true;
                }
            }
        }

        $indikator = new Indikator(); // Empty for create
        return view('pages.pemutu.indikators.create-edit-ajax', compact(
            'labelTypes', 'orgUnits', 'dokumens', 'parents', 'pegawais',
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

        $redirectUrl = $request->get('redirect_to', route('pemutu.indikators.index'));
        if (! $request->has('redirect_to') && $request->has('parent_dok_id')) {
            $redirectUrl = route('pemutu.dokumens.show-renop-with-indicators', $request->parent_dok_id);
        }

        return jsonSuccess('Indikator created successfully.', $redirectUrl);
    }

    public function show(Indikator $indikator)
    {
        return view('pages.pemutu.indikators.show', compact('indikator'));
    }

    public function edit(Indikator $indikator)
    {
        $labelTypes = LabelType::with(['labels' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $orgUnits = OrgUnit::with('children')->where('level', 1)->orderBy('seq')->get();

        $dokumens = Dokumen::with('dokSubs')
            ->whereIn('jenis', ['renstra', 'renop', 'standar'])
            ->orderBy('judul')
            ->get();

        $parents = Indikator::where('type', 'standar')
            ->where('indikator_id', '!=', $indikator->indikator_id)
            ->orderBy('no_indikator')
            ->get();

        $pegawais = Pegawai::with('latestDataDiri')->get()->sortBy('nama');

        return view('pages.pemutu.indikators.create-edit-ajax', compact('indikator', 'labelTypes', 'orgUnits', 'dokumens', 'parents', 'pegawais'));
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

        $redirectUrl = $request->get('redirect_to', route('pemutu.indikators.show', $indikator->encrypted_indikator_id));

        return jsonSuccess('Indikator updated successfully.', $redirectUrl);
    }

    public function destroy(Indikator $indikator)
    {
        $noIndikator = $indikator->no_indikator;
        $this->indikatorService->deleteIndikator($indikator->indikator_id);

        logActivity('pemutu', "Menghapus indikator: {$noIndikator}");

        return jsonSuccess('Indikator deleted successfully.', route('pemutu.indikators.index'));
    }
}
