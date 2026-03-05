<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\PeningkatanRtmRequest;
use App\Models\Event\Rapat;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\User;
use App\Services\Pemutu\PeningkatanService;
use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\Http\Request;

class PeningkatanController extends Controller
{
    public function __construct(
        protected PeningkatanService $PeningkatanService,
        protected PeriodeSpmiService $PeriodeSpmiService,
    ) {}

    /**
     * Daftar periode SPMI untuk Peningkatan.
     */
    public function index()
    {
        $periodes = $this->PeriodeSpmiService->getPeriodes();

        return view('pages.pemutu.peningkatan.index', compact('periodes'));
    }

    /**
     * Halaman RTM + Peningkatan (tabbed view).
     */
    public function show(PeriodeSpmi $periode, Request $request)
    {
        // Load the latest RTM Peningkatan rapat (if exists) with its relations
        $rapat = $periode->latest_rtm_peningkatan;
        if ($rapat) {
            $rapat->load(['agendas', 'pesertas.user', 'ketua_user', 'notulen_user', 'author_user']);
        }

        $users = User::with('pegawai.latestDataDiri')->get();

        return view('pages.pemutu.peningkatan.show', compact('periode', 'rapat', 'users'));
    }

    // ─── RTM Methods ──────────────────────────────────────────────

    /**
     * Form AJAX modal untuk membuat RTM Peningkatan baru.
     */
    public function createRtm(PeriodeSpmi $periode)
    {
        $users = User::with('pegawai.latestDataDiri')->get();

        return view('pages.pemutu.peningkatan.rtm-form', compact('periode', 'users'));
    }

    /**
     * Store RTM Peningkatan baru.
     */
    public function storeRtm(PeningkatanRtmRequest $request, PeriodeSpmi $periode)
    {
        $this->PeningkatanService->createRtm($periode, $request->validated());

        return jsonSuccess('RTM Peningkatan berhasil dibuat.', route('pemutu.peningkatan.show', $periode->encrypted_periodespmi_id));
    }

    /**
     * Form AJAX modal untuk edit data umum RTM Peningkatan.
     */
    public function editRtm(PeriodeSpmi $periode, Rapat $rapat)
    {
        $users = User::with('pegawai.latestDataDiri')->get();

        return view('pages.pemutu.peningkatan.rtm-form', compact('periode', 'rapat', 'users'));
    }

    /**
     * Update data umum RTM Peningkatan.
     */
    public function updateRtm(PeningkatanRtmRequest $request, PeriodeSpmi $periode, Rapat $rapat)
    {
        $this->PeningkatanService->updateRtm($rapat, $request->validated());

        return jsonSuccess('Data RTM Peningkatan berhasil diperbarui.', route('pemutu.peningkatan.show', $periode->encrypted_periodespmi_id));
    }
}
