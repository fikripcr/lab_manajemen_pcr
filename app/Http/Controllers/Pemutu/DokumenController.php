<?php
namespace App\Http\Controllers\Pemutu;

use App\Config\PemutuDokumenConfig;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\DokumenApprovalRequest;
use App\Http\Requests\Pemutu\DokumenRequest;
use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\RiwayatApproval;
use App\Services\Pemutu\DokumenApprovalService;
use App\Services\Pemutu\DokumenService;
use App\Services\Pemutu\DokumenSpmiService;
use App\Services\Pemutu\PeriodeSpmiService;
use App\Services\Sys\ApprovalService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DokumenController extends Controller
{
    public function __construct(
        protected DokumenSpmiService $dokumenSpmiService,
        protected DokumenService $dokumenService,
        protected PeriodeSpmiService $periodeSpmiService,
        protected DokumenApprovalService $approvalService,
        protected ApprovalService $sysApprovalService,
    ) {
        $this->middleware('permission:pemutu.dokumen.view')->only(['index', 'show', 'childrenData', 'summary', 'summaryData']);
        $this->middleware('permission:pemutu.dokumen.create')->only(['create', 'store']);
        $this->middleware('permission:pemutu.dokumen.update')->only(['edit', 'update']);
        $this->middleware('permission:pemutu.dokumen.delete')->only(['destroy']);
        $this->middleware('permission:pemutu.dokumen.upload')->only(['uploadFile']);
        $this->middleware('permission:pemutu.dokumen.approve')->only(['approveStore']);
    }

    // =========================================================================
    // WORKSPACE INDEX
    // =========================================================================

    /**
     * MAIN WORKSPACE PAGE
     */
    public function index(Request $request)
    {
        $pageTitle = 'Workspace Dokumen SPMI';
        $activeJenis = $request->query('jenis', 'kebijakan');

        // Active Context
        $activeKelompok = session('pemutu_active_kelompok', 'akademik');
        $siklusData = $this->periodeSpmiService->getSiklusData();
        $periode = $siklusData[$activeKelompok] ?? null;
        $selectedYe = $siklusData['tahun'];

        // Tree-based: Multiple documents allowed (Standar, Formulir, Manual, and Generic Kebijakan)
        $isTreeBased = in_array($activeJenis, ['standar', 'formulir', 'manual_prosedur', 'kebijakan']);

        // Optimization: ONLY fetch documents for the active jenis to improve performance
        $dokumentByJenis = [
            $activeJenis => $this->dokumenSpmiService->getDokumenByJenis($activeJenis, $selectedYe),
        ];

        return view('pages.pemutu.dokumen.index', [
            'pageTitle' => $pageTitle,
            'activeJenis' => $activeJenis,
            'selectedPeriode' => $selectedYe,
            'dokumentByJenis' => $dokumentByJenis,
            'activeTab' => $isTreeBased ? 'standar' : 'kebijakan',
            'periode' => $periode,
            'canModify' => pemutu_can_modify($selectedYe, $activeKelompok),
        ]);
    }

    // =========================================================================
    // SHOW DETAIL PANEL (AJAX)
    // =========================================================================

    /**
     * SHOW DETAIL PANEL (AJAX)
     */
    public function show(Request $request, $type, $id)
    {
        if ($type === 'dokumen') {
            $item = Dokumen::with(['mappedDokSubs.dokumen', 'riwayatApprovals'])->findOrFail(decryptIdIfEncrypted($id));
            $parentJenis = strtolower(trim($item->jenis));
            $parentConfig = \App\Config\PemutuDokumenConfig::for($parentJenis);
            $childLabel = $parentConfig->hasPoin() ? 'Poin' : 'Turunan';
            $isDokSubBased = $parentConfig->isTreeBased();
            $isKebijakan = in_array($parentJenis, \App\Config\PemutuDokumenConfig::all());

            // For Formulir: load available mapping options (targets: Standar or Manual Prosedur parent documents)
            $mappableOptions = collect();
            if ($parentJenis === 'formulir') {
                $mappableOptions = $this->dokumenService->getMappableDokumenOptions(
                    $parentJenis,
                    $item->periode ?? (int) date('Y')
                );
            }

            return view('pages.pemutu.dokumen._workspace', compact('type', 'item', 'childLabel', 'isDokSubBased', 'isKebijakan', 'mappableOptions'));
        } elseif ($type === 'poin') {
            $item = DokSub::with('dokumen', 'mappedTo.dokumen', 'mappedFrom.dokumen')->findOrFail(decryptIdIfEncrypted($id));
            $parentJenis = strtolower(trim($item->dokumen->jenis ?? ''));
            $isKebijakan = in_array($parentJenis, \App\Config\PemutuDokumenConfig::all());

            // For kebijakan poin: load available mapping options
            $mappableOptions = collect();
            $parentConfig = \App\Config\PemutuDokumenConfig::for($parentJenis);
            if ($isKebijakan && $parentConfig->canGenerateIndikator() === false && $parentConfig->mappableTo()) {
                $mappableOptions = $this->dokumenService->getMappablePoinOptions(
                    $parentJenis,
                    $item->dokumen->periode ?? (int) date('Y')
                );
            }

            return view('pages.pemutu.dokumen._workspace', compact('type', 'item', 'isKebijakan', 'mappableOptions'));
        }

        return abort(404, 'Invalid type');
    }

    // =========================================================================
    // CREATE / STORE
    // =========================================================================

    /**
     * MODAL CREATE (AJAX)
     */
    public function create(Request $request)
    {
        $type = $request->query('type', 'dokumen');
        $allowedTypes = ['standar' => 'Standar', 'manual_prosedur' => 'Manual Prosedur', 'formulir' => 'Formulir'];

        if ($request->tabs === 'kebijakan') {
            $allowedTypes = ['kebijakan' => 'Kebijakan', 'visi' => 'Visi', 'misi' => 'Misi', 'rjp' => 'RPJP', 'renstra' => 'Renstra'];
        }

        $fixedJenis = $request->fixed_jenis;
        $parent = null;
        $parentDokSub = null;
        $parentDok = null;

        if ($request->filled('parent_id')) {
            $parent = Dokumen::find(decryptIdIfEncrypted($request->parent_id));
            if ($parent) {
                $parentConfig = \App\Config\PemutuDokumenConfig::for($parent->jenis);
                $fixedJenis = $parentConfig->childTypes()[0] ?? null;
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

        $currentPeriode = null;
        if ($request->filled('periode_id')) {
            $currentPeriode = \App\Models\Pemutu\PeriodeSpmi::find(decryptIdIfEncrypted($request->periode_id));
        } elseif ($request->filled('periode')) {
            $currentPeriode = \App\Models\Pemutu\PeriodeSpmi::where('periode', $request->periode)->first();
        }

        // Fallback to Global Cycle if not provided
        if (! $currentPeriode) {
            $siklusData = $this->periodeSpmiService->getSiklusData();
            $currentPeriode = (object) [
                'periode' => $siklusData['tahun'],
                'jenis_periode' => 'Global',
            ];
        }

        // Additional variables based on type
        if ($type === 'dokumen') {
            return view('pages.pemutu.dokumen.create-edit-ajax', compact('type', 'allowedTypes', 'fixedJenis', 'parent', 'parentDokSub', 'currentPeriode'));
        } elseif ($type === 'poin') {
            if ($request->filled('parent_id')) {
                $dokumen = Dokumen::find(decryptIdIfEncrypted($request->parent_id));
                return view('pages.pemutu.dokumen.create-edit-ajax', compact('type', 'dokumen', 'currentPeriode'));
            }
        } elseif ($type === 'indikator') {
            return view('pages.pemutu.dokumen.create-edit-ajax', compact('type', 'parentDokSub', 'parentDok', 'currentPeriode'));
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
        $data['judul'] = $data['judul'] ?? $data['indikator'] ?? null;

        // --- GUARD: Periode Penetapan ---
        $year = (int) ($data['periode'] ?? session('siklus_spmi_tahun'));
        $kelompok = session('pemutu_active_kelompok', 'akademik');
        if (! pemutu_can_modify($year, $kelompok)) {
            return jsonError('Aksi dibatasi. Masa penetapan periode ini belum dibuka atau sudah berakhir.');
        }

        if (empty($data['judul'])) {
            return jsonError('Judul wajib diisi.');
        }

        if ($type === 'dokumen') {
            if (! empty($data['parent_id'])) {
                $data['parent_id'] = decryptIdIfEncrypted($data['parent_id']);
            }
            if (! empty($data['parent_doksub_id'])) {
                $data['parent_doksub_id'] = decryptIdIfEncrypted($data['parent_doksub_id']);
            }

            // Ensure periode is an ID if it's passed as a year string
            if (isset($data['periode']) && ! empty($data['periode']) && ! is_numeric($data['periode'])) {
                $periode = \App\Models\Pemutu\PeriodeSpmi::where('periode', $data['periode'])->first();
                if ($periode) {
                    $data['periode'] = $periode->periode;
                    $data['periode_id'] = $periode->periodespmi_id;
                }
            }

            if (empty($data['periode'])) {
                $siklusData = $this->periodeSpmiService->getSiklusData();
                $data['periode'] = $siklusData['tahun'];
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
                'jenis' => $model->jenis,
                'periode' => $model->periode,
                'id' => $model->encrypted_dok_id,
                'type' => 'dokumen',
            ]);
        } elseif ($type === 'poin') {
            $redirect = route('pemutu.dokumen.index', [
                'jenis' => $model->dokumen->jenis ?? 'standar',
                'periode' => $model->dokumen->periode ?? date('Y'),
                'id' => $model->encrypted_doksub_id,
                'type' => 'doksub',
            ]);
        } else {
            $redirect = url()->previous();
        }

        return jsonSuccess(ucfirst($type).' berhasil dibuat.', $redirect);
    }

    // =========================================================================
    // EDIT / UPDATE
    // =========================================================================

    /**
     * MODAL EDIT (AJAX)
     */
    public function edit(Request $request, $type, $id)
    {
        $decryptedId = decryptIdIfEncrypted($id);
        $mode = $request->query('mode');

        if ($type === 'dokumen') {
            $dokumen = Dokumen::findOrFail($decryptedId);
            return view('pages.pemutu.dokumen.create-edit-ajax', compact('type', 'dokumen', 'mode'));
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
        $data = $request->all();

        // --- GUARD: Periode Penetapan ---
        $year = (int) session('siklus_spmi_tahun');
        if ($type === 'dokumen') {
            $dokumen = Dokumen::find($decryptedId);
            $year = $dokumen->periode ?? $year;
        } elseif ($type === 'poin') {
            $dokSub = DokSub::with('dokumen')->find($decryptedId);
            $year = $dokSub->dokumen->periode ?? $year;
        } elseif ($type === 'indikator') {
            $year = session('siklus_spmi_tahun');
        }

        $kelompok = session('pemutu_active_kelompok', 'akademik');
        if (! pemutu_can_modify($year, $kelompok)) {
            return jsonError('Aksi dibatasi. Masa penetapan periode ini belum dibuka atau sudah berakhir.');
        }

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

        return jsonSuccess(ucfirst($type).' berhasil diperbarui.', $redirect);
    }

    // =========================================================================
    // DESTROY
    // =========================================================================

    /**
     * DESTROY (AJAX)
     */
    public function destroy(Request $request, $type, $id)
    {
        $decryptedId = decryptIdIfEncrypted($id);
        $redirect = url()->previous();

        // --- GUARD: Periode Penetapan ---
        $year = (int) session('siklus_spmi_tahun');
        if ($type === 'dokumen') {
            $item = Dokumen::withTrashed()->find($decryptedId);
            $year = $item->periode ?? $year;
        } elseif ($type === 'poin') {
            $item = DokSub::withTrashed()->with('dokumen')->find($decryptedId);
            $year = $item->dokumen->periode ?? $year;
        }

        $kelompok = session('pemutu_active_kelompok', 'akademik');
        if (! pemutu_can_modify($year, $kelompok)) {
            return jsonError('Penghapusan dibatasi. Masa penetapan periode ini belum dibuka atau sudah berakhir.');
        }

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

        return jsonSuccess(ucfirst($type).' berhasil dihapus.', $redirect);
    }

    // =========================================================================
    // CHILDREN DATATABLES
    // =========================================================================

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
                ->addColumn('kode', fn ($row) => $row->kode ?: '-')
                ->addColumn('judul', function ($row) {
                    $html = '<div class="fw-bold">'.e($row->judul).'</div>';
                    if ($row->is_hasilkan_indikator) {
                        $html .= '<div class="badge bg-green-lt mt-1">Hasilkan Indikator</div>';
                    }
                    return $html;
                })
                ->addColumn('jumlah_turunan', function ($row) {
                    $html = '';
                    if ($row->child_dokumens_count > 0) {
                        $html .= '<span class="badge bg-blue-lt me-1">'.$row->child_dokumens_count.' Dokumen</span>';
                    }
                    if ($row->indikators_count > 0) {
                        $html .= '<span class="badge bg-green-lt">'.$row->indikators_count.' Indikator</span>';
                    }
                    return $html ?: '-';
                })
                ->addColumn('action', function ($row) {
                    if (! pemutu_can_modify((int) ($row->periode ?? session('siklus_spmi_tahun')))) {
                        return '';
                    }
                    return view('components.tabler.datatables-actions', [
                        'editUrl' => route('pemutu.dokumen.edit', ['type' => 'poin', 'id' => $row->encrypted_doksub_id, 'mode' => 'title']),
                        'editModal' => true,
                        'editModalSize' => 'modal-lg',
                        'deleteUrl' => route('pemutu.dokumen.destroy', ['type' => 'poin', 'id' => $row->encrypted_doksub_id]),
                    ])->render();
                })
                ->rawColumns(['judul', 'kode', 'jumlah_turunan', 'action'])
                ->make(true);

        } elseif ($type === 'poin_dokumen') {
            $query = Dokumen::where('parent_doksub_id', $decryptedId)->orderBy('seq');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('judul', function ($row) {
                    $html = '<div class="fw-bold">'.e($row->judul).'</div>';
                    if ($row->kode) {
                        $html .= '<small class="text-muted">'.e($row->kode).'</small>';
                    }
                    return $html;
                })
                ->addColumn('jenis', fn ($row) => '<span class="badge bg-blue-lt">'.strtoupper($row->jenis).'</span>')
                ->addColumn('action', function ($row) {
                    if (! pemutu_can_modify((int) ($row->periode ?? session('siklus_spmi_tahun')))) {
                        return '';
                    }
                    return view('components.tabler.datatables-actions', [
                        'editUrl' => route('pemutu.dokumen.edit', ['type' => 'dokumen', 'id' => $row->encrypted_dok_id, 'mode' => 'title']),
                        'editModal' => true,
                        'editModalSize' => 'modal-lg',
                        'deleteUrl' => route('pemutu.dokumen.destroy', ['type' => 'dokumen', 'id' => $row->encrypted_dok_id]),
                    ])->render();
                })
                ->rawColumns(['judul', 'jenis', 'action'])
                ->make(true);

        } elseif ($type === 'poin_indikator') {
            $query = Indikator::with('orgUnits')->whereHas('dokSubs', function ($q) use ($decryptedId) {
                $q->where('pemutu_dok_sub.doksub_id', $decryptedId);
            });

            return DataTables::of($query)
                ->addColumn('no', fn ($row) => pemutuDtColNo($row))
                ->editColumn('indikator', fn ($row) => pemutuDtColIndikator($row))
                ->addColumn('target', fn ($row) => pemutuDtColTarget($row))
                ->addColumn('action', function ($row) {
                    $canModify = pemutu_can_modify((int) session('siklus_spmi_tahun'));
                    return view('components.tabler.datatables-actions', [
                        'viewUrl' => route('pemutu.indikator.show', $row->encrypted_indikator_id),
                        'editUrl' => $canModify ? route('pemutu.indikator.edit', ['indikator' => $row->encrypted_indikator_id, 'redirect_to' => url()->current()]) : null,
                        'editModal' => false,
                        'deleteUrl' => $canModify ? route('pemutu.dokumen.destroy', ['type' => 'indikator', 'id' => $row->encrypted_indikator_id]) : null,
                    ])->render();
                })
                ->rawColumns(['no', 'indikator', 'target', 'action'])
                ->make(true);

        } elseif ($type === 'renop_indikator') {
            $renopDoc = Dokumen::find($decryptedId);
            $year = $renopDoc ? $renopDoc->periode : date('Y');

            $query = Indikator::with('orgUnits')
                ->where('type', 'standar')
                ->whereHas('labels', function ($q) {
                    $q->where('name', 'renop');
                });

            return DataTables::of($query)
                ->addColumn('no', fn ($row) => pemutuDtColNo($row))
                ->editColumn('indikator', fn ($row) => pemutuDtColIndikator($row))
                ->addColumn('target', fn ($row) => pemutuDtColTarget($row))
                ->addColumn('action', function ($row) {
                    $canModify = pemutu_can_modify((int) session('siklus_spmi_tahun'));
                    return view('components.tabler.datatables-actions', [
                        'viewUrl' => route('pemutu.indikator.show', $row->encrypted_indikator_id),
                        'editUrl' => $canModify ? route('pemutu.indikator.edit', ['indikator' => $row->encrypted_indikator_id, 'redirect_to' => url()->current()]) : null,
                        'editModal' => false,
                    ])->render();
                })
                ->rawColumns(['no', 'indikator', 'target', 'action'])
                ->make(true);

        } elseif ($type === 'poin_mapping') {
            $doksub = DokSub::with('dokumen')->findOrFail($decryptedId);
            $query = $doksub->mappedTo()->with('dokumen');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('judul', function ($row) {
                    $html = '<div class="fw-bold">'.e($row->judul).'</div>';
                    if ($row->dokumen) {
                        $html .= '<small class="text-muted">'.\App\Config\PemutuDokumenConfig::for($row->dokumen->jenis)->label().'</small>';
                    }
                    return $html;
                })
                ->addColumn('kode', fn ($row) => $row->kode ? '<span class="badge bg-secondary-lt">'.e($row->kode).'</span>' : '-')
                ->addColumn('action', function ($row) use ($doksub) {
                    return '<a href="#" class="btn btn-sm btn-outline-danger btn-remove-mapping" data-source="'.encryptId($doksub->doksub_id).'" data-target="'.encryptId($row->doksub_id).'"><i class="ti ti-link-off"></i></a>';
                })
                ->rawColumns(['judul', 'kode', 'action'])
                ->make(true);
        }

        return abort(404);
    }

    // =========================================================================
    // MAPPING SYNC
    // =========================================================================

    /**
     * Sync mapping between poin
     */
    public function mappingSync(Request $request)
    {
        $result = $this->dokumenService->syncMapping(
            decryptIdIfEncrypted($request->source),
            decryptIdIfEncrypted($request->target),
            $request->action
        );

        return jsonSuccess('Mapping berhasil disinkronisasi.', url()->previous());
    }

    // =========================================================================
    // FILE UPLOAD
    // =========================================================================

    /**
     * Upload file to dokumen
     */
    public function uploadFile(Request $request, $type, $id)
    {
        $decryptedId = decryptIdIfEncrypted($id);
        $request->validate(['file' => 'required|file|max:10240']);

        if ($type === 'dokumen') {
            $model = Dokumen::findOrFail($decryptedId);
        } elseif ($type === 'poin') {
            $model = DokSub::findOrFail($decryptedId);
        } elseif ($type === 'indikator') {
            $model = Indikator::findOrFail($decryptedId);
        } else {
            return abort(404);
        }

        $model->addMedia($request->file('file'))->toMediaCollection('dokumen_files');

        return jsonSuccess('File berhasil diunggah.');
    }

    /**
     * Delete file from dokumen
     */
    public function deleteFile(Request $request, $type, $id, $mediaId)
    {
        $decryptedId = decryptIdIfEncrypted($id);

        if ($type === 'dokumen') {
            $model = Dokumen::findOrFail($decryptedId);
        } elseif ($type === 'poin') {
            $model = DokSub::findOrFail($decryptedId);
        } elseif ($type === 'indikator') {
            $model = Indikator::findOrFail($decryptedId);
        } else {
            return abort(404);
        }

        $media = $model->getMedia('dokumen_files')->firstWhere('id', $mediaId);
        if ($media) {
            $media->delete();
        }

        return jsonSuccess('File berhasil dihapus.');
    }

    // =========================================================================
    // EXPORT
    // =========================================================================

    /**
     * Export dokumen ke DOCX dengan daftar approver dan QR code.
     */
    public function export(Dokumen $dokumen)
    {
        return $this->approvalService->exportToDocx($dokumen);
    }

    // =========================================================================
    // APPROVAL (merged from DokumenApprovalController)
    // =========================================================================

    /**
     * Show approval form for a dokumen
     */
    public function approveCreate(Dokumen $dokumen)
    {
        $data = $this->sysApprovalService->getApprovalFormData($dokumen);
        $data['dokumen'] = $dokumen;

        return view('pages.pemutu.dokumen._approval_form', $data);
    }

    /**
     * Store / sync approvers for a dokumen
     */
    public function approveStore(DokumenApprovalRequest $request, Dokumen $dokumen)
    {
        $result = $this->sysApprovalService->syncApprovers($dokumen, $request->input('approvers', []));

        if (!empty($result['messages'])) {
            logActivity('pemutu', "Memperbarui daftar approval dokumen: {$dokumen->judul}. ".implode('. ', $result['messages']));
        }

        return jsonSuccess('Approver berhasil disinkronisasi.', url()->previous());
    }

    /**
     * Delete an approval entry
     */
    public function approveDestroy(RiwayatApproval $approval)
    {
        $subjectName = $this->sysApprovalService->deleteApproval($approval);
        logActivity('pemutu', "Menghapus approval untuk: {$subjectName}");

        return jsonSuccess('Approval berhasil dihapus');
    }

    /**
     * Public verification page for a dokumen
     */
    public function verify(Dokumen $dokumen)
    {
        $data = $this->sysApprovalService->getPublicVerificationData($dokumen);
        $data['dokumen'] = $dokumen;

        return view('pages.pemutu.dokumen.verify', $data);
    }

    // =========================================================================
    // SUMMARY
    // =========================================================================

    /**
     * Summary view
     */
    public function summary(Request $request)
    {
        $selectedJenis = $request->query('jenis', 'standar');
        $dokumens = $this->dokumenSpmiService->getDokumenByJenis($selectedJenis, $request->periode);

        return view('pages.pemutu.dokumen.summary', [
            'dokumens' => $dokumens,
            'selectedJenis' => $selectedJenis,
            'selectedPeriode' => $request->periode,
        ]);
    }

    /**
     * Summary data (DataTables)
     */
    public function summaryData(Request $request, $type, $id)
    {
        $decryptedId = decryptIdIfEncrypted($id);
        $dokumen = Dokumen::findOrFail($decryptedId);

        $query = $dokumen->indikators()
            ->with(['orgUnits', 'dokSubs'])
            ->orderBy('seq');

        return DataTables::of($query)
            ->addColumn('no', fn ($row) => pemutuDtColNo($row))
            ->editColumn('indikator', fn ($row) => pemutuDtColIndikator($row))
            ->addColumn('target', fn ($row) => pemutuDtColTarget($row))
            ->addColumn('ed_status', fn ($row) => pemutuDtColEdStatus($row))
            ->addColumn('ami_hasil', fn ($row) => pemutuDtColAmiHasil($row))
            ->addColumn('pengend_status', fn ($row) => pemutuDtColPengendStatus($row))
            ->rawColumns(['indikator', 'ed_status', 'ami_hasil', 'pengend_status'])
            ->make(true);
    }
}
