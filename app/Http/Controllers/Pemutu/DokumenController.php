<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\DokumenRequest;
use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Personil;
use App\Services\Pemutu\DokumenService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DokumenController extends Controller
{
    protected $DokumenService;

    public function __construct(DokumenService $DokumenService)
    {
        $this->DokumenService = $DokumenService;
    }

    public function index(Request $request)
    {
        $pageTitle = 'Dokumen SPMI';
        $activeTab = $request->query('tabs', 'kebijakan'); // 'kebijakan' or 'standar'

        $periods = Dokumen::select('periode')
            ->whereNotNull('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode');

        // Define jenis types based on active tab
        if ($activeTab === 'standar') {
            $jenisTypes = ['standar', 'formulir', 'manual_prosedur'];
        } else {
            $jenisTypes = ['visi', 'misi', 'rjp', 'renstra', 'renop'];
        }

        $dokumentByJenis = [];
        foreach ($jenisTypes as $jenis) {
            $dokumentByJenis[$jenis] = $this->DokumenService->getDokumenByJenis($jenis, $request->periode);
        }

        return view('pages.pemutu.dokumens.index', compact('pageTitle', 'dokumentByJenis', 'periods', 'activeTab'));
    }

    public function create(Request $request)
    {
        $pageTitle = 'Tambah Dokumen';
        $activeTab = $request->query('tabs', 'kebijakan');

        $dokumens = $this->DokumenService->getHierarchicalDokumens();

        $parent       = null;
        $parentDokSub = null;

        if ($request->has('parent_id')) {
            $parent = $this->DokumenService->getDokumenById($request->parent_id);
            if ($parent) {
                $activeTab = $this->getTabByJenis($parent->jenis);
            }
        }

        if ($request->has('parent_doksub_id')) {
            $parentDokSub = DokSub::find($request->parent_doksub_id);
        }

        // Context-aware allowed types
        if ($activeTab === 'standar') {
            $allowedTypes = ['standar', 'formulir', 'manual_prosedur'];
            $pageTitle    = 'Tambah Dokumen Standar';
        } else {
            $allowedTypes = ['visi', 'misi', 'rjp', 'renstra', 'renop'];
            $pageTitle    = 'Tambah Dokumen Kebijakan';
        }

        return view('pages.pemutu.dokumens.create', compact('dokumens', 'parent', 'allowedTypes', 'pageTitle', 'parentDokSub'));
    }

    public function createStandar()
    {
        $pageTitle = 'Tambah Dokumen Standar';
        return view('pages.pemutu.dokumens.create_standar', compact('pageTitle'));
    }

    public function store(DokumenRequest $request)
    {
        try {
            $data    = $request->validated();
            $dokumen = $this->DokumenService->createDokumen($data);

            $redirectUrl = $this->getIndexUrlByJenis($dokumen->jenis) . '&id=' . $dokumen->dok_id . '&type=dokumen';
            if ($request->filled('parent_doksub_id')) {
                $redirectUrl = route('pemutu.dok-subs.show', $request->parent_doksub_id);
            }

            return jsonSuccess('Dokumen berhasil dibuat.', $redirectUrl);
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function show(Dokumen $dokumen)
    {
        $pageTitle = 'Detail Dokumen: ' . $dokumen->judul;
        $personils = Personil::orderBy('nama')->get();
        $dokumen->load(['approvals.approver', 'approvals.statuses']);

        return view('pages.pemutu.dokumens.detail', compact('dokumen', 'pageTitle', 'personils'));
    }

    public function showRenopWithIndicators(Dokumen $dokumen)
    {

        $doksubs    = $dokumen->dokSubs;
        $indicators = collect();
        foreach ($doksubs as $doksub) {
            $indicators = $indicators->merge($doksub->indikators);
        }

        $pageTitle = 'Indikator untuk: ' . $dokumen->judul;
        return view('pages.pemutu.dokumens.renop_with_indicators', compact('dokumen', 'indicators', 'pageTitle'));
    }

    public function edit(Dokumen $dokumen)
    {
        $allDocs  = $this->DokumenService->getHierarchicalDokumens();
        $dokumens = $allDocs->filter(function ($d) use ($dokumen) {
            return $d->dok_id != $dokumen->dok_id;
        });

        $activeTab = $this->getTabByJenis($dokumen->jenis);
        if ($activeTab === 'standar') {
            $allowedTypes = ['standar', 'formulir', 'manual_prosedur'];
        } else {
            $allowedTypes = ['visi', 'misi', 'rjp', 'renstra', 'renop'];
        }

        return view('pages.pemutu.dokumens.edit', compact('dokumen', 'dokumens', 'allowedTypes'));
    }

    public function update(DokumenRequest $request, Dokumen $dokumen)
    {
        try {
            $this->DokumenService->updateDokumen($dokumen->dok_id, $request->validated());
            $redirectUrl = $this->getIndexUrlByJenis($dokumen->jenis) . '&id=' . $dokumen->dok_id . '&type=dokumen';

            return jsonSuccess('Dokumen berhasil diperbarui.', $redirectUrl);
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(Dokumen $dokumen)
    {
        try {
            $redirectOpt = $this->getIndexUrlByJenis($dokumen->jenis);
            $this->DokumenService->deleteDokumen($dokumen->dok_id);
            return jsonSuccess('Dokumen berhasil dihapus.', $redirectOpt);
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function reorder(Request $request)
    {
        try {
            $hierarchy = $request->input('hierarchy');
            $this->DokumenService->reorderDokumens($hierarchy);
            return jsonSuccess('Urutan berhasil diperbarui.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function childrenData(Request $request, Dokumen $dokumen)
    {
        try {
            $isDokSubBased = in_array(strtolower(trim($dokumen->jenis)), ['standar', 'formulir', 'manual_prosedur', 'renop']);

            if ($isDokSubBased) {
                $query = DokSub::where('dok_id', $dokumen->dok_id)->orderBy('seq');
                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('judul', function ($row) use ($dokumen) {
                        $title = '<div class="font-weight-bold">' . $row->judul . '</div>';
                        if ($row->is_hasilkan_indikator || (in_array($dokumen->jenis, ['renop']))) {
                            $label  = $dokumen->jenis === 'renop' ? 'Hasilkan Indikator Renop' : 'Hasilkan Indikator Standar';
                            $title .= '<div class="badge bg-green-lt mt-1">' . $label . '</div>';
                        }
                        return $title;
                    })
                    ->addColumn('action', function ($row) use ($dokumen) {
                        $editUrl    = route('pemutu.dok-subs.edit', $row);
                        $deleteUrl  = route('pemutu.dok-subs.destroy', $row);
                        $detailUrl  = route('pemutu.dok-subs.show', $row);
                        $modalTitle = in_array($dokumen->jenis, ['renop']) ? 'Edit Kegiatan' : 'Edit Sub Standar';

                        return '
                            <div class="btn-group btn-group-sm">
                                <a href="' . $detailUrl . '" class="btn btn-icon btn-ghost-info" title="Detail">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <button type="button" class="btn btn-icon btn-ghost-primary ajax-modal-btn" data-url="' . $editUrl . '" data-modal-title="' . $modalTitle . '" title="Edit">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-ghost-danger ajax-delete" data-url="' . $deleteUrl . '" data-title="Hapus?" data-text="Poin ini akan dihapus permanen.">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>';
                    })
                    ->rawColumns(['judul', 'action'])
                    ->make(true);
            } else {
                $query = $this->DokumenService->getChildrenQuery($dokumen->dok_id);
                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('judul', function ($row) {
                        $title = '<div class="font-weight-bold">' . $row->judul . '</div>';
                        if ($row->kode) {
                            $title .= '<div class="text-muted small">' . $row->kode . '</div>';
                        }
                        return $title;
                    })
                    ->addColumn('jumlah_turunan', function ($row) {
                        if ($row->children_count <= 0) {
                            return '<span class="text-muted">-</span>';
                        }

                        $jenis      = strtolower(trim($row->jenis));
                        $childLabel = match ($jenis) {
                            'visi'    => 'Misi',
                            'misi'    => 'RPJP',
                            'rjp'     => 'Renstra',
                            'renstra' => 'Renop',
                            'renop'   => 'Poin',
                            default   => 'Turunan'
                        };

                        return '<div class="badge bg-blue-lt">' . $row->children_count . ' ' . $childLabel . '</div>';
                    })
                    ->addColumn('action', function ($row) {
                        $editUrl   = route('pemutu.dokumens.edit', $row);
                        $deleteUrl = route('pemutu.dokumens.destroy', $row);
                        $detailUrl = route('pemutu.dokumens.show', $row);

                        return '
                            <div class="btn-group btn-group-sm">
                                <a href="' . $detailUrl . '" class="btn btn-icon btn-ghost-info" title="Detail">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <button type="button" class="btn btn-icon btn-ghost-primary ajax-modal-btn" data-url="' . $editUrl . '" data-modal-title="Edit Dokumen" title="Edit">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-ghost-danger ajax-delete" data-url="' . $deleteUrl . '" data-title="Hapus?" data-text="Dokumen ini akan dihapus permanen.">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>';
                    })
                    ->rawColumns(['judul', 'jumlah_turunan', 'action'])
                    ->make(true);
            }
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    private function getIndexUrlByJenis($jenis)
    {
        $tab = $this->getTabByJenis($jenis);
        return route('pemutu.dokumens.index', ['tabs' => $tab]);
    }

    private function getTabByJenis($jenis)
    {
        $standarTypes = ['standar', 'formulir', 'manual_prosedur'];
        return in_array(strtolower(trim($jenis)), $standarTypes) ? 'standar' : 'kebijakan';
    }
}
