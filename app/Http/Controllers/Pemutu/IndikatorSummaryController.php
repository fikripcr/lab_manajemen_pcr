<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\IndikatorSummaryStandar;
use App\Models\Pemutu\IndikatorSummaryPerforma;
use App\Models\Pemutu\PeriodeSpmi;
use Exception;
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
        $edTotalUnits  = DB::table('pemutu_indikator_orgunit')
            ->join('pemutu_indikator', 'pemutu_indikator_orgunit.indikator_id', '=', 'pemutu_indikator.indikator_id')
            ->where('pemutu_indikator.type', 'standar')
            ->count();
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
        $amiKts       = DB::table('pemutu_indikator_orgunit')
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
            'pengendFilled'
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

        return view('pages.pemutu.indikator-summary.performa', compact(
            'pageTitle',
            'periodes',
            'totalIndikator',
            'totalIndikatorActive',
            'kpiTotalPegawai',
            'kpiAvgScore'
        ));
    }

    /**
     * Data untuk DataTable - Standar.
     */
    public function dataStandar(Request $request)
    {
        try {
            $filters = $request->only(['kelompok_indikator', 'year', 'search']);

            $query = IndikatorSummaryStandar::query();

            // Filter by kelompok indikator
            if (!empty($filters['kelompok_indikator'])) {
                $query->where('kelompok_indikator', $filters['kelompok_indikator']);
            }

            // Filter by year
            if (!empty($filters['year'])) {
                $query->whereYear('periode_mulai', $filters['year']);
            }

            // Search - integrated with DataTable search
            if (!empty($filters['search'])) {
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
                    $html = '<div class="row">';
                    $html .= '<div class="col-12 mb-2">';
                    $html .= '<strong class="text-primary fs-5">' . e($row->no_indikator ?? '-') . '</strong>';
                    $html .= '</div>';
                    $html .= '<div class="col-12 mb-2">';
                    $html .= '<p class="mb-0">' . e($row->indikator ?? '-') . '</p>';
                    $html .= '</div>';
                    
                    // Unit Badges
                    if ($row->all_unit_names && $row->all_unit_names !== '-') {
                        $units = explode(' ;; ', $row->all_unit_names);
                        $html .= '<div class="col-12 mt-2">';
                        $html .= '<div class="d-flex flex-wrap gap-1">';
                        foreach (array_unique($units) as $unit) {
                            if ($unit) {
                                $html .= '<span class="badge bg-azure-lt text-azure-fg">' . e($unit) . '</span>';
                            }
                        }
                        $html .= '</div>';
                        $html .= '</div>';
                    }
                    
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('parent_info', function ($row) {
                    if ($row->parent_no_indikator) {
                        return '<span class="badge bg-azure-lt text-azure-fg">' . e($row->parent_no_indikator) . '</span>';
                    }
                    return '<span class="text-muted">-</span>';
                })
                ->addColumn('labels', function ($row) {
                    if (empty($row->label_details) || $row->label_details === '-') {
                        return '<span class="text-muted fst-italic small">-</span>';
                    }

                    $labels = explode(', ', $row->label_details);
                    $html    = '<div class="d-flex flex-wrap gap-1">';

                    foreach ($labels as $label) {
                        if (strpos($label, '|') !== false) {
                            [$name, $color] = explode('|', $label);
                            $html .= '<span class="badge bg-' . e($color) . '-lt text-' . e($color) . '-fg">' . e($name) . '</span>';
                        }
                    }

                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('ed_detail', function ($row) {
                    if (empty($row->ed_capaian_detail) || $row->ed_capaian_detail === '-') {
                        return '<span class="text-muted fst-italic small">Belum diisi</span>';
                    }

                    $html = '<div class="d-flex flex-column gap-2">';
                    $items = explode(' ;; ', $row->ed_capaian_detail);
                    
                    foreach ($items as $item) {
                        if (strpos($item, '|') !== false) {
                            [$unit, $capaian] = explode('|', $item, 2);
                            $html .= '<div class="card card-sm border-start border-success border-2">';
                            $html .= '<div class="card-body p-2">';
                            $html .= '<div class="row">';
                            $html .= '<div class="col-12">';
                            $html .= '<small class="text-muted d-block">' . e($unit) . '</small>';
                            $html .= '<strong class="text-success">' . e($capaian) . '</strong>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</div>';
                        }
                    }
                    
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('ed_analisis', function ($row) {
                    if (empty($row->ed_analisis_detail) || $row->ed_analisis_detail === '-') {
                        return '<span class="text-muted">-</span>';
                    }

                    $html = '<div class="d-flex flex-column gap-2">';
                    $items = explode(' ;; ', $row->ed_analisis_detail);
                    
                    foreach ($items as $item) {
                        if (strpos($item, '|') !== false) {
                            [$unit, $analisis] = explode('|', $item, 2);
                            $html .= '<div class="card card-sm border-start border-info border-2">';
                            $html .= '<div class="card-body p-2">';
                            $html .= '<small class="text-muted d-block">' . e($unit) . '</small>';
                            $html .= '<p class="mb-0 small">' . e($analisis) . '</p>';
                            $html .= '</div>';
                            $html .= '</div>';
                        }
                    }
                    
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('ami_detail', function ($row) {
                    if (empty($row->ami_hasil_detail) || $row->ami_hasil_detail === '-') {
                        return '<span class="text-muted fst-italic small">Belum dinilai</span>';
                    }

                    $html = '<div class="d-flex flex-column gap-2">';
                    $items = explode(' ;; ', $row->ami_hasil_detail);
                    
                    foreach ($items as $item) {
                        if (strpos($item, '|') !== false) {
                            $parts = explode('|', $item);
                            $unit = $parts[0] ?? '-';
                            $hasil = $parts[1] ?? '-';
                            $temuan = $parts[2] ?? '-';
                            
                            $badgeColor = match($hasil) {
                                'KTS' => 'danger',
                                'Terpenuhi' => 'success',
                                'Terlampaui' => 'info',
                                default => 'secondary',
                            };
                            
                            $html .= '<div class="card card-sm border-start border-' . $badgeColor . ' border-2">';
                            $html .= '<div class="card-body p-2">';
                            $html .= '<div class="d-flex justify-content-between align-items-center mb-1">';
                            $html .= '<small class="text-muted">' . e($unit) . '</small>';
                            $html .= '<span class="badge bg-' . $badgeColor . '-lt">' . e($hasil) . '</span>';
                            $html .= '</div>';
                            if ($temuan && $temuan !== '-') {
                                $html .= '<p class="mb-0 small text-muted">' . e($temuan) . '</p>';
                            }
                            $html .= '</div>';
                            $html .= '</div>';
                        }
                    }
                    
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('ami_rekomendasi', function ($row) {
                    if (empty($row->ami_rekomendasi_detail) || $row->ami_rekomendasi_detail === '-') {
                        return '<span class="text-muted">-</span>';
                    }

                    $html = '<div class="d-flex flex-column gap-2">';
                    $items = explode(' ;; ', $row->ami_rekomendasi_detail);
                    
                    foreach ($items as $item) {
                        if (strpos($item, '|') !== false) {
                            [$unit, $rekom] = explode('|', $item, 2);
                            $html .= '<div class="card card-sm border-start border-warning border-2">';
                            $html .= '<div class="card-body p-2">';
                            $html .= '<small class="text-muted d-block">' . e($unit) . '</small>';
                            $html .= '<p class="mb-0 small">' . e($rekom) . '</p>';
                            $html .= '</div>';
                            $html .= '</div>';
                        }
                    }
                    
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('pengend_detail', function ($row) {
                    if (empty($row->pengend_status_detail) || $row->pengend_status_detail === '-') {
                        return '<span class="text-muted fst-italic small">Belum ada</span>';
                    }

                    $html = '<div class="d-flex flex-column gap-2">';
                    $items = explode(' ;; ', $row->pengend_status_detail);
                    
                    foreach ($items as $item) {
                        if (strpos($item, '|') !== false) {
                            $parts = explode('|', $item);
                            $unit = $parts[0] ?? '-';
                            $status = $parts[1] ?? '-';
                            $analisis = $parts[2] ?? '-';
                            
                            $badgeColor = match(strtolower($status)) {
                                'selesai' => 'success',
                                'proses' => 'warning',
                                'belum' => 'danger',
                                default => 'secondary',
                            };
                            
                            $html .= '<div class="card card-sm border-start border-' . $badgeColor . ' border-2">';
                            $html .= '<div class="card-body p-2">';
                            $html .= '<div class="d-flex justify-content-between align-items-center mb-1">';
                            $html .= '<small class="text-muted">' . e($unit) . '</small>';
                            $html .= '<span class="badge bg-' . $badgeColor . '-lt">' . e($status) . '</span>';
                            $html .= '</div>';
                            if ($analisis && $analisis !== '-') {
                                $html .= '<p class="mb-0 small text-muted">' . e($analisis) . '</p>';
                            }
                            $html .= '</div>';
                            $html .= '</div>';
                        }
                    }
                    
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group btn-group-sm" role="group">';
                    $html .= '<a href="' . route('pemutu.indikator-summary.detail', $row->encrypted_indikator_id) . '" class="btn btn-primary" title="Lihat Detail">';
                    $html .= '<i class="ti ti-eye"></i>';
                    $html .= '</a>';
                    $html .= '<a href="' . route('pemutu.indikators.show', $row->encrypted_indikator_id) . '" class="btn btn-ghost-secondary" title="Kelola Indikator">';
                    $html .= '<i class="ti ti-settings"></i>';
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
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Data untuk DataTable - Performa.
     */
    public function dataPerforma(Request $request)
    {
        try {
            $filters = $request->only(['kelompok_indikator', 'year', 'search']);

            $query = IndikatorSummaryPerforma::query();

            // Filter by kelompok indikator
            if (!empty($filters['kelompok_indikator'])) {
                $query->where('kelompok_indikator', $filters['kelompok_indikator']);
            }

            // Filter by year
            if (!empty($filters['year'])) {
                $query->whereYear('periode_mulai', $filters['year']);
            }

            // Search - integrated with DataTable search
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('no_indikator', 'LIKE', "%{$search}%")
                        ->orWhere('indikator', 'LIKE', "%{$search}%")
                        ->orWhere('parent_no_indikator', 'LIKE', "%{$search}%")
                        ->orWhere('all_labels', 'LIKE', "%{$search}%")
                        ->orWhere('all_org_units', 'LIKE', "%{$search}%");
                });
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('indikator_full', function ($row) {
                    $html = '<div class="row">';
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
                        return '<span class="badge bg-azure-lt text-azure-fg">' . e($row->parent_no_indikator) . '</span>';
                    }
                    return '<span class="text-muted">-</span>';
                })
                ->addColumn('labels', function ($row) {
                    if (empty($row->all_labels)) {
                        return '<span class="text-muted fst-italic small">-</span>';
                    }

                    $labels  = explode(', ', $row->all_labels);
                    $colors  = explode(', ', $row->all_label_colors ?? '');
                    $html    = '<div class="d-flex flex-wrap gap-1">';

                    foreach ($labels as $index => $label) {
                        $color = $colors[$index] ?? 'secondary';
                        $html .= '<span class="badge bg-' . $color . '-lt text-' . $color . '-fg">' . e($label) . '</span>';
                    }

                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('kpi_detail', function ($row) {
                    $totalPegawai = $row->total_pegawai_with_kpi ?? 0;

                    if ($totalPegawai > 0) {
                        $html = '<div class="row">';
                        $html .= '<div class="col-12">';
                        $html .= '<span class="text-muted small">Pegawai: </span>';
                        $html .= '<strong class="text-primary">' . $totalPegawai . '</strong>';
                        $html .= '</div>';

                        $statusCounts = [
                            'draft' => $row->kpi_draft_count ?? 0,
                            'submitted' => $row->kpi_submitted_count ?? 0,
                            'approved' => $row->kpi_approved_count ?? 0,
                            'rejected' => $row->kpi_rejected_count ?? 0,
                        ];

                        foreach ($statusCounts as $statusValue => $count) {
                            if ($count > 0) {
                                $badgeColor = match($statusValue) {
                                    'submitted' => 'info',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'draft' => 'secondary',
                                    default => 'secondary',
                                };
                                $html .= '<span class="badge bg-' . $badgeColor . '-lt me-1 mb-1">' . ucfirst($statusValue) . ': ' . $count . '</span>';
                            }
                        }
                        $html .= '</div>';
                        return $html;
                    }

                    return '<span class="text-muted fst-italic small">Belum ada KPI</span>';
                })
                ->addColumn('kpi_score', function ($row) {
                    $avgScore = $row->kpi_avg_score ? number_format($row->kpi_avg_score, 1) : '-';
                    $minScore = $row->kpi_min_score ? number_format($row->kpi_min_score, 1) : '-';
                    $maxScore = $row->kpi_max_score ? number_format($row->kpi_max_score, 1) : '-';

                    $html = '<div class="text-center">';
                    $html .= '<div class="h4 mb-0 text-primary">' . $avgScore . '</div>';
                    $html .= '<small class="text-muted" title="Min: ' . $minScore . ', Max: ' . $maxScore . '">';
                    $html .= 'Min: ' . $minScore . '<br>Max: ' . $maxScore;
                    $html .= '</small>';
                    $html .= '</div>';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group btn-group-sm" role="group">';
                    $html .= '<a href="' . route('pemutu.indikator-summary.detail', $row->encrypted_indikator_id) . '" class="btn btn-primary" title="Lihat Detail">';
                    $html .= '<i class="ti ti-eye"></i>';
                    $html .= '</a>';
                    $html .= '<a href="' . route('pemutu.evaluasi-kpi.index') . '" class="btn btn-ghost-success" title="Kelola KPI">';
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
        } catch (Exception $e) {
            logError($e);
            return jsonError('Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Display detail indikator.
     */
    public function detail($id)
    {
        try {
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
        } catch (Exception $e) {
            abort(404);
        }
    }

    /**
     * Export data to Excel.
     */
    public function export(Request $request)
    {
        $filters = $request->only(['type', 'kelompok_indikator', 'year', 'search']);
        
        return Excel::download(new \App\Exports\IndikatorSummaryExport($filters), 'indikator-summary-' . now()->format('Y-m-d') . '.xlsx');
    }
}
