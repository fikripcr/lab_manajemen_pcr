<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\OrgUnit;
use App\Models\Hr\Pegawai;
use App\Models\Hr\RiwayatPenugasan;
use App\Services\Hr\PegawaiService;

class RiwayatPenugasanController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function index(\Illuminate\Http\Request $request, Pegawai $pegawai = null)
    {
        if ($pegawai) {
            return view('pages.hr.data-diri.tabs.penugasan', compact('pegawai'));
        }
        return $this->massIndex();
    }

    public function create(Pegawai $pegawai)
    {
        // Get all jabatan struktural type units (Direktur, Wadir, Kepala Bagian, Ketua Jurusan, Ketua Prodi, etc.)
        $units = OrgUnit::whereIn('type', ['jabatan_struktural', 'departemen', 'prodi'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $currentPenugasan = $pegawai->latestPenugasan;

        return view('pages.hr.pegawai.penugasan.create', compact('pegawai', 'units', 'currentPenugasan'));
    }

    public function store(\App\Http\Requests\Hr\RiwayatPenugasanRequest $request, Pegawai $pegawai)
    {
        try {
            $this->pegawaiService->addPenugasan($pegawai, $request->validated());
            return jsonSuccess('Penugasan berhasil ditambahkan.', route('hr.pegawai.show', $pegawai->pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function edit(Pegawai $pegawai, RiwayatPenugasan $penugasan)
    {
        $units = OrgUnit::whereIn('type', ['jabatan_struktural', 'departemen', 'prodi'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.hr.pegawai.penugasan.edit', compact('pegawai', 'penugasan', 'units'));
    }

    public function update(\App\Http\Requests\Hr\RiwayatPenugasanRequest $request, Pegawai $pegawai, RiwayatPenugasan $penugasan)
    {
        try {
            $this->pegawaiService->updatePenugasan($penugasan, $request->validated());
            return jsonSuccess('Penugasan berhasil diperbarui.', route('hr.pegawai.show', $pegawai->pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    public function destroy(Pegawai $pegawai, RiwayatPenugasan $penugasan)
    {
        try {
            $this->pegawaiService->deletePenugasan($pegawai, $penugasan);
            return jsonSuccess('Penugasan berhasil dihapus.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    // End current assignment
    public function endAssignment(\App\Http\Requests\Hr\EndPenugasanRequest $request, Pegawai $pegawai, RiwayatPenugasan $penugasan)
    {
        try {
            $this->pegawaiService->endPenugasan($penugasan, $request->validated()['tgl_selesai']);
            return jsonSuccess('Penugasan berhasil diakhiri.');
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }

    // Mass Penugasan Page
    public function massIndex()
    {
        $units = OrgUnit::with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.hr.pegawai.mass-penugasan', compact('units'));
    }

    // Mass Penugasan Detail (AJAX)
    public function massDetail($unitId)
    {
        $unit = OrgUnit::findOrFail($unitId);

        $assignments = RiwayatPenugasan::with('pegawai')
            ->where('org_unit_id', $unitId)
            ->orderByDesc('tgl_mulai')
            ->get();

        return view('pages.hr.pegawai._mass_assignment_detail', compact('unit', 'assignments'));
    }

    // Mass Assign (AJAX)
    public function massAssign(\App\Http\Requests\Hr\MassPenugasanRequest $request)
    {
        try {
            $pegawai = Pegawai::findOrFail($request->pegawai_id);
            $this->pegawaiService->addPenugasan($pegawai, $request->validated());

            return response()->json(['success' => true, 'message' => 'Penugasan berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
