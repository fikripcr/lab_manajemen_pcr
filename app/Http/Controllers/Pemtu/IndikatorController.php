<?php
namespace App\Http\Controllers\Pemtu;

use App\Http\Controllers\Controller;
use App\Models\Pemtu\DokSub;
use App\Models\Pemtu\Dokumen;
use App\Models\Pemtu\Indikator;
use App\Models\Pemtu\OrgUnit;
use App\Services\Pemtu\IndikatorService;
use Illuminate\Http\Request;

class IndikatorController extends Controller
{
    protected $indikatorService;

    public function __construct(IndikatorService $indikatorService)
    {
        $this->indikatorService = $indikatorService;
    }

    public function index()
    {
        return view('pages.pemtu.indikators.index');
    }

    public function paginate()
    {
        $data = Indikator::with(['dokSub.dokumen', 'labels.type'])->select('indikator.*');

        return \Yajra\DataTables\DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('dokumen_judul', function ($row) {
                return $row->dokSub->dokumen->judul ?? '-';
            })
            ->addColumn('doksub_judul', function ($row) {
                return $row->dokSub->judul ?? '-';
            })
            ->addColumn('labels', function ($row) {
                return '<div class="d-flex flex-wrap gap-1">' . $row->labels->map(function ($label) {
                    $color = $label->type->color ?? 'blue';
                    return '<span class="badge bg-' . $color . '-lt text-' . $color . '">' . e($label->name) . '</span>';
                })->implode('') . '</div>';
            })
            ->addColumn('action', function ($row) {
                $showUrl   = route('pemtu.indikators.show', $row->indikator_id);
                $editUrl   = route('pemtu.indikators.edit', $row->indikator_id);
                $deleteUrl = route('pemtu.indikators.destroy', $row->indikator_id);

                return '
                    <div class="btn-list flex-nowrap justify-content-end">
                        <a href="' . $showUrl . '" class="btn btn-sm btn-icon btn-ghost-info" title="Detail">
                            <i class="ti ti-eye"></i>
                        </a>
                        <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-ghost-primary" title="Edit">
                            <i class="ti ti-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-ghost-danger ajax-delete" data-url="' . $deleteUrl . '" data-title="Hapus Indikator?" data-text="Data ini akan dihapus permanen.">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['labels', 'action'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $dokSubId = $request->query('doksub_id');
        $dokSub   = $dokSubId ? DokSub::with('dokumen')->find($dokSubId) : null;

        // Get LabelTypes with Labels for separate inputs
        $labelTypes = \App\Models\Pemtu\LabelType::with(['labels' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        // Fetch Root OrgUnits (Level 1) with Children for Hierarchy
        $orgUnits = OrgUnit::with('children')->where('level', 1)->orderBy('seq')->get();

        // Get Renop Documents only
        $dokumens = Dokumen::with('dokSubs')
            ->where('jenis', 'Renop') // Filter specific for Indikator
            ->orderBy('judul')
            ->get();

        return view('pages.pemtu.indikators.create', compact('dokSub', 'labelTypes', 'orgUnits', 'dokumens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doksub_id'       => 'required|exists:dok_sub,doksub_id',
            'indikator'       => 'required|string',
            'target'          => 'nullable|string',
            'jenis_indikator' => 'nullable|string|max:20',
            'labels'          => 'array',
            'org_units'       => 'array',
        ]);

        $data = $request->all();

        // Auto Generate Number and Seq via Service
        $dokSub               = DokSub::with('dokumen')->findOrFail($request->doksub_id);
        $data['no_indikator'] = $this->indikatorService->generateNoIndikator($dokSub);
        $data['seq']          = $this->indikatorService->generateSeq($request->doksub_id);

        $indikator = Indikator::create($data);

        // Sync Pivots
        if ($request->has('labels')) {
            // Flatten labels because they come as labels[type_id][] potentially or just labels[] if multiple selects share same name
            $indikator->labels()->sync($request->labels);
        }

        // Handle Assignments (Unit + Target)
        if ($request->has('assignments')) {
            $syncData = [];
            foreach ($request->assignments as $unitId => $val) {
                // Check if unit is selected
                if (isset($val['selected']) && $val['selected'] == 1) {
                    $syncData[$unitId] = ['target' => $val['target'] ?? null];
                }
            }
            $indikator->orgUnits()->sync($syncData);
        }
        // Related DokSubs might be handled if passed, but typically implied by parent doksub_id
        // If user selects other related doksubs manually:
        if ($request->has('related_doksubs')) {
            $indikator->relatedDokSubs()->sync($request->related_doksubs);
        }

        return response()->json([
            'message'  => 'Indikator created successfully.',
            'redirect' => route('pemtu.indikators.index'),
        ]);
    }

    public function show($id)
    {
        $indikator = Indikator::with(['labels', 'orgUnits', 'relatedDokSubs', 'dokSub.dokumen'])->findOrFail($id);
        return view('pages.pemtu.indikators.show', compact('indikator'));
    }

    public function edit($id)
    {
        $indikator = Indikator::with(['labels', 'orgUnits', 'relatedDokSubs'])->findOrFail($id);

        $labelTypes = \App\Models\Pemtu\LabelType::with(['labels' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        // Fetch Root OrgUnits (Level 1) with Children for Hierarchy
        $orgUnits = OrgUnit::with('children')->where('level', 1)->orderBy('seq')->get();

        // Get Renop Documents only
        $dokumens = Dokumen::with('dokSubs')
            ->where('jenis', 'Renop')
            ->orderBy('judul')
            ->get();

        return view('pages.pemtu.indikators.edit', compact('indikator', 'labelTypes', 'orgUnits', 'dokumens'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_indikator'    => 'nullable|string|max:20',
            'indikator'       => 'required|string',
            'target'          => 'nullable|string',
            'jenis_indikator' => 'nullable|string|max:20',
            'seq'             => 'nullable|integer',
            'labels'          => 'array',
            'org_units'       => 'array',
            'related_doksubs' => 'array',
        ]);

        $indikator = Indikator::findOrFail($id);
        $indikator->update($request->all());

        // Sync Pivots
        if ($request->has('labels')) {
            $indikator->labels()->sync($request->labels);
        } else {
            $indikator->labels()->detach();
        }

        // Handle Assignments (Unit + Target)
        if ($request->has('assignments')) {
            $syncData = [];
            foreach ($request->assignments as $unitId => $val) {
                if (isset($val['selected']) && $val['selected'] == 1) {
                    $syncData[$unitId] = ['target' => $val['target'] ?? null];
                }
            }
            $indikator->orgUnits()->sync($syncData);
        } else {
            $indikator->orgUnits()->detach();
        }

        if ($request->has('related_doksubs')) {
            $indikator->relatedDokSubs()->sync($request->related_doksubs);
        } else {
            $indikator->relatedDokSubs()->detach();
        }

        // Redirect to Detail Page instead of JSON
        return redirect()->route('pemtu.indikators.show', $indikator->indikator_id)
            ->with('success', 'Indikator updated successfully.');
    }

    public function destroy($id)
    {
        $indikator = Indikator::findOrFail($id);
        $doksubId  = $indikator->doksub_id;
        $indikator->delete();

        return response()->json([
            'success'  => true,
            'message'  => 'Indikator deleted successfully.',
            'redirect' => route('pemtu.indikators.index'),
        ]);
    }
}
