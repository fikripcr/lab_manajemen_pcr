<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\DokumenRequest;
use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Models\Shared\Personil;
use App\Services\Pemutu\DokumenService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DokumenController extends Controller
{
    public function __construct(protected DokumenService $dokumenService)
    {}

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
            $jenisTypes = ['kebijakan', 'visi', 'misi', 'rjp', 'renstra', 'renop'];
        }
        $dokumentByJenis = [];
        foreach ($jenisTypes as $jenis) {
            $dokumentByJenis[$jenis] = $this->dokumenService->getDokumenByJenis($jenis, $request->periode);
        }

        return view('pages.pemutu.dokumens.index', compact('pageTitle', 'dokumentByJenis', 'periods', 'activeTab'));
    }

    public function create(Request $request)
    {
        $pageTitle = 'Tambah Dokumen';
        $activeTab = $request->query('tabs', 'kebijakan');

        $dokumens = $this->dokumenService->getHierarchicalDokumens();

        $parent       = null;
        $parentDokSub = null;

        if ($request->has('parent_id')) {
            $parent = $this->dokumenService->getDokumenById($request->parent_id);
            if ($parent) {
                $activeTab = $this->getTabByJenis($parent->jenis);
            }
        }

        if ($request->has('parent_doksub_id')) {
            $parentDokSub = DokSub::find($request->parent_doksub_id);
        }

        if ($activeTab === 'standar') {
            $allowedTypes = [
                'standar'         => 'Standar',
                'formulir'        => 'Formulir',
                'manual_prosedur' => 'Manual Prosedur',
            ];
            $pageTitle = 'Tambah Dokumen Standar';
        } else {
            $allowedTypes = [
                'kebijakan' => 'Kebijakan',
                'visi'      => 'Visi',
                'misi'      => 'Misi',
                'rjp'       => 'Rencana Jangka Panjang (RJP)',
                'renstra'   => 'Rencana Strategis (Renstra)',
                'renop'     => 'Rencana Operasional (Renop)',
            ];
            $pageTitle = 'Tambah Dokumen Kebijakan';
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
            $dokumen = $this->dokumenService->createDokumen($data);

            $redirectUrl = $this->getIndexUrlByJenis($dokumen->jenis) . '&id=' . $dokumen->dok_id . '&type=dokumen';
            if ($request->filled('parent_doksub_id')) {
                $redirectUrl = route('pemutu.dok-subs.show', $request->parent_doksub_id);
            }

            return jsonSuccess('Dokumen berhasil dibuat.', $redirectUrl);
        } catch (Exception $e) {
            logError($e);
            return jsonError($e->getMessage());
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
        $allDocs  = $this->dokumenService->getHierarchicalDokumens();
        $dokumens = $allDocs->filter(function ($d) use ($dokumen) {
            return $d->dok_id != $dokumen->dok_id;
        });

        $activeTab = $this->getTabByJenis($dokumen->jenis);
        if ($activeTab === 'standar') {
            $allowedTypes = [
                'standar'         => 'Standar',
                'formulir'        => 'Formulir',
                'manual_prosedur' => 'Manual Prosedur',
            ];
        } else {
            $allowedTypes = [
                'kebijakan' => 'Kebijakan',
                'visi'      => 'Visi',
                'misi'      => 'Misi',
                'rjp'       => 'Rencana Jangka Panjang (RJP)',
                'renstra'   => 'Rencana Strategis (Renstra)',
                'renop'     => 'Rencana Operasional (Renop)',
            ];
        }

        return view('pages.pemutu.dokumens.edit', compact('dokumen', 'dokumens', 'allowedTypes'));
    }

    public function update(DokumenRequest $request, Dokumen $dokumen)
    {
        try {
            $this->dokumenService->updateDokumen($dokumen->dok_id, $request->validated());
            $redirectUrl = $this->getIndexUrlByJenis($dokumen->jenis) . '&id=' . $dokumen->dok_id . '&type=dokumen';

            return jsonSuccess('Dokumen berhasil diperbarui.', $redirectUrl);
        } catch (Exception $e) {
            logError($e);
            return jsonError($e->getMessage());
        }
    }

    public function destroy(Dokumen $dokumen)
    {
        try {
            $redirectOpt = $this->getIndexUrlByJenis($dokumen->jenis);
            $this->dokumenService->deleteDokumen($dokumen->dok_id);
            return jsonSuccess('Dokumen berhasil dihapus.', $redirectOpt);
        } catch (Exception $e) {
            logError($e);
            return jsonError($e->getMessage());
        }
    }

    public function reorder(\App\Http\Requests\Shared\ReorderRequest $request)
    {
        try {
            $hierarchy = $request->validated('hierarchy');
            $this->dokumenService->reorderDokumens($hierarchy);
            return jsonSuccess('Urutan berhasil diperbarui.');
        } catch (Exception $e) {
            logError($e);
            return jsonError($e->getMessage());
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
                    ->addColumn('jumlah_turunan', function ($row) {
                        return '<span class="text-muted">-</span>';
                    })
                    ->addColumn('action', function ($row) {
                        return view('components.tabler.datatables-actions', [
                            'editUrl'   => route('pemutu.dok-subs.edit', $row->encrypted_doksub_id),
                            'editModal' => true,
                            'deleteUrl' => route('pemutu.dok-subs.destroy', $row->encrypted_doksub_id),
                        ])->render();
                    })
                    ->rawColumns(['judul', 'jumlah_turunan', 'action'])
                    ->make(true);
            } else {
                $query = $this->dokumenService->getChildrenQuery($dokumen->dok_id);
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

                        $html = '<div class="badge bg-blue-lt">' . $row->children_count . ' ' . $childLabel . '</div>';

                        // Check for related Renop Indicators
                        $relatedIndicators = collect();
                        foreach ($row->dokSubs as $ds) {
                            foreach ($ds->indikators as $ind) {
                                if ($ind->type === 'renop') {
                                    $relatedIndicators->push($ind->kode ?? 'Renop');
                                }
                            }
                        }

                        if ($relatedIndicators->isNotEmpty()) {
                            $uniques = $relatedIndicators->unique()->take(3); // Limit to 3
                            foreach ($uniques as $kode) {
                                $html .= '<div class="mt-1"><span class="badge bg-purple-lt" title="Terkait Indikator Renop">Renop: ' . e($kode) . '</span></div>';
                            }
                            if ($relatedIndicators->count() > 3) {
                                $html .= '<div class="mt-1"><small class="text-muted">+' . ($relatedIndicators->count() - 3) . ' lainnya</small></div>';
                            }
                        }

                        return $html;
                    })
                    ->addColumn('action', function ($row) {
                        return view('components.tabler.datatables-actions', [
                            'editUrl'   => route('pemutu.dokumens.edit', $row->encrypted_dok_id),
                            'editModal' => false,
                            'deleteUrl' => route('pemutu.dokumens.destroy', $row->encrypted_dok_id),
                        ])->render();
                    })
                    ->rawColumns(['judul', 'jumlah_turunan', 'action'])
                    ->make(true);
            }
        } catch (Exception $e) {
            logError($e);
            return jsonError($e->getMessage());
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
