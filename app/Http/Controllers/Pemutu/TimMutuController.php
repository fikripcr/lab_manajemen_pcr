<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Shared\Pegawai;
use App\Services\Pemutu\TimMutuService;
use Exception;
use Illuminate\Http\Request;

class TimMutuController extends Controller
{
    protected $TimMutuService;

    public function __construct(TimMutuService $TimMutuService)
    {
        $this->TimMutuService = $TimMutuService;
    }

    /**
     * Index — select a Periode SPMI to manage.
     */
    public function index()
    {
        $periodes = PeriodeSpmi::orderByDesc('periode')->get();

        $activePeriode = $periodes->first();
        $summary       = $activePeriode
            ? $this->TimMutuService->getSummary($activePeriode->periodespmi_id)
            : null;

        return view('pages.pemutu.tim-mutu.index', compact('periodes', 'activePeriode', 'summary'));
    }

    /**
     * Manage — card-based assignment page for a specific periode.
     */
    /**
     * Manage — card-based assignment page for a specific periode.
     */
    public function manage($periodeId)
    {
        $periode     = PeriodeSpmi::findOrFail($periodeId);
        $orgUnits    = $this->TimMutuService->getOrgUnitsFlat();
        $assignments = $this->TimMutuService->getByPeriode($periodeId);

        // Pre-build assignment data keyed by orgunit_id
        $assignmentMap = [];
        foreach ($assignments as $unitId => $items) {
            $assignmentMap[$unitId] = [
                'auditee'       => $items->where('role', 'auditee')->first(),
                'ketua_auditor' => $items->where('role', 'ketua_auditor')->first(),
                'auditor'       => $items->where('role', 'auditor')->values(),
                'anggota'       => $items->where('role', 'anggota')->values(),
            ];
        }

        return view('pages.pemutu.tim-mutu.manage', compact(
            'periode', 'orgUnits', 'assignmentMap'
        ));
    }

    /**
     * Show modal to edit Tim Mutu for a specific Unit.
     */
    public function editUnit($periodeId, $unitId)
    {
        $periode = \App\Models\Pemutu\PeriodeSpmi::findOrFail($periodeId);
        $unit    = \App\Models\Pemutu\OrgUnit::findOrFail($unitId);

        $assignments = \App\Models\Pemutu\TimMutu::forPeriode($periodeId)
            ->forUnit($unitId)
            ->with('pegawai')
            ->get();

        $auditee      = $assignments->where('role', 'auditee')->first();
        $ketuaAuditor = $assignments->where('role', 'ketua_auditor')->first();
        $auditor      = $assignments->where('role', 'auditor');
        $anggota      = $assignments->where('role', 'anggota');

        return view('pages.pemutu.tim-mutu.edit-ajax', compact('periode', 'unit', 'auditee', 'ketuaAuditor', 'auditor', 'anggota'));
    }

    /**
     * Store — save assignments for a single OrgUnit (called via AJAX from modal).
     */
    public function storeUnit(\App\Http\Requests\Pemutu\StoreTimMutuRequest $request, $periodeId, $unitId)
    {
        try {
            $validated = $request->validated();

            $this->TimMutuService->updateUnitTimMutu(
                $periodeId,
                $unitId,
                $validated['auditee_id'] ?? null,
                $validated['ketua_auditor_id'] ?? null,
                $validated['auditor_ids'] ?? [],
                $validated['anggota_ids'] ?? []
            );

            return jsonSuccess([
                'message'  => 'Tim Mutu berhasil disimpan.',
                'redirect' => route('pemutu.tim-mutu.manage', $periodeId),
            ]);
        } catch (Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    /**
     * AJAX Select2 search for pegawai.
     */
    public function searchPegawai(Request $request)
    {
        $results = $this->TimMutuService->searchPegawai($request->input('q', ''));

        return response()->json(['results' => $results]);
    }
}
