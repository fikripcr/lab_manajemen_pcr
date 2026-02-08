<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;
use Illuminate\Http\Request;

class RiwayatStatAktifitasController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function create(Pegawai $pegawai)
    {
        $statusAktifitas = \App\Models\Hr\StatusAktifitas::where('is_active', 1)->get();
        return view('pages.hr.pegawai.status-aktifitas.create', compact('pegawai', 'statusAktifitas'));
    }

    public function store(Request $request, Pegawai $pegawai)
    {
        $data = $request->validate([
            'statusaktifitas_id' => 'required|exists:hr_status_aktifitas,statusaktifitas_id',
            'tmt'                => 'required|date',
            'no_sk'              => 'nullable|string|max:100',
            'keterangan'         => 'nullable|string',
        ]);

        try {
            $this->pegawaiService->requestChange($pegawai, \App\Models\Hr\RiwayatStatAktifitas::class, $data, 'latest_riwayatstataktifitas_id');
            return jsonSuccess('Perubahan Status Aktifitas berhasil diajukan.', route('hr.pegawai.show', $pegawai->pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
