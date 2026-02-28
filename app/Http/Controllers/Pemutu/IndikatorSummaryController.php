<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\IndikatorSummary;
use App\Models\Pemutu\IndikatorSummaryPerforma;
use App\Models\Pemutu\IndikatorSummaryStandar;
use App\Models\Pemutu\PeriodeSpmi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class IndikatorSummaryController extends Controller
{
    /**
     * Redirect to default tab (Standar).
     */
    public function index()
    {
        return redirect()->route('pemutu.indikator-summary.standar');
    }

    /**
     * Display halaman Summary Indikator Standar.
     */
    public function standar()
    {
        $pageTitle = 'Summary Indikator Standar';
        $periodes  = PeriodeSpmi::orderBy('periode', 'desc')->get();

        // Global summary statistics untuk Standar
        $totalIndikator       = DB::table('pemutu_indikator')->where('type', 'standar')->count();
        $totalIndikatorActive = DB::table('pemutu_indikator')->where('type', 'standar')->whereNull('deleted_at')->count();

        // Summary ED (hanya untuk standar)
        $edTotalUnits = DB::table('pemutu_indikator_orgunit')
            ->join('pemutu_indikator', 'pemutu_indikator_orgunit.indikator_id', '=', 'pemutu_indikator.indikator_id')
            ->where('pemutu_indikator.type', 'standar')
            ->count();

        $uniqueAssignedStandar = DB::table('pemutu_indikator_orgunit')
            ->join('pemutu_indikator', 'pemutu_indikator_orgunit.indikator_id', '=', 'pemutu_indikator.indikator_id')
            ->where('pemutu_indikator.type', 'standar')
            ->distinct('pemutu_indikator_orgunit.indikator_id')
            ->count('pemutu_indikator_orgunit.indikator_id');

        $edFilledUnits = DB::table('pemutu_indikator_orgunit')
            ->join('pemutu_indikator', 'pemutu_indikator_orgunit.indikator_id', '=', 'pemutu_indikator.indikator_id')
            ->where('pemutu_indikator.type', 'standar')
            ->whereNotNull('pemutu_indikator_orgunit.ed_capaian')
            ->where('pemutu_indikator_orgunit.ed_capaian', '!=', '')
            ->count();

        // Summary AMI (hanya untuk standar)
        $amiAssessed = DB::table('pemutu_indikator_orgunit')
            ->join('pemutu_indikator', 'pemutu_indikator_orgunit.indikator_id', '=', 'pemutu_indikator.indikator_id')
            ->where('pemutu_indikator.type', 'standar')
            ->whereNotNull('pemutu_indikator_orgunit.ami_hasil_akhir')
            ->count();
        $amiKts = DB::table('pemutu_indikator_orgunit')
            ->join('pemutu_indikator', 'pemutu_indikator_orgunit.indikator_id', '=', 'pemutu_indikator.indikator_id')
            ->where('pemutu_indikator.type', 'standar')
            ->where('pemutu_indikator_orgunit.ami_hasil_akhir', 0)
            ->count();
        $amiTerpenuhi = DB::table('pemutu_indikator_orgunit')
            ->join('pemutu_indikator', 'pemutu_indikator_orgunit.indikator_id', '=', 'pemutu_indikator.indikator_id')
            ->where('pemutu_indikator.type', 'standar')
            ->where('pemutu_indikator_orgunit.ami_hasil_akhir', 1)
            ->count();
        $amiTerlampaui = DB::table('pemutu_indikator_orgunit')
            ->join('pemutu_indikator', 'pemutu_indikator_orgunit.indikator_id', '=', 'pemutu_indikator.indikator_id')
            ->where('pemutu_indikator.type', 'standar')
            ->where('pemutu_indikator_orgunit.ami_hasil_akhir', 2)
            ->count();

        // Summary Pengendalian (hanya untuk standar)
        $pengendFilled = DB::table('pemutu_indikator_orgunit')
            ->join('pemutu_indikator', 'pemutu_indikator_orgunit.indikator_id', '=', 'pemutu_indikator.indikator_id')
            ->where('pemutu_indikator.type', 'standar')
            ->whereNotNull('pemutu_indikator_orgunit.pengend_status')
            ->where('pemutu_indikator_orgunit.pengend_status', '!=', '')
            ->count();

        return view('pages.pemutu.indikator-summary.standar', compact(
            'pageTitle',
            'periodes',
            'totalIndikator',
            'totalIndikatorActive',
            'edTotalUnits',
            'edFilledUnits',
            'amiAssessed',
            'amiKts',
            'amiTerpenuhi',
            'amiTerlampaui',
            'pengendFilled',
            'uniqueAssignedStandar'
        ));
    }

    /**
     * Display halaman Summary Indikator Performa (KPI).
     */
    public function performa()
    {
        $pageTitle = 'Summary Indikator Performa (KPI)';
        $periodes  = PeriodeSpmi::orderBy('periode', 'desc')->get();

        // Global summary statistics untuk Performa
        $totalIndikator       = DB::table('pemutu_indikator')->where('type', 'performa')->count();
        $totalIndikatorActive = DB::table('pemutu_indikator')->where('type', 'performa')->whereNull('deleted_at')->count();

        // Summary KPI
        $kpiTotalPegawai = DB::table('pemutu_indikator_pegawai')
            ->join('pemutu_indikator', 'pemutu_indikator_pegawai.indikator_id', '=', 'pemutu_indikator.indikator_id')
            ->where('pemutu_indikator.type', 'performa')
            ->distinct('pegawai_id')
            ->count('pegawai_id');

        $kpiAvgScore = DB::table('pemutu_indikator_pegawai')
            ->join('pemutu_indikator', 'pemutu_indikator_pegawai.indikator_id', '=', 'pemutu_indikator.indikator_id')
            ->where('pemutu_indikator.type', 'performa')
            ->avg('pemutu_indikator_pegawai.score');

        $pegawais = \App\Models\Shared\Pegawai::whereHas('latestDataDiri')->get()->sortBy(function ($pegawai) {
            return $pegawai->nama;
        });

        $units = \App\Models\Shared\StrukturOrganisasi::orderBy('name')->get();

        return view('pages.pemutu.indikator-summary.performa', compact(
            'pageTitle',
            'periodes',
            'totalIndikator',
            'totalIndikatorActive',
            'kpiTotalPegawai',
            'kpiAvgScore',
            'pegawais',
            'units'
        ));
    }

    /**
     * Data untuk DataTable - Standar.
     */
    public function dataStandar(Request $request)
    {
        $filters = $request->only(['kelompok_indikator', 'year', 'search']);

        $query = IndikatorSummaryStandar::query();

        // Filter by kelompok indikator
        if (! empty($filters['kelompok_indikator'])) {
            $query->where('kelompok_indikator', $filters['kelompok_indikator']);
        }

        // Filter by year
        if (! empty($filters['year'])) {
            $query->whereYear('periode_mulai', $filters['year']);
        }

        // Search - integrated with DataTable search
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('no_indikator', 'LIKE', "%{$search}%")
                    ->orWhere('indikator', 'LIKE', "%{$search}%")
                    ->orWhere('parent_no_indikator', 'LIKE', "%{$search}%")
                    ->orWhere('label_details', 'LIKE', "%{$search}%")
                    ->orWhere('all_unit_names', 'LIKE', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('indikator_full', function ($row) {
                $html  = '<div class="d-flex flex-column">';
                $html .= '<strong class="text-primary">' . e($row->no_indikator ?? '-') . '</strong>';
                $html .= '<p class="mb-1">' . e($row->indikator ?? '-') . '</p>';
                $html .= '<div><span class="status status-azure">' . e($row->unit_name ?? $row->unit_code ?? '-') . '</span></div>';
                $html .= '</div>';
                return $html;
            })
            ->addColumn('parent_info', function ($row) {
                if ($row->parent_no_indikator) {
                    return '<span class="status status-azure">' . e($row->parent_no_indikator) . '</span>';
                }
                return '<span class="text-muted fst-italic">-</span>';
            })
            ->addColumn('labels', function ($row) {
                if (empty($row->label_details) || $row->label_details === '-') {
                    return '<span class="text-muted fst-italic small">-</span>';
                }

                $labels = explode(', ', $row->label_details);
                $html   = '<div class="d-flex flex-wrap gap-1">';
                foreach ($labels as $label) {
                    if (strpos($label, '|') !== false) {
                        [$name, $color]  = explode('|', $label);
                        $html           .= '<span class="status status-' . e($color) . '">' . e($name) . '</span>';
                    }
                }
                $html .= '</div>';
                return $html;
            })
            ->addColumn('ed_detail', function ($row) {
                $capaian = $row->ed_capaian ?? '-';
                $skala   = $row->ed_skala !== null ? '[' . $row->ed_skala . ']' : '';

                return '<div class="text-center"><strong class="text-success fs-3 d-block">' . e($capaian) . '</strong><span class="text-muted small">' . $skala . '</span></div>';
            })
            ->addColumn('ed_analisis', function ($row) {
                return $this->renderTruncatedText($row->ed_analisis, 'text-info');
            })
            ->addColumn('ami_detail', function ($row) {
                if (! $row->ami_hasil_label) {
                    return '<span class="text-muted fst-italic small">Belum dinilai</span>';
                }

                $badgeColor = match ($row->ami_hasil_label) {
                    'KTS'        => 'danger',
                    'Terpenuhi'  => 'success',
                    'Terlampaui' => 'info',
                    default      => 'secondary',
                };

                $html = '<div class="mb-2"><span class="badge bg-' . $badgeColor . '-lt">' . e($row->ami_hasil_label) . '</span></div>';
                if ($row->ami_hasil_temuan && $row->ami_hasil_temuan !== '-') {
                    $html .= $this->renderTruncatedText($row->ami_hasil_temuan, 'text-muted small');
                }

                return $html;
            })
            ->addColumn('ami_rekomendasi', function ($row) {
                return $this->renderTruncatedText($row->ami_hasil_temuan_rekom, 'text-muted');
            })
            ->addColumn('pengend_detail', function ($row) {
                if (! $row->pengend_status) {
                    return '<span class="text-muted fst-italic small">Belum ada</span>';
                }

                $badgeColor = match (strtolower($row->pengend_status)) {
                    'selesai'     => 'success',
                    'proses'      => 'warning',
                    'belum'       => 'danger',
                    'penyesuaian' => 'azure',
                    default       => 'secondary',
                };

                $html = '<div class="text-center mb-2"><span class="badge bg-' . $badgeColor . '-lt">' . e($row->pengend_status) . '</span></div>';
                if ($row->pengend_analisis && $row->pengend_analisis !== '-') {
                    $html .= $this->renderTruncatedText($row->pengend_analisis, 'text-muted small');
                }

                return $html;
            })
            ->addColumn('action', function ($row) {
                $html  = '<div class="btn-group btn-group-sm" role="group">';
                $html .= '<a href="' . route('pemutu.indikators.show', encryptId($row->indikator_id)) . '" class="btn btn-ghost-primary" title="Detail Indikator">';
                $html .= '<i class="ti ti-eye"></i>';
                $html .= '</a>';
                $html .= '</div>';
                return $html;
            })
            ->rawColumns([
                'indikator_full',
                'parent_info',
                'labels',
                'ed_detail',
                'ed_analisis',
                'ami_detail',
                'ami_rekomendasi',
                'pengend_detail',
                'action',
            ])
            ->make(true);
    }

    /**
     * Data untuk DataTable - Performa.
     */
    public function dataPerforma(Request $request)
    {
        $filters = $request->only(['kelompok_indikator', 'year', 'search', 'pegawai_id', 'unit_id']);

        $query = IndikatorSummaryPerforma::query();

        // Filter by kelompok indikator
        if (! empty($filters['kelompok_indikator'])) {
            $query->ofKelompok($filters['kelompok_indikator']);
        }

        // Filter by year
        if (! empty($filters['year'])) {
            $query->ofYear($filters['year']);
        }

        // Filter by pegawai
        if (! empty($filters['pegawai_id'])) {
            $query->ofPegawai($filters['pegawai_id']);
        }

        // Filter by unit
        if (! empty($filters['unit_id'])) {
            $query->ofUnit($filters['unit_id']);
        }

        // Search - integrated with DataTable search
        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('indikator_full', function ($row) {
                $html  = '<div class="row">';
                $html .= '<div class="col-12">';
                $html .= '<strong class="text-primary">' . e($row->no_indikator ?? '-') . '</strong>';
                $html .= '</div>';
                $html .= '<div class="col-12">';
                $html .= '<p class="mb-0 small text-muted">' . e($row->indikator ?? '-') . '</p>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->addColumn('parent_info', function ($row) {
                if ($row->parent_no_indikator) {
                    return '<span class="status status-azure">' . e($row->parent_no_indikator) . '</span>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('labels', function ($row) {
                if (empty($row->all_labels)) {
                    return '<span class="text-muted fst-italic small">-</span>';
                }

                $labels = explode(', ', $row->all_labels);
                $colors = explode(', ', $row->all_label_colors ?? '');
                $html   = '<div class="d-flex flex-wrap gap-1">';

                foreach ($labels as $index => $label) {
                    $color  = $colors[$index] ?? 'secondary';
                    $html  .= '<span class="status status-' . $color . '">' . e($label) . '</span>';
                }

                $html .= '</div>';
                return $html;
            })
            ->addColumn('kpi_detail', function ($row) {
                $statusColor = match ($row->kpi_status) {
                    'submitted' => 'info',
                    'approved'  => 'success',
                    'rejected'  => 'danger',
                    default     => 'secondary',
                };

                $html  = '<div class="row">';
                $html .= '<div class="col-12 mb-1">';
                $html .= '<strong class="text-primary d-block">' . e($row->pegawai_name ?? '-') . '</strong>';
                $html .= '<span class="text-muted small d-block">' . e($row->pegawai_nip ?? '-') . '</span>';
                $html .= '</div>';

                if ($row->unit_name) {
                    $html .= '<div class="col-12 mb-1">';
                    $html .= '<span class="status status-azure status-lite small">' . e($row->unit_name) . '</span>';
                    $html .= '</div>';
                }

                $html .= '<div class="col-12">';
                $html .= '<span class="badge bg-' . $statusColor . '-lt">' . ucfirst($row->kpi_status ?? 'draft') . '</span>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->addColumn('kpi_score', function ($row) {
                $score  = $row->kpi_score !== null ? number_format($row->kpi_score, 1) : '-';
                $target = $row->kpi_target !== null ? e($row->kpi_target) : '-';
                $weight = $row->kpi_weight !== null ? e($row->kpi_weight) . '%' : '-';

                $html  = '<div class="text-center">';
                $html .= '<div class="h3 mb-0 text-primary">' . $score . '</div>';
                $html .= '<div class="text-muted small">Target: ' . $target . '</div>';
                $html .= '<div class="text-muted small">Bobot: ' . $weight . '</div>';
                $html .= '</div>';

                return $html;
            })
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group btn-group-sm" role="group">';

                // Route for editing this specific KPI assignment
                $html .= '<a href="' . route('pemutu.evaluasi-kpi.index', ['pegawai_id' => $row->pegawai_id]) . '" class="btn btn-ghost-success" title="Evaluasi KPI Pegawai">';
                $html .= '<i class="ti ti-clipboard-data"></i>';
                $html .= '</a>';
                $html .= '</div>';

                return $html;
            })
            ->rawColumns([
                'indikator_full',
                'parent_info',
                'labels',
                'kpi_detail',
                'kpi_score',
                'action',
            ])
            ->make(true);
    }

    /**
     * Display detail indikator.
     */
    public function detail($id)
    {
        $indikator = IndikatorSummary::findOrFail($id);

        // Get detailed data from related tables
        $edDetails = DB::table('pemutu_indikator_orgunit')
            ->join('struktur_organisasi', 'pemutu_indikator_orgunit.org_unit_id', '=', 'struktur_organisasi.orgunit_id')
            ->where('pemutu_indikator_orgunit.indikator_id', $indikator->indikator_id)
            ->whereNotNull('pemutu_indikator_orgunit.ed_capaian')
            ->select(
                'struktur_organisasi.name as unit_name',
                'struktur_organisasi.code as unit_code',
                'pemutu_indikator_orgunit.ed_capaian',
                'pemutu_indikator_orgunit.ed_analisis',
                'pemutu_indikator_orgunit.ed_skala',
                'pemutu_indikator_orgunit.ed_attachment',
                'pemutu_indikator_orgunit.ed_links',
                'pemutu_indikator_orgunit.updated_at'
            )
            ->get();

        $amiDetails = DB::table('pemutu_indikator_orgunit')
            ->join('struktur_organisasi', 'pemutu_indikator_orgunit.org_unit_id', '=', 'struktur_organisasi.orgunit_id')
            ->where('pemutu_indikator_orgunit.indikator_id', $indikator->indikator_id)
            ->whereNotNull('pemutu_indikator_orgunit.ami_hasil_akhir')
            ->select(
                'struktur_organisasi.name as unit_name',
                'struktur_organisasi.code as unit_code',
                'pemutu_indikator_orgunit.ami_hasil_akhir',
                'pemutu_indikator_orgunit.ami_hasil_temuan',
                'pemutu_indikator_orgunit.ami_hasil_temuan_sebab',
                'pemutu_indikator_orgunit.ami_hasil_temuan_akibat',
                'pemutu_indikator_orgunit.ami_hasil_temuan_rekom',
                'pemutu_indikator_orgunit.updated_at'
            )
            ->get();

        $pengendDetails = DB::table('pemutu_indikator_orgunit')
            ->join('struktur_organisasi', 'pemutu_indikator_orgunit.org_unit_id', '=', 'struktur_organisasi.orgunit_id')
            ->where('pemutu_indikator_orgunit.indikator_id', $indikator->indikator_id)
            ->whereNotNull('pemutu_indikator_orgunit.pengend_status')
            ->select(
                'struktur_organisasi.name as unit_name',
                'struktur_organisasi.code as unit_code',
                'pemutu_indikator_orgunit.pengend_status',
                'pemutu_indikator_orgunit.pengend_target',
                'pemutu_indikator_orgunit.pengend_analisis',
                'pemutu_indikator_orgunit.pengend_penyesuaian',
                'pemutu_indikator_orgunit.pengend_important_matrix',
                'pemutu_indikator_orgunit.pengend_urgent_matrix',
                'pemutu_indikator_orgunit.updated_at'
            )
            ->get();

        $pageTitle = 'Detail Indikator: ' . ($indikator->no_indikator ?? 'N/A');

        return view('pages.pemutu.indikator-summary.detail', compact(
            'pageTitle',
            'indikator',
            'edDetails',
            'amiDetails',
            'pengendDetails'
        ));
    }

    /**
     * Export data to Excel.
     */
    public function export(Request $request)
    {
        $filters  = $request->only(['kelompok_indikator', 'year', 'search']);
        $fileName = 'summary_indikator_standar_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new \App\Exports\Pemutu\IndikatorSummaryExport($filters), $fileName);
    }
    /**
     * Helper to render truncated text with "Read More" button.
     */
    protected function renderTruncatedText($text, $extraClass = '')
    {
        if (empty($text) || $text === '-') {
            return '<span class="text-muted">-</span>';
        }

        $plainText = strip_tags($text);
        $excerpt   = mb_strimwidth($plainText, 0, 100, '...');

        if (mb_strlen($plainText) <= 100) {
            return '<div class="summary-text ' . $extraClass . '">' . $text . '</div>';
        }

        $id    = 'text-' . uniqid();
        $html  = '<div class="summary-wrapper">';
        $html .= '<div id="' . $id . '-excerpt" class="summary-text-excerpt ' . $extraClass . '">' . e($excerpt) . '</div>';
        $html .= '<div id="' . $id . '-full" class="summary-text-full ' . $extraClass . ' d-none">' . $text . '</div>';
        $html .= '<a href="javascript:void(0)" class="text-primary small btn-read-more" data-target="' . $id . '">Selengkapnya</a>';
        $html .= '</div>';

        return $html;
    }
}
