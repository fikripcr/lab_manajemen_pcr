<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\EndPenugasanRequest;
use App\Http\Requests\Hr\MassPenugasanRequest;
use App\Http\Requests\Hr\RiwayatPenugasanRequest;
use App\Models\Hr\OrgUnit;
use App\Models\Hr\Pegawai;
use App\Models\Hr\RiwayatPenugasan;
use App\Services\Hr\PegawaiService;

class RiwayatPenugasanController extends Controller
{
    public function __construct(protected PegawaiService $pegawaiService)
    {}

    public function index(Pegawai $pegawai = null)
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
        $penugasan        = new RiwayatPenugasan();

        return view('pages.hr.pegawai.penugasan.create-edit-ajax', compact('pegawai', 'units', 'currentPenugasan', 'penugasan'));
    }

    public function store(RiwayatPenugasanRequest $request, Pegawai $pegawai)
    {
        $this->pegawaiService->addPenugasan($pegawai, $request->validated());
        return jsonSuccess('Penugasan berhasil ditambahkan.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
    }

    public function edit(Pegawai $pegawai, RiwayatPenugasan $penugasan)
    {
        $units = OrgUnit::whereIn('type', ['jabatan_struktural', 'departemen', 'prodi'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.hr.pegawai.penugasan.create-edit-ajax', compact('pegawai', 'penugasan', 'units'));
    }

    public function update(RiwayatPenugasanRequest $request, Pegawai $pegawai, RiwayatPenugasan $penugasan)
    {
        $this->pegawaiService->updatePenugasan($penugasan, $request->validated());
        return jsonSuccess('Penugasan berhasil diperbarui.', route('hr.pegawai.show', $pegawai->encrypted_pegawai_id));
    }

    public function destroy(Pegawai $pegawai, RiwayatPenugasan $penugasan)
    {
        $this->pegawaiService->deletePenugasan($pegawai, $penugasan);
        return jsonSuccess('Penugasan berhasil dihapus.');
    }

    // End current assignment
    public function endAssignment(EndPenugasanRequest $request, Pegawai $pegawai, RiwayatPenugasan $penugasan)
    {
        $this->pegawaiService->endPenugasan($penugasan, $request->validated()['tgl_selesai']);
        return jsonSuccess('Penugasan berhasil diakhiri.');
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
    public function massAssign(MassPenugasanRequest $request)
    {
        $pegawai = Pegawai::findOrFail($request->pegawai_id);
        $this->pegawaiService->addPenugasan($pegawai, $request->validated());

        return jsonSuccess('Penugasan berhasil ditambahkan.');
    }
}
