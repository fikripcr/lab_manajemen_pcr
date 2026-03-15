<?php
namespace App\Http\Controllers\Pemutu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pemutu\DokumenApprovalRequest;
use App\Models\Pemutu\Dokumen;
use App\Models\Pemutu\RiwayatApproval;
use App\Models\Hr\Pegawai;
use Illuminate\Support\Facades\DB;

class DokumenApprovalController extends Controller
{
    public function create(Dokumen $dokumen)
    {
        $pegawais = Pegawai::with('latestDataDiri')->get()->sortBy('nama');
        return view('pages.pemutu.dokumen._approval_form', compact('dokumen', 'pegawais'));
    }

    public function store(DokumenApprovalRequest $request, Dokumen $dokumen)
    {
        DB::beginTransaction();

        // Sync Logic: Remove existing pending approvals first to replace with new list
        $dokumen->riwayatApprovals()->where('status', 'Pending')->delete();

        $approvers = $request->input('approvers', []);
        $addedNames = [];

        foreach ($approvers as $appData) {
            // Skip empty rows if any (though required by validation)
            if (empty($appData['pegawai_id'])) continue;

            $pegawai = Pegawai::findOrFail($appData['pegawai_id']);

            RiwayatApproval::create([
                'model'      => Dokumen::class,
                'model_id'   => $dokumen->dok_id,
                'status'     => 'Pending',
                'pegawai_id' => $pegawai->pegawai_id,
                'pejabat'    => $pegawai->nama,
                'jabatan'    => $appData['jabatan'] ?? '-',
                'catatan'    => null,
            ]);
            
            $addedNames[] = $pegawai->nama;
        }

        DB::commit();

        $namesString = implode(', ', $addedNames);
        logActivity('pemutu', "Memperbarui daftar approval dokumen: {$dokumen->judul}. Total: " . count($addedNames) . " orang ({$namesString})");

        return jsonSuccess(count($addedNames) . ' Approver berhasil disinkronisasi.');
    }

    public function destroy(RiwayatApproval $approval)
    {
        $dokName = $approval->subject?->judul ?? 'Dokumen';
        $approval->delete();

        logActivity('pemutu', "Menghapus approval untuk dokumen: {$dokName}");

        return jsonSuccess('Approval berhasil dihapus');
    }
}
