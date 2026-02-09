<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\DokSubRequest;
use App\Services\Pemutu\DokSubService;
use App\Services\Pemutu\DokumenService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DokSubController extends Controller
{
    protected $dokSubService;
    protected $dokumenService;

    public function __construct(DokSubService $dokSubService, DokumenService $dokumenService)
    {
        $this->dokSubService  = $dokSubService;
        $this->dokumenService = $dokumenService;
    }

    public function show($id)
    {
        $dokSub = $this->dokSubService->getDokSubById($id);
        if (! $dokSub) {
            abort(404);
        }

        $parent = $dokSub->dokumen;

        // Determine Child Type based on Parent Dokumen Type
        // Logic remains here as it's view-specific presentation logic
        $parentJenis = strtolower(trim($parent->jenis));
        $childType   = match ($parentJenis) {
            'visi'    => 'Misi',
            'misi'    => 'RPJP',
            'rjp'     => 'Renstra',
            'renstra' => 'Renop',
            default   => ''
        };

        return view('pages.pemutu.dok-subs.detail', compact('dokSub', 'parent', 'childType'));
    }

    public function create(Request $request)
    {
        $dokId   = $request->query('dok_id');
        $dokumen = $this->dokumenService->getDokumenById($dokId);
        if (! $dokumen) {
            abort(404);
        }

        return view('pages.pemutu.dok-subs.create', compact('dokumen'));
    }

    public function store(DokSubRequest $request)
    {
        try {
            $this->dokSubService->createDokSub($request->validated());

            return jsonSuccess('Sub-Document created successfully.', route('pemutu.dokumens.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $dokSub = $this->dokSubService->getDokSubById($id);
        if (! $dokSub) {
            abort(404);
        }

        return view('pages.pemutu.dok-subs.edit', compact('dokSub'));
    }

    public function update(DokSubRequest $request, $id)
    {
        try {
            $this->dokSubService->updateDokSub($id, $request->validated());
            $dokSub = $this->dokSubService->getDokSubById($id);

            return jsonSuccess('Sub-Document updated successfully.', route('pemutu.dokumens.show', $dokSub->dok_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->dokSubService->deleteDokSub($id);

            return jsonSuccess('Sub-Document deleted successfully.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function data(Request $request, $dokId)
    {
        if ($request->ajax()) {
            try {
                $query = $this->dokSubService->getFilteredQuery(['dok_id' => $dokId]);

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('seq', function ($row) {
                        return '<span class="badge badge-outline text-muted">' . $row->seq . '</span>';
                    })
                    ->addColumn('judul', function ($row) {
                        $detailUrl = route('pemutu.dok-subs.show', $row->doksub_id);
                        return '
                            <a href="' . $detailUrl . '" class="fw-medium text-reset" title="Lihat Detail & Turunan">
                                ' . $row->judul . '
                            </a>
                            <div class="text-muted small text-truncate" style="max-width: 300px;">' . strip_tags($row->isi) . '</div>
                        ';
                    })
                    ->addColumn('action', function ($row) {
                        $editUrl   = route('pemutu.dok-subs.edit', $row->doksub_id);
                        $deleteUrl = route('pemutu.dok-subs.destroy', $row->doksub_id);

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
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }
}
