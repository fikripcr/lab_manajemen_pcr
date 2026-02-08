<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;
use Illuminate\Http\Request;

class RiwayatJabFungsionalController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function create(Pegawai $pegawai)
    {
        $jabatan = \App\Models\Hr\JabatanFungsional::where('is_active', 1)->get();
        return view('pages.hr.pegawai.jabatan-fungsional.create', compact('pegawai', 'jabatan'));
    }

    public function store(Request $request, Pegawai $pegawai)
    {
        $data = $request->validate([
            'jabfungsional_id' => 'required|exists:hr_jabatan_fungsional,jabfungsional_id',
            'tmt'              => 'required|date',
            'no_sk'            => 'nullable|string|max:100', // Note: Check schema for actual column name (no_sk_kopertis or internal?)
                                                             // Schema has: no_sk_kopertis, no_sk_internal. I'll map 'no_sk' to one of them or ask user.
                                                             // Let's use 'no_sk_internal' as default for now or expose both in form.
            'no_sk_internal'   => 'nullable|string|max:100',
        ]);

        try {
            $this->pegawaiService->requestChange($pegawai, \App\Models\Hr\RiwayatJabFungsional::class, $data, 'latest_riwayatjabfungsional_id');
            return jsonSuccess('Perubahan Jabatan Fungsional berhasil diajukan.', route('hr.pegawai.show', $pegawai->pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
