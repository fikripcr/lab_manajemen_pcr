<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\EvaluasiKpiRequest;
use App\Models\Pemutu\IndikatorPegawai;
use App\Models\Pemutu\PeriodeKpi;
use App\Services\Pemutu\EvaluasiKpiService;

use Yajra\DataTables\Facades\DataTables;

class EvaluasiKpiController extends Controller
{
    public function __construct(protected EvaluasiKpiService $EvaluasiKpiService)
    {}

    public function index()
    {
        $pageTitle = 'Evaluasi KPI';
        $data      = $this->EvaluasiKpiService->getPeriodes();

        return view('pages.pemutu.evaluasi-kpi.index', array_merge(compact('pageTitle'), $data));
    }

    public function show(PeriodeKpi $periode)
    {
        $user = auth()->user();
        return view('pages.pemutu.evaluasi-kpi.show', compact('periode', 'user'));
    }

    public function data(PeriodeKpi $periode)
    {
        $user = auth()->user();

        $query = IndikatorPegawai::with('indikator')
            ->where('periode_kpi_id', $periode->periode_kpi_id)
            ->where('pegawai_id', $user->pegawai?->pegawai_id);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('indikator_full', function ($row) {
                return '<strong>' . ($row->indikator->no_indikator ?? '-') . '</strong><br>' . $row->indikator->indikator;
            })
            ->addColumn('target', function ($row) {
                return $row->target_value ?? '<span class="text-muted">-</span>';
            })
            ->addColumn('capaian', function ($row) {
                return $row->realization ?? '<span class="text-muted fst-italic">Belum diisi</span>';
            })
            ->addColumn('analisis', function ($row) {
                $text = $row->kpi_analisis ?? '-';
                $html = '<div style="max-height: 200px; overflow-y: auto;" class="mb-2">' . nl2br(e($text)) . '</div>';

                // Evidence items
                $evidenceHtml = '';
                if ($row->attachment) {
                    $url           = route('pemutu.evaluasi-kpi.download', $row->encrypted_indikator_pegawai_id);
                    $evidenceHtml .= '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-ghost-primary me-1 mb-1" title="Unduh File Pendukung" data-bs-toggle="tooltip"><i class="ti ti-file-download fs-3"></i></a>';
                }

                if (! empty($row->kpi_links)) {
                    $links = json_decode($row->kpi_links, true) ?? [];
                    foreach ($links as $link) {
                        $name          = htmlspecialchars($link['name'] ?? 'Tautan');
                        $url           = htmlspecialchars($link['url'] ?? '#');
                        $evidenceHtml .= '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-ghost-info me-1 mb-1" title="' . $name . '" data-bs-toggle="tooltip"><i class="ti ti-link fs-3"></i></a>';
                    }
                }

                if ($evidenceHtml) {
                    $html .= '<div class="d-flex flex-wrap border-top pt-2">' . $evidenceHtml . '</div>';
                }

                return $html;
            })
            ->addColumn('action', function ($row) {
                return '<button type="button" class="btn btn-sm btn-primary ajax-modal-btn"
                    data-url="' . route('pemutu.evaluasi-kpi.edit', $row->encrypted_indikator_pegawai_id) . '"
                    data-modal-title="Isi Evaluasi KPI">
                    Isi KPI
                    </button>';
            })
            ->rawColumns(['indikator_full', 'target', 'capaian', 'file', 'action'])
            ->make(true);
    }

    public function edit(IndikatorPegawai $indikatorPegawai)
    {
        return view('pages.pemutu.evaluasi-kpi.edit-ajax', $this->EvaluasiKpiService->getEditData($indikatorPegawai));
    }

    public function update(EvaluasiKpiRequest $request, IndikatorPegawai $indikatorPegawai)
    {
        $this->EvaluasiKpiService->update(
            $indikatorPegawai,
            $request->validated(),
            $request->hasFile('attachment') ? $request->file('attachment') : null
        );

        return jsonSuccess('Evaluasi KPI berhasil disimpan.', route('pemutu.evaluasi-kpi.show', $indikatorPegawai->periodeKpi->encrypted_periode_kpi_id));
    }

    public function downloadAttachment(IndikatorPegawai $indikatorPegawai)
    {
        $ext      = pathinfo($indikatorPegawai->attachment ?? '', PATHINFO_EXTENSION);
        $filename = 'KPI_' . str_replace('/', '_', $indikatorPegawai->indikator->no_indikator ?? 'file') . '_' . date('Ymd') . ($ext ? '.' . $ext : '');

        return downloadStorageFile($indikatorPegawai->attachment, $filename, logActivity: true);
    }
}

