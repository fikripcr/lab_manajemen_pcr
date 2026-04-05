<?php

namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Hr\StrukturOrganisasi;
use App\Models\Pemutu\IndikatorSummary;
use App\Services\Hr\StrukturOrganisasiService;
use App\Services\Pemutu\IndikatorHistoryService;
use App\Services\Pemutu\IndikatorService;
use App\Services\Pemutu\IndikatorSummaryStandarService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SummaryController extends Controller
{
    public function __construct(
        protected StrukturOrganisasiService $strukturOrganisasiService,
        protected PeriodeSpmiService $periodeSpmiService,
        protected IndikatorSummaryStandarService $standarService,
        protected IndikatorService $indikatorService,
        protected IndikatorHistoryService $historyService
    ) {
        // Simplified to single permission
        $this->middleware('permission:pemutu.summary.view');
    }

    // =========================================================================
    // SUMMARY INDEX & REDIRECTS
    // =========================================================================

    /**
     * Redirect to default tab (Standar).
     */
    public function index()
    {
        return redirect()->route('pemutu.summary.standar');
    }

    /**
     * Redirect to Performa tab.
     */
    public function performaIndex()
    {
        return redirect()->route('pemutu.summary.performa');
    }

    // =========================================================================
    // STANDAR SUMMARY (ED, AMI, PENGENDALIAN)
    // =========================================================================

    /**
     * Standar Summary View
     */
    public function standar(Request $request)
    {
        $siklus = $this->periodeSpmiService->getSiklusData();
        $units = $this->strukturOrganisasiService->getHierarchicalList();
        $pageTitle = 'Summary Indikator Standar';

        return view('pages.pemutu.summary.index-standar', compact('pageTitle', 'siklus', 'units'));
    }

    /**
     * Standar Summary Data (DataTables)
     */
    public function dataStandar(Request $request)
    {
        $query = $this->standarService->getDataTable($request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('indikator', function ($row) {
                return pemutuDtColIndikator($row);
            })
            ->editColumn('ed_status', function ($row) {
                return pemutuDtColEdStatus($row);
            })
            ->editColumn('ami_status', function ($row) {
                return pemutuDtColAmiStatus($row);
            })
            ->editColumn('ami_hasil', function ($row) {
                return pemutuDtColAmiHasil($row);
            })
            ->editColumn('pengend_status', function ($row) {
                return pemutuDtColPengendStatus($row);
            })
            ->editColumn('action', function ($row) {
                return pemutuDtColActionSummaryDetail($row);
            })
            ->rawColumns(['indikator', 'ed_status', 'ami_status', 'ami_hasil', 'pengend_status', 'action'])
            ->make(true);
    }

    /**
     * Standar Summary Count (Stats Cards)
     */
    public function summaryCount(Request $request)
    {
        $siklus = $this->periodeSpmiService->getSiklusData();
        $stats = $this->standarService->getSummaryStats($request, $siklus['tahun']);

        return response()->json(['data' => $stats]);
    }

    // =========================================================================
    // PERFORMA SUMMARY (KPI)
    // =========================================================================

    /**
     * Performa Summary View
     */
    public function performa(Request $request)
    {
        $siklus = $this->periodeSpmiService->getSiklusData();
        $units = $this->strukturOrganisasiService->getHierarchicalList();
        $pageTitle = 'Summary Indikator Performa';

        return view('pages.pemutu.summary.index-performa', compact('pageTitle', 'siklus', 'units'));
    }

    /**
     * Performa Summary Data (DataTables)
     */
    public function dataPerforma(Request $request)
    {
        $query = $this->standarService->getPerformaDataTable($request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('indikator', function ($row) {
                return pemutuDtColIndikator($row);
            })
            ->editColumn('capaian', function ($row) {
                return pemutuDtColCapaian($row);
            })
            ->editColumn('action', function ($row) {
                return pemutuDtColActionSummaryPerformaDetail($row);
            })
            ->rawColumns(['indikator', 'capaian', 'action'])
            ->make(true);
    }

    /**
     * Performa Summary Count (Stats Cards)
     */
    public function summaryCountPerforma(Request $request)
    {
        $siklus = $this->periodeSpmiService->getSiklusData();
        $stats = $this->standarService->getPerformaSummaryStats($request, $siklus['tahun']);

        return response()->json(['data' => $stats]);
    }

    // =========================================================================
    // DETAIL MODAL
    // =========================================================================

    /**
     * Detail Indikator Summary (Modal)
     */
    public function detail(Request $request, $id)
    {
        $indikator = $this->indikatorService->getDetailSummary($id, $request->type ?? 'standar');
        $units = $this->strukturOrganisasiService->getHierarchicalList();
        $pageTitle = 'Detail Indikator';

        return view('pages.pemutu.summary.detail-modal', compact('indikator', 'units', 'pageTitle'));
    }

    // =========================================================================
    // EXPORT
    // =========================================================================

    /**
     * Export Summary to Excel
     */
    public function export(Request $request)
    {
        $siklus = $this->periodeSpmiService->getSiklusData();
        $type = $request->input('type', 'standar');

        // Use existing export logic if available, or simple response for now
        // Assuming IndikatorSummaryStandarService has export or we redirect
        return $this->standarService->exportToExcel($request, $siklus['tahun'], $type);
    }

    // =========================================================================
    // 5-YEAR SUMMARY (PEPP HISTORY)
    // =========================================================================

    /**
     * 5-Year Historical Summary (PPEPP Timeline)
     */
    public function fiveYear(Request $request)
    {
        $years = $this->historyService->getAvailableYears();
        $defaultYear = !empty($years) ? $years[0] : date('Y');
        $selectedYear = $request->get('year', $defaultYear);

        $units = $this->strukturOrganisasiService->getHierarchicalList();

        // Fetch top-level indicators (Visi/Misi/Renstra roots)
        $roots = $this->historyService->getRootIndicators($selectedYear);

        return view('pages.pemutu.summary.five-year', [
            'pageTitle' => 'Histori PPEPP 5 Tahun',
            'roots' => $roots,
            'units' => $units,
            'years' => $years,
            'selectedYear' => $selectedYear,
        ]);
    }

    /**
     * Detail PPEPP for a specific indicator across years
     */
    public function fiveYearDetail($id)
    {
        $historyData = $this->historyService->getHistoricalTimeline($id);
        $units = $this->strukturOrganisasiService->getHierarchicalList();

        if (!$historyData) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        return view('pages.pemutu.summary.five-year-detail', [
            'historyData' => $historyData,
            'units' => $units,
        ]);
    }

    /**
     * Chart Data for 5-Year View
     */
    public function fiveYearChartData(Request $request)
    {
        $data = $this->historyService->getChartData($request);
        return response()->json($data);
    }
}
