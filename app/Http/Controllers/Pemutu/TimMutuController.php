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
     * Show modal to edit Tim Auditee for a specific Unit.
     */
    public function editAuditee(PeriodeSpmi $periode, StrukturOrganisasi $unit)
    {
        $periodeId = $periode->periodespmi_id;
        $unitId    = $unit->orgunit_id;

        $assignments = TimMutu::forPeriode($periodeId)
            ->forUnit($unitId)
            ->with('pegawai')
            ->get();

        $auditee = $assignments->where('role', 'auditee')->first();
        $anggota = $assignments->where('role', 'anggota');

        return view('pages.pemutu.tim-mutu.edit-auditee-ajax', compact('periode', 'unit', 'auditee', 'anggota'));
    }

    /**
     * Store — save auditee assignments for a single OrgUnit.
     */
    public function storeAuditee(StoreTimMutuRequest $request, PeriodeSpmi $periode, StrukturOrganisasi $unit)
    {
        $validated = $request->validated();
        $periodeId = $periode->periodespmi_id;
        $unitId    = $unit->orgunit_id;

        $auditeeId  = isset($validated['auditee_id']) ? decryptId($validated['auditee_id']) : null;
        $anggotaIds = collect($validated['anggota_ids'] ?? [])->filter()->map(fn($id) => decryptId($id))->toArray();

        // Pass nulls for auditor side to a theoretical updateUnitTimMutuPart
        // or just use existing updateUnitTimMutu if it supports partial updates.
        // Actually, existing updateUnitTimMutu rewrites EVERYTHING. We must use a separate service method, which I will add next.
        $this->timMutuService->updateAuditee($periodeId, $unitId, $auditeeId, $anggotaIds);

        logActivity('pemutu', "Memperbarui Tim Auditee untuk unit: {$unit->name} pada periode: {$periode->periode}");
        return jsonSuccess('Tim Auditee berhasil disimpan.', route('pemutu.tim-mutu.manage', $periode->encrypted_periodespmi_id));
    }

    /**
     * Show modal to edit Tim Auditor for a specific Unit.
     */
    public function editAuditor(PeriodeSpmi $periode, StrukturOrganisasi $unit)
    {
        $periodeId = $periode->periodespmi_id;
        $unitId    = $unit->orgunit_id;

        $assignments = TimMutu::forPeriode($periodeId)
            ->forUnit($unitId)
            ->with('pegawai')
            ->get();

        $ketuaAuditor = $assignments->where('role', 'ketua_auditor')->first();
        $auditor      = $assignments->where('role', 'auditor');

        return view('pages.pemutu.tim-mutu.edit-auditor-ajax', compact('periode', 'unit', 'ketuaAuditor', 'auditor'));
    }

    /**
     * Store — save auditor assignments for a single OrgUnit.
     */
    public function storeAuditor(StoreTimMutuRequest $request, PeriodeSpmi $periode, StrukturOrganisasi $unit)
    {
        $validated = $request->validated();
        $periodeId = $periode->periodespmi_id;
        $unitId    = $unit->orgunit_id;

        $ketuaAuditorId = isset($validated['ketua_auditor_id']) ? decryptId($validated['ketua_auditor_id']) : null;
        $auditorIds     = collect($validated['auditor_ids'] ?? [])->filter()->map(fn($id) => decryptId($id))->toArray();

        $this->timMutuService->updateAuditor($periodeId, $unitId, $ketuaAuditorId, $auditorIds);

        logActivity('pemutu', "Memperbarui Tim Auditor untuk unit: {$unit->name} pada periode: {$periode->periode}");
        return jsonSuccess('Tim Auditor berhasil disimpan.', route('pemutu.tim-mutu.manage', $periode->encrypted_periodespmi_id));
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
