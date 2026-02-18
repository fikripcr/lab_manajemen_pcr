<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\DokSubRequest;
use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Services\Pemutu\DokSubService;
use App\Services\Pemutu\DokumenService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DokSubController extends Controller
{
    protected $DokSubService;
    protected $DokumenService;

    public function __construct(DokSubService $DokSubService, DokumenService $DokumenService)
    {
        $this->DokSubService  = $DokSubService;
        $this->DokumenService = $DokumenService;
    }

    public function show(DokSub $dokSub)
    {
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

        return view('pages.pemutu.dok-subs.detail', compact('dokSub', 'parent', 'childType'));
    }

    public function create(Request $request)
    {
        $dokId   = (int) $request->query('dok_id');
        $dokumen = $this->DokumenService->getDokumenById($dokId);
        if (! $dokumen) {
            abort(404);
        }

        return view('pages.pemutu.dok-subs.create', compact('dokumen'));
    }

    public function store(DokSubRequest $request)
    {
        try {
            $data                          = $request->validated();
            $data['is_hasilkan_indikator'] = $request->boolean('is_hasilkan_indikator');
            $this->DokSubService->createDokSub($data);

            return jsonSuccess('Sub-Document created successfully.', route('pemutu.dokumens.show', $data['dok_id']));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(DokSub $dokSub)
    {
        return view('pages.pemutu.dok-subs.edit', compact('dokSub'));
    }

    public function update(DokSubRequest $request, DokSub $dokSub)
    {
        try {
            $data                          = $request->validated();
            $data['is_hasilkan_indikator'] = $request->boolean('is_hasilkan_indikator');
            $this->DokSubService->updateDokSub($dokSub->doksub_id, $data);

            return jsonSuccess('Sub-Document updated successfully.', route('pemutu.dokumens.show', $dokSub->dok_id));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(DokSub $dokSub)
    {
        try {
            $this->DokSubService->deleteDokSub($dokSub->doksub_id);
            return jsonSuccess('Sub-Document deleted successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function data(Request $request, Dokumen $dokumen)
    {
        if ($request->ajax()) {
            try {
                $query = $this->DokSubService->getFilteredQuery(['dok_id' => $dokumen->dok_id]);

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('seq', function ($row) {
                        return '<span class="badge badge-outline text-muted">' . $row->seq . '</span>';
                    })
                    ->addColumn('judul', function ($row) {
                        $detailUrl = route('pemutu.dok-subs.show', $row);
                        return '
                            <a href="' . $detailUrl . '" class="fw-medium text-reset" title="Lihat Detail & Turunan">
                                ' . $row->judul . '
                            </a>
                            <div class="text-muted small text-truncate" style="max-width: 300px;">' . strip_tags($row->isi) . '</div>
                        ';
                    })
                    ->addColumn('action', function ($row) {
                        return view('pages.pemutu.dok-subs._action', compact('row'))->render();
                    })
                    ->rawColumns(['seq', 'judul', 'action'])
                    ->make(true);
            } catch (Exception $e) {
                return jsonError($e->getMessage(), 500);
            }
        }
    }
}
