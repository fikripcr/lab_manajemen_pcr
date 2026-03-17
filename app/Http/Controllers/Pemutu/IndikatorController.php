<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\IndikatorRequest;
use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Hr\StrukturOrganisasi;
use App\Models\Hr\Pegawai;
use App\Services\Pemutu\DokumenService;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\PelaksanaanService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class IndikatorController extends Controller
{
    public function __construct(
        protected IndikatorService $indikatorService,
        protected PelaksanaanService $PelaksanaanService,
        protected DokumenService $dokumenService,
        protected PeriodeSpmiService $PeriodeSpmiService
    ) {}

    public function index(Request $request)
    {
        // Allowed types
        $types      = ['standar' => 'Standar', 'performa' => 'Performa'];
        $activeType = $request->query('type', 'standar');
        if (! array_key_exists($activeType, $types)) {
            $activeType = 'standar';
        }

        $siklus = $this->PeriodeSpmiService->getSiklusData();

        // Filters data
        $siklusData = $this->PeriodeSpmiService->getSiklusData();
        $dokumens   = $this->dokumenService->getStandardDocumentsByYear($siklusData['tahun']);
        $labelOptions = \App\Models\Pemutu\Label::with('children')->whereNull('parent_id')->orderBy('name')->get();
        $labelParents = [];
        foreach ($labelOptions as $label) {
            if ($label->children->count() > 0) {
                foreach ($label->children->sortBy('name') as $child) {
                    $labelParents[$child->encrypted_label_id] = $label->name . ' - ' . $child->name;
                }
            } else {
                $labelParents[$label->encrypted_label_id] = $label->name;
            }
        }
        $renstraOptions = \App\Models\Pemutu\DokSub::whereHas('dokumen', function ($q) use ($siklusData) {
            $q->where('jenis', 'renstra')->where('periode', 'like', '%' . $siklusData['tahun'] . '%');
        })->with('dokumen')->get()->mapWithKeys(function ($item) {
            return [$item->encrypted_doksub_id => '[' . ($item->dokumen?->periode ?? 'RENSTRA') . '] ' . $item->judul];
        })->toArray();

        return view('pages.pemutu.indikator.index', compact('dokumens', 'labelParents', 'types', 'activeType', 'siklus', 'renstraOptions'));
    }

    public function data(Request $request)
    {
        $query = $this->indikatorService->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('indikator', function ($row) {
                return '[' . $row->no_indikator . '] ' . $row->indikator;
            })
            ->addColumn('dokumen_judul', function ($row) {
                $html = '';
                foreach ($row->dokSubs as $ds) {
                    if ($ds->dokumen) {
                        $url = route('pemutu.dokumen.index', [
                            'jenis' => $ds->dokumen->jenis,
                            'id'    => $ds->dokumen->encrypted_dok_id,
                            'type'  => $ds->dokumen->jenis,
                        ]);
                        $html .= '<a href="' . $url . '" class="d-block mb-1 text-inherit">' . e($ds->dokumen->judul) . '</a>';
                    }
                }
                return $html ?: '-';
            })
            ->addColumn('doksub_judul', function ($row) {
                return $row->dokSubs->pluck('judul')->implode(', ') ?: '-';
            })
            ->addColumn('kelompok_indikator', function ($row) {
                $color = $row->kelompok_indikator == 'Akademik' ? 'green' : 'orange';
                return '<span class="badge bg-' . $color . '-lt text-' . $color . '">' . e($row->kelompok_indikator) . '</span>';
            })
            ->addColumn('jenis_data', function ($row) {
                $color = $row->jenis_data == 'Kualitatif' ? 'blue' : 'purple';
                return '<span class="badge bg-' . $color . '-lt text-' . $color . '">' . e($row->jenis_data) . '</span>';
            })
            ->addColumn('renstra_poin', function ($row) {
                if ($row->renstraPoin) {
                    $url = route('pemutu.dokumen.index', [
                        'jenis' => $row->renstraPoin->dokumen?->jenis ?? 'renstra',
                        'id'    => $row->renstraPoin->encrypted_doksub_id,
                        'type'  => 'doksub',
                    ]);
                    $text = ($row->renstraPoin->dokumen?->judul ?? 'Renstra') . ': ' . $row->renstraPoin->judul;
                    return '<a href="' . $url . '" class="text-inherit">' . e($text) . '</a>';
                }
                return '-';
            })
            ->addColumn('labels', function ($row) {
                return '<div class="d-flex flex-wrap gap-1">' . $row->labels->map(function ($label) {
                    $color = $label->color ?? 'blue';
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
            ->rawColumns(['tipe', 'labels', 'action', 'renstra_poin', 'dokumen_judul', 'kelompok_indikator', 'jenis_data'])
            ->make(true);
    }

    public function create(Request $request)
    {
        // View Dependencies
        $labelParents = \App\Models\Pemutu\Label::whereNull('parent_id')->with(['children' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $orgUnits = StrukturOrganisasi::with('descendants')->where('level', 1)->orderBy('seq')->get();
        $parents  = Indikator::where('type', 'standar')->orderBy('no_indikator')->get();

        $pegawais = Pegawai::with('latestDataDiri')->get()->sortBy('nama');

        $isRenopContext  = $request->get('is_renop_context') == 1;
        $parentDok       = null;
        $selectedDokSubs = [];

        $siklus = $this->PeriodeSpmiService->getSiklusData();
        $tahun = $siklus['tahun'];

        $standardOptions = DokSub::whereHas('dokumen', function ($q) use ($tahun) {
            $q->where('jenis', 'standar')->where('periode', 'like', '%' . $tahun . '%');
        })->with('dokumen')->get();

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

                if ($parentDok && strtolower(trim($parentDok->jenis)) === 'renop') {
                    $isRenopContext = true;
                }
            }
        }

        // Identify Target Year for filtering Renstra Points
        $targetYear = null;
        if ($parentDok && $parentDok->periode) {
            if (preg_match('/\b(20\d{2})\b/', $parentDok->periode, $matches)) {
                $targetYear = $matches[1];
            }
        } elseif (! empty($selectedDokSubs)) {
            $ds = $selectedDokSubs->first();
            if ($ds && $ds->dokumen && $ds->dokumen->periode) {
                if (preg_match('/\b(20\d{2})\b/', $ds->dokumen->periode, $matches)) {
                    $targetYear = $matches[1];
                }
            }
        }

        $renstraOptions = DokSub::whereHas('dokumen', function ($q) use ($targetYear) {
            $q->where('jenis', 'renstra');
            if ($targetYear) {
                $q->where('periode', 'like', '%' . $targetYear . '%');
            }
        })->with('dokumen')->get();

        $indikator = new Indikator(); // Empty for create
        return view('pages.pemutu.indikator.create-edit', compact(
            'labelParents', 'orgUnits', 'parents', 'pegawais',
            'parentDok', 'selectedDokSubs', 'indikator', 'isRenopContext', 'renstraOptions', 'standardOptions'
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
                    $id = decryptIdIfEncrypted($unitId);
                    if ($id) {
                        $syncData[$id] = ['target' => $val['target'] ?? null];
                    }
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
            $ids = is_array($data['doksub_ids']) ? $data['doksub_ids'] : [$data['doksub_ids']];
            $data['doksub_ids'] = array_filter(array_map('decryptIdIfEncrypted', $ids));
        }
        if (isset($data['labels'])) {
            $data['labels'] = array_filter(array_map('decryptIdIfEncrypted', $data['labels']));
        }
        if (isset($data['parent_id'])) {
            $data['parent_id'] = decryptIdIfEncrypted($data['parent_id']);
        }
        if (isset($data['renstra_poin_id'])) {
            $data['renstra_poin_id'] = decryptIdIfEncrypted($data['renstra_poin_id']);
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
        $labelParents = \App\Models\Pemutu\Label::whereNull('parent_id')->with(['children' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $orgUnits = StrukturOrganisasi::with('descendants')->where('level', 1)->orderBy('seq')->get();

        $parents = Indikator::where('type', 'standar')
            ->where('indikator_id', '!=', $indikator->indikator_id)
            ->orderBy('no_indikator')
            ->get();

        $pegawais = Pegawai::with('latestDataDiri')->get()->sortBy('nama');

        // Identify Target Year for filtering Renstra Points
        $selectedDokSubs = $indikator->dokSubs()->with('dokumen')->get();
        $targetYear      = null;
        foreach ($selectedDokSubs as $ds) {
            if ($ds->dokumen && $ds->dokumen->periode) {
                if (preg_match('/\b(20\d{2})\b/', $ds->dokumen->periode, $matches)) {
                    $targetYear = $matches[1];
                    break;
                }
            }
        }

        $siklus = $this->PeriodeSpmiService->getSiklusData();
        $tahun = $siklus['tahun'];

        $standardOptions = DokSub::whereHas('dokumen', function ($q) use ($tahun) {
            $q->where('jenis', 'standar')->where('periode', 'like', '%' . $tahun . '%');
        })->with('dokumen')->get();

        $renstraOptions = DokSub::whereHas('dokumen', function ($q) use ($tahun) {
            $q->where('jenis', 'renstra')->where('periode', 'like', '%' . $tahun . '%');
        })->with('dokumen')->get();
        return view('pages.pemutu.indikator.create-edit', compact('indikator', 'labelParents', 'orgUnits', 'parents', 'pegawais', 'renstraOptions', 'selectedDokSubs', 'standardOptions'));
    }

    public function update(IndikatorRequest $request, Indikator $indikator)
    {
        $data = $request->validated();

        // Handle Assignments Parsing
        if ($request->has('assignments')) {
            $syncData = [];
            foreach ($request->assignments as $unitId => $val) {
                if (isset($val['selected']) && $val['selected'] == 1) {
                    $id = decryptIdIfEncrypted($unitId);
                    if ($id) {
                        $syncData[$id] = ['target' => $val['target'] ?? null];
                    }
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
            $ids = is_array($data['doksub_ids']) ? $data['doksub_ids'] : [$data['doksub_ids']];
            $data['doksub_ids'] = array_filter(array_map('decryptIdIfEncrypted', $ids));
        }
        if (isset($data['labels'])) {
            $data['labels'] = array_filter(array_map('decryptIdIfEncrypted', $data['labels']));
        }
        if (isset($data['parent_id'])) {
            $data['parent_id'] = decryptIdIfEncrypted($data['parent_id']);
        }
        if (isset($data['renstra_poin_id'])) {
            $data['renstra_poin_id'] = decryptIdIfEncrypted($data['renstra_poin_id']);
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
