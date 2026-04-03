<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Hr\StrukturOrganisasi;
use App\Services\Pemutu\IndikatorHistoryService;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FiveYearSummaryController extends Controller
{
    public function __construct(
        protected IndikatorService $indikatorService,
        protected IndikatorHistoryService $indikatorHistoryService,
        protected PeriodeSpmiService $periodeSpmiService
    ) {}

    /**
     * Display 5-year historical summary dashboard.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Summary Historis 5 Tahun - PPEPP';

        // Get active kelompok from session (standard across Pemutu module)
        $activeKelompok = session('pemutu_active_kelompok', 'akademik');
        $kelompokLabel  = $activeKelompok === 'akademik' ? 'Akademik' : 'Non Akademik';
        $kelompok       = $activeKelompok;

        // Get cycle year safely
        $siklus      = $this->periodeSpmiService->getSiklusData();
        $currentYear = (int) ($siklus['tahun'] ?? date('Y'));
        $minYear     = $currentYear - 4;

        // Get active view mode (indikator|unit)
        $viewMode = $request->input('mode', 'indikator');
        $unitId = $request->input('unit_id');

        try {
            // Get statistics
            $stats = $this->indikatorHistoryService->getFiveYearStatistics($kelompokLabel, $currentYear, $unitId);

            // Get categorical summary data
            $summaryData = [];
            if ($viewMode === 'unit') {
                $summaryData = $this->indikatorHistoryService->getFiveYearUnitSummaryData($kelompokLabel, $currentYear, 5);
            } else {
                $summaryData = $this->indikatorHistoryService->getFiveYearSummaryData($kelompokLabel, $currentYear, 5, $unitId);
            }
        } catch (\Exception $e) {
            // Fallback if database query fails
            $stats = [
                'total_indicators' => 0,
                'by_year'          => [],
                'ami_stats'        => ['total' => 0, 'kts' => 0, 'terpenuhi' => 0, 'terlampaui' => 0],
            ];
            $summaryData = ['indicators' => [], 'years' => range($currentYear, $minYear)];
        }

        // Calculate trend for each indicator/unit
        if ($viewMode === 'unit') {
            foreach ($summaryData['units'] as $uId => $unit) {
                $summaryData['units'][$uId]['trend'] = $this->calculateUnitTrend($unit['timeline']);
            }
        } else {
            foreach ($summaryData['indicators'] as $groupKey => $group) {
                $summaryData['indicators'][$groupKey]['trend'] = $this->calculateTrend($group['chain']);
            }
        }

        return view('pages.pemutu.five-year-summary.index', compact(
            'pageTitle',
            'kelompok',
            'kelompokLabel',
            'currentYear',
            'minYear',
            'stats',
            'summaryData',
            'viewMode'
        ));
    }

    /**
     * Get detailed PPEPP data for a specific indicator across years.
     */
    public function detail(Request $request, int $rootIndikatorId)
    {
        $rootIndikator = DB::table('pemutu_indikator')
            ->where('indikator_id', $rootIndikatorId)
            ->first();

        if (! $rootIndikator) {
            return response()->json([
                'success' => false,
                'message' => 'Indikator tidak ditemukan',
            ], 404);
        }

        // Get full history chain
        $history = $this->indikatorHistoryService->getIndicatorFiveYearHistory($rootIndikatorId);

        // Build PPEPP timeline
        $timeline = [];
        foreach ($history['history'] as $yearData) {
            $tahun     = $yearData['tahun'];
            $indikator = $yearData['indikator'];

            // Get org units with full PPEPP data
            $orgUnitsData = [];
            foreach ($yearData['org_units'] as $orgUnit) {
                $orgUnitsData[] = [
                    'unit_name'        => $orgUnit->orgUnit->name ?? 'N/A',
                    'unit_code'        => $orgUnit->orgUnit->code ?? 'N/A',
                    'target'           => $orgUnit->target,
                    'ed_capaian'       => $orgUnit->ed_capaian,
                    'ed_analisis'      => $orgUnit->ed_analisis,
                    'ami_hasil_akhir'  => $orgUnit->ami_hasil_akhir,
                    'ami_hasil_temuan' => $orgUnit->ami_hasil_temuan,
                    'ami_rtp_isi'      => $orgUnit->ami_rtp_isi,
                    'pengend_status'   => $orgUnit->pengend_status,
                ];
            }

            $timeline[] = [
                'tahun'          => $tahun,
                'no_indikator'   => $indikator->no_indikator,
                'indikator_text' => $indikator->indikator,
                'target'         => $indikator->target,
                'unit_ukuran'    => $indikator->unit_ukuran,
                'origin_from'    => $indikator->origin_from,
                'org_units'      => $orgUnitsData,
                'has_next'       => $indikator->nextIndikators()->exists(),
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'root_indikator' => $rootIndikator,
                'timeline'       => $timeline,
                'years'          => $history['years'],
            ],
        ]);
    }

    /**
     * Get detailed unit history (all indicators for a unit over 5 years).
     */
    public function unitDetail(Request $request, int $unitId)
    {
        $orgUnit = StrukturOrganisasi::find($unitId);
        if (!$orgUnit) {
            return response()->json(['success' => false, 'message' => 'Unit tidak ditemukan'], 404);
        }

        $kelompok = session('pemutu_active_kelompok', 'akademik');
        $kelompokLabel = $kelompok === 'akademik' ? 'Akademik' : 'Non Akademik';
        $siklus = $this->periodeSpmiService->getSiklusData();
        $currentYear = (int) ($siklus['tahun'] ?? date('Y'));

        // Fetch all indicators for this unit over 5 years
        $data = $this->indikatorHistoryService->getFiveYearSummaryData($kelompokLabel, $currentYear, 5, $unitId);

        return response()->json([
            'success' => true,
            'data'    => [
                'unit'       => $orgUnit,
                'indicators' => $data['indicators'],
                'years'      => $data['years'],
            ],
        ]);
    }

    /**
     * Get comparative data for visualization.
     */
    public function chartData(Request $request)
    {
        $kelompok = $request->get('kelompok', 'Akademik');

        try {
            $stats = $this->indikatorHistoryService->getFiveYearStatistics($kelompok, null, null);

            $chartData = [
                'labels'          => array_keys($stats['by_year']),
                'datasets'        => [
                    [
                        'label'           => 'Jumlah Indikator',
                        'data'            => array_values($stats['by_year']),
                        'borderColor'     => 'rgb(59, 130, 246)',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'tension'         => 0.4,
                    ],
                ],
                'amiDistribution' => $stats['ami_stats'],
            ];

            return response()->json([
                'success' => true,
                'data'    => $chartData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data chart',
                'data'    => ['labels' => [], 'datasets' => []],
            ]);
        }
    }

    /**
     * Calculate trend from year data.
     */
    private function calculateTrend(array $chain): string
    {
        // Sort chain by year ascending to ensure trend is chronological
        ksort($chain);

        $statuses = [];
        foreach ($chain as $year => $data) {
            if (isset($data->ami_hasil_akhir) && $data->ami_hasil_akhir !== null) {
                $statuses[] = (int) $data->ami_hasil_akhir;
            }
        }

        if (count($statuses) < 2) {
            return 'Data tidak cukup';
        }

        $first = reset($statuses); // Oldest available year
        $last  = end($statuses);   // Newest available year

        if ($last > $first) {
            return 'Meningkat';
        } elseif ($last < $first) {
            return 'Menurun';
        } else {
            return 'Stabil';
        }
    }

    /**
     * Calculate trend for unit performance.
     */
    private function calculateUnitTrend(array $timeline): string
    {
        ksort($timeline);
        $scores = [];
        foreach ($timeline as $year => $data) {
            $scores[] = $data->score;
        }

        if (count($scores) < 2) {
            return 'Data tidak cukup';
        }

        $first = reset($scores);
        $last  = end($scores);

        if ($last > $first + 5) { // Significant increase
            return 'Meningkat';
        } elseif ($last < $first - 5) { // Significant decrease
            return 'Menurun';
        } else {
            return 'Stabil';
        }
    }
}
