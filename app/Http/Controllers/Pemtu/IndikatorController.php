<?php
namespace App\Http\Controllers\Pemtu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemtu\IndikatorRequest;
use App\Models\Pemtu\DokSub;
use App\Models\Pemtu\Dokumen;
use App\Models\Pemtu\OrgUnit;
use App\Services\Pemtu\IndikatorService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class IndikatorController extends Controller
{
    protected $indikatorService;

    public function __construct(IndikatorService $indikatorService)
    {
        $this->indikatorService = $indikatorService;
    }

    public function index()
    {
        // Filters data
        $dokumens   = Dokumen::whereNull('parent_id')->orderBy('judul')->pluck('judul', 'dok_id')->toArray();
        $labelTypes = \App\Models\Pemtu\LabelType::orderBy('name')->pluck('name', 'labeltype_id')->toArray();

        return view('pages.pemtu.indikators.index', compact('dokumens', 'labelTypes'));
    }

    public function paginate(Request $request)
    {
        $query = $this->indikatorService->getFilteredQuery($request->all());

        return DataTables::of($query)
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

        // View Dependencies - Can be moved to separate Service helper if reused often
        $labelTypes = \App\Models\Pemtu\LabelType::with(['labels' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $orgUnits = OrgUnit::with('children')->where('level', 1)->orderBy('seq')->get();

        $dokumens = Dokumen::with('dokSubs')
            ->where('jenis', 'Renop')
            ->orderBy('judul')
            ->get();

        return view('pages.pemtu.indikators.create', compact('dokSub', 'labelTypes', 'orgUnits', 'dokumens'));
    }

    public function store(IndikatorRequest $request)
    {
        try {
            $data = $request->validated();

            // Handle Assignments Parsing (prepare for Service)
            if ($request->has('assignments')) {
                $syncData = [];
                foreach ($request->assignments as $unitId => $val) {
                    if (isset($val['selected']) && $val['selected'] == 1) {
                        $syncData[$unitId] = ['target' => $val['target'] ?? null];
                    }
                }
                $data['org_units'] = $syncData;
            }

            // Labels are usually flat array of IDs, so $request->labels is fine.
            // Related DokSubs ($request->related_doksubs) also fine.

            $this->indikatorService->createIndikator($data);

            return jsonSuccess('Indikator created successfully.', route('pemtu.indikators.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $indikator = $this->indikatorService->getIndikatorById($id);
        if (! $indikator) {
            abort(404);
        }

        return view('pages.pemtu.indikators.show', compact('indikator'));
    }

    public function edit($id)
    {
        $indikator = $this->indikatorService->getIndikatorById($id);
        if (! $indikator) {
            abort(404);
        }

        $labelTypes = \App\Models\Pemtu\LabelType::with(['labels' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $orgUnits = OrgUnit::with('children')->where('level', 1)->orderBy('seq')->get();

        $dokumens = Dokumen::with('dokSubs')
            ->where('jenis', 'Renop')
            ->orderBy('judul')
            ->get();

        return view('pages.pemtu.indikators.edit', compact('indikator', 'labelTypes', 'orgUnits', 'dokumens'));
    }

    public function update(IndikatorRequest $request, $id)
    {
        try {
            $data = $request->validated();

            // Handle Assignments Parsing
            if ($request->has('assignments')) {
                $syncData = [];
                foreach ($request->assignments as $unitId => $val) {
                    if (isset($val['selected']) && $val['selected'] == 1) {
                        $syncData[$unitId] = ['target' => $val['target'] ?? null];
                    }
                }
                $data['org_units'] = $syncData;
            }

            $this->indikatorService->updateIndikator($id, $data);

            return jsonSuccess('Indikator updated successfully.', route('pemtu.indikators.show', $id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->indikatorService->deleteIndikator($id);

            return jsonSuccess('Indikator deleted successfully.', route('pemtu.indikators.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
