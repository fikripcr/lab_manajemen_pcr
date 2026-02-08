<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;
use Illuminate\Http\Request;

class RiwayatStatPegawaiController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function create(Pegawai $pegawai)
    {
        $statusPegawai = \App\Models\Hr\StatusPegawai::where('is_active', 1)->get();
        return view('pages.hr.pegawai.status-pegawai.create', compact('pegawai', 'statusPegawai'));
    }

    public function store(Request $request, Pegawai $pegawai)
    {
        $data = $request->validate([
            'statuspegawai_id' => 'required|exists:hr_status_pegawai,statuspegawai_id',
            'tmt'              => 'required|date',
            'no_sk'            => 'nullable|string|max:100',
            // 'file_sk' => 'nullable|file...',
        ]);

        try {
            $this->pegawaiService->requestChange($pegawai, \App\Models\Hr\RiwayatStatPegawai::class, $data, 'latest_riwayatstatpegawai_id');
            return jsonSuccess('Perubahan Status Pegawai berhasil diajukan.', route('hr.pegawai.show', $pegawai->pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
