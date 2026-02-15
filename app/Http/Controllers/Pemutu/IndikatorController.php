<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\IndikatorRequest;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\LabelType;
use App\Models\Pemutu\OrgUnit;
use App\Models\Pemutu\Personil;
use App\Services\Pemutu\IndikatorService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class IndikatorController extends Controller
{
    protected $IndikatorService;

    public function __construct(IndikatorService $IndikatorService)
    {
        $this->IndikatorService = $IndikatorService;
    }

    public function index()
    {
        // Filters data
        $dokumens   = Dokumen::whereNull('parent_id')->orderBy('judul')->pluck('judul', 'dok_id')->toArray();
        $labelTypes = LabelType::orderBy('name')->pluck('name', 'labeltype_id')->toArray();
        $types      = ['standar' => 'Standar', 'renop' => 'Renop', 'performa' => 'Performa'];

        return view('pages.pemutu.indikators.index', compact('dokumens', 'labelTypes', 'types'));
    }

    public function paginate(Request $request)
    {
        $query = $this->IndikatorService->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('dokumen_judul', function ($row) {
                return $row->dokSubs->map(function ($ds) {
                    return $ds->dokumen->judul ?? '-';
                })->unique()->implode(', ') ?: '-';
            })
            ->addColumn('tipe', function ($row) {
                $color = match ($row->type) {
                    'standar'  => 'primary',
                    'renop'    => 'purple',
                    'performa' => 'success',
                    default    => 'secondary'
                };
                $label = match ($row->type) {
                    'standar'  => 'Standar',
                    'renop'    => 'Renop',
                    'performa' => 'Performa',
                    default    => ucfirst($row->type ?? '-')
                };
                return '<span class="badge bg-' . $color . '-lt">' . $label . '</span>';
            })
            ->addColumn('doksub_judul', function ($row) {
                return $row->dokSubs->pluck('judul')->implode(', ') ?: '-';
            })
            ->addColumn('labels', function ($row) {
                return '<div class="d-flex flex-wrap gap-1">' . $row->labels->map(function ($label) {
                    $color = $label->type->color ?? 'blue';
                    return '<span class="badge bg-' . $color . '-lt text-' . $color . '">' . e($label->name) . '</span>';
                })->implode('') . '</div>';
            })
            ->addColumn('action', function ($row) {
                $showUrl   = route('pemutu.indikators.show', $row->indikator_id);
                $editUrl   = route('pemutu.indikators.edit', $row->indikator_id);
                $deleteUrl = route('pemutu.indikators.destroy', $row->indikator_id);

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
            ->rawColumns(['tipe', 'labels', 'action'])
            ->make(true);
    }

    public function create(Request $request)
    {
        // View Dependencies
        $labelTypes = LabelType::with(['labels' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $orgUnits = OrgUnit::with('children')->where('level', 1)->orderBy('seq')->get();

        $dokumens = Dokumen::with('dokSubs')
            ->whereIn('jenis', ['renstra', 'renop', 'standar'])
            ->orderBy('judul')
            ->get();

        $parents = Indikator::where('type', 'standar')->orderBy('no_indikator')->get();

        $personils = Personil::orderBy('nama')->get();

        // Handle Contextual Pre-selection
        $parentDok = null;
        if ($request->has('parent_dok_id')) {
            $parentDok = Dokumen::with('dokSubs')->find($request->parent_dok_id);
            if ($parentDok) {
                // Determine suggested type
                $suggestedType = match ($parentDok->jenis) {
                    'renop'   => 'renop',
                    'standar' => 'standar',
                    default   => 'renop'
                };

                $request->merge([
                    'type'       => $request->get('type', $suggestedType),
                    'doksub_ids' => $request->get('doksub_ids', $parentDok->dokSubs->pluck('doksub_id')->toArray()),
                ]);
            }
        }

        return view('pages.pemutu.indikators.create', compact('labelTypes', 'orgUnits', 'dokumens', 'parents', 'personils', 'parentDok'));
    }

    public function store(IndikatorRequest $request)
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

            // Handle KPI Assignments Parsing
            if ($request->has('kpi_assign')) {
                $kpiData = [];
                foreach ($request->kpi_assign as $val) {
                    if (isset($val['selected']) && $val['selected'] == 1) {
                        unset($val['selected']);
                        $kpiData[] = $val;
                    }
                }
                $data['kpi_assignments'] = $kpiData;
            }

            $this->IndikatorService->createIndikator($data);

            $redirectUrl = route('pemutu.indikators.index');
            if ($request->has('parent_dok_id')) {
                $redirectUrl = route('pemutu.dokumens.show-renop-with-indicators', $request->parent_dok_id);
            }

            if ($request->ajax()) {
                return jsonSuccess('Indikator created successfully.', $redirectUrl);
            }

            return redirect($redirectUrl)->with('success', 'Indikator created successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $indikator = $this->IndikatorService->getIndikatorById($id);
        if (! $indikator) {
            abort(404);
        }

        return view('pages.pemutu.indikators.show', compact('indikator'));
    }

    public function edit($id)
    {
        $indikator = $this->IndikatorService->getIndikatorById($id);
        if (! $indikator) {
            abort(404);
        }

        $labelTypes = LabelType::with(['labels' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        $orgUnits = OrgUnit::with('children')->where('level', 1)->orderBy('seq')->get();

        $dokumens = Dokumen::with('dokSubs')
            ->whereIn('jenis', ['renstra', 'renop', 'standar'])
            ->orderBy('judul')
            ->get();

        $parents = Indikator::where('type', 'standar')
            ->where('indikator_id', '!=', $id)
            ->orderBy('no_indikator')
            ->get();

        $personils = Personil::orderBy('nama')->get();

        return view('pages.pemutu.indikators.edit', compact('indikator', 'labelTypes', 'orgUnits', 'dokumens', 'parents', 'personils'));
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

            if ($request->has('kpi_assign')) {
                $kpiData = [];
                foreach ($request->kpi_assign as $val) {
                    if (isset($val['selected']) && $val['selected'] == 1) {
                        unset($val['selected']);
                        $kpiData[] = $val;
                    }
                }
                $data['kpi_assignments'] = $kpiData;
            }

            // doksub_ids is already in $data from validated()

            $this->IndikatorService->updateIndikator($id, $data);

            $redirectUrl = route('pemutu.indikators.show', $id);

            if ($request->ajax()) {
                return jsonSuccess('Indikator updated successfully.', $redirectUrl);
            }

            return redirect($redirectUrl)->with('success', 'Indikator updated successfully.');
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->IndikatorService->deleteIndikator($id);

            return jsonSuccess('Indikator deleted successfully.', route('pemutu.indikators.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
