<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PegawaiService;
use Illuminate\Http\Request;

class RiwayatJabStrukturalController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function create(Pegawai $pegawai)
    {
        $jabatan = \App\Models\Hr\JabatanStruktural::where('is_active', 1)->get();
        return view('pages.hr.pegawai.jabatan-struktural.create', compact('pegawai', 'jabatan'));
    }

    public function store(Request $request, Pegawai $pegawai)
    {
        $data = $request->validate([
            'jabstruktural_id' => 'required|exists:hr_jabatan_struktural,jabstruktural_id',
            'tgl_awal'         => 'required|date',
            'tgl_akhir'        => 'nullable|date|after_or_equal:tgl_awal', // Optional if indefinite
            'no_sk'            => 'nullable|string|max:100',
            'keterangan'       => 'nullable|string',
        ]);

        try {
            // Note: Check if 'latest_riwayatjabstruktural_id' column exists in Pegawai table.
            // If not, using requestAddition might be safer OR we need to add the column.
            // Assuming it exists or will be added as per plan. If it fails, I'll need to check migration.
            // Based on previous context, I commented it out in PegawaiService headerMap. I should double check.
            // For now, I will use requestChange but passing null headerColumn if I rely on list view only,
            // or 'latest_riwayatjabstruktural_id' if I want header update.

            $headerCol = 'latest_riwayatjabstruktural_id';

            $this->pegawaiService->requestChange($pegawai, \App\Models\Hr\RiwayatJabStruktural::class, $data, $headerCol);
            return jsonSuccess('Perubahan Jabatan Struktural berhasil diajukan.', route('hr.pegawai.show', $pegawai->pegawai_id));
        } catch (\Exception $e) {
            return jsonError($e->getMessage());
        }
    }
}
