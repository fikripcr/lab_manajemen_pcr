<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;
use Illuminate\Http\Request;

class PengembanganDiriController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function create(Pegawai $pegawai)
    {
        return view('pages.hr.pegawai.pengembangan.create', compact('pegawai'));
    }

    public function store(Request $request, Pegawai $pegawai)
    {
        $data = $request->validate([
            'jenis_kegiatan'     => 'required|string|max:100',
            'nama_kegiatan'      => 'required|string|max:255',
            'nama_penyelenggara' => 'nullable|string|max:255',
            'peran'              => 'nullable|string|max:100',
            'tgl_mulai'          => 'required|date',
            'tgl_selesai'        => 'nullable|date|after_or_equal:tgl_mulai',
            'keterangan'         => 'nullable|string',
        ]);

        try {
            $this->pegawaiService->requestAddition($pegawai, \App\Models\Hr\PengembanganDiri::class, $data);
            return jsonSuccess('Riwayat Pengembangan Diri berhasil diajukan. Menunggu persetujuan admin.', route('hr.pegawai.show', $pegawai->pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
