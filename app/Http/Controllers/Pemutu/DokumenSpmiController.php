<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Services\Pemutu\DokumenService;
use App\Services\Pemutu\DokumenSpmiService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DokumenSpmiController extends Controller
{
    public function __construct(
        protected DokumenSpmiService $dokumenSpmiService,
        protected DokumenService $dokumenService
    ) {}

    /**
     * MAIN WORKSPACE PAGE
     */
    public function index(Request $request)
    {
        $pageTitle   = 'Workspace Dokumen SPMI';
        $activeJenis = $request->query('jenis', 'visi');

        $periods = Dokumen::select('periode')
            ->whereNotNull('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode');

        if ($periods->isEmpty()) {
            $periods->push(date('Y'));
        }

        // Default to the latest period if none provided
        $selectedPeriode = $request->periode ?: $periods->first();

        if (in_array($activeJenis, ['standar', 'formulir', 'manual_prosedur'])) {
            $activeTab       = 'standar';
            $dokumentByJenis = [];
            foreach (['standar', 'formulir', 'manual_prosedur'] as $j) {
                $dokumentByJenis[$j] = $this->dokumenSpmiService->getDokumenByJenis($j, $selectedPeriode);
            }
        } else {
            $activeTab = 'kebijakan';
            // Kebijakan: flat 5-document architecture
            $docsArr         = $this->dokumenService->getKebijakanByPeriode($selectedPeriode);
            $dokumentByJenis = [];
            foreach ($docsArr as $j => $doc) {
                // Ensure it's a collection so ->first() works in the specialized view
                $dokumentByJenis[$j] = $doc ? collect([$doc]) : collect();
            }
        }

        return view('pages.pemutu.dokumen.index', compact('pageTitle', 'dokumentByJenis', 'periods', 'activeTab', 'selectedPeriode', 'activeJenis'));
    }

    /**
     * SHOW DETAIL PANEL (AJAX)
     */
    public function show(Request $request, $type, $id)
    {
        if ($type === 'dokumen') {
            $item          = Dokumen::findOrFail(decryptIdIfEncrypted($id));
            $parentJenis   = strtolower(trim($item->jenis));
            $childLabel    = pemutuChildLabel($parentJenis);
            $isDokSubBased = pemutuIsDokSubBased($parentJenis);
            $isKebijakan   = in_array($parentJenis, pemutuKebijakanJenisList());
            return view('pages.pemutu.dokumen._workspace', compact('type', 'item', 'childLabel', 'isDokSubBased', 'isKebijakan'));
        } elseif ($type === 'poin') {
            $item        = DokSub::with('dokumen', 'mappedTo.dokumen', 'mappedFrom.dokumen')->findOrFail(decryptIdIfEncrypted($id));
            $parentJenis = strtolower(trim($item->dokumen->jenis ?? ''));
            $isKebijakan = in_array($parentJenis, pemutuKebijakanJenisList());

            // For kebijakan poin: load available mapping options
            $mappableOptions = collect();
            if ($isKebijakan && pemutuMappableJenis($parentJenis)) {
                $mappableOptions = $this->dokumenService->getMappablePoinOptions(
                    $parentJenis,
                    $item->dokumen->periode ?? (int) date('Y')
                );
            }

            return view('pages.pemutu.dokumen._workspace', compact('type', 'item', 'isKebijakan', 'mappableOptions'));
        }

        return abort(404, 'Invalid type');
    }

    /**
     * MODAL CREATE (AJAX)
     */
    public function create(Request $request)
    {
        $type     = $request->query('type', 'dokumen');
        $dokumens = $this->dokumenSpmiService->getHierarchicalDokumens();

        $activeTab = $request->query('tabs', 'kebijakan');
        if ($activeTab === 'standar') {
            $allowedTypes = ['standar' => 'Standar', 'formulir' => 'Formulir', 'manual_prosedur' => 'Manual Prosedur'];
        } else {
            $allowedTypes = ['kebijakan' => 'Kebijakan', 'visi' => 'Visi', 'misi' => 'Misi', 'rjp' => 'RPJP', 'renstra' => 'Renstra', 'renop' => 'Renop'];
        }

        $fixedJenis   = $request->fixed_jenis;
        $parent       = null;
        $parentDokSub = null;
        $parentDok    = null;

        if ($request->filled('parent_id')) {
            $parent = Dokumen::find(decryptIdIfEncrypted($request->parent_id));
            if ($parent) {
                $fixedJenis = pemutuFixedJenis($parent->jenis);
            }
        }
        if ($request->filled('parent_doksub_id')) {
            $parentDokSub = DokSub::find(decryptIdIfEncrypted($request->parent_doksub_id));
            if ($parentDokSub && $type === 'poin') {
                $dokumen = $parentDokSub->dokumen;
            } elseif ($parentDokSub && $type === 'indikator') {
                $parentDok = $parentDokSub->dokumen;
            }
        }

        // Additional variables based on type
        if ($type === 'dokumen') {
            return view('pages.pemutu.dokumen.create-edit-ajax', compact('type', 'dokumens', 'allowedTypes', 'fixedJenis', 'parent', 'parentDokSub'));
        } elseif ($type === 'poin') {
            if ($request->filled('parent_id')) {
                $dokumen = Dokumen::find(decryptIdIfEncrypted($request->parent_id));
                return view('pages.pemutu.dokumen.create-edit-ajax', compact('type', 'dokumen'));
            }
        } elseif ($type === 'indikator') {
            return view('pages.pemutu.dokumen.create-edit-ajax', compact('type', 'parentDokSub', 'parentDok'));
        }

        return abort(404);
    }

    /**
     * STORE (AJAX)
     */
    public function store(Request $request)
    {
        $type = $request->query('type', 'dokumen');
        $data = $request->all();

        $data['judul'] = $data['judul'] ?? $data['indikator'] ?? null; // Normalizing field names

        // Validate required
        if (empty($data['judul'])) {
            return jsonError("Judul wajib diisi.");
        }

        if ($type === 'dokumen') {
            if (! empty($data['parent_id'])) {
                $data['parent_id'] = decryptIdIfEncrypted($data['parent_id']);
            }

            if (! empty($data['parent_doksub_id'])) {
                $data['parent_doksub_id'] = decryptIdIfEncrypted($data['parent_doksub_id']);
            }

            $model = $this->dokumenSpmiService->createDokumen($data);
        } elseif ($type === 'poin') {
            if (! empty($data['dok_id'])) {
                $data['dok_id'] = decryptIdIfEncrypted($data['dok_id']);
            }

            $model = $this->dokumenSpmiService->createPoin($data);
        } elseif ($type === 'indikator') {
            $data['parent_dok_id'] = ! empty($data['parent_dok_id']) ? decryptIdIfEncrypted($data['parent_dok_id']) : null;
            if (! empty($data['doksub_ids'])) {
                $data['doksub_ids'] = array_map('decryptIdIfEncrypted', $data['doksub_ids']);
            }

            $model = $this->dokumenSpmiService->createIndikator($data);
        }

        $redirect = null;
        if ($type === 'dokumen') {
            $redirect = route('pemutu.dokumen.index', [
                'jenis'   => $model->jenis,
                'periode' => $model->periode,
                'id'      => $model->encrypted_dok_id,
                'type'    => 'dokumen',
            ]);
        } elseif ($type === 'poin') {
            $redirect = route('pemutu.dokumen.index', [
                'jenis'   => $model->dokumen->jenis ?? 'standar',
                'periode' => $model->dokumen->periode ?? date('Y'),
                'id'      => $model->encrypted_doksub_id,
                'type'    => 'doksub',
            ]);
        } elseif ($type === 'indikator') {
            // Indikators might not have a direct direct parent "jenis" in the same way, but often we want to stay on the same tab
            // For now, reload same page
            $redirect = url()->previous();
        }

        return jsonSuccess(ucfirst($type) . ' berhasil dibuat.', $redirect);
    }

    /**
     * MODAL EDIT (AJAX)
     */
    public function edit(Request $request, $type, $id)
    {
        $decryptedId = decryptIdIfEncrypted($id);
        $mode        = $request->query('mode'); // 'title' or 'content'

        if ($type === 'dokumen') {
            $dokumen  = Dokumen::findOrFail($decryptedId);
            $dokumens = $this->dokumenSpmiService->getHierarchicalDokumens()->filter(fn($d) => $d->dok_id != $dokumen->dok_id);
            return view('pages.pemutu.dokumen.create-edit-ajax', compact('type', 'dokumen', 'dokumens', 'mode'));
        } elseif ($type === 'poin') {
            $dokSub = DokSub::findOrFail($decryptedId);
            return view('pages.pemutu.dokumen.create-edit-ajax', compact('type', 'dokSub', 'mode'));
        } elseif ($type === 'indikator') {
            $indikator = Indikator::findOrFail($decryptedId);
            return view('pages.pemutu.dokumen.create-edit-ajax', compact('type', 'indikator', 'mode'));
        }

        return abort(404);
    }

    /**
     * UPDATE (AJAX)
     */
    public function update(Request $request, $type, $id)
    {
        $decryptedId = decryptIdIfEncrypted($id);
        $data        = $request->all();

        if ($type === 'dokumen') {
            if (! empty($data['parent_id'])) {
                $data['parent_id'] = decryptIdIfEncrypted($data['parent_id']);
            }

            $this->dokumenSpmiService->updateDokumen($decryptedId, $data);
            $model = Dokumen::find($decryptedId);
        } elseif ($type === 'poin') {
            if (! empty($data['dok_id'])) {
                $data['dok_id'] = decryptIdIfEncrypted($data['dok_id']);
            }
            $this->dokumenSpmiService->updatePoin($decryptedId, $data);
            $model = DokSub::with('dokumen')->find($decryptedId);
        } elseif ($type === 'indikator') {
            if (! empty($data['doksub_ids'])) {
                $data['doksub_ids'] = array_map('decryptIdIfEncrypted', $data['doksub_ids']);
            }

            $this->dokumenSpmiService->updateIndikator($decryptedId, $data);
            $model = Indikator::find($decryptedId);
        }

        $redirect = null;
        if ($type === 'dokumen') {
            $redirect = route('pemutu.dokumen.index', ['jenis' => $model->jenis, 'periode' => $model->periode]);
        } elseif ($type === 'poin') {
            $redirect = route('pemutu.dokumen.index', ['jenis' => $model->dokumen->jenis ?? 'standar', 'periode' => $model->dokumen->periode ?? date('Y')]);
        } else {
            $redirect = url()->previous();
        }

        return jsonSuccess(ucfirst($type) . ' berhasil diperbarui.', $redirect);
    }

    /**
     * DESTROY (AJAX)
     */
    public function destroy(Request $request, $type, $id)
    {
        $decryptedId = decryptIdIfEncrypted($id);
        $redirect    = url()->previous();

        if ($type === 'dokumen') {
            $item = Dokumen::withTrashed()->find($decryptedId);
            if ($item) {
                $redirect = route('pemutu.dokumen.index', ['jenis' => $item->jenis, 'periode' => $item->periode]);
            }
            $this->dokumenSpmiService->deleteDokumen($decryptedId);
        } elseif ($type === 'poin') {
            $item = DokSub::withTrashed()->with('dokumen')->find($decryptedId);
            if ($item && $item->dokumen) {
                $redirect = route('pemutu.dokumen.index', ['jenis' => $item->dokumen->jenis, 'periode' => $item->dokumen->periode]);
            }
            $this->dokumenSpmiService->deletePoin($decryptedId);
        } elseif ($type === 'indikator') {
            $this->dokumenSpmiService->deleteIndikator($decryptedId);
        }

        return jsonSuccess(ucfirst($type) . ' berhasil dihapus.', $redirect);
    }

    /**
     * CHILDREN DATATABLES (AJAX)
     */
    public function childrenData(Request $request, $type, $id)
    {
        $decryptedId = decryptIdIfEncrypted($id);

        if ($type === 'dokumen') {
            $query = DokSub::withCount(['childDokumens', 'indikators'])->where('dok_id', $decryptedId)->orderBy('seq');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('kode', function ($row) {
                    return $row->kode ?: '-';
                })
                ->addColumn('judul', function ($row) {
                    $html = '<div class="fw-bold">' . e($row->judul) . '</div>';
                    if ($row->is_hasilkan_indikator) {
                        $html .= '<div class="badge bg-green-lt mt-1">Hasilkan Indikator</div>';
                    }
                    return $html;
                })
                ->addColumn('jumlah_turunan', function ($row) {
                    $html = '';
                    if ($row->child_dokumens_count > 0) {
                        $html .= '<span class="badge bg-blue-lt me-1">' . $row->child_dokumens_count . ' Dokumen</span>';
                    }

                    if ($row->indikators_count > 0) {
                        $html .= '<span class="badge bg-green-lt">' . $row->indikators_count . ' Indikator</span>';
                    }

                    return $html ?: '-';
                })
                ->addColumn('action', function ($row) {
                    return view('components.tabler.datatables-actions', [
                        'editUrl'       => route('pemutu.dokumen-spmi.edit', ['type' => 'poin', 'id' => $row->encrypted_doksub_id, 'mode' => 'title']),
                        'editModal'     => true,
                        'editModalSize' => 'modal-lg',
                        'deleteUrl'     => route('pemutu.dokumen-spmi.destroy', ['type' => 'poin', 'id' => $row->encrypted_doksub_id]),
                    ])->render();
                })
                ->rawColumns(['judul', 'kode', 'jumlah_turunan', 'action'])
                ->make(true);
        } elseif ($type === 'poin_dokumen') {
            $query = Dokumen::where('parent_doksub_id', $decryptedId)->orderBy('seq');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('judul', function ($row) {
                    $html = '<div class="fw-bold">' . e($row->judul) . '</div>';
                    if ($row->kode) {
                        $html .= '<small class="text-muted">' . e($row->kode) . '</small>';
                    }
                    return $html;
                })
                ->addColumn('jenis', function ($row) {return '<span class="badge bg-blue-lt">' . strtoupper($row->jenis) . '</span>';})
                ->addColumn('action', function ($row) {
                    return view('components.tabler.datatables-actions', [
                        'editUrl'       => route('pemutu.dokumen-spmi.edit', ['type' => 'dokumen', 'id' => $row->encrypted_dok_id, 'mode' => 'title']),
                        'editModal'     => true,
                        'editModalSize' => 'modal-lg',
                        'deleteUrl'     => route('pemutu.dokumen-spmi.destroy', ['type' => 'dokumen', 'id' => $row->encrypted_dok_id]),
                    ])->render();
                })
                ->rawColumns(['judul', 'jenis', 'action'])
                ->make(true);
        } elseif ($type === 'poin_indikator') {
            // Get indikator items for this doksub
            $query = Indikator::with('orgUnits')->whereHas('dokSubs', function ($q) use ($decryptedId) {
                $q->where('pemutu_dok_sub.doksub_id', $decryptedId);
            });
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('no_indikator', function ($row) {
                    return $row->no_indikator ?: '-';
                })
                ->addColumn('indikator', function ($row) {
                    return '<div class="fw-bold">' . e($row->indikator) . '</div>';
                })
                ->addColumn('unit_target', function ($row) {
                    if ($row->orgUnits->isEmpty()) {
                        return '<span class="text-muted">-</span>';
                    }
                    return $row->orgUnits->map(function ($unit) {
                        $target = $unit->pivot->target ?? '-';
                        return '<div class="d-flex justify-content-between gap-2"><span class="text-muted small">' . e($unit->name) . '</span><span class="badge bg-blue-lt">' . e($target) . '</span></div>';
                    })->implode('');
                })
                ->addColumn('action', function ($row) {
                    return view('components.tabler.datatables-actions', [
                        'editUrl'   => route('pemutu.indikator.edit', ['indikator' => $row->encrypted_indikator_id, 'redirect_to' => url()->current()]),
                        'editModal' => false,
                        'deleteUrl' => route('pemutu.dokumen-spmi.destroy', ['type' => 'indikator', 'id' => $row->encrypted_indikator_id]),
                    ])->render();
                })
                ->rawColumns(['indikator', 'no_indikator', 'unit_target', 'action'])
                ->make(true);
        } elseif ($type === 'poin_mapping') {
            // Get mapped poin (from the level above) for a given DokSub
            $doksub = DokSub::with('dokumen')->findOrFail($decryptedId);
            $query  = $doksub->mappedTo()->with('dokumen');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('judul', function ($row) {
                    $html = '<div class="fw-bold">' . e($row->judul) . '</div>';
                    if ($row->dokumen) {
                        $html .= '<small class="text-muted">' . pemutuJenisLabel($row->dokumen->jenis) . '</small>';
                    }
                    return $html;
                })
                ->addColumn('kode', fn($row) => $row->kode ? '<span class="badge bg-secondary-lt">' . e($row->kode) . '</span>' : '-')
                ->addColumn('action', function ($row) use ($doksub) {
                    return '<button class="btn btn-sm btn-outline-danger btn-remove-mapping" '
                    . 'data-doksub-id="' . $doksub->encrypted_doksub_id . '" '
                    . 'data-mapped-id="' . $row->encrypted_doksub_id . '"'
                        . '><i class="ti ti-unlink"></i> Lepas</button>';
                })
                ->rawColumns(['judul', 'kode', 'action'])
                ->make(true);
        }

        return abort(404);
    }

    /**
     * MAPPING SYNC (AJAX) — Attach or detach poin mapping
     */
    public function mappingSync(Request $request)
    {
        $request->validate([
            'doksub_id'        => 'required',
            'mapped_doksub_id' => 'required',
            'action'           => 'required|in:attach,detach',
        ]);

        $doksubId       = decryptIdIfEncrypted($request->doksub_id);
        $mappedDoksubId = decryptIdIfEncrypted($request->mapped_doksub_id);

        $doksub = DokSub::with('dokumen')->findOrFail($doksubId);
        $mapped = DokSub::with('dokumen')->findOrFail($mappedDoksubId);

        // Validate: mapping must be to the correct level above
        $sourceJenis    = strtolower(trim($doksub->dokumen->jenis ?? ''));
        $targetJenis    = strtolower(trim($mapped->dokumen->jenis ?? ''));
        $expectedTarget = pemutuMappableJenis($sourceJenis);

        if ($expectedTarget !== $targetJenis) {
            return jsonError("Poin {$sourceJenis} hanya bisa dipetakan ke poin " . pemutuJenisLabel($expectedTarget) . '.');
        }

        if ($request->action === 'attach') {
            $doksub->mappedTo()->syncWithoutDetaching([$mappedDoksubId]);
            return jsonSuccess('Mapping berhasil ditambahkan.');
        } else {
            $doksub->mappedTo()->detach($mappedDoksubId);
            return jsonSuccess('Mapping berhasil dihapus.');
        }
    }

    /**
     * UPLOAD FILE PENDUKUNG (AJAX) — Spatie Media Library
     * Supports multiple file uploads to 'dokumen_pendukung' collection.
     */
    public function uploadFile(Request $request, $type, $id)
    {
        $request->validate([
            'files'   => 'required|array',
            'files.*' => 'file|max:20480', // max 20MB per file
        ]);

        $model = ($type === 'poin')
            ? DokSub::findOrFail(decryptIdIfEncrypted($id))
            : Dokumen::findOrFail(decryptIdIfEncrypted($id));

        foreach ($request->file('files') as $file) {
            $model->addMedia($file)
                ->toMediaCollection('dokumen_pendukung');
        }

        $judul = ($type === 'poin') ? $model->judul : $model->judul;
        logActivity('dokumen_management', "Mengunggah " . count($request->file('files')) . " file ke {$type}: {$judul}");

        return jsonSuccess('File berhasil diunggah.');
    }

    /**
     * DELETE FILE PENDUKUNG (AJAX) — Spatie Media Library
     */
    public function deleteFile(Request $request, $type, $id, $mediaId)
    {
        $model = ($type === 'poin')
            ? DokSub::findOrFail(decryptIdIfEncrypted($id))
            : Dokumen::findOrFail(decryptIdIfEncrypted($id));

        $media = $model->getMedia('dokumen_pendukung')->firstWhere('id', $mediaId);

        if (! $media) {
            return jsonError('File tidak ditemukan.');
        }

        $media->delete();
        logActivity('dokumen_management', "Menghapus file dari {$type}: {$model->judul}");

        return jsonSuccess('File berhasil dihapus.');
    }

    /**
     * SUMMARY PAGE – Tree-based layout.
     */
    public function summary(Request $request)
    {
        $pageTitle       = 'Rekap Capaian';
        $activeJenis     = $request->query('jenis', 'visi');
        $selectedPeriode = $request->periode ?: (date('Y') + 2); // Default 2026 based on DB if empty

        $periods = Dokumen::select('periode')
            ->whereNotNull('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode');

        if ($periods->isEmpty()) {
            $periods->push($selectedPeriode);
        }

        // We only care about Kebijakan docs for the Summary tree
        $kebijakanTypes = ['visi', 'misi', 'rjp', 'renstra', 'renop'];

        if (! in_array($activeJenis, $kebijakanTypes)) {
            $activeJenis = 'visi';
        }

        $docsArr         = $this->dokumenService->getKebijakanByPeriode($selectedPeriode);
        $dokumentByJenis = [];
        foreach ($docsArr as $j => $doc) {
            $dokumentByJenis[$j] = $doc ? collect([$doc]) : collect();
        }

        return view('pages.pemutu.dokumen.summary', compact(
            'pageTitle', 'periods', 'selectedPeriode', 'activeJenis', 'dokumentByJenis', 'kebijakanTypes'
        ));
    }

    /**
     * AJAX Endpoint to fetch summary data for a specifically clicked node.
     */
    public function summaryData(Request $request, $type, $id)
    {
        $kebijakanChain = ['visi', 'misi', 'rjp', 'renstra', 'renop'];
        $indicators     = collect();
        $title          = '';
        $jenis          = '';
        $periode        = $request->periode ?: date('Y');

        if ($type === 'dokumen') {
            $doc     = Dokumen::with('dokSubs')->findOrFail(decryptIdIfEncrypted($id));
            $title   = $doc->judul;
            $jenis   = strtolower(trim($doc->jenis));
            $periode = $doc->periode;

            $startIndex = array_search($jenis, $kebijakanChain);

            // Trace down for ALL poin in this document
            foreach ($doc->dokSubs as $poin) {
                // If it's already renop, get indicators directly
                if ($jenis === 'renop') {
                    if ($poin->is_hasilkan_indikator) {
                        $inds       = $poin->indikators()->with(['orgUnits', 'parent.orgUnits'])->get();
                        $indicators = $indicators->merge($inds);
                    }
                } else {
                    $chain      = $this->traceChainDown($poin, $kebijakanChain, $startIndex, $periode);
                    $indicators = $indicators->merge($this->collectIndicatorsFromChain($chain));
                }
            }
        } elseif ($type === 'poin') {
            $poin    = DokSub::with('dokumen')->findOrFail(decryptIdIfEncrypted($id));
            $title   = "Poin: " . $poin->judul;
            $jenis   = strtolower(trim($poin->dokumen->jenis ?? ''));
            $periode = $poin->dokumen->periode ?? date('Y');

            $startIndex = array_search($jenis, $kebijakanChain);

            if ($jenis === 'renop') {
                if ($poin->is_hasilkan_indikator) {
                    $inds       = $poin->indikators()->with(['orgUnits', 'parent.orgUnits'])->get();
                    $indicators = $indicators->merge($inds);
                }
            } else {
                $chain      = $this->traceChainDown($poin, $kebijakanChain, $startIndex, $periode);
                $indicators = $indicators->merge($this->collectIndicatorsFromChain($chain));
            }
        }

        // Unique indicators by ID
        $indicators = $indicators->unique('indikator_id')->values();

        // Aggregate AMI Results
        $unitsEvaluated = collect();
        $amiCounts      = [
            'total'     => 0,
            'terpenuhi' => 0,
            'melampaui' => 0,
            'kts'       => 0,
            'none'      => 0,
        ];

        $detailUnits = []; // Store specific scores per unit to show in the UI

        foreach ($indicators as $ind) {
            foreach ($ind->orgUnits as $ou) {
                $unitsEvaluated->push($ou->name);
                $amiResult = $ou->pivot->ami_hasil_akhir;
                $edCapaian = $ou->pivot->ed_capaian;
                $target    = $ou->pivot->target;

                $amiCounts['total']++;
                if ($amiResult === 1) {
                    $amiCounts['terpenuhi']++;
                } elseif ($amiResult === 2) {
                    $amiCounts['melampaui']++;
                } elseif ($amiResult === 0) {
                    $amiCounts['kts']++;
                } else {
                    $amiCounts['none']++;
                }

                // Collect breakdown per unit
                if (! isset($detailUnits[$ou->name])) {
                    $detailUnits[$ou->name] = [
                        'name'            => $ou->name,
                        'total_indikator' => 0,
                        'terpenuhi'       => 0,
                        'melampaui'       => 0,
                        'kts'             => 0,
                        'indicators'      => [],
                    ];
                }

                $detailUnits[$ou->name]['total_indikator']++;
                if ($amiResult === 1) {
                    $detailUnits[$ou->name]['terpenuhi']++;
                } elseif ($amiResult === 2) {
                    $detailUnits[$ou->name]['melampaui']++;
                } elseif ($amiResult === 0) {
                    $detailUnits[$ou->name]['kts']++;
                }

                $detailUnits[$ou->name]['indicators'][] = [
                    'no'      => $ind->no_indikator,
                    'nama'    => $ind->indikator,
                    'target'  => $target,
                    'capaian' => $edCapaian,
                    'ami'     => $amiResult,
                ];
            }
        }

        $unitsEvaluated = $unitsEvaluated->unique()->values();

        // Calculate Percentages
        $total       = $amiCounts['total'];
        $percentages = [
            'terpenuhi' => $total > 0 ? round(($amiCounts['terpenuhi'] / $total) * 100, 1) : 0,
            'melampaui' => $total > 0 ? round(($amiCounts['melampaui'] / $total) * 100, 1) : 0,
            'kts'       => $total > 0 ? round(($amiCounts['kts'] / $total) * 100, 1) : 0,
        ];

        return view('pages.pemutu.dokumen._summary_data', compact(
            'title', 'jenis', 'indicators', 'unitsEvaluated', 'amiCounts', 'percentages', 'detailUnits'
        ));
    }

    /**
     * Recursively trace the chain from a poin down through mappedFrom relations.
     */
    private function traceChainDown(DokSub $poin, array $kebijakanChain, int $currentLevel, $periode): array
    {
        $result    = [];
        $nextLevel = $currentLevel + 1;

        if ($nextLevel >= count($kebijakanChain)) {
            return $result;
        }

        // Find poin that map TO this poin (i.e. the children in the chain)
        $children = $poin->mappedFrom()
            ->whereHas('dokumen', function ($q) use ($kebijakanChain, $nextLevel, $periode) {
                $q->where('jenis', $kebijakanChain[$nextLevel])
                    ->where('periode', $periode);
            })
            ->with(['dokumen', 'indikators.orgUnits', 'indikators.parent.orgUnits'])
            ->get();

        foreach ($children as $child) {
            $childData = [
                'poin'       => $child,
                'jenis'      => $kebijakanChain[$nextLevel],
                'indicators' => collect(),
                'chain'      => [],
            ];

            // If this is a Renop poin with indicators, collect them
            if ($kebijakanChain[$nextLevel] === 'renop' && $child->is_hasilkan_indikator) {
                $childData['indicators'] = $child->indikators()
                    ->with(['orgUnits', 'parent.orgUnits'])
                    ->get();
            }

            // Continue tracing down
            $childData['chain'] = $this->traceChainDown($child, $kebijakanChain, $nextLevel, $periode);

            $result[] = $childData;
        }

        return $result;
    }

    /**
     * Collect all indicators from the whole chain tree.
     */
    private function collectIndicatorsFromChain(array $chain)
    {
        $indicators = collect();

        foreach ($chain as $node) {
            if (isset($node['indicators']) && $node['indicators'] instanceof \Illuminate\Support\Collection) {
                $indicators = $indicators->merge($node['indicators']);
            }
            if (! empty($node['chain'])) {
                $indicators = $indicators->merge($this->collectIndicatorsFromChain($node['chain']));
            }
        }

        return $indicators;
    }
}
