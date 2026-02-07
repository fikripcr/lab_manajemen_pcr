<?php
namespace App\Http\Controllers\Pemtu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemtu\DokumenRequest;
use App\Models\Pemtu\DokSub;
use App\Models\Pemtu\Dokumen; // Still needed for model binding or specific view logic? Service returns models.
use App\Services\Pemtu\DokumenService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DokumenController extends Controller
{
    protected $dokumenService;

    public function __construct(DokumenService $dokumenService)
    {
        $this->dokumenService = $dokumenService;
    }

    public function index(Request $request)
    {
        $pageTitle = 'Dokumen SPMI';

        // Periods for filter
        // Can be moved to service but simple pluck is fine here or in service.
        // Let's keep specific view-prep logic here unless it's complex.
        $periods = Dokumen::select('periode')
            ->whereNotNull('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode');

        // Get all documents by jenis for tab filtering (including children)
        // Service method: getDokumenByJenis($jenis, $periode)
        $dokumentByJenis = [];
        $jenisTypes      = ['visi', 'misi', 'rjp', 'renstra', 'renop', 'standar', 'formulir', 'sop', 'dll'];

        // Optimization: Maybe fetching all in one query is better?
        // Service has getFilteredQuery.
        // But the view expects categorized data.
        // Let's stick to loop for now, or optimise later.
        foreach ($jenisTypes as $jenis) {
            $dokumentByJenis[$jenis] = $this->dokumenService->getDokumenByJenis($jenis, $request->periode);
        }

        return view('pages.pemtu.dokumens.index', compact('pageTitle', 'dokumentByJenis', 'periods'));
    }

    public function create(Request $request)
    {
        $pageTitle = 'Tambah Dokumen Kebijakan';

        // Helper for view (can be in service if reused)
        // $dokumens = $this->dokumenService->getHierarchicalDokumens();
        // View needs list for Parent selection?
        // Original Create: $dokumens = Dokumen::all(); (but hierarchical preferred)
        // Let's use service hierarchical fetch.
        $dokumens = $this->dokumenService->getHierarchicalDokumens();

        $parent       = null;
        $parentDokSub = null;

        if ($request->has('parent_id')) {
            $parent = $this->dokumenService->getDokumenById($request->parent_id);
        }

        // Parent DokSub logic (if linking to sub-doc as parent? logic from previous code)
        if ($request->has('parent_doksub_id')) {
            $parentDokSub = DokSub::find($request->parent_doksub_id);
        }

        $allowedTypes = ['visi', 'misi', 'rjp', 'renstra', 'renop', 'standar', 'formulir', 'dll'];

        return view('pages.pemtu.dokumens.create', compact('dokumens', 'parent', 'allowedTypes', 'pageTitle', 'parentDokSub'));
    }

    public function createStandar()
    {
        $pageTitle = 'Tambah Dokumen Standar';
        return view('pages.pemtu.dokumens.create_standar', compact('pageTitle'));
    }

    public function store(DokumenRequest $request)
    {
        try {
            $data = $request->validated();

            // Additional Logic handled in Service (Level, Seq, Default Periode)
            // But redirect logic needs data.
            $dokumen = $this->dokumenService->createDokumen($data);

            $redirectUrl = route('pemtu.dokumens.index');
            if ($request->filled('parent_doksub_id')) {
                $redirectUrl = route('pemtu.dok-subs.show', $request->parent_doksub_id);
            } elseif ($dokumen->parent_id) {
                $redirectUrl = route('pemtu.dokumens.show', $dokumen->parent_id);
            }

            return jsonSuccess('Dokumen berhasil dibuat.', $redirectUrl);
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $dokumen = $this->dokumenService->getDokumenById($id);
        if (! $dokumen) {
            abort(404);
        }

        $pageTitle = 'Detail Dokumen: ' . $dokumen->judul;

        return view('pages.pemtu.dokumens.detail', compact('dokumen', 'pageTitle'));
    }

    public function edit($id)
    {
        $dokumen = $this->dokumenService->getDokumenById($id);
        if (! $dokumen) {
            abort(404);
        }

        $allDocs = $this->dokumenService->getHierarchicalDokumens();

        // Exclude self/children to prevent cycles
        // Logic stays here or move to service 'getPotentialParents($excludeId)'?
        // Simple filter is fine here.
        $dokumens = $allDocs->filter(function ($d) use ($id) {
            return $d->dok_id != $id; // And check children recursively? For now simple check.
        });

        return view('pages.pemtu.dokumens.edit', compact('dokumen', 'dokumens'));
    }

    public function update(DokumenRequest $request, $id)
    {
        try {
            $this->dokumenService->updateDokumen($id, $request->validated());

            // Determine redirect
            $dokumen     = $this->dokumenService->getDokumenById($id);
            $redirectUrl = route('pemtu.dokumens.index');
            if ($dokumen->parent_id) {
                $redirectUrl = route('pemtu.dokumens.show', $dokumen->parent_id);
            } else {
                // If it was standard, maybe index?
                $redirectUrl = route('pemtu.dokumens.show', $id);
            }

            return jsonSuccess('Dokumen berhasil diperbarui.', $redirectUrl);
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $dokumen     = $this->dokumenService->getDokumenById($id);
            $redirectOpt = route('pemtu.dokumens.index');
            if ($dokumen && $dokumen->parent_id) {
                $redirectOpt = route('pemtu.dokumens.show', $dokumen->parent_id);
            }

            $this->dokumenService->deleteDokumen($id);

            return jsonSuccess('Dokumen berhasil dihapus.', $redirectOpt);
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function reorder(Request $request)
    {
        try {
            $hierarchy = $request->input('hierarchy');
            $this->dokumenService->reorderDokumens($hierarchy);

            return jsonSuccess('Urutan berhasil diperbarui.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function childrenData(Request $request, $id)
    {
        try {
            $query = $this->dokumenService->getChildrenQuery($id);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('judul', function ($row) {
                    $title = '<div class="font-weight-bold">' . $row->judul . '</div>';
                    if ($row->kode) {
                        $title .= '<div class="text-muted small">' . $row->kode . '</div>';
                    }
                    if ($row->children_count > 0) {
                        $title .= '<div class="badge bg-blue-lt mt-1">' . $row->children_count . ' Children</div>';
                    }
                    return $title;
                })
                ->addColumn('action', function ($row) {
                    $editUrl   = route('pemtu.dokumens.edit', $row->dok_id);
                    $deleteUrl = route('pemtu.dokumens.destroy', $row->dok_id); // Ensure destroy uses id

                    return '
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-icon btn-ghost-primary ajax-modal-btn" data-url="' . $editUrl . '" data-modal-title="Edit Dokumen" title="Edit">
                                <i class="ti ti-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-icon btn-ghost-danger ajax-delete" data-url="' . $deleteUrl . '" data-title="Hapus?" data-text="Dokumen ini akan dihapus permanen." title="Hapus">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['judul', 'action']) // no seq needed
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
