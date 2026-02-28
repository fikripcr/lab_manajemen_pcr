<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\StoreTimMutuRequest;
use App\Http\Requests\Shared\SearchRequest;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Pemutu\TimMutu;
use App\Models\Shared\StrukturOrganisasi;
use App\Services\Pemutu\PeriodeSpmiService;
use App\Services\Pemutu\TimMutuService;

class TimMutuController extends Controller
{
    public function __construct(
        protected TimMutuService $timMutuService,
        protected PeriodeSpmiService $PeriodeSpmiService,
    ) {}

    /**
     * Index — select a Periode SPMI to manage.
     */
    public function index()
    {
        $periodes = $this->PeriodeSpmiService->getAll();

        $activePeriode = $periodes->first();
        $summary       = $activePeriode
            ? $this->timMutuService->getSummary($activePeriode->periodespmi_id)
            : null;

        return view('pages.pemutu.tim-mutu.index', compact('periodes', 'activePeriode', 'summary'));
    }

    /**
     * Manage — card-based assignment page for a specific periode.
     */
    /**
     * Manage — card-based assignment page for a specific periode.
     */
    public function manage(PeriodeSpmi $periode)
    {
        $periodeId   = $periode->periodespmi_id;
        $orgUnits    = $this->timMutuService->getOrgUnitsPaginated();
        $assignments = $this->timMutuService->getByPeriode($periodeId);

        // Pre-build assignment data keyed by orgunit_id
        $assignmentMap = [];
        foreach ($assignments as $unitId => $items) {
            $encryptedId                 = encryptId($unitId);
            $assignmentMap[$encryptedId] = [
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
    public function editUnit(PeriodeSpmi $periode, StrukturOrganisasi $unit)
    {
        $periodeId = $periode->periodespmi_id;
        $unitId    = $unit->orgunit_id;

        $assignments = TimMutu::forPeriode($periodeId)
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
    public function storeUnit(StoreTimMutuRequest $request, PeriodeSpmi $periode, StrukturOrganisasi $unit)
    {
        $validated = $request->validated();
        $periodeId = $periode->periodespmi_id;
        $unitId    = $unit->orgunit_id;

        // Decrypt encrypted pegawai IDs from the form
        $auditeeId      = $validated['auditee_id'] ? decryptId($validated['auditee_id']) : null;
        $ketuaAuditorId = $validated['ketua_auditor_id'] ? decryptId($validated['ketua_auditor_id']) : null;
        $auditorIds     = collect($validated['auditor_ids'] ?? [])->filter()->map(fn($id) => decryptId($id))->toArray();
        $anggotaIds     = collect($validated['anggota_ids'] ?? [])->filter()->map(fn($id) => decryptId($id))->toArray();

        $this->timMutuService->updateUnitTimMutu(
            $periodeId,
            $unitId,
            $auditeeId,
            $ketuaAuditorId,
            $auditorIds,
            $anggotaIds
        );

        logActivity('pemutu', "Memperbarui Tim Mutu untuk unit: {$unit->name} pada periode: {$periode->periode}");

        return jsonSuccess('Tim Mutu berhasil disimpan.', route('pemutu.tim-mutu.manage', $periode->encrypted_periodespmi_id));
    }

    /**
     * AJAX Select2 search for pegawai.
     */
    public function searchPegawai(SearchRequest $request)
    {
        $results = $this->timMutuService->searchPegawai($request->validated('q', ''));

        return response()->json(['results' => $results]);
    }
}
