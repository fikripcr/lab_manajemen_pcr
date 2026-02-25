<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Services\Pemutu\DokumenSpmiService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DokumenSpmiController extends Controller
{
    public function __construct(protected DokumenSpmiService $dokumenSpmiService)
    {}

    /**
     * MAIN WORKSPACE PAGE
     */
    public function index(Request $request)
    {
        $pageTitle = 'Workspace Dokumen SPMI';
        $activeTab = $request->query('tabs', 'kebijakan');

        $periods = Dokumen::select('periode')
            ->whereNotNull('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode');

        if ($activeTab === 'standar') {
            $jenisTypes = ['standar', 'formulir', 'manual_prosedur'];
        } else {
            $jenisTypes = ['kebijakan', 'visi', 'misi', 'rjp', 'renstra', 'renop'];
        }

        $dokumentByJenis = [];
        foreach ($jenisTypes as $jenis) {
            $dokumentByJenis[$jenis] = $this->dokumenSpmiService->getDokumenByJenis($jenis, $request->periode);
        }

        return view('pages.pemutu.dokumens.index', compact('pageTitle', 'dokumentByJenis', 'periods', 'activeTab'));
    }

    /**
     * SHOW DETAIL PANEL (AJAX)
     */
    public function show(Request $request, $type, $id)
    {
        try {
            if ($type === 'dokumen') {
                $item          = Dokumen::findOrFail(decryptIdIfEncrypted($id));
                $parentJenis   = strtolower(trim($item->jenis));
                $childLabel    = pemutuChildLabel($parentJenis);
                $isDokSubBased = pemutuIsDokSubBased($parentJenis);
                return view('pages.pemutu.dokumens._workspace', compact('type', 'item', 'childLabel', 'isDokSubBased'));
            } elseif ($type === 'poin') {
                $item = DokSub::with('dokumen')->findOrFail(decryptIdIfEncrypted($id));
                return view('pages.pemutu.dokumens._workspace', compact('type', 'item'));
            }

            return abort(404, 'Invalid type');
        } catch (Exception $e) {
            return '<div class="alert alert-danger">Gagal memuat dokumen: ' . $e->getMessage() . '</div>';
        }
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

        $fixedJenis   = null;
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
            return view('pages.pemutu.dokumens.create-edit-ajax', compact('type', 'dokumens', 'allowedTypes', 'fixedJenis', 'parent', 'parentDokSub'));
        } elseif ($type === 'poin') {
            if ($request->filled('parent_id')) {
                $dokumen = Dokumen::find(decryptIdIfEncrypted($request->parent_id));
                return view('pages.pemutu.dokumens.create-edit-ajax', compact('type', 'dokumen'));
            }
        } elseif ($type === 'indikator') {
            return view('pages.pemutu.dokumens.create-edit-ajax', compact('type', 'parentDokSub', 'parentDok'));
        }

        return abort(404);
    }

    /**
     * STORE (AJAX)
     */
    public function store(Request $request)
    {
        try {
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

            return jsonSuccess(ucfirst($type) . ' berhasil dibuat.');
        } catch (Exception $e) {
            logError($e);
            return jsonError($e->getMessage());
        }
    }

    /**
     * MODAL EDIT (AJAX)
     */
    public function edit(Request $request, $type, $id)
    {
        $decryptedId = decryptIdIfEncrypted($id);

        if ($type === 'dokumen') {
            $dokumen  = Dokumen::findOrFail($decryptedId);
            $dokumens = $this->dokumenSpmiService->getHierarchicalDokumens()->filter(fn($d) => $d->dok_id != $dokumen->dok_id);
            return view('pages.pemutu.dokumens.create-edit-ajax', compact('type', 'dokumen', 'dokumens'));
        } elseif ($type === 'poin') {
            $dokSub = DokSub::findOrFail($decryptedId);
            return view('pages.pemutu.dokumens.create-edit-ajax', compact('type', 'dokSub'));
        } elseif ($type === 'indikator') {
            $indikator = Indikator::findOrFail($decryptedId);
            return view('pages.pemutu.dokumens.create-edit-ajax', compact('type', 'indikator'));
        }

        return abort(404);
    }

    /**
     * UPDATE (AJAX)
     */
    public function update(Request $request, $type, $id)
    {
        try {
            $decryptedId = decryptIdIfEncrypted($id);
            $data        = $request->all();

            if ($type === 'dokumen') {
                if (! empty($data['parent_id'])) {
                    $data['parent_id'] = decryptIdIfEncrypted($data['parent_id']);
                }

                $this->dokumenSpmiService->updateDokumen($decryptedId, $data);
            } elseif ($type === 'poin') {
                $this->dokumenSpmiService->updatePoin($decryptedId, $data);
            } elseif ($type === 'indikator') {
                if (! empty($data['doksub_ids'])) {
                    $data['doksub_ids'] = array_map('decryptIdIfEncrypted', $data['doksub_ids']);
                }

                $this->dokumenSpmiService->updateIndikator($decryptedId, $data);
            }

            return jsonSuccess(ucfirst($type) . ' berhasil diperbarui.');
        } catch (Exception $e) {
            logError($e);
            return jsonError($e->getMessage());
        }
    }

    /**
     * DESTROY (AJAX)
     */
    public function destroy(Request $request, $type, $id)
    {
        try {
            $decryptedId = decryptIdIfEncrypted($id);
            if ($type === 'dokumen') {
                $this->dokumenSpmiService->deleteDokumen($decryptedId);
            } elseif ($type === 'poin') {
                $this->dokumenSpmiService->deletePoin($decryptedId);
            } elseif ($type === 'indikator') {
                $this->dokumenSpmiService->deleteIndikator($decryptedId);
            }
            return jsonSuccess(ucfirst($type) . ' berhasil dihapus.');
        } catch (Exception $e) {
            logError($e);
            return jsonError($e->getMessage());
        }
    }

    /**
     * CHILDREN DATATABLES (AJAX)
     */
    public function childrenData(Request $request, $type, $id)
    {
        try {
            $decryptedId = decryptIdIfEncrypted($id);

            if ($type === 'dokumen') {
                $query = DokSub::withCount(['childDokumens', 'indikators'])->where('dok_id', $decryptedId)->orderBy('seq');

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('judul', function ($row) {
                        $html = '<div class="fw-bold">' . e($row->judul) . '</div>';
                        if ($row->kode) {
                            $html .= '<small class="text-muted">' . e($row->kode) . '</small>';
                        }
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
                            'editUrl'   => route('pemutu.dokumen-spmi.edit', ['type' => 'poin', 'id' => $row->encrypted_doksub_id]),
                            'editModal' => true,
                            'deleteUrl' => route('pemutu.dokumen-spmi.destroy', ['type' => 'poin', 'id' => $row->encrypted_doksub_id]),
                        ])->render();
                    })
                    ->rawColumns(['judul', 'jumlah_turunan', 'action'])
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
                            'editUrl'   => route('pemutu.dokumen-spmi.edit', ['type' => 'dokumen', 'id' => $row->encrypted_dok_id]),
                            'editModal' => true,
                            'deleteUrl' => route('pemutu.dokumen-spmi.destroy', ['type' => 'dokumen', 'id' => $row->encrypted_dok_id]),
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
                    ->addColumn('indikator', function ($row) {return '<div class="fw-bold">' . e($row->indikator) . '</div><small class="text-muted">' . e($row->no_indikator) . '</small>';})
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
                            'editUrl'   => route('pemutu.dokumen-spmi.edit', ['type' => 'indikator', 'id' => $row->encrypted_indikator_id]),
                            'editModal' => true,
                            'deleteUrl' => route('pemutu.dokumen-spmi.destroy', ['type' => 'indikator', 'id' => $row->encrypted_indikator_id]),
                        ])->render();
                    })
                    ->rawColumns(['indikator', 'unit_target', 'action'])
                    ->make(true);
            }

            return abort(404);
        } catch (Exception $e) {
            logError($e);
            return jsonError($e->getMessage());
        }
    }
}
