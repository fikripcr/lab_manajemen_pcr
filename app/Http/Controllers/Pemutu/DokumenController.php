<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\DokumenRequest;
use App\Models\Pemutu\DokSub;
use App\Models\Pemutu\Dokumen;
use App\Services\Pemutu\DokumenService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DokumenController extends Controller
{
    public function __construct(protected DokumenService $dokumenService)
    {}

    public function index(Request $request)
    {
        $pageTitle = 'Dokumen SPMI';
        $jenis     = $request->query('jenis', 'visi'); // Default to 'visi' as it's the first tab

        $periods = Dokumen::select('periode')
            ->whereNotNull('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode');

        if ($periods->isEmpty()) {
            $periods->push(date('Y'));
        }

        // All possible types
        $jenisTypes = ['visi', 'misi', 'rjp', 'renstra', 'renop', 'standar', 'formulir', 'manual_prosedur'];

        $dokumentByJenis = [];
        foreach ($jenisTypes as $jt) {
            // We only need the data for the active jenis to be complete,
            // but for simple trees we can load what's needed.
            if ($jt === $jenis) {
                $dokumentByJenis[$jt] = $this->dokumenService->getDokumenByJenis($jt, $request->periode);
            } else {
                // If it's a small list, we can load it, or just leave it empty for AJAX if needed.
                // For now, let's load current requested one.
                $dokumentByJenis[$jt] = $jt === $jenis ? $this->dokumenService->getDokumenByJenis($jt, $request->periode) : collect();
            }
        }

        // Overwrite active one ensuring it's loaded
        $dokumentByJenis[$jenis] = $this->dokumenService->getDokumenByJenis($jenis, $request->periode);

        return view('pages.pemutu.dokumen.index', compact('pageTitle', 'dokumentByJenis', 'periods', 'jenis'));
    }

    public function create(Request $request)
    {
        $pageTitle = 'Tambah Dokumen';
        $activeTab = $request->query('tabs', 'kebijakan');

        $dokumens = $this->dokumenService->getHierarchicalDokumens();

        $parent       = null;
        $parentDokSub = null;
        $fixedJenis   = null;

        if ($request->has('parent_id')) {
            $parentId = decryptIdIfEncrypted($request->parent_id);
            $parent   = $this->dokumenService->getDokumenById($parentId);
            if ($parent) {
                $activeTab = $this->getTabByJenis($parent->jenis);

                if ($request->has('parent_doksub_id')) {
                    $doksubId     = decryptIdIfEncrypted($request->input('parent_doksub_id'));
                    $parentDokSub = DokSub::find($doksubId);
                    if ($parentDokSub) {
                        $fixedJenis = pemutuFixedJenis($parent->jenis);
                    }
                }
            }
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

        return view('pages.pemutu.dokumen.create', compact('dokumens', 'parent', 'allowedTypes', 'pageTitle', 'parentDokSub', 'fixedJenis'));
    }

    public function createStandar()
    {
        $pageTitle = 'Tambah Dokumen Standar';
        return view('pages.pemutu.dokumen.create_standar', compact('pageTitle'));
    }

    public function createDokSubAjax(Request $request)
    {
        $dokId   = (int) decryptIdIfEncrypted($request->query('dok_id'));
        $dokumen = $this->dokumenService->getDokumenById($dokId);
        if (! $dokumen) {
            \abort(404);
        }

        $dokSub = new DokSub(); // Empty model for create case
        return \view('pages.pemutu.dok-subs.create-edit-ajax', compact('dokumen', 'dokSub'));
    }

    public function editDokSubAjax(DokSub $dokSub)
    {
        return \view('pages.pemutu.dok-subs.create-edit-ajax', compact('dokSub'));
    }

    public function store(DokumenRequest $request)
    {
        $data    = $request->validated();
        $dokumen = $this->dokumenService->createDokumen($data);

        $redirectUrl = $this->getIndexUrlByJenis($dokumen->jenis) . '&id=' . $dokumen->dok_id . '&type=dokumen';
        if ($request->filled('parent_doksub_id')) {
            $redirectUrl = \route('pemutu.dok-subs.show', $request->input('parent_doksub_id'));
        }

        // If AJAX, we might want to skip redirect to allow inline UI refresh
        if ($request->ajax()) {
            return jsonSuccess('Dokumen berhasil dibuat.');
        }

        return jsonSuccess('Dokumen berhasil dibuat.', $redirectUrl);
    }

    public function show(Dokumen $dokumen)
    {
        // 1. Determine labels and types
        // 1. Determine labels and types
        $parentJenis = strtolower(trim($dokumen->jenis));
        $childLabel  = pemutuChildLabel($parentJenis);

        $isDokSubBased  = pemutuIsDokSubBased($parentJenis);
        $showIndikators = in_array($parentJenis, ['renop', 'standar']);

        $activeSubTab = \request()->get('subtab', 'overview');

        // 2. Prepare normalized data for unified component
        $data = [
            'item'           => $dokumen,
            'isDokumen'      => true,
            'childLabel'     => $childLabel,
            'isDokSubBased'  => $isDokSubBased,
            'showIndikators' => $showIndikators,
            'activeSubTab'   => $activeSubTab,
        ];

        // 3. Handle AJAX response (Return only the panel fragment)
        if (\request()->ajax() || \request()->has('ajax')) {
            return \view('pages.pemutu.shared._detail_panel', $data);
        }

        // 4. Handle Full Page Load
        return \view('pages.pemutu.dokumen.detail', array_merge($data, [
            'dokumen' => $dokumen,
        ]));
    }

    public function showRenopWithIndicators(Dokumen $dokumen)
    {

        $doksubs    = $dokumen->dokSubs;
        $indicators = collect();
        foreach ($doksubs as $doksub) {
            $indicators = $indicators->merge($doksub->indikators);
        }

        $pageTitle = 'Indikator untuk: ' . $dokumen->judul;
        return view('pages.pemutu.dokumen.renop_with_indicators', compact('dokumen', 'indicators', 'pageTitle'));
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

        return view('pages.pemutu.dokumen.edit', compact('dokumen', 'dokumens', 'allowedTypes'));
    }

    public function update(DokumenRequest $request, Dokumen $dokumen)
    {
        $this->dokumenService->updateDokumen($dokumen->dok_id, $request->validated());

        if ($request->ajax()) {
            return jsonSuccess('Dokumen berhasil diperbarui.');
        }

        $redirectUrl = $this->getIndexUrlByJenis($dokumen->jenis) . '&id=' . $dokumen->dok_id . '&type=dokumen';
        return jsonSuccess('Dokumen berhasil diperbarui.', $redirectUrl);
    }

    public function destroy(Dokumen $dokumen)
    {
        $redirectOpt = $this->getIndexUrlByJenis($dokumen->jenis);
        $this->dokumenService->deleteDokumen($dokumen->dok_id);
        return jsonSuccess('Dokumen berhasil dihapus.', $redirectOpt);
    }

    public function reorder(\App\Http\Requests\Hr\ReorderRequest $request)
    {
        $hierarchy = $request->validated('hierarchy');
        $this->dokumenService->reorderDokumens($hierarchy);
        return jsonSuccess('Urutan berhasil diperbarui.');
    }

    public function childrenData(Dokumen $dokumen)
    {
        $isDokSubBased = in_array(strtolower(trim($dokumen->jenis)), [
            'standar', 'formulir', 'manual_prosedur', 'renop',
            'visi', 'misi', 'rjp', 'renstra',
        ]);

        if ($isDokSubBased) {
            $query = DokSub::withCount(['childDokumens', 'indikators'])
                ->where('dok_id', $dokumen->dok_id)
                ->orderBy('seq');

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
                ->addColumn('jumlah_turunan', function ($row) use ($dokumen) {
                    $count    = $row->child_dokumens_count;
                    $indCount = $row->indikators_count;

                    if ($count <= 0 && $indCount <= 0) {
                        return '<span class="text-muted">-</span>';
                    }

                    $html = '';
                    if ($count > 0) {
                        $childJenis = match (strtolower(trim($dokumen->jenis))) {
                            'visi'    => 'Misi',
                            'misi'    => 'RJP',
                            'rjp'     => 'Renstra',
                            'renstra' => 'Renop',
                            default   => 'Dokumen'
                        };
                        $html .= '<span class="badge bg-blue-lt me-1" title="' . $count . ' ' . $childJenis . '"><i class="ti ti-files me-1"></i>' . $count . '</span>';
                    }

                    if ($indCount > 0) {
                        $html .= '<span class="badge bg-green-lt mt-1" title="' . $indCount . ' Indikator"><i class="ti ti-chart-bar me-1"></i>' . $indCount . '</span>';
                    }

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    return view('components.tabler.datatables-actions', [
                        'viewUrl'   => route('pemutu.dok-subs.show', $row->encrypted_doksub_id),
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
                    $childLabel = pemutuChildLabel($jenis);

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
                        'editUrl'   => route('pemutu.dokumen.edit', $row->encrypted_dok_id),
                        'editModal' => false,
                        'deleteUrl' => route('pemutu.dokumen.destroy', $row->encrypted_dok_id),
                    ])->render();
                })
                ->rawColumns(['judul', 'jumlah_turunan', 'action'])
                ->make(true);
        }
    }

    private function getIndexUrlByJenis($jenis)
    {
        $tab = pemutuTabByJenis($jenis);
        return route('pemutu.dokumen.index', ['tabs' => $tab]);
    }
}
