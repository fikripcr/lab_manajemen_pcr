<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\StoreTimMutuRequest;
use App\Http\Requests\Hr\SearchRequest;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Pemutu\TimMutu;
use App\Models\Hr\StrukturOrganisasi;
use App\Services\Pemutu\PeriodeSpmiService;
use App\Services\Pemutu\TimMutuService;

class TimMutuController extends Controller
{
    public function __construct(
        protected TimMutuService $timMutuService,
        protected PeriodeSpmiService $PeriodeSpmiService,
    ) {
        // $this->authorizeResourcePermissions('pemutu.tim-mutu');
        // $this->middleware('permission:pemutu.tim-mutu.update')->only(['editAuditee', 'storeAuditee', 'editAuditor', 'storeAuditor', 'manage']);
    }

    /**
     * Index — manage Tim Mutu based on global cycle.
     */
    public function index()
    {
        $siklus = $this->PeriodeSpmiService->getSiklusData();
        
        $data = [
            'pageTitle' => 'Tim Mutu',
            'siklus'    => $siklus,
            'units'     => \App\Services\Hr\StrukturOrganisasiService::getHierarchicalList(),
        ];

        $assignmentMap = [];
        
        foreach (['akademik', 'non_akademik'] as $type) {
            $periode = $siklus[$type];
            if ($periode) {
                $assignments = $this->timMutuService->getByPeriode($periode->periodespmi_id);
                foreach ($assignments as $unitId => $items) {
                    $encryptedId = encryptId($unitId);
                    $assignmentMap[$encryptedId] = [
                        'periode_id'    => $periode->encrypted_periodespmi_id,
                        'auditee'       => $items->where('role', 'auditee')->first(),
                        'ketua_auditor' => $items->where('role', 'ketua_auditor')->first(),
                        'auditor'       => $items->where('role', 'auditor')->values(),
                        'anggota'       => $items->where('role', 'anggota')->values(),
                    ];
                }
            }
        }
        
        $data['assignmentMap'] = $assignmentMap;

        return view('pages.pemutu.tim-mutu.index', $data);
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
            ->with('hr_pegawai')
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
        return jsonSuccess('Tim Auditee berhasil disimpan.', route('pemutu.tim-mutu.index'));
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
            ->with('hr_pegawai')
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
        return jsonSuccess('Tim Auditor berhasil disimpan.', route('pemutu.tim-mutu.index'));
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
