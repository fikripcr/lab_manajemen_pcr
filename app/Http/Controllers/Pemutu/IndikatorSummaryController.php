<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\IndikatorSummary;
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

        $pegawais = \App\Models\Hr\Pegawai::whereHas('latestDataDiri')->get()->sortBy(function ($pegawai) {
            return $pegawai->nama;
        });

        $units = \App\Models\Hr\StrukturOrganisasi::orderBy('name')->get();

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
        $query = DB::table('pemutu_indikator_orgunit as io')
            ->join('vw_pemutu_summary_indikator_standar as v', 'io.indikator_id', '=', 'v.indikator_id')
            ->leftJoin('hr_struktur_organisasi as so', 'io.org_unit_id', '=', 'so.orgunit_id')
            ->select('io.*', 'v.*', 'so.name as unit_name', 'so.code as unit_code');

        // Filter by Kelompok
        if ($request->filled('kelompok_indikator')) {
            $query->where('v.kelompok_indikator', $request->kelompok_indikator);
        }

        // Filter by Year
        if ($request->filled('year')) {
            $query->whereYear('v.periode_mulai', $request->year);
        }

        // Filter by ED Status
        if ($request->filled('ed_status')) {
            if ($request->ed_status === 'filled') {
                $query->whereNotNull('io.ed_capaian')->where('io.ed_capaian', '!=', '');
            } elseif ($request->ed_status === 'empty') {
                $query->where(function ($q) {
                    $q->whereNull('io.ed_capaian')->orWhere('io.ed_capaian', '');
                });
            }
        }

        // Filter by AMI Hasil
        if ($request->filled('ami_hasil')) {
            if ($request->ami_hasil === 'empty') {
                $query->whereNull('io.ami_hasil_akhir');
            } else {
                $query->where('io.ami_hasil_akhir', $request->ami_hasil);
            }
        }

        // Filter by Pengendalian Status
        if ($request->filled('pengend_status')) {
            if ($request->pengend_status === 'filled') {
                $query->whereNotNull('io.pengend_status')->where('io.pengend_status', '!=', '');
            } elseif ($request->pengend_status === 'empty') {
                $query->where(function ($q) {
                    $q->whereNull('io.pengend_status')->orWhere('io.pengend_status', '');
                });
            }
        }

        // For dynamic summary count, we need the search applied to the query.
        if ($request->filled('search')) {
            $searchValue = $request->input('search.value') ?? $request->input('search');
            $search      = is_array($searchValue) ? ($searchValue['value'] ?? '') : (string) $searchValue;
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('v.no_indikator', 'LIKE', "%{$search}%")
                        ->orWhere('v.indikator', 'LIKE', "%{$search}%")
                        ->orWhere('v.parent_no_indikator', 'LIKE', "%{$search}%")
                        ->orWhere('so.name', 'LIKE', "%{$search}%")
                        ->orWhere('so.code', 'LIKE', "%{$search}%");
                });
            }
        }

        return DataTables::of($query)
            ->addColumn('no', function ($row) {
                return pemutuDtColNo($row);
            })
            ->addColumn('indikator_full', function ($row) {
                return pemutuDtColIndikator($row);
            })
            ->addColumn('target', function ($row) {
                return pemutuDtColTarget($row);
            })
            ->addColumn('status_ed', function ($row) {
                return pemutuDtColStatusEd($row);
            })
            ->addColumn('status_ami', function ($row) {
                return pemutuDtColStatusAmi($row);
            })
            ->addColumn('rtp', function ($row) {
                if (empty($row->ami_rtp_isi)) {
                    return '<span class="text-muted small">-</span>';
                }

                return '<span class="badge bg-warning-lt" title="' . e($row->ami_rtp_isi) . '">RTP</span>';
            })
            ->addColumn('ptp', function ($row) {
                if (empty($row->ed_ptp_isi)) {
                    return '<span class="text-muted small">-</span>';
                }

                return '<span class="badge bg-purple-lt" title="' . e($row->ed_ptp_isi) . '">PTP</span>';
            })
            ->addColumn('te', function ($row) {
                if (empty($row->ami_te_isi)) {
                    return '<span class="text-muted small">-</span>';
                }

                return '<span class="badge bg-azure-lt" title="' . e($row->ami_te_isi) . '">TE</span>';
            })
            ->addColumn('pengend_detail', function ($row) {
                if (! $row->pengend_status) {
                    return '<span class="text-muted small">-</span>';
                }

                return pemutuDtColStatusPengend($row);
            })
            ->addColumn('peningkatan_detail', function ($row) {
                return '<span class="text-muted small">-</span>';
            })
            ->addColumn('action', function ($row) {
                $html  = '<div class="btn-group btn-group-sm" role="group">';
                $html .= '<a href="' . route('pemutu.indikator.show', encryptId($row->indikator_id)) . '" class="btn btn-ghost-primary" title="Detail Indikator">';
                $html .= '<i class="ti ti-eye"></i>';
                $html .= '</a>';
                $html .= '</div>';
                return $html;
            })
            ->rawColumns([
                'no',
                'indikator_full',
                'target',
                'status_ed',
                'status_ami',
                'rtp',
                'ptp',
                'te',
                'pengend_detail',
                'peningkatan_detail',
                'action',
            ])
            ->make(true);
    }

    /**
     * Data untuk DataTable - Performa.
     */
    public function dataPerforma(Request $request, \App\Services\Pemutu\IndikatorSummaryPerformaService $service)
    {
        $query = $service->getQuery($request);

        return DataTables::of($query)
            ->addColumn('no', function ($row) {
                return '<div class="text-center">' . pemutuDtColNo($row->indikator) . '</div>';
            })
            ->addColumn('indikator_full', function ($row) {
                return pemutuDtColIndikator($row->indikator);
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
            ->addColumn('parent_info', function ($row) {
                if ($row->indikator?->parent?->no_indikator) {
                    return '<span class="status status-azure">' . e($row->indikator->parent->no_indikator) . '</span>';
                }
                return '<span class="text-muted fst-italic">-</span>';
            })
            ->addColumn('labels', function ($row) {
                $labels = $row->indikator?->labels ?? collect();
                if ($labels->isEmpty()) {
                    return '<span class="text-muted fst-italic small">-</span>';
                }

                $html = '<div class="d-flex flex-wrap gap-1">';
                foreach ($labels as $indikatorLabel) {
                    if ($indikatorLabel->label) {
                        $name   = $indikatorLabel->label->name;
                        $color  = $indikatorLabel->label->color ?? 'secondary';
                        $html  .= '<span class="status status-' . e($color) . '">' . e($name) . '</span>';
                    }
                }
                $html .= '</div>';
                return $html;
            })
            ->addColumn('kpi_detail', function ($row) {
                $statusColor = match ($row->status) {
                    'submitted' => 'info',
                    'approved'  => 'success',
                    'rejected'  => 'danger',
                    default     => 'secondary',
                };

                $html  = '<div class="row">';
                $html .= '<div class="col-12 mb-1">';
                $html .= '<strong class="text-primary d-block">' . e($row->pegawai?->nama ?? '-') . '</strong>';
                $html .= '<span class="text-muted small d-block">' . e($row->pegawai?->nip ?? '-') . '</span>';
                $html .= '</div>';

                if ($row->pegawai?->orgUnit) {
                    $html .= '<div class="col-12 mb-1">';
                    $html .= '<span class="status status-azure status-lite small">' . e($row->pegawai->orgUnit->name) . '</span>';
                    $html .= '</div>';
                }

                $html .= '<div class="col-12">';
                $html .= '<span class="badge bg-' . $statusColor . '-lt">' . ucfirst($row->status ?? 'draft') . '</span>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->addColumn('kpi_score', function ($row) {
                $score  = $row->score !== null ? number_format($row->score, 1) : '-';
                $target = $row->target_value !== null ? e($row->target_value) : '-';
                $weight = $row->weight !== null ? e($row->weight) . '%' : '-';

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
                'no',
                'indikator_full',
                'target',
                'capaian',
                'analisis',
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
            ->join('hr_struktur_organisasi', 'pemutu_indikator_orgunit.org_unit_id', '=', 'hr_struktur_organisasi.orgunit_id')
            ->where('pemutu_indikator_orgunit.indikator_id', $indikator->indikator_id)
            ->whereNotNull('pemutu_indikator_orgunit.ed_capaian')
            ->select(
                'hr_struktur_organisasi.name as unit_name',
                'hr_struktur_organisasi.code as unit_code',
                'pemutu_indikator_orgunit.ed_capaian',
                'pemutu_indikator_orgunit.ed_analisis',
                'pemutu_indikator_orgunit.ed_skala',
                'pemutu_indikator_orgunit.ed_attachment',
                'pemutu_indikator_orgunit.ed_links',
                'pemutu_indikator_orgunit.updated_at'
            )
            ->get();

        $amiDetails = DB::table('pemutu_indikator_orgunit')
            ->join('hr_struktur_organisasi', 'pemutu_indikator_orgunit.org_unit_id', '=', 'hr_struktur_organisasi.orgunit_id')
            ->where('pemutu_indikator_orgunit.indikator_id', $indikator->indikator_id)
            ->whereNotNull('pemutu_indikator_orgunit.ami_hasil_akhir')
            ->select(
                'hr_struktur_organisasi.name as unit_name',
                'hr_struktur_organisasi.code as unit_code',
                'pemutu_indikator_orgunit.ami_hasil_akhir',
                'pemutu_indikator_orgunit.ami_hasil_temuan',
                'pemutu_indikator_orgunit.ami_hasil_temuan_sebab',
                'pemutu_indikator_orgunit.ami_hasil_temuan_akibat',
                'pemutu_indikator_orgunit.ami_hasil_temuan_rekom',
                'pemutu_indikator_orgunit.updated_at'
            )
            ->get();

        $pengendDetails = DB::table('pemutu_indikator_orgunit')
            ->join('hr_struktur_organisasi', 'pemutu_indikator_orgunit.org_unit_id', '=', 'hr_struktur_organisasi.orgunit_id')
            ->where('pemutu_indikator_orgunit.indikator_id', $indikator->indikator_id)
            ->whereNotNull('pemutu_indikator_orgunit.pengend_status')
            ->select(
                'hr_struktur_organisasi.name as unit_name',
                'hr_struktur_organisasi.code as unit_code',
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
        if ($request->type === 'performa') {
            $fileName = 'summary_indikator_performa_' . date('Ymd_His') . '.xlsx';
            return Excel::download(new \App\Exports\Pemutu\IndikatorSummaryPerformaExport($request), $fileName);
        }

        $filters  = $request->only(['kelompok_indikator', 'year', 'search', 'ed_status', 'ami_hasil', 'pengend_status']);
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

    /**
     * Get summary counts for Standar cards based on filters.
     */
    public function summaryCount(Request $request)
    {
        $query = DB::table('pemutu_indikator_orgunit as io')
            ->join('vw_pemutu_summary_indikator_standar as v', 'io.indikator_id', '=', 'v.indikator_id')
            ->leftJoin('hr_struktur_organisasi as so', 'io.org_unit_id', '=', 'so.orgunit_id');

        // Apply same filters as dataStandar
        if ($request->filled('kelompok_indikator')) {
            $query->where('v.kelompok_indikator', $request->kelompok_indikator);
        }
        if ($request->filled('year')) {
            $query->whereYear('v.periode_mulai', $request->year);
        }
        if ($request->filled('ed_status')) {
            if ($request->ed_status === 'filled') {
                $query->whereNotNull('io.ed_capaian')->where('io.ed_capaian', '!=', '');
            } elseif ($request->ed_status === 'empty') {
                $query->where(function ($q) {
                    $q->whereNull('io.ed_capaian')->orWhere('io.ed_capaian', '');
                });
            }
        }
        if ($request->filled('ami_hasil')) {
            if ($request->ami_hasil === 'empty') {
                $query->whereNull('io.ami_hasil_akhir');
            } else {
                $query->where('io.ami_hasil_akhir', $request->ami_hasil);
            }
        }
        if ($request->filled('pengend_status')) {
            if ($request->pengend_status === 'filled') {
                $query->whereNotNull('io.pengend_status')->where('io.pengend_status', '!=', '');
            } elseif ($request->pengend_status === 'empty') {
                $query->where(function ($q) {
                    $q->whereNull('io.pengend_status')->orWhere('io.pengend_status', '');
                });
            }
        }
        if ($request->filled('search')) {
            $search = $request->input('search.value') ?? $request->input('search');
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('v.no_indikator', 'LIKE', "%{$search}%")
                        ->orWhere('v.indikator', 'LIKE', "%{$search}%")
                        ->orWhere('v.parent_no_indikator', 'LIKE', "%{$search}%")
                        ->orWhere('so.name', 'LIKE', "%{$search}%")
                        ->orWhere('so.code', 'LIKE', "%{$search}%");
                });
            }
        }

        $allData = $query->get();

        return response()->json([
            'success' => true,
            'data'    => [
                'edTotalUnits'          => $allData->count(),
                'uniqueAssignedStandar' => $allData->pluck('indikator_id')->unique()->count(),
                'edFilledUnits'         => $allData->filter(fn($r) => ! empty($r->ed_capaian))->count(),
                'amiAssessed'           => $allData->filter(fn($r) => $r->ami_hasil_akhir !== null)->count(),
                'amiKts'                => $allData->filter(fn($r) => $r->ami_hasil_akhir == 0 && $r->ami_hasil_akhir !== null)->count(),
                'amiTerpenuhi'          => $allData->filter(fn($r) => $r->ami_hasil_akhir == 1)->count(),
                'amiTerlampaui'         => $allData->filter(fn($r) => $r->ami_hasil_akhir == 2)->count(),
                'pengendFilled'         => $allData->filter(fn($r) => ! empty($r->pengend_status))->count(),
            ],
        ]);
    }
    /**
     * Get summary counts for Performa cards based on filters.
     */
    public function summaryCountPerforma(Request $request, \App\Services\Pemutu\IndikatorSummaryPerformaService $service)
    {
        $counts = $service->getSummaryCounts($request);

        return response()->json([
            'success' => true,
            'data'    => $counts,
        ]);
    }
}
