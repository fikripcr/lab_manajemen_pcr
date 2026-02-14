<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\IndikatorRequest;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\Personil;
use App\Services\Pemutu\IndikatorService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KpiController extends Controller
{
    protected $IndikatorService;

    public function __construct(IndikatorService $IndikatorService)
    {
        $this->IndikatorService = $IndikatorService;
    }

    public function index()
    {
        // Filters for the view
        $parents  = Indikator::where('type', 'standar')->orderBy('no_indikator')->pluck('indikator', 'indikator_id')->toArray();
        $dokumens = Dokumen::whereNull('parent_id')
            ->where('jenis', 'standar')
            ->orderBy('judul')
            ->pluck('judul', 'dok_id')
            ->toArray();

        return view('pages.pemutu.kpi.index', compact('parents', 'dokumens'));
    }

    public function paginate(Request $request)
    {
        // Force type 'performa' for this controller
        $filters         = $request->all();
        $filters['type'] = 'performa';

        $query = $this->IndikatorService->getFilteredQuery($filters);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('parent_info', function ($row) {
                if ($row->parent) {
                    return '<div class="text-muted small">Induk: ' . $row->parent->no_indikator . '</div>
                            <div class="text-truncate" style="max-width: 300px;" title="' . e($row->parent->indikator) . '">' . e($row->parent->indikator) . '</div>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('target_info', function ($row) {
                $count = $row->personils->count();
                if ($count > 0) {
                    return '<span class="badge bg-purple-lt">' . $count . ' Pegawai</span>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function ($row) {
                $editUrl   = route('pemutu.kpi.edit', $row->indikator_id);
                $deleteUrl = route('pemutu.kpi.destroy', $row->indikator_id);
                // $showUrl   = route('pemutu.indikators.show', $row->indikator_id); // Reuse existing show or create new if needed

                return '
                    <div class="btn-list flex-nowrap justify-content-end">
                        <a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-ghost-primary" title="Edit">
                            <i class="ti ti-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-ghost-danger ajax-delete" data-url="' . $deleteUrl . '" data-title="Hapus Sasaran Kinerja?" data-text="Data ini akan dihapus permanen.">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['parent_info', 'target_info', 'action'])
            ->make(true);
    }

    public function create()
    {
        // Data for the form
        $parents = Indikator::where('type', 'standar')
            ->orderBy('no_indikator')
            ->get();

        $personils = Personil::orderBy('nama')->get();

        // Dokumen list for context if needed (though KPI usually links to Parent Indikator)
        // But the `IndikatorRequest` might assume `doksub_ids`.
        // Logic: A KPI usually belongs to the same DokSub as its Parent Standard?
        // Or strictly hierarchical?
        // For now, let's keep it simple: required fields only.

        return view('pages.pemutu.kpi.create', compact('parents', 'personils'));
    }

    public function store(IndikatorRequest $request)
    {
        try {
            $data         = $request->validated();
            $data['type'] = 'performa'; // Ensure type is performa

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

            // Auto-assign doksub_ids from parent if not present?
            // If parent_id is set, we might want to copy the doksub relation.
            if (! empty($data['parent_id']) && empty($data['doksub_ids'])) {
                $parent = Indikator::with('dokSubs')->find($data['parent_id']);
                if ($parent && $parent->dokSubs->isNotEmpty()) {
                    $data['doksub_ids'] = $parent->dokSubs->pluck('doksub_id')->toArray();
                }
            }

            $this->IndikatorService->createIndikator($data);

            return jsonSuccess('Sasaran Kinerja berhasil dibuat.', route('pemutu.kpi.index'));

        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        $indikator = $this->IndikatorService->getIndikatorById($id);
        if (! $indikator || $indikator->type !== 'performa') {
            abort(404);
        }

        $parents = Indikator::where('type', 'standar')
            ->where('indikator_id', '!=', $id)
            ->orderBy('no_indikator')
            ->get();

        $personils = Personil::orderBy('nama')->get();

        return view('pages.pemutu.kpi.edit', compact('indikator', 'parents', 'personils'));
    }

    public function update(IndikatorRequest $request, $id)
    {
        try {
            $data         = $request->validated();
            $data['type'] = 'performa';

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

            // Auto-assign doksub_ids from parent if not present
            if (! empty($data['parent_id']) && empty($data['doksub_ids'])) {
                $parent = Indikator::with('dokSubs')->find($data['parent_id']);
                if ($parent && $parent->dokSubs->isNotEmpty()) {
                    $data['doksub_ids'] = $parent->dokSubs->pluck('doksub_id')->toArray();
                }
            }

            $this->IndikatorService->updateIndikator($id, $data);

            return jsonSuccess('Sasaran Kinerja berhasil diperbarui.', route('pemutu.kpi.index'));

        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->IndikatorService->deleteIndikator($id);
            return jsonSuccess('Sasaran Kinerja berhasil dihapus.', route('pemutu.kpi.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
