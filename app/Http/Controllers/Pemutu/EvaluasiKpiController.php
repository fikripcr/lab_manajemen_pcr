<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\EvaluasiKpiRequest;
use App\Models\Pemutu\IndikatorPegawai;
use App\Models\Pemutu\PeriodeKpi;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\IndikatorPegawaiService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EvaluasiKpiController extends Controller
{
    public function __construct(
        protected IndikatorService $indikatorService,
        protected IndikatorPegawaiService $indikatorPegawaiService
    )
    {}

    public function index()
    {
        $pageTitle = 'Evaluasi KPI';
        $data      = $this->indikatorPegawaiService->getKpiPeriodes();

        return view('pages.pemutu.evaluasi-kpi.index', array_merge(compact('pageTitle'), $data));
    }

    public function show(PeriodeKpi $periode)
    {
        $user = auth()->user();

        return view('pages.pemutu.evaluasi-kpi.show', compact('periode', 'user'));
    }

    public function data(PeriodeKpi $periode)
    {
        $query = $this->indikatorPegawaiService->getKpiDataTableQuery($periode);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('hr_pegawai', function ($row) {
                return $row->pegawai?->nama ?? '-';
            })
            ->addColumn('indikator_full', function ($row) {
                $no   = $row->indikator?->no_indikator ?? '-';
                $nama = $row->indikator?->indikator ?? '-';

                return '<strong>' . $no . '</strong><br>' . $nama;
            })
            ->addColumn('target', function ($row) {
                return $row->target_value ?? '<span class="text-muted">-</span>';
            })
            ->addColumn('capaian', function ($row) {
                return $row->realization ?? '<span class="text-muted fst-italic">Belum diisi</span>';
            })
            ->addColumn('analisis', function ($row) {
                $text = $row->kpi_analisis ?? '-';
                $html = '<div style="max-height: 200px; overflow-y: auto;" class="mb-2">' . $text . '</div>';

                // Evidence items
                $evidenceHtml = '';
                if ($row->getMedia('kpi_attachments')->count() > 0) {
                    foreach ($row->getMedia('kpi_attachments') as $media) {
                        $url           = $media->getUrl();
                        $evidenceHtml .= '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-ghost-primary me-1 mb-1" title="Disisipkan: ' . e($media->file_name) . '" data-bs-toggle="tooltip"><i class="ti ti-file-download fs-3"></i></a>';
                    }
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
                    data-modal-title="Isi Evaluasi KPI"
                    data-modal-size="modal-xl">
                    Isi
                    </button>';
            })
            ->filterColumn('indikator', function ($query, $keyword) {
                $query->whereHas('indikator', function ($q) use ($keyword) {
                    $q->where('indikator', 'like', "%{$keyword}%")
                        ->orWhere('no_indikator', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['hr_pegawai', 'indikator_full', 'target', 'capaian', 'file', 'action', 'analisis'])
            ->make(true);
    }

    public function edit(IndikatorPegawai $indikatorPegawai)
    {
        return view('pages.pemutu.evaluasi-kpi.edit-ajax', $this->indikatorPegawaiService->getKpiEditData($indikatorPegawai, $this->indikatorService));
    }

    public function update(EvaluasiKpiRequest $request, IndikatorPegawai $indikatorPegawai)
    {
        $this->indikatorPegawaiService->updateKpi(
            $indikatorPegawai,
            $request->validated(),
            $this->indikatorService
        );

        if ($request->hasFile('filepond')) {
            foreach ($request->file('filepond') as $file) {
                $indikatorPegawai->addMedia($file)->toMediaCollection('kpi_attachments');
            }
        }

        return jsonSuccess('Evaluasi KPI berhasil disimpan.');
    }

    public function uploadFile(Request $request, IndikatorPegawai $indikatorPegawai)
    {
        $request->validate([
            'files'   => 'required|array',
            'files.*' => 'file|max:20480',
        ]);

        foreach ($request->file('files') as $file) {
            $indikatorPegawai->addMedia($file)->toMediaCollection('kpi_attachments');
        }

        logActivity('pemutu', 'Mengunggah ' . count($request->file('files')) . " file ke Evaluasi KPI ID: {$indikatorPegawai->indikator_pegawai_id}");

        return jsonSuccess('File berhasil diunggah.');
    }

    public function deleteFile(Request $request, IndikatorPegawai $indikatorPegawai, $mediaId)
    {
        $media = $indikatorPegawai->getMedia('kpi_attachments')->firstWhere('id', $mediaId);

        if (! $media) {
            return jsonError('File tidak ditemukan.');
        }

        $media->delete();
        logActivity('pemutu', "Menghapus file dari Evaluasi KPI ID: {$indikatorPegawai->indikator_pegawai_id}");

        return jsonSuccess('File berhasil dihapus.');
    }
}
