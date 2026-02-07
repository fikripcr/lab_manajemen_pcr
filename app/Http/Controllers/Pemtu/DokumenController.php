<?php
namespace App\Http\Controllers\Pemtu;

use App\Http\Controllers\Controller;
use App\Models\Pemtu\Dokumen;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    private function getHierarchicalDokumens($parentId = null, $prefix = '')
    {
        $docs = Dokumen::where('parent_id', $parentId)
            ->orderBy('seq')
            ->orderBy('judul')
            ->get();

        $result = [];
        foreach ($docs as $doc) {
            $doc->judul = $prefix . ' ' . $doc->judul;
            $result[]   = $doc;
            $result     = array_merge($result, $this->getHierarchicalDokumens($doc->dok_id, $prefix . '--'));
        }
        return $result;
    }

    public function index(Request $request)
    {
        // Recursive eager load for Tree View
        $dokumens = Dokumen::whereNull('parent_id')
            ->when($request->periode, function ($q) use ($request) {
                $q->where('periode', $request->periode);
            })
            ->orderBy('seq')
            ->with(['children' => function ($q) {
                $q->orderBy('seq')->with(['children' => function ($qq) {
                    $qq->orderBy('seq')->with(['children' => function ($qqq) {
                        $qqq->orderBy('seq');
                    }]);
                }]);
            }])
            ->get();

        // Get unique periods for filter dropdown
        $periods = Dokumen::select('periode')->distinct()->orderBy('periode', 'desc')->pluck('periode');

        return view('pages.pemtu.dokumens.index', compact('dokumens', 'periods'));
    }

    public function show($id)
    {
        $dokumen = Dokumen::with(['dokSubs', 'children'])->findOrFail($id);
        return view('pages.pemtu.dokumens.detail', compact('dokumen'));
    }

    public function create()
    {
        $dokumens     = $this->getHierarchicalDokumens();
        $parent       = null;
        $allowedTypes = ['visi', 'misi', 'rjp', 'renstra', 'renop'];
        $parentDokSub = null;

        // Check for Parent DokSub first (New Flow)
        if (request()->has('parent_doksub_id')) {
            $parentDokSub = DokSub::find(request('parent_doksub_id'));
            if ($parentDokSub) {
                // The 'Parent' for hierarchy logic is the Dokumen that OWNS this DokSub
                $parent = $parentDokSub->dokumen;
                // But we must ensure the new Dokumen is linked to this DokSub
            }
        }
        // Fallback or explicit parent_id
        elseif (request()->has('parent_id')) {
            $parent = Dokumen::find(request('parent_id'));
        }

        if ($parent) {
            // Logic for allowed types based on Parent
            $parentJenis = strtolower(trim($parent->jenis));
            switch ($parentJenis) {
                case 'visi':
                    $allowedTypes = ['misi'];
                    break;
                case 'misi':
                    $allowedTypes = ['rjp']; // Or RPJP
                    break;
                case 'rjp':
                    $allowedTypes = ['renstra'];
                    break;
                case 'renstra':
                    $allowedTypes = ['renop'];
                    break;
                case 'renop':
                    // Stop hierarchy here for this module
                    $allowedTypes = [];
                    break;
                default:
                    $allowedTypes = [];
                    break;
            }
        }

        $pageTitle = 'Tambah Dokumen Baru';
        if ($parent) {
            $parentJenis = strtolower(trim($parent->jenis));
            // Determine label for the child to be created
            $childType = match ($parentJenis) {
                'visi'    => 'Misi',
                'misi'    => 'RPJP',
                'rjp'     => 'Renstra',
                'renstra' => 'Renop',
                default   => ''
            };

            if ($childType) {
                $pageTitle = "Tambah $childType";
                if ($parentDokSub) {
                    $pageTitle .= " (Turunan dari Poin: {$parentDokSub->judul})";
                } else {
                    $pageTitle .= " (Anak dari {$parent->judul})";
                }
            }
        }

        return view('pages.pemtu.dokumens.create', compact('dokumens', 'parent', 'allowedTypes', 'pageTitle', 'parentDokSub'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'parent_id' => 'nullable|exists:dokumen,dok_id',
            'kode'      => 'nullable|string|max:20',
            'jenis'     => 'required|string|in:visi,misi,rjp,renstra,renop,standar,formulir,dll',
        ]);

        $data = $request->all();

        // Default Periode to current year
        $data['periode'] = date('Y');

        // Default Type (Jenis) to NULL or generic if empty? (User asked to remove type selection)
        // Migration allows null, model fillable has it. Let's leave it null or set a default if needed.

        // Calculate Level
        if (! empty($data['parent_id'])) {
            $parent        = Dokumen::find($data['parent_id']);
            $data['level'] = $parent ? $parent->level + 1 : 1;
        } else {
            $data['level'] = 1;
        }

        // Default Seq
        if (! empty($data['parent_id'])) {
            $data['seq'] = Dokumen::where('parent_id', $data['parent_id'])->max('seq') + 1;
        } else {
            $data['seq'] = Dokumen::whereNull('parent_id')->max('seq') + 1;
        }

        Dokumen::create($data);

        $redirectUrl = route('pemtu.dokumens.index');

        if (! empty($data['parent_doksub_id'])) {
            $redirectUrl = route('pemtu.dok-subs.show', $data['parent_doksub_id']);
        } elseif (! empty($data['parent_id'])) {
            // Optional: Redirect to parent detail
            $redirectUrl = route('pemtu.dokumens.show', $data['parent_id']);
        }

        return response()->json([
            'message'  => 'Dokumen berhasil dibuat.',
            'redirect' => $redirectUrl,
        ]);
    }

    public function edit($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $allDocs = $this->getHierarchicalDokumens();
        // Exclude self/children to prevent cycles (simple check)
        $dokumens = collect($allDocs)->filter(function ($d) use ($id) {
            return $d->dok_id != $id;
        });

        return view('pages.pemtu.dokumens.edit', compact('dokumen', 'dokumens'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'parent_id' => 'nullable|exists:dokumen,dok_id',
            'kode'      => 'nullable|string|max:20',
            'jenis'     => 'required|string|in:visi,misi,rjp,renstra,renop,standar,formulir,dll',
        ]);

        $dokumen = Dokumen::findOrFail($id);

        if ($request->parent_id == $id) {
            return response()->json(['message' => 'Tidak bisa menjadi parent untuk diri sendiri.'], 422);
        }

        $data = $request->all();

        // Auto-set Year/Periode if missing (though update usually preserves or updates field)
        // If user wants "Tahun langsung set tahun ini", maybe on update too? Or only create?
        // Usually creation time. I'll keep existing unless logic demands change.
        // Actually, let's ensure periode is set if not present?
        if (empty($data['periode'])) {
            $data['periode'] = date('Y');
        }

        // Recalculate Level if parent changed
        if ($data['parent_id'] != $dokumen->parent_id) {
            if (! empty($data['parent_id'])) {
                $parent        = Dokumen::find($data['parent_id']);
                $data['level'] = $parent ? $parent->level + 1 : 1;
            } else {
                $data['level'] = 1;
            }
        }

        $dokumen->update($data);

        return response()->json([
            'message'  => 'Dokumen berhasil diperbarui.',
            'redirect' => route('pemtu.dokumens.index'),
        ]);
    }

    public function destroy($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        if ($dokumen->children()->exists()) {
            return response()->json([
                'message' => 'Tidak bisa menghapus dokumen yang memiliki sub-dokumen/anak. Pindahkan atau hapus anak terlebih dahulu.',
            ], 422);
        }

        $dokumen->delete();

        return response()->json([
            'success'  => true,
            'message'  => 'Dokumen berhasil dihapus.',
            'redirect' => route('pemtu.dokumens.index'),
        ]);
    }

    public function reorder(Request $request)
    {
        $hierarchy = $request->input('hierarchy');

        \DB::transaction(function () use ($hierarchy) {
            $this->updateHierarchy($hierarchy);
        });

        return response()->json(['message' => 'Urutan berhasil diperbarui.']);
    }

    private function updateHierarchy($items, $parentId = null, $level = 1)
    {
        foreach ($items as $index => $item) {
            $doc = Dokumen::find($item['id']);
            if ($doc) {
                $doc->parent_id = $parentId;
                $doc->seq       = $index + 1;
                $doc->level     = $level;
                $doc->save();

                if (isset($item['children']) && is_array($item['children'])) {
                    $this->updateHierarchy($item['children'], $doc->dok_id, $level + 1);
                }
            }
        }
    }

    public function childrenData(Request $request, $id)
    {
        if ($request->ajax()) {
            $query = Dokumen::where('parent_id', $id);

            return \DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('seq', function ($row) {
                    return '<span class="badge badge-outline text-muted">' . $row->seq . '</span>';
                })
                ->editColumn('judul', function ($row) {
                    $html  = '<div class="d-flex align-items-center">';
                    $html .= '<a href="' . route('pemtu.dokumens.show', $row->dok_id) . '" class="text-reset fw-bold">' . $row->judul . '</a>';
                    if ($row->kode) {
                        $html .= '<span class="text-muted ms-2 small">(' . $row->kode . ')</span>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $editUrl   = route('pemtu.dokumens.edit', $row->dok_id);
                    $deleteUrl = route('pemtu.dokumens.destroy', $row->dok_id);

                    return '
                        <div class="btn-list flex-nowrap">
                            <button type="button" class="btn btn-sm btn-icon ajax-modal-btn" data-url="' . $editUrl . '" data-modal-title="Edit Dokumen">
                                <i class="ti ti-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-icon btn-danger ajax-delete" data-url="' . $deleteUrl . '" data-title="Hapus?" data-text="Dokumen ini akan dihapus permanen.">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['seq', 'judul', 'action'])
                ->make(true);
        }
    }
}
