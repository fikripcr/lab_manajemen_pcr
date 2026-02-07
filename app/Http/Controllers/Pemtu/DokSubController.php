<?php
namespace App\Http\Controllers\Pemtu;

use App\Http\Controllers\Controller;
use App\Models\Pemtu\DokSub;
use App\Models\Pemtu\Dokumen;
use Illuminate\Http\Request;

class DokSubController extends Controller
{
    public function show($id)
    {
        $dokSub = DokSub::with(['dokumen.parent', 'childDokumens'])->findOrFail($id);
        $parent = $dokSub->dokumen;

        // Determine Child Type based on Parent Dokumen Type
        $parentJenis = strtolower(trim($parent->jenis));
        $childType   = match ($parentJenis) {
            'visi'    => 'Misi',
            'misi'    => 'RPJP',
            'rjp'     => 'Renstra',
            'renstra' => 'Renop',
            default   => ''
        };

        return view('pages.pemtu.dok-subs.detail', compact('dokSub', 'parent', 'childType'));
    }

    public function create(Request $request)
    {
        $dokId   = $request->query('dok_id');
        $dokumen = Dokumen::findOrFail($dokId);
        return view('pages.pemtu.dok-subs.create', compact('dokumen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dok_id' => 'required|exists:dokumen,dok_id',
            'judul'  => 'required|string|max:150',
            'isi'    => 'nullable|string',
            'seq'    => 'nullable|integer',
        ]);

        DokSub::create($request->all());

        return response()->json([
            'message' => 'Sub-Document created successfully.',
            // Reload the parent document page to show new sub
            // 'redirect' => route('pemtu.dokumens.index'),
        ]);
    }

    public function edit($id)
    {
        $dokSub = DokSub::findOrFail($id);
        return view('pages.pemtu.dok-subs.edit', compact('dokSub'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'isi'   => 'nullable|string',
            'seq'   => 'nullable|integer',
        ]);

        $dokSub = DokSub::findOrFail($id);
        $dokSub->update($request->all());

        return response()->json([
            'message'  => 'Sub-Document updated successfully.',
            'redirect' => route('pemtu.dokumens.show', $dokSub->dok_id),
        ]);
    }

    public function destroy($id)
    {
        $dokSub = DokSub::findOrFail($id);
        $dokSub->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sub-Document deleted successfully.',
            // 'redirect' => route('pemtu.dokumens.index'),
        ]);
    }
    public function data(Request $request, $dokId)
    {
        if ($request->ajax()) {
            $query = DokSub::where('dok_id', $dokId)->orderBy('seq');

            return \DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('seq', function ($row) {
                    return '<span class="badge badge-outline text-muted">' . $row->seq . '</span>';
                })
                ->addColumn('judul', function ($row) {
                    $detailUrl = route('pemtu.dok-subs.show', $row->doksub_id);
                    return '
                        <a href="' . $detailUrl . '" class="fw-medium text-reset" title="Lihat Detail & Turunan">
                            ' . $row->judul . '
                        </a>
                        <div class="text-muted small text-truncate" style="max-width: 300px;">' . strip_tags($row->isi) . '</div>
                    ';
                })
                ->addColumn('action', function ($row) {
                    $editUrl   = route('pemtu.dok-subs.edit', $row->doksub_id);
                    $deleteUrl = route('pemtu.dok-subs.destroy', $row->doksub_id);

                    return '
                        <div class="btn-list flex-nowrap">
                            <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-outline-primary" title="Edit Content">
                                <i class="ti ti-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-icon btn-danger ajax-delete" data-url="' . $deleteUrl . '" data-title="Hapus Sub-Dokumen?" data-text="Data ini akan dihapus permanen.">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>';
                })
                ->rawColumns(['seq', 'judul', 'action'])
                ->make(true);
        }
    }
}
